<<?php
    session_start();
    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }
    $page = 'olap';

    // Konfigurasi URL Tomcat Mondrian Anda
    // Pastikan Tomcat sudah berjalan di port 8080 (atau sesuaikan jika beda)
    $baseUrlMondrian = "http://localhost:8080/mondrian/testpage.jsp";
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
                background-color: var(--bg-primary);
                font-family: 'Segoe UI', sans-serif;
                color: var(--text-primary);
            }

            .olap-container {
                background: var(--bg-card);
                padding: 25px;
                border-radius: 12px;
                box-shadow: var(--shadow);
                overflow-x: auto;
                min-height: 500px;
                transition: background-color 0.3s ease;
            }

            /* Dark mode styles */
            body.dark-mode .bg-white {
                background-color: var(--bg-card) !important;
            }

            body.dark-mode .card {
                background-color: var(--bg-card);
                border-color: var(--border-color);
            }

            body.dark-mode .card-header {
                background-color: var(--bg-secondary) !important;
                border-color: var(--border-color) !important;
            }

            body.dark-mode .text-dark {
                color: var(--text-primary) !important;
            }

            body.dark-mode .text-muted {
                color: var(--text-muted) !important;
            }

            body.dark-mode .text-primary {
                color: #60a5fa !important;
            }

            body.dark-mode .text-secondary {
                color: var(--text-secondary) !important;
            }

            body.dark-mode .alert-info {
                background-color: var(--bg-secondary);
                border-color: var(--border-color);
                color: var(--text-primary);
            }

            body.dark-mode .alert-heading {
                color: var(--text-primary) !important;
            }

            body.dark-mode .btn-primary {
                background-color: #3b82f6;
                border-color: #3b82f6;
            }

            body.dark-mode .pvtUi {
                color: var(--text-primary) !important;
            }

            body.dark-mode p {
                color: var(--text-secondary);
            }

            /* OLAP Pivot Table Dark Mode */
            body.dark-mode .pvtTable {
                background-color: var(--bg-card) !important;
                color: var(--text-primary) !important;
            }

            body.dark-mode .pvtTable thead tr th,
            body.dark-mode .pvtTable tbody tr th {
                background-color: var(--bg-secondary) !important;
                color: var(--text-primary) !important;
                border-color: var(--border-color) !important;
            }

            body.dark-mode .pvtTable tbody tr td {
                background-color: var(--bg-card) !important;
                color: var(--text-primary) !important;
                border-color: var(--border-color) !important;
            }

            body.dark-mode .pvtTotal,
            body.dark-mode .pvtGrandTotal {
                background-color: var(--bg-secondary) !important;
                color: var(--text-primary) !important;
            }

            body.dark-mode .pvtAxisContainer,
            body.dark-mode .pvtVals {
                background-color: var(--bg-card) !important;
                border-color: var(--border-color) !important;
                color: var(--text-primary) !important;
            }

            body.dark-mode select,
            body.dark-mode input {
                background-color: var(--bg-secondary);
                color: var(--text-primary);
                border-color: var(--border-color);
            }

            body.dark-mode .pvtFilterBox {
                background-color: var(--bg-secondary);
                color: var(--text-primary);
            }

            .olap-wrapper {
                background-color: var(--bg-card) !important;
                border-radius: 10px;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
                padding: 20px;
                height: 85vh;
                /* Tinggi menyesuaikan layar */
                display: flex;
                flex-direction: column;
            }

            .olap-controls {
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid #e3e6f0;
            }

            /* Iframe styling agar terlihat menyatu */
            #mondrianFrame {
                width: 100%;
                flex-grow: 1;
                /* Mengisi sisa ruang */
                border: none;
                border-radius: 8px;
                background-color: #fff;
            }

            h2.fw-bold {
                font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
                font-size: 1.75rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                line-height: 1.2;
            }
        </style>
    </head>

    <body>

        <div class="container-fluid">
            <div class="row">
                <?php include 'sidebar.php'; ?>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                        <h2 class="fw-bold text-dark">OLAP Dashboard (Mondrian)</h2>
                    </div>

                    <div class="olap-wrapper">

                        <div class="olap-controls">
                            <label for="reportSelector" class="form-label fw-bold"><i class="fas fa-cube me-2"></i>Pilih Analisis OLAP:</label>
                            <select id="reportSelector" class="form-select form-select-lg shadow-sm">
                                <option value="schema_line_qty">1. Analisis Volume Penjualan per Lini Produk (Qty)</option>
                                <option value="schema_class_customer">2. Kontribusi Pendapatan: Class vs Customer Type</option>
                                <option value="schema_tax_year" selected>3. Total Pajak (Tax Amount) per Tahun</option>
                                <option value="schema_avg_ticket">4. Rata-rata Nilai Transaksi per Wilayah</option>
                                <option value="schema_freight">5. Total Biaya Pengiriman (Freight) per Wilayah</option>
                            </select>
                        </div>

                        <div class="text-center" id="loadingParams" style="display:none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Memuat Data Cube...</p>
                        </div>

                        <iframe id="mondrianFrame" src="" title="Mondrian OLAP View"></iframe>

                    </div>

                </main>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                var baseUrl = "<?php echo $baseUrlMondrian; ?>"; // Ambil URL dari PHP

                function loadReport() {
                    var selectedQuery = $('#reportSelector').val();
                    var fullUrl = baseUrl + '?query=' + selectedQuery;

                    // Tampilkan loading sebentar (opsional, visual saja)
                    $('#mondrianFrame').css('opacity', '0.5');

                    // Set URL ke Iframe
                    $('#mondrianFrame').attr('src', fullUrl);

                    // Kembalikan opacity setelah load (trick sederhana)
                    $('#mondrianFrame').on('load', function() {
                        $(this).css('opacity', '1');
                    });
                }

                // Load default saat halaman pertama dibuka
                loadReport();

                // Event saat dropdown berubah
                $('#reportSelector').change(function() {
                    loadReport();
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>