<?php
include "../includes/session.php";
include "../includes/connection.php";

$staff_id = $_SESSION['user_id'];

// Quick stats for the dashboard
$stmt = $conn->prepare("SELECT COUNT(*) AS total_tasks, SUM(CASE WHEN status != 'Resolved' AND status != 'Closed' THEN 1 ELSE 0 END) AS pending_tasks FROM complaints WHERE assigned_to = ?");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$total_tasks = $stats['total_tasks'] ?? 0;
$pending_tasks = $stats['pending_tasks'] ?? 0;

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<style>
    body { 
        background: #f8fafc; 
        font-family: 'Inter', system-ui, -apple-system, sans-serif; 
        overflow-x: hidden;
    }
    
    /* Layout Wrapper - Explicit Margin to clear sidebar */
    .main-content {
        margin-left: 260px;
        background: #f8fafc; 
        min-height: calc(100vh - 65px); 
        display: flex;
        flex-direction: column;
        transition: margin-left 0.3s ease;
    }

    /* Responsive Sidebar Offset for Mobile */
    @media (max-width: 991.98px) {
        .main-content {
            margin-left: 0 !important;
        }
    }
    
    /* Premium Gold Banner */
    .gold-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); 
        border-radius: 20px; 
        padding: 40px;
        color: #ffffff; 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); 
        border: 1px solid rgba(212, 175, 55, 0.3); 
        position: relative; 
        overflow: hidden; 
        z-index: 1;
    }

    /* Decorative Glowing Sphere */
    .gold-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, transparent 70%);
        border-radius: 50%;
        z-index: -1;
    }
    
    /* Summary Card Styling */
    .summary-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.05);
    }

    /* Professional Action Cards */
    .action-card {
        background: #ffffff; 
        border: 1px solid #e2e8f0; 
        border-radius: 20px; 
        padding: 35px 20px;
        text-align: center; 
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 8px 20px rgba(0,0,0,0.03);
        text-decoration: none; 
        display: block; 
        color: #1e293b; 
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    /* Top Border Gradient Animation */
    .action-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #d4af37, #fcf6ba);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: rgba(212, 175, 55, 0.4);
    }
    
    .action-card:hover::before {
        opacity: 1;
    }

    .action-card:hover h5 {
        color: #aa771c; 
    }
    
    /* Springy Icon Animation */
    .icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin: 0 auto 20px auto;
        font-size: 32px;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .action-card:hover .icon-wrapper {
        transform: scale(1.15) rotate(5deg);
    }
</style>

<div class="main-content">
    <div class="container-fluid p-4 p-lg-5 flex-grow-1">
        
        <!-- Welcome Banner -->
        <div class="gold-banner mb-5"> 
            <h2 class="fw-bold mb-2 tracking-tight">Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2> 
            <p class="text-white-50 mb-0 fs-6">Staff Dashboard • Manage your assigned tasks and notifications efficiently.</p> 
        </div>

        <div class="row g-4 mb-5">
            
            <!-- Active Tasks Summary Card -->
            <div class="col-12"> 
                <div class="summary-card p-4 p-lg-5 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div>
                        <h6 class="text-muted fw-bold mb-2 text-uppercase tracking-wider">Your Pending Tasks</h6> 
                        <h1 class="text-dark fw-bold mb-0 display-4">
                            <?php echo $pending_tasks; ?> 
                            <span class="fs-5 text-muted fw-medium ms-2">of <?php echo $total_tasks; ?> total</span> 
                        </h1>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center rounded-circle shadow-sm flex-shrink-0" style="width: 80px; height: 80px;">
                        <i class="fas fa-clipboard-list fa-2x"></i> 
                    </div>
                </div>
            </div>

            <!-- Action Buttons / Cards -->
            <div class="col-md-4"> 
                <a href="my_tasks.php" class="action-card"> 
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary"> 
                        <i class="fas fa-tasks"></i> 
                    </div>
                    <h5 class="fw-bold mb-2">My Tasks</h5> 
                    <p class="text-muted small mb-0">View and update complaints</p> 
                </a>
            </div>

            <div class="col-md-4"> 
                <a href="notifications.php" class="action-card"> 
                    <div class="icon-wrapper bg-warning bg-opacity-10 text-warning"> 
                        <i class="fas fa-bell"></i> 
                    </div>
                    <h5 class="fw-bold mb-2">Notifications</h5> 
                    <p class="text-muted small mb-0">Check recent alerts</p> 
                </a>
            </div>

            <div class="col-md-4"> 
                <a href="../settings.php" class="action-card"> 
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success"> 
                        <i class="fas fa-user-circle"></i> 
                    </div>
                    <h5 class="fw-bold mb-2">My Profile</h5> 
                    <p class="text-muted small mb-0">Update account details</p> 
                </a>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-auto w-100"> 
        <div class="container text-center text-muted small fw-medium"> 
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved. 
        </div>
    </footer>
</div>