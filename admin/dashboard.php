<?php
include "../includes/session.php";
include "../includes/connection.php";

// Optimized Count Queries with FIXED SQL spacing
function getCount($conn, $table, $condition = "") {
    $sql = "SELECT COUNT(id) AS total FROM " . $table . " " . $condition;
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result)['total'];
}

$totalUsers = getCount($conn, "users");
$totalComplaints = getCount($conn, "complaints");
$pendingComplaints = getCount($conn, "complaints", "WHERE status='Pending'");
$resolvedComplaints = getCount($conn, "complaints", "WHERE status='Resolved'");

include "../includes/header.php";
?>

<!-- FontAwesome & Chart.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
include "../includes/sidebar.php";
?>

<!-- Custom Premium Gold UI Styles -->
<style>
    :root {
        --gold-primary: #d4af37;
        --gold-secondary: #aa771c;
        --gold-gradient: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
        --dark-slate: #0f172a;
    }

    body {
        background: #f8fafc;
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* Premium Gold Header Banner */
    .gold-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 30px;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.3);
        position: relative;
        overflow: hidden;
    }
    .gold-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(212,175,55,0.07) 0%, transparent 60%);
        pointer-events: none;
    }
    .gold-text-glow {
        background: var(--gold-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    /* Luxury Action Buttons */
    .btn-gold-action {
        background: linear-gradient(135deg, #bf953f, #aa771c);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(170, 119, 28, 0.2);
    }
    .btn-gold-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(170, 119, 28, 0.4);
        color: white;
        background: linear-gradient(135deg, #aa771c, #8a5e13);
    }

    /* KPI Cards Styling with Golden Accents */
    .kpi-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.04);
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 5px; height: 100%;
        background: var(--gold-gradient);
    }

    /* Chart Cards */
    .chart-box {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    .chart-box:hover {
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }
</style>

<!-- Main Content Wrapper -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: calc(100vh - 65px);">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Welcome Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">Welcome Admin 👋</h2>
                <p class="text-white-50 mb-0">Complaint Management System Executive Overview</p>
            </div>
            <div>
                <span class="badge px-3 py-2 rounded-pill fw-bold" style="background: rgba(212, 175, 55, 0.2); color: #fcf6ba; border: 1px solid rgba(212, 175, 55, 0.4);">
                    <i class="fas fa-crown me-1 text-warning"></i> Premium Portal
                </span>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="mb-4 d-flex flex-wrap gap-2">
            <a href="view_complaints.php" class="btn btn-gold-action"><i class="fas fa-list me-2"></i> View Complaints</a>
            <a href="../reports/report.php" class="btn btn-dark shadow-sm rounded-pill px-4 text-white"><i class="fas fa-chart-line text-info me-2"></i> Reports</a>
            <a href="../reports/charts.php" class="btn btn-dark shadow-sm rounded-pill px-4 text-white"><i class="fas fa-chart-pie text-warning me-2"></i> Charts</a>
            <a href="search_complaints.php" class="btn btn-dark shadow-sm rounded-pill px-4 text-white"><i class="fas fa-search text-success me-2"></i> Search</a>
        </div>

        <!-- Dashboard KPI Cards Grid -->
        <div class="row g-4 mb-4">
            
            <div class="col-lg-3 col-md-6">
                <div class="card kpi-card bg-white h-100 p-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold text-muted small mb-1">Total Users</h6>
                            <h2 class="fw-bold mb-0 text-dark"><?php echo $totalUsers; ?></h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(78, 115, 223, 0.1); color: #4e73df;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card kpi-card bg-white h-100 p-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold text-muted small mb-1">Total Complaints</h6>
                            <h2 class="fw-bold mb-0 text-dark"><?php echo $totalComplaints; ?></h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(54, 185, 204, 0.1); color: #36b9cc;">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card kpi-card bg-white h-100 p-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold text-muted small mb-1">Pending</h6>
                            <h2 class="fw-bold mb-0 text-warning"><?php echo $pendingComplaints; ?></h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card kpi-card bg-white h-100 p-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold text-muted small mb-1">Resolved</h6>
                            <h2 class="fw-bold mb-0 text-success"><?php echo $resolvedComplaints; ?></h2>
                        </div>
                        <div class="p-3 rounded-circle" style="background: rgba(28, 200, 138, 0.1); color: #1cc88a;">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Analytics Row: Dual Style Charts (Bar & Doughnut) -->
        <div class="row g-4">
            
            <!-- Bar Chart Overview -->
            <div class="col-lg-8">
                <div class="chart-box h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-bar text-warning me-2"></i> Status Volume Analytics</h5>
                    </div>
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="complaintBarChart"></canvas> 
                    </div>
                </div>
            </div>

            <!-- Doughnut Ratio Breakdown -->
            <div class="col-lg-4">
                <div class="chart-box h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-pie text-success me-2"></i> Resolution Ratio</h5>
                    </div>
                    <div style="position: relative; height: 320px; width: 100%; display: flex; justify-content: center; align-items: center;">
                        <canvas id="complaintDoughnutChart"></canvas> 
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Charts Initialization Script -->
    <script>
    // 1. Bar Chart Setup
    const barCtx = document.getElementById('complaintBarChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Pending Cases', 'Resolved Cases'],
            datasets: [{
                label: 'Volume',
                data: [<?php echo $pendingComplaints; ?>, <?php echo $resolvedComplaints; ?>],
                backgroundColor: ['rgba(246, 194, 62, 0.85)', 'rgba(28, 200, 138, 0.85)'],
                hoverBackgroundColor: ['#f6c23e', '#1cc88a'],
                borderRadius: 10,
                barPercentage: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 },
                    grid: { borderDash: [4, 4], color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Doughnut Chart Setup
    const doughnutCtx = document.getElementById('complaintDoughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Resolved'],
            datasets: [{
                data: [<?php echo $pendingComplaints; ?>, <?php echo $resolvedComplaints; ?>],
                backgroundColor: ['#f6c23e', '#1cc88a'],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true, font: { weight: '600' } }
                }
            }
        }
    });
    </script>

    <!-- CENTERED WHITE FOOTER -->
    <footer class="bg-white border-top py-3 mt-auto w-100">
        <div class="container text-center text-muted small">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>

</div>