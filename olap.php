<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$page = 'olap';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Mondrian OLAP Cube - AdventureWorks</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js"></script>

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .olap-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            min-height: 500px;
        }

        /* --- PERBAIKAN TAMPILAN DRAG & DROP --- */

        /* 1. Kotak Atribut (Tombol yang bisa ditarik) */
        .pvtAttr {
            background-color: #4e73df !important;
            /* Paksa jadi Biru */
            color: #ffffff !important;
            /* Paksa Teks Putih */
            border: 1px solid #4e73df !important;
            padding: 5px 12px !important;
            border-radius: 5px !important;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: grab;
            margin: 3px !important;
        }

        /* 2. Saat Mouse Hover di Tombol */
        .pvtAttr:hover {
            background-color: #2e59d9 !important;
            /* Biru lebih gelap saat hover */
            border-color: #2653d4 !important;
        }

        /* 3. Area Drop Zone (Baris & Kolom) */
        .pvtAxisContainer,
        .pvtVals {
            background: #f8f9fa !important;
            border: 1px dashed #d1d3e2 !important;
            border-radius: 5px;
            padding: 10px !important;
        }

        /* 4. Dropdown (Select Box) */
        .pvtAggregator,
        .pvtRenderer {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #d1d3e2;
            color: #495057;
            background-color: #fff;
            outline: none;
        }

        /* 5. Tabel Hasil (Grid) */
        table.pvtTable {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        table.pvtTable thead tr th,
        table.pvtTable tbody tr th {
            background-color: #f1f3f9 !important;
            border: 1px solid #e3e6f0;
            padding: 8px;
            font-weight: 700;
            color: #5a5c69;
        }

        table.pvtTable tbody tr td {
            border: 1px solid #e3e6f0;
            padding: 8px;
            color: #5a5c69;
        }

        /* Baris Total */
        .pvtTotal,
        .pvtGrandTotal {
            font-weight: bold;
            background-color: #eaecf4 !important;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark">OLAP System (Mondrian Cube)</h2>
                        <p class="text-muted mb-0">Drag & Drop dimensi untuk melakukan Slice, Dice, dan Drill-down data.</p>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i> Reset Cube
                    </button>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="olap-container">
                            <div id="loading-msg" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Sedang memuat Cube Data...</p>
                            </div>
                            <div id="output"></div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4 shadow-sm border-0" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Panduan Penggunaan OLAP:</h5>
                    <hr>
                    <ul class="mb-0">
                        <li><strong>Slice & Dice:</strong> Tarik kotak biru (Dimensi) dari area atas ke area "Rows" atau "Cols".</li>
                        <li><strong>Filter:</strong> Klik panah kecil pada kotak dimensi untuk memfilter data tertentu.</li>
                        <li><strong>Measures:</strong> Ubah "Count" menjadi "Sum" pada dropdown untuk melihat total Sales Amount.</li>
                        <li><strong>Visualisasi:</strong> Ubah "Table" menjadi "Heatmap" atau "Bar Chart" untuk melihat pola visual.</li>
                    </ul>
                </div>

            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Ambil data dari API
            $.getJSON("api/data_olap.php", function(mps) {

                $("#loading-msg").hide(); // Sembunyikan loading

                $("#output").pivotUI(mps, {
                    // Konfigurasi Awal (Default View)
                    rows: ["Category", "Product"],
                    cols: ["Year"],
                    vals: ["Amount"],
                    aggregatorName: "Sum", // Default menjumlahkan Amount
                    rendererName: "Table", // Tampilan awal tabel
                    renderers: $.extend(
                        $.pivotUtilities.renderers,
                        $.pivotUtilities.c3_renderers
                    )
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>