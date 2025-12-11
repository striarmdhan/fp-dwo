<?php
session_start();
// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
// Set ID Halaman untuk Sidebar
$page = 'sales';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Overview - AdventureWorks Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: var(--bg-primary);
            font-family: 'Segoe UI', sans-serif;
            color: var(--text-primary);
        }

        /* Styling Card KPI */
        .kpi-card {
            border: none;
            border-radius: 12px;
            background: var(--bg-card);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Styling Container Grafik */
        .chart-container {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow);
            height: 100%;
            transition: background-color 0.3s ease;
        }

        .chart-title {
            font-weight: 700;
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        /* Dark mode specific styles */
        body.dark-mode .bg-white {
            background-color: var(--bg-card) !important;
        }

        body.dark-mode .text-dark {
            color: var(--text-primary) !important;
        }

        body.dark-mode .text-muted {
            color: var(--text-muted) !important;
        }

        body.dark-mode .text-success {
            color: #4ade80 !important;
        }

        body.dark-mode .text-primary {
            color: #60a5fa !important;
        }

        body.dark-mode .text-warning {
            color: #fbbf24 !important;
        }

        body.dark-mode .text-secondary {
            color: var(--text-secondary) !important;
        }

        body.dark-mode .text-gray-900,
        body.dark-mode .text-gray-800,
        body.dark-mode .text-gray-700 {
            color: var(--text-primary) !important;
        }

        body.dark-mode .card {
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }

        body.dark-mode .card-header {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
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

        body.dark-mode .table-light {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary);
        }

        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) > * {
            --bs-table-bg-type: var(--bg-secondary);
            background-color: var(--bg-secondary);
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

        body.dark-mode .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }

        body.dark-mode .btn-outline-secondary:hover {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        body.dark-mode .badge {
            border-color: var(--border-color) !important;
        }

        body.dark-mode p {
            color: var(--text-secondary);
        }

        /* Global Header Styling */
        h2.fw-bold {
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

            /* Header area di mobile */
            .d-flex.justify-content-between.align-items-center.mb-4 {
                background: white;
                margin-left: -1rem;
                margin-right: -1rem;
                padding: 1rem !important;
                margin-bottom: 1rem !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                position: sticky;
                top: 60px;
                z-index: 10;
            }

            h2 {
                font-size: 1.3rem !important;
                margin-bottom: 0 !important;
            }

            /* Adjust KPI cards spacing */
            .row.g-4 {
                margin-top: 0.5rem;
            }
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
                        <h2 class="fw-bold text-dark">Sales & Product Overview</h2>
                    </div>
                    <!-- <button class="btn btn-primary btn-sm shadow-sm" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i> Refresh Data
                    </button> -->
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card kpi-card p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.8rem;">Total Revenue</h6>
                                    <h3 class="fw-bold mb-0 text-dark" id="kpi-revenue">Loading...</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card kpi-card p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.8rem;">Units Sold</h6>
                                    <h3 class="fw-bold mb-0 text-dark" id="kpi-qty">Loading...</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card kpi-card p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.8rem;">Total Transactions</h6>
                                    <h3 class="fw-bold mb-0 text-dark" id="kpi-trx">Loading...</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">

                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="chart-title">Revenue Share by Class</h5>
                                    <p class="text-muted small mb-3">Proporsi pendapatan produk High, Medium, Low.</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </button>
                            </div>
                            <div style="height: 300px; position: relative;">
                                <canvas id="chartClass"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="chart-title">Sales Volume by Product Line</h5>
                                    <p class="text-muted small mb-3">Perbandingan jumlah unit terjual antar lini produk.</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </button>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="chartLine"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="m-0 fw-bold text-dark">
                                    <i class="fas fa-trophy text-warning me-2"></i>Top 5 Best Selling Products
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light text-secondary">
                                            <tr>
                                                <th class="ps-4">Product Name</th>
                                                <th class="text-center">Class</th>
                                                <th class="text-end">Revenue</th>
                                                <th class="text-center" style="width: 30%;">Contribution</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <tr>
                                                <td colspan="3" class="text-center py-4">Memuat data...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="text-center text-muted py-4 small">
                    &copy; 2024 AdventureWorks Dashboard · Created for DWO
                </footer>

            </main>
        </div>
    </div>

    <script>
        // Variabel Global Chart agar bisa di-destroy/update
        let pieChartInstance = null;
        let barChartInstance = null;

        document.addEventListener("DOMContentLoaded", function() {
            loadSalesData(); // Load awal tanpa filter
        });

        // Fungsi Load Data (Menerima Parameter Filter)
        function loadSalesData(classFilter = null, lineFilter = null) {
            let url = 'api/data_sales.php';
            let params = [];

            if (classFilter) params.push('class=' + classFilter);
            if (lineFilter) params.push('line=' + lineFilter);

            if (params.length > 0) url += '?' + params.join('&');

            console.log("Fetching:", url); // Debugging URL

            // Ubah kursor jadi loading
            document.body.style.cursor = 'wait';

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    document.body.style.cursor = 'default';

                    if (data.status === 'error') {
                        alert("API Error: " + data.message);
                        return;
                    }

                    // 1. Update KPI (Hanya sekali di awal atau selalu update terserah strategi)
                    if (!classFilter && !lineFilter) {
                        document.getElementById('kpi-revenue').innerText = formatCurrency(data.kpi_revenue);
                        document.getElementById('kpi-qty').innerText = formatNumber(data.kpi_qty);
                        document.getElementById('kpi-trx').innerText = formatNumber(data.kpi_trx);
                    }

                    // 2. Render Charts
                    // Cek apakah ini mode Drill-down Bar Chart?
                    // Kalau sedang drill-down, JANGAN render ulang Pie Chart biar gak kedip
                    if (!lineFilter) {
                        renderPieChart(data.chart_class);
                    }

                    // Render Bar Chart (Selalu render karena dia yang di-drill-down)
                    renderBarChart(data.chart_line, lineFilter);

                    // 3. Render Table (Selalu render karena dia kena efek Cross-filter)
                    renderTopTable(data.top_product, data.kpi_revenue);
                })
                .catch(err => {
                    document.body.style.cursor = 'default';
                    console.error(err);
                });
        }

        // --- 1. PIE CHART (Cross-Filtering) ---
        function renderPieChart(data) {
            const ctx = document.getElementById('chartClass').getContext('2d');

            if (pieChartInstance) pieChartInstance.destroy();

            pieChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(i => i.label),
                    datasets: [{
                        data: data.map(i => i.value),
                        // Simpan kode asli (H/M/L) di meta data untuk keperluan filter
                        codes: data.map(i => i.code),
                        backgroundColor: ['#ff9f43', '#28c76f', '#00cfe8', '#ea5455'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    return label + ': ' + formatCurrency(value) + ' (Klik untuk Filter)';
                                }
                            }
                        }
                    },
                    // EVENT KLIK UNTUK CROSS-FILTERING
                    onClick: (evt, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            // Ambil kode 'H', 'M', atau 'L' dari dataset
                            const code = pieChartInstance.data.datasets[0].codes[index];
                            const label = pieChartInstance.data.labels[index];

                            // Konfirmasi ke user (Opsional, biar UX jelas)
                            // alert("Cross-filter Aktif: Menampilkan Top Produk untuk Kelas " + label);

                            // Reload data dengan filter Class
                            loadSalesData(code, null);
                        } else {
                            // Klik di ruang kosong = Reset Filter
                            loadSalesData(null, null);
                        }
                    }
                }
            });
        }

        // --- 2. BAR CHART (Drill-Down) ---
        function renderBarChart(data, isDrillDown) {
            const ctx = document.getElementById('chartLine').getContext('2d');

            if (barChartInstance) barChartInstance.destroy();

            // Urutkan data
            data.sort((a, b) => b.value - a.value);

            // Warna dinamis: Jika Drill-down warnanya Oranye, kalau Normal Biru
            const barColor = isDrillDown ? '#ff9f43' : '#4e73df';
            const chartTitle = isDrillDown ? 'Detail Sub-Category' : 'Sales by Product Line';

            barChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(i => i.label),
                    datasets: [{
                        label: 'Unit Terjual',
                        data: data.map(i => i.value),
                        codes: data.map(i => i.code), // Simpan kode R/M/T
                        backgroundColor: barColor,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                footer: (items) => isDrillDown ? 'Klik untuk Kembali' : 'Klik untuk Drill-down Detail'
                            }
                        }
                    },
                    // EVENT KLIK UNTUK DRILL-DOWN
                    onClick: (evt, elements) => {
                        if (elements.length > 0) {
                            if (isDrillDown) {
                                // Jika sedang drill-down, klik lagi untuk KEMBALI (Reset)
                                loadSalesData(null, null);
                            } else {
                                // Jika normal, lakukan DRILL-DOWN
                                const index = elements[0].index;
                                const code = barChartInstance.data.datasets[0].codes[index];
                                loadSalesData(null, code);
                            }
                        }
                    }
                }
            });
        }

        // --- 3. TABEL (Render Dengan Kolom Class) ---
        function renderTopTable(products, totalRevenue) {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            if (!products || products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3">Tidak ada data untuk filter ini</td></tr>';
                return;
            }

            products.forEach(p => {
                let val = parseFloat(p.value);
                let tot = parseFloat(totalRevenue);
                let percent = (tot > 0) ? (val / tot) * 100 : 0;

                // Logika Warna Badge Sesuai Kelas
                let badgeColor = 'bg-secondary'; // Default
                if (p.class === 'High') badgeColor = 'text-primary-emphasis bg-primary-subtle border border-primary-subtle';
                else if (p.class === 'Medium') badgeColor = 'text-success-emphasis bg-success-subtle border border-success-subtle';
                else if (p.class === 'Low') badgeColor = 'text-warning-emphasis bg-warning-subtle border border-warning-subtle';

                tbody.innerHTML += `
                <tr>
                    <td class="ps-4 fw-bold text-dark">${p.name}</td>
                    
                    <td class="text-center">
                        <span class="badge ${badgeColor} rounded-pill px-3">${p.class}</span>
                    </td>

                    <td class="text-end fw-bold text-success">${formatCurrency(p.value)}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: ${percent}%"></div>
                            </div>
                            <small class="text-muted" style="width: 40px;">${percent.toFixed(1)}%</small>
                        </div>
                    </td>
                </tr>
            `;
            });
        }

        function formatCurrency(num) {
            if (!num) return "$0";
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                maximumFractionDigits: 0
            }).format(num);
        }

        function formatNumber(num) {
            if (!num) return "0";
            return new Intl.NumberFormat('en-US').format(num);
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>