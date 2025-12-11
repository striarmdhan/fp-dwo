<?php
error_reporting(0);
include '../koneksi.php';
header('Content-Type: application/json');

try {
    $conn->query("SET SESSION sql_mode = ''");

    // Ambil Parameter Filter Group (Benua)
    // Contoh: 'North America', 'Europe', 'Pacific'
    $filterGroup = isset($_GET['group']) ? $_GET['group'] : null;

    // Base Query Filter
    $whereClause = "";
    if ($filterGroup) {
        $whereClause = " WHERE t.Group = '$filterGroup' ";
    }

    // --- 1. CHART 1: Rata-rata Nilai Transaksi per Wilayah (BQ No. 2) ---
    // Rumus: Total Sales / Jumlah Transaksi (Count Rows)
    // Kita urutkan dari yang terbesar (Highest Avg Ticket)
    $sql_avg = "SELECT 
                    t.TerritoryName as label, 
                    t.Group as region_group,
                    (SUM(f.SalesAmount) / COUNT(f.SalesID)) as value 
                FROM factsales f
                JOIN dimterritory t ON f.TerritoryKey = t.TerritoryKey
                $whereClause
                GROUP BY t.TerritoryName
                ORDER BY value DESC";
    $chart_avg = $conn->query($sql_avg)->fetchAll(PDO::FETCH_ASSOC);

    // --- 2. CHART 2: Total Freight (Ongkir) per Wilayah (BQ No. 3) ---
    // Kita urutkan dari yang terbesar (Highest Freight)
    $sql_freight = "SELECT 
                        t.TerritoryName as label, 
                        SUM(f.Freight) as value 
                    FROM factsales f
                    JOIN dimterritory t ON f.TerritoryKey = t.TerritoryKey
                    $whereClause
                    GROUP BY t.TerritoryName
                    ORDER BY value DESC";
    $chart_freight = $conn->query($sql_freight)->fetchAll(PDO::FETCH_ASSOC);

    // --- 3. TABEL DETAIL (Untuk Data Grid) ---
    $sql_table = "SELECT 
                    t.TerritoryName as territory,
                    t.CountryRegionCode as country,
                    t.Group as region_group,
                    COUNT(f.SalesID) as trx_count,
                    SUM(f.SalesAmount) as total_sales,
                    SUM(f.Freight) as total_freight,
                    (SUM(f.SalesAmount) / COUNT(f.SalesID)) as avg_ticket
                  FROM factsales f
                  JOIN dimterritory t ON f.TerritoryKey = t.TerritoryKey
                  $whereClause
                  GROUP BY t.TerritoryName, t.CountryRegionCode, t.Group
                  ORDER BY avg_ticket DESC";
    $table_data = $conn->query($sql_table)->fetchAll(PDO::FETCH_ASSOC);

    // --- 4. LIST GROUP (Untuk Tombol Filter) ---
    // Ambil daftar benua yang tersedia (North America, Europe, dll)
    $sql_groups = "SELECT DISTINCT `Group` FROM dimterritory ORDER BY `Group`";
    $groups = $conn->query($sql_groups)->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'status' => 'success',
        'chart_avg' => $chart_avg,
        'chart_freight' => $chart_freight,
        'table_data' => $table_data,
        'groups' => $groups
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
