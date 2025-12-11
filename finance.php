<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$page = 'finance';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Overview - AdventureWorks Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }

        .chart-area {
            position: relative;
            height: 400px;
            width: 100%;
        }

        #taxChart {
            cursor: pointer;
        }

        /* Dark mode styles */
        body.dark-mode .bg-light {
            background-color: var(--bg-primary) !important;
        }

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

        body.dark-mode .text-dark, body.dark-mode .text-gray-800 {
            color: var(--text-primary) !important;
        }

        body.dark-mode .text-muted, body.dark-mode .text-gray-300 {
            color: var(--text-muted) !important;
        }

        body.dark-mode .text-primary {
            color: #60a5fa !important;
        }

        body.dark-mode .text-xs {
            color: var(--text-muted) !important;
        }

        body.dark-mode .text-secondary {
            color: var(--text-secondary) !important;
        }

        body.dark-mode .text-gray-600,
        body.dark-mode .text-gray-700 {
            color: var(--text-secondary) !important;
        }

        body.dark-mode .table {
            color: var(--text-primary) !important;
            --bs-table-border-color: var(--border-color);
            --bs-table-bg: var(--bg-card);
        }

        body.dark-mode .table td,
        body.dark-mode .table th {
            color: var(--text-primary) !important;
        }

        body.dark-mode .table-dark {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        body.dark-mode .table-dark th {
            border-color: var(--border-color) !important;
        }

        body.dark-mode .table > :not(caption) > * > * {
            background-color: var(--bg-card);
        }

        body.dark-mode .table-hover > tbody > tr:hover > * {
            --bs-table-bg-state: var(--bg-secondary);
            background-color: var(--bg-secondary) !important;
        }

        body.dark-mode .border-bottom {
            border-color: var(--border-color) !important;
        }

        body.dark-mode .border-left-primary {
            border-left-color: #3b82f6 !important;
        }

        body.dark-mode .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }

        body.dark-mode .btn-outline-secondary:hover {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        body.dark-mode p {
            color: var(--text-secondary);
        }

        /* Global Header Styling */
        h2.fw-bold, h1.h2 {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 767.98px) {
            main {
                padding-top: 60px !important;
            }

            .d-flex.justify-content-between {
                background: white;
                margin-left: -1rem;
                margin-right: -1rem;
                padding: 1rem !important;
                margin-top: 0 !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }

            h1, h2 {
                font-size: 1.3rem !important;
                margin-bottom: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <?php include 'sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light min-vh-100">

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="fw-bold text-dark">Tax Overview</h2>
                </div>

                <div class="row mb-4">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2 card-hover border-0 border-start border-primary border-4">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Tax Collected (Pajak)</div>
                                        <div class="h3 mb-0 font-weight-bold text-gray-800" id="kpi-tax">Loading...</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-line"></i> Analisis Tren Pajak (Drill-down)
                                </h6>
                                <div class="btn-toolbar mb-2 mb-md-0">
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetChart()">
                                            <i class="fas fa-sync-alt"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="taxChart"></canvas>
                                </div>
                                <div class="mt-3 small text-muted text-center">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Instruksi:</strong> Klik titik <u>Tahun</u> pada grafik untuk melihat detail <u>Bulanan</u>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 bg-white">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-table"></i> Rincian Data
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-hover text-sm">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Periode</th>
                                                <th class="text-end">Tax Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <tr>
                                                <td colspan="2" class="text-center">Memuat data...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        // Variabel Global Chart
        let myChart = null;

        // 1. Inisialisasi Saat Halaman Dimuat
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Dashboard Ready.");
            loadChart(); // Load data tahunan (default)
        });

        // 2. Fungsi Mengambil Data dari API (PHP)
        function loadChart(year = null) {
            // Tentukan URL API (apakah drill-down atau tidak)
            let url = 'api/data_finance.php';
            if (year) {
                url += '?year=' + year;
            }

            console.log("Fetching Data from: " + url);

            // Ambil data JSON
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal koneksi ke API");
                    return response.json();
                })
                .then(data => {
                    // Update KPI Card (Total Sum)
                    const totalValue = data.reduce((acc, curr) => acc + parseFloat(curr.value), 0);
                    document.getElementById('kpi-tax').innerText = formatCurrency(totalValue);

                    // Siapkan Data untuk Chart & Table
                    const labels = data.map(item => item.label);
                    const values = data.map(item => item.value);
                    const title = year ? 'Tren Pajak Bulanan (' + year + ')' : 'Tren Pajak Tahunan (2001-2004)';

                    // Render Tabel (Visual 3)
                    renderTable(data);

                    // Render Grafik (Visual 2)
                    renderChart(labels, values, title);
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan memuat data. Pastikan API data_finance.php sudah benar.");
                });
        }

        // 3. Fungsi Render Grafik (Chart.js)
        function renderChart(labels, values, title) {
            const canvas = document.getElementById('taxChart');
            const ctx = canvas.getContext('2d');

            // Hapus chart lama jika ada (wajib biar gak numpuk)
            if (myChart) {
                myChart.destroy();
            }

            // Buat Chart Baru
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: title,
                        data: values,
                        borderColor: '#4e73df', // Warna Garis Biru
                        backgroundColor: 'rgba(78, 115, 223, 0.1)', // Warna Area Transparan
                        borderWidth: 3,
                        pointRadius: 6, // Titik agak besar
                        pointHoverRadius: 9, // Titik membesar saat hover
                        pointHitRadius: 20, // Area klik diperluas (biar gampang diklik)
                        fill: true,
                        tension: 0.3 // Garis agak melengkung
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += formatCurrency(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // --- CLICK EVENT LISTENER MANUAL (SOLUSI DRILL-DOWN MACET) ---
            canvas.onclick = function(evt) {
                const points = myChart.getElementsAtEventForMode(evt, 'nearest', {
                    intersect: true
                }, true);

                if (points.length) {
                    const firstPoint = points[0];
                    const label = myChart.data.labels[firstPoint.index];

                    console.log("Klik pada label:", label);

                    // Cek: Drill-down hanya jalan jika labelnya adalah TAHUN (4 digit angka)
                    // Jika user klik 'January' (Bulan), tidak akan terjadi apa-apa.
                    if (String(label).length === 4 && !isNaN(label)) {
                        // Efek visual loading
                        document.body.style.cursor = 'wait';

                        // Panggil Data Bulanan
                        loadChart(label);

                        // Balikin cursor setelah 0.5 detik
                        setTimeout(() => {
                            document.body.style.cursor = 'default';
                        }, 500);
                    }
                }
            };
        }

        // 4. Fungsi Render Tabel (Update Isi Tabel di Kanan)
        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; // Kosongkan tabel lama

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.label}</td>
                    <td class="text-end fw-bold text-primary">${formatCurrency(row.value)}</td>
                `;
                tableBody.appendChild(tr);
            });
        }

        // Helper: Format Angka ke Dollar
        function formatCurrency(num) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(num);
        }

        // Fungsi Tombol Reset
        function resetChart() {
            loadChart();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>