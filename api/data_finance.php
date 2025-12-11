<?php
include '../koneksi.php';

header('Content-Type: application/json');

$year = isset($_GET['year']) ? $_GET['year'] : null;

try {
    if ($year) {
        // --- SKENARIO DRILL-DOWN: Detail Bulan pada Tahun tertentu ---
        $sql = "SELECT 
                    t.MonthName as label, 
                    SUM(f.TaxAmt) as value 
                FROM factsales f
                JOIN dimtime t ON f.DateKey = t.DateKey
                WHERE t.Year = :year
                GROUP BY MONTH(t.FullDate), t.MonthName
                ORDER BY MONTH(t.FullDate) ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute(['year' => $year]);

    } else {
        // --- SKENARIO UTAMA: Tren Tahunan ---
        $sql = "SELECT 
                    t.Year as label, 
                    SUM(f.TaxAmt) as value 
                FROM factsales f
                JOIN dimtime t ON f.DateKey = t.DateKey
                GROUP BY t.Year
                ORDER BY t.Year ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);

} catch(PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>