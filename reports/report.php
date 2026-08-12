<?php
include "../includes/session.php";
include "../includes/connection.php";

// Optimized Query using COUNT()
function getComplaintCount($conn, $status = null) {
    if ($status) {
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM complaints WHERE status = ?");
        $stmt->bind_param("s", $status);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM complaints");
    }
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

$total_complaints = getComplaintCount($conn);
$pending = getComplaintCount($conn, 'Pending');
$in_progress = getComplaintCount($conn, 'In Progress');
$resolved = getComplaintCount($conn, 'Resolved');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complaint Reports Dashboard</title>
    
    <!-- Bootstrap 5 & Icons[cite: 3] -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Chart.js[cite: 3] -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #344767;
        }
        .page-header {
            background: #fff;
            padding: 1.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .card { 
            border: none; 
            border-radius: 1rem; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            background: #ffffff;
        }
        .stats-card { 
            transition: all 0.3s ease-in-out; 
            overflow: hidden;
            position: relative;
        }
        .stats-card:hover { 
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            right: 20px;
            bottom: 10px;
        }
        .card-title-sm {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        /* Custom Gradients for Cards */
        .border-l-primary { border-left: 4px solid #4e73df; }
        .border-l-warning { border-left: 4px solid #f6c23e; }
        .border-l-info { border-left: 4px solid #36b9cc; }
        .border-l-success { border-left: 4px solid #1cc88a; }
    </style>
</head>
<body>

<div class="container-fluid px-4 py-5">
    
    <!-- Top Header & Actions -->
    <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0">📊 Complaint Analytics</h2>
            <p class="text-muted mb-0 small">Overview of system complaint statuses and reports</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2 flex-wrap">
            <a href="report.php" class="btn btn-outline-primary shadow-sm"><i class="bi bi-arrow-clockwise me-1"></i> Refresh</a>
            <div class="btn-group shadow-sm">
                <a href="pdf_report.php" class="btn btn-danger"><i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF</a>
                <a href="excel_report.php" class="btn btn-success"><i class="bi bi-file-earmark-excel-fill me-1"></i> Excel</a>
            </div>
            <button onclick="window.print()" class="btn btn-dark shadow-sm"><i class="bi bi-printer-fill me-1"></i> Print</button>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-l-primary h-100 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title-sm text-primary mb-1">Total Complaints</div>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $total_complaints; ?></h2>
                    </div>
                    <i class="bi bi-folder2-open stats-icon text-primary"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-l-warning h-100 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title-sm text-warning mb-1">Pending</div>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $pending; ?></h2>
                    </div>
                    <i class="bi bi-hourglass-split stats-icon text-warning"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-l-info h-100 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title-sm text-info mb-1">In Progress</div>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $in_progress; ?></h2>
                    </div>
                    <i class="bi bi-tools stats-icon text-info"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-l-success h-100 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title-sm text-success mb-1">Resolved</div>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $resolved; ?></h2>
                    </div>
                    <i class="bi bi-check-circle-fill stats-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        <!-- Bar Chart Area[cite: 3] -->
        <div class="col-lg-8">
            <div class="card h-100 p-4">
                <h5 class="fw-bold mb-4">Complaint Volume by Status</h5>
                <div class="chart-container">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- NEW: Doughnut Chart Area for Distribution -->
        <div class="col-lg-4">
            <div class="card h-100 p-4">
                <h5 class="fw-bold mb-4">Status Distribution</h5>
                <div class="chart-container d-flex justify-content-center align-items-center">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Shared Chart Data[cite: 3]
const chartLabels = ["Pending", "In Progress", "Resolved"];
const chartData = [<?php echo $pending; ?>, <?php echo $in_progress; ?>, <?php echo $resolved; ?>];
const chartColors = ["#f6c23e", "#36b9cc", "#1cc88a"];
const hoverColors = ["#dda20a", "#258391", "#13855c"];

// 1. Initialize Bar Chart[cite: 3]
const barCtx = document.getElementById("barChart").getContext('2d');
new Chart(barCtx, {
    type: "bar",
    data: {
        labels: chartLabels,
        datasets: [{
            label: "Number of Complaints",
            data: chartData,
            backgroundColor: chartColors,
            hoverBackgroundColor: hoverColors,
            borderRadius: 6,
            borderWidth: 0,
            barPercentage: 0.6
        }]
    },
    options: { 
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { 
                beginAtZero: true,
                grid: { borderDash: [2, 4], color: "#e3e6f0" }
            },
            x: { 
                grid: { display: false }
            }
        }
    }
});

// 2. Initialize New Doughnut Chart
const doughnutCtx = document.getElementById("doughnutChart").getContext('2d');
new Chart(doughnutCtx, {
    type: "doughnut",
    data: {
        labels: chartLabels,
        datasets: [{
            data: chartData,
            backgroundColor: chartColors,
            hoverBackgroundColor: hoverColors,
            borderWidth: 2,
            hoverOffset: 4
        }]
    },
    options: { 
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: { 
                position: 'bottom',
                labels: { padding: 20, usePointStyle: true }
            }
        }
    }
});

</script>
</body>
</html>