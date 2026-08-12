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
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .notification-card { border: none; border-radius: 15px; transition: 0.3s; }
        .notification-card:hover { transform: translateY(-5px); }
        .badge-status { float: right; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bell text-warning"></i> Notifications</h2>
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <hr>

    <?php if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) { ?>
        <div class="card notification-card shadow mb-3">
            <div class="card-body">
                <span class="badge bg-success badge-status">
                    <?php echo htmlspecialchars($row['status']); ?>
                </span>
                <h5><i class="fas fa-envelope text-primary"></i> Complaint Notification</h5>
                <p class="mt-3">
                    <?php echo htmlspecialchars($row['message']); ?>
                </p>
                <small class="text-muted">
                    <i class="fas fa-clock"></i> <?php echo htmlspecialchars($row['created_at']); ?>
                </small>
            </div>
        </div>
    <?php }
    } else { ?>
        <div class="alert alert-info">
            No notifications found.
        </div>
    <?php } ?>
</div>
</body>
</html>