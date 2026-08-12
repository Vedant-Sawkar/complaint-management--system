<?php
include "../includes/session.php";
include "../includes/connection.php";

$user_id = $_SESSION['user_id'];

/* Securely Mark notifications as read[cite: 24] */
$stmt_update = $conn->prepare("UPDATE notifications SET status='Read' WHERE user_id=?");
$stmt_update->bind_param("i", $user_id);
$stmt_update->execute();

/* Securely Get notifications[cite: 24] */
$stmt_select = $conn->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC");
$stmt_select->bind_param("i", $user_id);
$stmt_select->execute();
$result = $stmt_select->get_result();

// Include Global Header and Sidebar
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!-- Main Content Wrapper to accommodate the fixed sidebar and sticky footer -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">

    <div class="container-fluid py-4 flex-grow-1">

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

    <!-- Centered White Footer -->
    <footer class="bg-white border-top py-3 mt-auto w-100">
        <div class="container text-center text-muted small">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>