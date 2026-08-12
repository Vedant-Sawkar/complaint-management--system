<?php
include "../includes/session.php";
include "../includes/connection.php";

$user_id = $_SESSION['user_id'];

// Mark notifications as Read
$stmt_update = $conn->prepare("UPDATE notifications SET status='Read' WHERE user_id=?");
$stmt_update->bind_param("i", $user_id);
$stmt_update->execute();

// Fetch Notifications
$stmt_select = $conn->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC");
$stmt_select->bind_param("i", $user_id);
$stmt_select->execute();
$result = $stmt_select->get_result();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<style>
    :root {
        --gold-primary: #d4af37;
        --sidebar-width: 260px;
    }
    body { 
        background: #f8fafc; 
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        overflow-x: hidden;
    }

    /* Responsive Content Wrapper */
    .content-wrapper {
        margin-left: var(--sidebar-width);
        background: #f8fafc;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    @media (max-width: 991.98px) {
        .content-wrapper {
            margin-left: 0 !important;
        }
    }

    /* Premium Gold Banner */
    .gold-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 30px;
        color: #ffffff;
        border: 1px solid rgba(212, 175, 55, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .gold-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%);
        border-radius: 50%;
        z-index: -1;
    }

    /* Premium Notification Cards */
    .notification-card { 
        border: 1px solid #e2e8f0; 
        border-radius: 16px; 
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: #ffffff;
        border-left: 5px solid var(--gold-primary);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .notification-card:hover { 
        transform: translateY(-5px) scale(1.01); 
        box-shadow: 0 15px 30px rgba(0,0,0,0.06); 
    }
    
    .icon-circle {
        width: 55px; 
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid p-4 p-lg-5 flex-grow-1">

        <!-- Header Banner -->
        <div class="gold-banner mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 tracking-tight"><i class="fas fa-bell text-warning me-2"></i> Notifications</h2>
                <p class="text-white-50 mb-0">Stay updated on recent activities and task assignments.</p>
            </div>
            <a href="dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>

        <div class="row">
            <div class="col-lg-10 col-xl-8 mx-auto">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card notification-card mb-4">
                            <div class="card-body p-4 d-flex align-items-start gap-4">
                                
                                <div class="icon-circle bg-warning bg-opacity-10 text-warning flex-shrink-0 shadow-sm border border-warning border-opacity-25">
                                    <i class="fas fa-envelope-open-text fs-4"></i>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                        <h5 class="fw-bold mb-0 text-dark tracking-tight">System Alert</h5>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 small fw-bold tracking-wider">
                                            <i class="fas fa-check-double me-1"></i> <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </div>
                                    <p class="text-secondary mb-3 fs-6 lh-lg">
                                        <?php echo htmlspecialchars($row['message']); ?>
                                    </p>
                                    <div class="text-muted small fw-semibold bg-light d-inline-block px-3 py-1 rounded-pill">
                                        <i class="far fa-clock me-1 text-primary"></i> <?php echo date('F d, Y - h:i A', strtotime($row['created_at'])); ?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-5 mt-4">
                        <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-4 border" style="width: 100px; height: 100px;">
                            <i class="far fa-bell-slash fa-3x text-muted opacity-50"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">All caught up!</h4>
                        <p class="text-muted mb-0 fs-6">You have no new notifications right now.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    
    <footer class="bg-white border-top py-4 mt-auto w-100">
        <div class="container text-center text-muted small fw-medium">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>