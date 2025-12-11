<?php
error_reporting(0);
include '../koneksi.php';
header('Content-Type: application/json');

try {
    $conn->query("SET SESSION sql_mode = ''");

    // Query OLAP: Mengambil data denormalisasi (Fakta + Semua Dimensi)
    // Kita batasi LIMIT 3000 agar browser tidak nge-lag saat merender pivot table
    $sql = "SELECT 
                p.CategoryName as Category,
                p.SubCategoryName as Product,
                p.ProductClass as Class,
                t.Group as Region,
                t.CountryRegionCode as Country,
                tm.Year as Year,
                tm.MonthName as Month,
                f.OrderQty as Qty,
                f.SalesAmount as Amount
            FROM factsales f
            JOIN dimproduct p ON f.ProductKey = p.ProductKey
            JOIN dimterritory t ON f.TerritoryKey = t.TerritoryKey
            JOIN dimtime tm ON f.DateKey = tm.DateKey
            -- Filter data sampah agar Pivot Table bersih
            WHERE p.CategoryName IS NOT NULL 
            AND t.Group IS NOT NULL
            LIMIT 3000";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
