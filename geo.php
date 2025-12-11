<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$page = 'geo'; // Penanda Sidebar
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Geo Analysis - AdventureWorks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .filter-btn {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.9rem;
            margin-right: 5px;
            transition: 0.3s;
        }

        .filter-btn.active {
            background-color: #4e73df;
            color: white;
            border-color: #4e73df;
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
                        <h2 class="fw-bold text-dark">Territory & Logistics Analysis</h2>
                        <p class="text-muted mb-0">Analisis performa penjualan dan biaya pengiriman per wilayah.</p>
                    </div>
                    <div id="filter-container" class="d-flex">
                        <button class="btn btn-outline-secondary filter-btn active" onclick="filterData(null, this)">All Regions</button>
                    </div>
                </div>

                <div class="row g-4 mb-4">

                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="fw-bold text-secondary mb-1">Average Ticket Size</h5>
                                    <p class="small text-muted">Rata-rata nilai transaksi per struk belanja.</p>
                                </div>
                                <span class="badge bg-primary">Daya Beli</span>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="avgChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="fw-bold text-secondary mb-1">Total Freight Cost</h5>
                                    <p class="small text-muted">Total biaya pengiriman yang dibebankan.</p>
                                </div>
                                <span class="badge bg-warning text-dark">Logistik</span>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="freightChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-globe me-2"></i>Territory Performance Details</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Territory</th>
                                                <th>Country</th>
                                                <th>Group</th>
                                                <th class="text-end">Trx Count</th>
                                                <th class="text-end">Total Freight</th>
                                                <th class="text-end fw-bold">Avg Ticket Size</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <tr>
                                                <td colspan="6" class="text-center py-4">Memuat data...</td>
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
        let avgChartInstance = null;
        let freightChartInstance = null;

        document.addEventListener("DOMContentLoaded", function() {
            loadGeoData();
        });

        function loadGeoData(group = null) {
            let url = 'api/data_geo.php';
            if (group) url += '?group=' + encodeURIComponent(group);

            document.body.style.cursor = 'wait';

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    document.body.style.cursor = 'default';
                    if (data.status === 'error') {
                        alert(data.message);
                        return;
                    }

                    // 1. Render Tombol Filter (Hanya sekali di awal)
                    if (!document.getElementById('generated-filters')) {
                        renderFilterButtons(data.groups);
                    }

                    // 2. Render Charts
                    renderAvgChart(data.chart_avg);
                    renderFreightChart(data.chart_freight);

                    // 3. Render Table
                    renderTable(data.table_data);
                });
        }

        // --- RENDER TOMBOL FILTER ---
        function renderFilterButtons(groups) {
            const container = document.getElementById('filter-container');
            // Tandai bahwa filter sudah digenerate agar tidak double
            container.id = 'generated-filters';

            groups.forEach(g => {
                const btn = document.createElement('button');
                btn.className = 'btn btn-outline-secondary filter-btn';
                btn.innerText = g;
                btn.onclick = function() {
                    filterData(g, this);
                };
                container.appendChild(btn);
            });
        }

        // --- LOGIKA KLIK FILTER ---
        function filterData(group, btnElement) {
            // Update tampilan tombol aktif
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-outline-secondary');
            });
            btnElement.classList.remove('btn-outline-secondary');
            btnElement.classList.add('active', 'btn-primary');

            // Load Data Baru
            loadGeoData(group);
        }

        // --- CHART 1: Horizontal Bar (Avg Ticket) ---
        function renderAvgChart(data) {
            const ctx = document.getElementById('avgChart').getContext('2d');
            if (avgChartInstance) avgChartInstance.destroy();

            avgChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(i => i.label),
                    datasets: [{
                        label: 'Avg Ticket ($)',
                        data: data.map(i => i.value),
                        backgroundColor: '#4e73df',
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'x', // Horizontal
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // --- CHART 2: Vertical Bar (Freight) ---
        function renderFreightChart(data) {
            const ctx = document.getElementById('freightChart').getContext('2d');
            if (freightChartInstance) freightChartInstance.destroy();

            freightChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(i => i.label),
                    datasets: [{
                        label: 'Total Freight ($)',
                        data: data.map(i => i.value),
                        backgroundColor: '#f6c23e', // Kuning/Warning
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // --- RENDER TABEL ---
        function renderTable(data) {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';
            data.forEach(row => {
                tbody.innerHTML += `
                <tr>
                    <td class="ps-4 fw-bold">${row.territory}</td>
                    <td>${row.country}</td>
                    <td><span class="badge bg-light text-dark border">${row.region_group}</span></td>
                    <td class="text-end">${parseInt(row.trx_count).toLocaleString()}</td>
                    <td class="text-end text-warning fw-bold">${formatCurrency(row.total_freight)}</td>
                    <td class="text-end text-primary fw-bold">${formatCurrency(row.avg_ticket)}</td>
                </tr>
            `;
            });
        }

        function formatCurrency(num) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                maximumFractionDigits: 0
            }).format(num);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>