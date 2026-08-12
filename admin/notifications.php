<?php
include "../includes/session.php";

include "../includes/connection.php";


$user_id = $_SESSION['user_id'];

/* Securely Mark notifications as read */
$stmt_update = $conn->prepare("UPDATE notifications SET status='Read' WHERE user_id=?");
$stmt_update->bind_param("i", $user_id);
$stmt_update->execute();

/* Securely Get notifications */
$stmt_select = $conn->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC");
$stmt_select->bind_param("i", $user_id);
$stmt_select->execute();
$result = $stmt_select->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { 
            background: #f8fafc; 
            font-family: "Segoe UI", Roboto, "Helvetica Neue", sans-serif; 
        }

        /* Page Header */
        .page-header { 
            background: white; 
            padding: 25px 30px; 
            border-radius: 20px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
            margin-bottom: 30px; 
            border: 1px solid #e2e8f0; 
        }
        
        .page-title { 
            font-size: 28px; 
            font-weight: 800; 
            color: #1e293b; 
            margin: 0; 
        }

        /* Notification Cards */
        .notification-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            transition: all 0.3s ease;
            border-left: 5px solid #3b82f6; /* Blue accent border */
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .notification-card:hover {
            transform: translateX(5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.06);
            border-left-color: #2563eb;
        }

        .icon-wrapper {
            width: 55px;
            height: 55px;
            background: #eff6ff;
            color: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .notification-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .notification-text {
            color: #475569;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .time-text {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Empty State */
        .empty-state {
            background: white;
            border-radius: 20px;
            border: 1px dashed #e2e8f0;
            padding: 60px 20px;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title"><i class="fas fa-bell text-warning me-2"></i> Notifications</h1>
            <p class="text-muted mb-0 mt-1">Stay updated with your latest alerts</p>
        </div>
        <div>
            <a href="dashboard.php" class="btn btn-light fw-semibold rounded-pill px-4 border shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                <!-- Notification Item -->
                <div class="card notification-card mb-4 p-4">
                    <div class="d-flex align-items-start gap-4">
                        
                        <!-- Icon -->
                        <div class="icon-wrapper">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="notification-title">System Alert</h5>
                                <span class="badge-status">
                                    <i class="fas fa-check-double text-success me-1"></i> <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                            <p class="notification-text">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </p>
                            <div class="mt-2 time-text">
                                <i class="far fa-clock me-1"></i> <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php
                }
            } else {
            ?>
                <!-- Empty State -->
                <div class="empty-state text-center shadow-sm">
                    <i class="fas fa-bell-slash text-muted opacity-50 mb-3" style="font-size: 60px;"></i>
                    <h4 class="fw-bold text-dark mb-2">No new notifications</h4>
                    <p class="text-muted mb-0">You're all caught up! Check back later for updates.</p>
                </div>
            <?php
            }
            ?>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>