<?php
include "../includes/session.php";
include "../includes/connection.php";

$stmt_count = $conn->prepare("SELECT COUNT(id) AS total FROM activity_logs");
$stmt_count->execute();
$total_activities = $stmt_count->get_result()->fetch_assoc()['total'];

$sql = "SELECT activity_logs.*, users.name FROM activity_logs LEFT JOIN users ON users.id = activity_logs.user_id ORDER BY activity_logs.id DESC LIMIT 100";
$result = mysqli_query($conn, $sql);

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<style>
    :root {
        --gold-primary: #d4af37;
        --gold-secondary: #aa771c;
        --gold-gradient: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
    }

    body {
        background: #f8fafc;
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

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

    .logs-card { 
        border: none; 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        border: 1px solid #e2e8f0;
        background: #ffffff;
        overflow: hidden;
    }

    .table thead { 
        background: #f8fafc; 
        color: #475569; 
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    .table th {
        border-bottom: 2px solid #e2e8f0;
        padding: 16px 20px;
    }

    .table td {
        padding: 16px 20px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .activity-icon { 
        width: 38px; 
        height: 38px; 
        border-radius: 50%; 
        background: rgba(212, 175, 55, 0.1); 
        color: #aa771c; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
</style>

<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="fa-solid fa-clock-rotate-left text-warning me-2"></i> Activity Logs</h2>
                <p class="text-white-50 mb-0">Track and review complete system activity history</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                    <i class="fa-solid fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Total Activities Counter Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 text-center bg-white border">
            <h6 class="text-muted text-uppercase fw-bold mb-1 small">Total System Activities</h6>
            <h2 class="text-dark fw-bold mb-0"><?php echo $total_activities; ?></h2>
        </div>

        <!-- Logs Table Card -->
        <div class="card logs-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4">ID</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td class="px-4 fw-bold text-primary">#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 fw-semibold text-dark">
                                        <div class="activity-icon"><i class="fa-solid fa-user"></i></div>
                                        <?php echo htmlspecialchars($row['name'] ?? 'System/Unknown'); ?>
                                    </div>
                                </td>
                                <td class="text-secondary"><?php echo htmlspecialchars($row['activity']); ?></td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                                        <i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($row['created_at']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div> 

    <!-- CENTERED WHITE FOOTER -->
    <footer class="bg-white border-top py-3 mt-auto w-100">
        <div class="container text-center text-muted small">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>