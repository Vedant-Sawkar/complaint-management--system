<?php
// Ensure session is started if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base = "";
$current = dirname($_SERVER['PHP_SELF']);

if (
    strpos($current, "/admin") !== false ||
    strpos($current, "/user") !== false ||
    strpos($current, "/manager") !== false ||
    strpos($current, "/staff") !== false
) {
    $base = "../";
}

// Check if a user is logged in securely
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Determine dashboard link based on user role when logged in
$dashboard = $base . "index.php";
$notificationLink = "#";
$role = "";

if ($isLoggedIn) {
    $role = strtolower($_SESSION['role'] ?? '');
    switch ($role) {
        case "admin":
            $dashboard = $base . "admin/dashboard.php";
            $notificationLink = $base . "admin/notifications.php";
            break;
        case "manager":
            $dashboard = $base . "manager/dashboard.php";
            $notificationLink = $base . "manager/notifications.php";
            break;
        case "staff":
            $dashboard = $base . "staff/dashboard.php";
            $notificationLink = $base . "staff/notifications.php";
            break;
        default:
            $dashboard = $base . "user/dashboard.php";
            $notificationLink = $base . "user/notifications.php";
            break;
    }
}

// Get dynamic user details if logged in
$userName = $isLoggedIn ? ($_SESSION['name'] ?? 'User') : '';
$userRole = $isLoggedIn ? ucfirst($_SESSION['role'] ?? 'User') : ''; 
$avatarName = urlencode($userName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional Complaint Management System for efficient issue tracking and resolution.">
    <meta name="theme-color" content="#1e293b">
    
    <title>Complaint Management System</title>

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #f8fafc; 
        }
        
        .app-header {
            background-color: #1e293b; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .header-brand-icon {
            width: 36px;
            height: 36px;
            background: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Notification Badge CSS */
        .notification-badge {
            width: 10px;
            height: 10px;
            background-color: #ef4444;
            border: 2px solid #1e293b;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            right: 2px;
        }

        .dropdown-menu-custom {
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            padding: 0.5rem;
        }
        
        .dropdown-menu-custom .dropdown-item {
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .dropdown-menu-custom .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .dropdown-toggle.no-caret::after {
            display: none;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.75);
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .nav-link-custom:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.1);
        }

        @media (max-width: 991.98px) {
            .mobile-divider {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin: 15px 0;
            }
        }
    </style>
</head>
<body>

    <!-- Professional Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark app-header shadow-sm sticky-top py-2">
        <div class="container-fluid px-4">
            
            <!-- Brand / Logo -->
            <a class="navbar-brand d-flex align-items-center fw-bold tracking-tight me-4" href="<?php echo $isLoggedIn ? $dashboard : $base . 'index.php'; ?>">
                <div class="header-brand-icon me-3">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span class="d-none d-sm-inline">Complaint CMS</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navbar Links & Actions -->
            <div class="collapse navbar-collapse" id="topNavbar">
                
                <!-- Quick Navigation Options (Left Aligned) -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-3 mt-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom px-3" href="<?php echo $base; ?>index.php">
                            <i class="fas fa-globe me-2"></i>Website
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom px-3" href="<?php echo $dashboard; ?>">
                                <i class="fas fa-chart-pie me-2"></i>Dashboard
                            </a>
                        </li>
                        
                        <!-- NEW COMPLAINT: HIDDEN FOR STAFF -->
                        <?php if ($role === 'user'): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom px-3" href="<?php echo $base; ?>user/add_complaint.php">
                                <i class="fas fa-plus-circle me-2"></i>New Complaint
                            </a>
                        </li>
                        <?php endif; ?>
                        
                    <?php endif; ?>
                </ul>

                <div class="mobile-divider d-lg-none"></div>

                <!-- User Actions (Right Aligned) -->
                <ul class="navbar-nav ms-auto align-items-lg-center gap-3">
                    
                    <?php if ($isLoggedIn): ?>
                        
                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown position-relative">
                            <a class="nav-link text-white-50 dropdown-toggle no-caret px-2" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fs-5"></i>
                                <span class="notification-badge"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom mt-3 shadow-lg" aria-labelledby="notificationDropdown" style="width: 280px;">
                                <li>
                                    <div class="dropdown-header d-flex justify-content-between align-items-center fw-bold text-uppercase text-muted small tracking-wider">
                                        Notifications
                                        <span class="badge bg-primary rounded-pill">New</span>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li>
                                    <a class="dropdown-item py-2" href="<?php echo $notificationLink; ?>">
                                        <small class="d-block fw-semibold text-dark">System Alert</small>
                                        <small class="text-muted d-block text-truncate">Check your latest ticket updates.</small>
                                        <small class="text-primary" style="font-size: 0.7rem;">Just now</small>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li><a class="dropdown-item text-center py-2 text-primary fw-bold small" href="<?php echo $notificationLink; ?>">View All Notifications</a></li>
                            </ul>
                        </li>

                        <!-- Dynamic User Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white p-0 ps-lg-3 text-decoration-none" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name=<?php echo $avatarName; ?>&background=38bdf8&color=fff&bold=true" alt="Profile" class="rounded-circle me-2 border border-2 border-secondary" width="40" height="40">
                                <div class="text-start lh-1">
                                    <span class="d-block fw-semibold fs-6"><?php echo htmlspecialchars($userName); ?></span>
                                    <small class="text-white-50" style="font-size: 0.75rem;"><?php echo htmlspecialchars($userRole); ?></small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom mt-3 shadow-lg" aria-labelledby="userDropdown">
                                <li><div class="dropdown-header fw-bold text-uppercase text-muted small tracking-wider">Account</div></li>
                                <li><a class="dropdown-item py-2" href="<?php echo $base; ?>settings.php"><i class="fas fa-user-circle me-2 text-muted"></i> My Profile</a></li>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-bold" href="<?php echo $base; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </li>

                    <?php else: ?>
                        <!-- Public Links -->
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom fw-semibold" href="<?php echo $base; ?>login.php">
                                <i class="fas fa-right-to-bracket me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-info btn-sm ms-lg-2 fw-bold rounded-pill px-3 shadow-sm" href="<?php echo $base; ?>register.php">
                                Create Account
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

</body>
</html>