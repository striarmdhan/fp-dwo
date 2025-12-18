<?php
error_reporting(0);
include '../koneksi.php';
header('Content-Type: application/json');

try {
    $conn->query("SET SESSION sql_mode = ''");

    // --- Ambil Parameter Filter dari URL ---
    $filterClass = isset($_GET['class']) ? $_GET['class'] : null; // 'H', 'M', 'L'
    $filterLine  = isset($_GET['line']) ? $_GET['line'] : null;   // 'R', 'M', 'T', 'S'

    // --- 1. KPI CARDS (Global - Tidak terpengaruh filter sementara ini) ---
    $q1 = $conn->query("SELECT SUM(f.SalesAmount) FROM factsales f JOIN dimproduct p ON f.ProductKey=p.ProductKey WHERE TRIM(p.ProductClass) IN ('H','M','L')");
    $totalRevenue = $q1->fetchColumn();

    $q2 = $conn->query("SELECT SUM(f.OrderQty) FROM factsales f JOIN dimproduct p ON f.ProductKey=p.ProductKey WHERE TRIM(p.ProductLine) IN ('R','M','T','S')");
    $totalQty = $q2->fetchColumn();

    $q3 = $conn->query("SELECT COUNT(*) FROM factsales f JOIN dimproduct p ON f.ProductKey=p.ProductKey WHERE TRIM(p.ProductClass) IN ('H','M','L')");
    $totalTrx = $q3->fetchColumn();

    // --- 2. STACKED BAR CHART: Revenue Store vs Individual per Class ---
    // Query ini mengambil breakdown pendapatan berdasarkan Class DAN Tipe Customer
    $sql_cust = "SELECT 
                    TRIM(p.ProductClass) as class,
                    TRIM(c.CustomerType) as type, 
                    SUM(f.SalesAmount) as revenue
                 FROM factsales f
                 JOIN dimproduct p ON f.ProductKey = p.ProductKey
                 JOIN dimcustomer c ON f.CustomerKey = c.CustomerKey
                 WHERE TRIM(p.ProductClass) IN ('H', 'M', 'L')
                 GROUP BY TRIM(p.ProductClass), TRIM(c.CustomerType)
                 ORDER BY FIELD(TRIM(p.ProductClass), 'H', 'M', 'L')";

    $raw_data = $conn->query($sql_cust)->fetchAll(PDO::FETCH_ASSOC);

    // Pivot Data Manual untuk Chart.js
    // Kita butuh struktur array yang urut: High (idx 0), Medium (idx 1), Low (idx 2)
    $storeData = [0, 0, 0];
    $individualData = [0, 0, 0];
    $codes = ['H', 'M', 'L'];    // Kode untuk referensi saat klik filter
    $labels = ['High', 'Medium', 'Low'];

    foreach ($raw_data as $row) {
        $idx = -1;
        if ($row['class'] == 'H') $idx = 0;
        elseif ($row['class'] == 'M') $idx = 1;
        elseif ($row['class'] == 'L') $idx = 2;

        if ($idx >= 0) {
            // Cek tipe customer (Sesuaikan string ini dengan isi databasemu: 'Store'/'S' atau 'Individual'/'I')
            // Asumsi: Database berisi kata 'Store' atau 'Individual'
            if (stripos($row['type'], 'Store') !== false || $row['type'] == 'S') {
                $storeData[$idx] = (float)$row['revenue'];
            } else {
                $individualData[$idx] = (float)$row['revenue'];
            }
        }
    }

    $chart_customer_analysis = [
        'labels' => $labels,
        'codes' => $codes, // Dikirim untuk keperluan filter
        'store' => $storeData,
        'individual' => $individualData
    ];

    // --- 3. BAR CHART KANAN: Qty by Product Line ---
    if ($filterLine) {
        // Mode Drill-down: Tampilkan SubCategory
        $sql_line = "SELECT 
                        p.SubCategoryName as label, 
                        SUM(f.OrderQty) as value
                     FROM factsales f
                     JOIN dimproduct p ON f.ProductKey = p.ProductKey
                     WHERE TRIM(p.ProductLine) = '$filterLine'
                     GROUP BY p.SubCategoryName
                     ORDER BY value DESC";
    } else {
        // Mode Normal: Tampilkan Product Line
        $sql_line = "SELECT 
                        CASE 
                            WHEN TRIM(p.ProductLine) = 'R' THEN 'Road'
                            WHEN TRIM(p.ProductLine) = 'M' THEN 'Mountain'
                            WHEN TRIM(p.ProductLine) = 'T' THEN 'Touring'
                            WHEN TRIM(p.ProductLine) = 'S' THEN 'Standard'
                        END as label,
                        TRIM(p.ProductLine) as code,
                        SUM(f.OrderQty) as value
                     FROM factsales f
                     JOIN dimproduct p ON f.ProductKey = p.ProductKey
                     WHERE TRIM(p.ProductLine) IN ('R', 'M', 'T', 'S')
                     GROUP BY TRIM(p.ProductLine)
                     ORDER BY value DESC";
    }
    $chart_line = $conn->query($sql_line)->fetchAll(PDO::FETCH_ASSOC);

    // --- 4. TABEL: Top 5 Produk ---
    // Query ini akan bereaksi terhadap $filterClass (dari klik chart kiri)
    $sql_top = "SELECT 
                    p.ProductName as name, 
                    CASE 
                        WHEN TRIM(p.ProductClass) = 'H' THEN 'High'
                        WHEN TRIM(p.ProductClass) = 'M' THEN 'Medium'
                        WHEN TRIM(p.ProductClass) = 'L' THEN 'Low'
                        ELSE 'NA'
                    END as class,
                    SUM(f.SalesAmount) as value 
                FROM factsales f
                JOIN dimproduct p ON f.ProductKey = p.ProductKey
                WHERE TRIM(p.ProductClass) IN ('H', 'M', 'L') ";

    // Logic Filter Class
    if ($filterClass) {
        $sql_top .= " AND TRIM(p.ProductClass) = '$filterClass' ";
    }

    $sql_top .= " GROUP BY p.ProductName, p.ProductClass ORDER BY value DESC LIMIT 5";

    $top_product = $conn->query($sql_top)->fetchAll(PDO::FETCH_ASSOC);

    // --- Output JSON ---
    echo json_encode([
        'status' => 'success',
        'kpi_revenue' => $totalRevenue,
        'kpi_qty' => $totalQty,
        'kpi_trx' => $totalTrx,
        'chart_class' => $chart_customer_analysis, // Data Baru (Stacked)
        'chart_line' => $chart_line,
        'top_product' => $top_product,
        'active_filter' => $filterClass // Info filter yg sedang aktif
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
