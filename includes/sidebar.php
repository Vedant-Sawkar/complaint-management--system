<?php
// Put this at the very top of your includes/sidebar.php
$base_url = "/Backhand/complaint-management-system"; 
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="sidebar">

    <div class="logo text-center text-white py-3 border-bottom border-secondary">
        <h4 class="fw-bold mb-0"><i class="fas fa-shield-alt text-primary me-2"></i>Complaint CMS</h4>
    </div>

    <ul class="mt-3">
        <?php 
        // Get the current user's role from session
        $role = $_SESSION['role'] ?? 'user'; 
        ?>

        <!-- Common Dashboard Link (FIXED: Now dynamically routes based on role) -->
        <li>
           <a href="<?php echo $base_url; ?>/<?php echo $role; ?>/dashboard.php" class="nav-link">
                <i class="fas fa-home"></i> Dashboard
          </a>
        </li>

        <!-- ADMIN ONLY LINKS -->
        <?php if ($role === 'admin') { ?>
            <li>
                <a href="view_complaints.php">
                    <i class="fas fa-file-alt me-2"></i> All Complaints
                </a>
            </li>
            <li>
                <a href="assign_complaint.php">
                    <i class="fas fa-tasks me-2"></i> Assign Complaints
                </a>
            </li>
            <li>
                <a href="users.php">
                    <i class="fas fa-users me-2"></i> Manage Users
                </a>
            </li>
            <li>
                <a href="categories.php">
                    <i class="fas fa-list me-2"></i> Categories
                </a>
            </li>
            <li>
                <a href="../reports/report.php">
                    <i class="fas fa-chart-pie me-2"></i> Reports & Analytics
                </a>
            </li>
            <li>
                <a href="activity_logs.php">
                    <i class="fas fa-history me-2"></i> System Activity Logs
                </a>
            </li>
            <li>
                <a href="complaint_history.php">
                    <i class="fas fa-archive me-2"></i> Deleted Complaints
                </a>
            </li>
            <li>
                <a href="restore_users.php">
                    <i class="fas fa-user-clock me-2"></i> Restore Users
                </a>
            </li>
        <?php } ?>

        <!-- MANAGER ONLY LINKS -->
        <?php if ($role === 'manager') { ?>
            <li>
                <a href="assign_complaint.php">
                    <i class="fas fa-user-shield me-2"></i> Assign Complaints
                </a>
            </li>
        <?php } ?>

        <!-- STAFF ONLY LINKS -->
        <?php if ($role === 'staff') { ?>
            <li>
                <a href="my_tasks.php">
                    <i class="fas fa-clipboard-list me-2"></i> My Tasks
                </a>
            </li>
        <?php } ?>

        <!-- USER ONLY LINKS -->
        <?php if ($role === 'user') { ?>
            <li>
                <a href="<?php echo $base_url; ?>/user/my_complaints.php" class="nav-link">
                    <i class="fas fa-list"></i> My Complaints
                </a>
            </li>
            <li>
                <a href="<?php echo $base_url; ?>/user/add_complaint.php" class="nav-link">
                    <i class="fas fa-plus"></i> Add Complaint
                </a>
            </li>
        <?php } ?>

        <!-- COMMON SETTINGS (Visible to everyone) -->
        <li class="mt-4"><hr class="text-secondary"></li>
        
        <li>
            <a href="<?php echo $base_url; ?>/settings.php" class="nav-link">
                <i class="fas fa-cog"></i> Account Settings
            </a>
        </li>
        <li>
           <a href="<?php echo $base_url; ?>/change_password.php" class="nav-link">
                <i class="fas fa-key"></i> Change Password
            </a>
        </li>
        <li>
            <a href="#" onclick="toggleDarkMode()">
                <i class="fas fa-moon me-2"></i> Dark Mode Toggle
            </a>
        </li>
        <li>
            <a href="<?php echo $base_url; ?>/logout.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>

    </ul>
</div>

<style>
.sidebar {
    width:250px;
    height: 100vh;
    background: #0f172a; /* Modern dark blue/slate */
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    box-shadow: 4px 0 15px rgba(0,0,0,0.05);
}

.sidebar::-webkit-scrollbar {
    width: 6px;
}
.sidebar::-webkit-scrollbar-thumb {
    background-color: #334155;
    border-radius: 10px;
}

.sidebar ul {
    list-style: none;
    padding: 0 10px;
    margin: 0;
}

.sidebar ul li {
    padding: 3px 0;
}

.sidebar ul li a {
    display: block;
    color: #cbd5e1;
    text-decoration: none;
    padding: 12px 18px;
    font-size: 15px;
    font-weight: 500;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.sidebar ul li a:hover {
    background: #1e293b;
    color: #ffffff;
    padding-left: 25px;
}

.sidebar ul li a i {
    width: 25px;
    text-align: center;
}

body.dark-mode {
    background: #121212 !important;
    color: white !important;
}

body.dark-mode .card {
    background: #1f1f1f !important;
    color: white !important;
    border-color: #333 !important;
}

body.dark-mode .sidebar {
    background: #000000;
    border-right: 1px solid #222;
}
</style>

<script>
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
}

function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }
}
</script>