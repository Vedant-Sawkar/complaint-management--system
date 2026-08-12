<?php
// Ensure session is started in the parent file before this is included
include_once "connection.php";

$user_id = $_SESSION['user_id'];

// Secure Query using Prepared Statement
$stmt = $conn->prepare("SELECT id FROM notifications WHERE user_id=? AND status='Unread'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$count = $stmt->num_rows;
$stmt->close();
?>

<nav class="navbar navbar-expand-lg navbar-custom shadow">

    <div class="container-fluid">

        <!-- Left Side -->
        <div class="d-flex align-items-center">
            <button class="btn menu-btn me-3">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="text-white fw-bold mb-0">
                Complaint CMS
            </h4>
        </div>

        <!-- Search -->
        <div class="search-box mx-auto">
            <input
                type="text"
                class="form-control"
                placeholder="Search complaints..."
            >
        </div>

        <!-- Right Side -->
        <div class="d-flex align-items-center">

            <!-- Notification -->
            <a href="notifications.php" class="notification-icon text-white me-4 position-relative">
                <i class="fas fa-bell fa-lg"></i>
                <?php if ($count > 0) { ?>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                        <?php echo htmlspecialchars($count); ?>
                    </span>
                <?php } ?>
            </a>

            <!-- Profile -->
            <div class="dropdown">
                <a href="#" class="text-decoration-none dropdown-toggle text-white d-flex align-items-center" data-bs-toggle="dropdown">
                    <img src="../assets/uploads/default.png" class="profile-img me-2" alt="Profile">
                    <!-- Securely output the session name preventing XSS -->
                    <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <h6 class="dropdown-header">
                            <!-- Securely output the session name -->
                            <?php echo htmlspecialchars($_SESSION['name']); ?>
                        </h6>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../profile.php">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="dashboard.php">
                            <i class="fas fa-gauge me-2"></i> Dashboard
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

<style>
.navbar-custom { background: linear-gradient(135deg, #0f172a, #1e3a8a); padding: 15px 25px; margin-left: 260px; }
.menu-btn { background: rgba(255,255,255,0.15); color: white; border: none; width: 42px; height: 42px; border-radius: 10px; }
.menu-btn:hover { background: rgba(255,255,255,0.25); }
.search-box { width: 350px; }
.search-box input { border-radius: 25px; border: none; padding: 10px 20px; }
.search-box input:focus { box-shadow: 0 0 10px rgba(255,255,255,0.3); }
.notification-icon { text-decoration: none; }
.profile-img { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid white; }
.dropdown-menu { border: none; border-radius: 15px; min-width: 220px; }
.dropdown-item { padding: 10px 18px; }
.dropdown-item:hover { background: #f3f4f6; }
@media (max-width: 992px) {
    .navbar-custom { margin-left: 0; }
    .search-box { display: none; }
}
</style>