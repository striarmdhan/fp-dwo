<?php
error_reporting(0);
include '../koneksi.php';
header('Content-Type: application/json');

try {
    $conn->query("SET SESSION sql_mode = ''");

    // Ambil Parameter Filter dari URL
    $filterClass = isset($_GET['class']) ? $_GET['class'] : null; // Contoh: 'H', 'M', 'L'
    $filterLine  = isset($_GET['line']) ? $_GET['line'] : null;   // Contoh: 'R', 'M', 'T'

    // --- 1. KPI CARDS (Global) ---
    $q1 = $conn->query("SELECT SUM(f.SalesAmount) FROM factsales f JOIN dimproduct p ON f.ProductKey=p.ProductKey WHERE TRIM(p.ProductClass) IN ('H','M','L')");
    $totalRevenue = $q1->fetchColumn();

    $q2 = $conn->query("SELECT SUM(f.OrderQty) FROM factsales f JOIN dimproduct p ON f.ProductKey=p.ProductKey WHERE TRIM(p.ProductLine) IN ('R','M','T','S')");
    $totalQty = $q2->fetchColumn();

    $q3 = $conn->query("SELECT COUNT(*) FROM factsales f JOIN dimproduct p ON f.ProductKey=p.ProductKey WHERE TRIM(p.ProductClass) IN ('H','M','L')");
    $totalTrx = $q3->fetchColumn();

    // --- 2. PIE CHART: Sales by Class ---
    $sql_class = "SELECT 
                    CASE 
                        WHEN TRIM(p.ProductClass) = 'H' THEN 'High'
                        WHEN TRIM(p.ProductClass) = 'M' THEN 'Medium'
                        WHEN TRIM(p.ProductClass) = 'L' THEN 'Low'
                    END as label, 
                    TRIM(p.ProductClass) as code, 
                    SUM(f.SalesAmount) as value
                  FROM factsales f
                  JOIN dimproduct p ON f.ProductKey = p.ProductKey
                  WHERE TRIM(p.ProductClass) IN ('H', 'M', 'L') 
                  GROUP BY TRIM(p.ProductClass)";
    $chart_class = $conn->query($sql_class)->fetchAll(PDO::FETCH_ASSOC);

    // --- 3. BAR CHART: Qty by Product Line ---
    if ($filterLine) {
        // Drill-down: SubCategory
        $sql_line = "SELECT 
                        p.SubCategoryName as label, 
                        SUM(f.OrderQty) as value
                     FROM factsales f
                     JOIN dimproduct p ON f.ProductKey = p.ProductKey
                     WHERE TRIM(p.ProductLine) = '$filterLine'
                     GROUP BY p.SubCategoryName
                     ORDER BY value DESC";
    } else {
        // Normal: Product Line
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
    // Query Awal
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

    // Tambahkan Filter Class jika ada (dari Pie Chart)
    if ($filterClass) {
        $sql_top .= " AND TRIM(p.ProductClass) = '$filterClass' ";
    }

    // PERBAIKAN: Kode duplikat di bawah ini sudah dibuang
    $sql_top .= " GROUP BY p.ProductName, p.ProductClass ORDER BY value DESC LIMIT 5";

    // Eksekusi Query
    $top_product = $conn->query($sql_top)->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'kpi_revenue' => $totalRevenue,
        'kpi_qty' => $totalQty,
        'kpi_trx' => $totalTrx,
        'chart_class' => $chart_class,
        'chart_line' => $chart_line,
        'top_product' => $top_product,
        'filter_active' => [
            'class' => $filterClass,
            'line' => $filterLine
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
