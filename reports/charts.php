<?php
include "../includes/connection.php";

// Fetch counts for the charts
function getCount($conn, $status = null) {
    if ($status) {
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM complaints WHERE status = ?");
        $stmt->bind_param("s", $status);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM complaints");
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}

$total = getCount($conn);
$pending = getCount($conn, 'Pending');
$progress = getCount($conn, 'In Progress');
$resolved = getCount($conn, 'Resolved');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Complaint Analytics Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CSS[cite: 4] -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons[cite: 4] -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Chart.js[cite: 4] -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f1f5f9; /* Softer professional background */
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif; /* */
            color: #334155;
        }
        .page-header {
            background: #ffffff;
            padding: 2rem 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            margin-bottom: 2rem;
            border-top: 4px solid #3b82f6;
        }
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .chart-card {
            background: #ffffff;
            border-radius: 1rem;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .chart-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }
        .chart-container {
            position: relative; 
            height: 350px; 
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container-fluid px-4 py-5">

    <!-- Page Header[cite: 4] -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3 mx-auto" style="max-width: 1400px;">
        <div>
            <h1 class="page-title mb-1"><i class="fas fa-chart-pie text-primary me-2"></i> Analytics Dashboard</h1>
            <p class="text-muted mb-0 small">Visual representation of system complaint statistics[cite: 4]</p>
        </div>
        <div>
            <a href="report.php" class="btn btn-outline-secondary fw-semibold rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to Reports
            </a>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 justify-content-center mx-auto" style="max-width: 1400px;">
        
        <!-- Bar Chart (Volume Comparison) -->
        <div class="col-lg-8">
            <div class="chart-card d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="chart-title mb-1">Status-wise Complaint Distribution[cite: 4]</h5>
                        <p class="text-muted small mb-0">Total versus individual status metrics</p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill fw-bold">
                        Total: <?php echo $total; ?>[cite: 4]
                    </span>
                </div>
                <div class="chart-container mt-auto">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart (Percentage Breakdown) -->
        <div class="col-lg-4">
            <div class="chart-card d-flex flex-column">
                <div class="mb-4">
                    <h5 class="chart-title mb-1">Status Breakdown</h5>
                    <p class="text-muted small mb-0">Proportion of active vs resolved cases</p>
                </div>
                <div class="chart-container d-flex justify-content-center align-items-center mt-auto">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Shared Data Variables[cite: 4]
const totalCount = <?php echo $total; ?>;
const pendingCount = <?php echo $pending; ?>;
const progressCount = <?php echo $progress; ?>;
const resolvedCount = <?php echo $resolved; ?>;

// Modern Color Palette
const colors = {
    total: 'rgba(59, 130, 246, 0.85)',
    pending: 'rgba(245, 158, 11, 0.85)',
    progress: 'rgba(6, 182, 212, 0.85)',
    resolved: 'rgba(34, 197, 94, 0.85)'
};

// 1. Initialize Bar Chart[cite: 4]
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Total Complaints', 'Pending', 'In Progress', 'Resolved'],
        datasets: [{
            label: 'Volume',
            data: [totalCount, pendingCount, progressCount, resolvedCount],
            backgroundColor: [colors.total, colors.pending, colors.progress, colors.resolved],
            borderRadius: 6,
            borderWidth: 0,
            barPercentage: 0.5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: '#f1f5f9', borderDash: [5, 5] }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// 2. Initialize New Doughnut Chart
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'In Progress', 'Resolved'], // Excluded 'Total' for accurate percentage distribution
        datasets: [{
            data: [pendingCount, progressCount, resolvedCount],
            backgroundColor: [colors.pending, colors.progress, colors.resolved],
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '75%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                padding: 12
            }
        }
    }
});
</script>

<!-- Bootstrap Bundle JS[cite: 4] -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>