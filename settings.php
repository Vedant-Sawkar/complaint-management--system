<?php
include "includes/session.php"; //[cite: 13]
include "includes/connection.php"; //[cite: 13]
include "includes/header.php"; //[cite: 13]


$user_id = $_SESSION['user_id']; //[cite: 13]
$message = ""; //[cite: 13]
$msg_type = ""; //[cite: 13]

// Securely fetching user data[cite: 13]
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?"); //[cite: 13]
$stmt->bind_param("i", $user_id); //[cite: 13]
$stmt->execute(); //[cite: 13]
$user = $stmt->get_result()->fetch_assoc(); //[cite: 13]

// Check if user is admin[cite: 13]
$is_admin = false; //[cite: 13]
if (isset($user['role']) && $user['role'] === 'admin') { //[cite: 13]
    $is_admin = true; //[cite: 13]
}

if (isset($_POST['save'])) { //[cite: 13]

    $name = trim($_POST['name']); //[cite: 13]
    $email = trim($_POST['email']); //[cite: 13]
    $mobile = trim($_POST['mobile']); //[cite: 13]
    $password = $_POST['password'];  //[cite: 13]
    
    $email_notifications = 0; //[cite: 13]
    if (isset($_POST['email_notifications'])) { //[cite: 13]
        $email_notifications = 1; //[cite: 13]
    }
    
    $theme_preference = 'light'; //[cite: 13]
    if (isset($_POST['theme_preference'])) { //[cite: 13]
        $theme_preference = $_POST['theme_preference']; //[cite: 13]
    }

    // Password Update Logic[cite: 13]
    if (!empty($password)) { //[cite: 13]
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); //[cite: 13]
        $sql = "UPDATE users SET name=?, email=?, mobile=?, password=?, email_notifications=?, theme_preference=? WHERE id=?"; //[cite: 13]
        $stmt_update = $conn->prepare($sql); //[cite: 13]
        $stmt_update->bind_param("ssssisi", $name, $email, $mobile, $hashed_password, $email_notifications, $theme_preference, $user_id); //[cite: 13]
    } else {
        $sql = "UPDATE users SET name=?, email=?, mobile=?, email_notifications=?, theme_preference=? WHERE id=?"; //[cite: 13]
        $stmt_update = $conn->prepare($sql); //[cite: 13]
        $stmt_update->bind_param("sssisi", $name, $email, $mobile, $email_notifications, $theme_preference, $user_id); //[cite: 13]
    }

    $update_success = $stmt_update->execute(); //[cite: 13]

    if ($update_success) { //[cite: 13]
        $_SESSION['name'] = $name; //[cite: 13]
        
        // Refresh local user array[cite: 13]
        $user['name'] = $name; //[cite: 13]
        $user['email'] = $email; //[cite: 13]
        $user['mobile'] = $mobile; //[cite: 13]
        $user['email_notifications'] = $email_notifications; //[cite: 13]
        $user['theme_preference'] = $theme_preference; //[cite: 13]

        $message = "Settings and system preferences updated successfully!"; //[cite: 13]
        $msg_type = "success"; //[cite: 13]
    } else {
        $message = "Failed to update settings. Please try again."; //[cite: 13]
        $msg_type = "danger"; //[cite: 13]
    }
}

$current_role = 'User'; //[cite: 13]
if (isset($user['role'])) { //[cite: 13]
    $current_role = $user['role']; //[cite: 13]
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Settings - Complaint CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Base styles */
        body { background: #f8fafc; font-family: "Segoe UI", Roboto, sans-serif; } /*[cite: 13]*/
        .page-header { background: white; padding: 25px 30px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #e2e8f0; } /*[cite: 13]*/
        .page-title { font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; } /*[cite: 13]*/
        
        .settings-card { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; background: white; } /*[cite: 13]*/
        .settings-header { background: linear-gradient(135deg, #2563eb, #7c3aed); color: white; padding: 30px; } /*[cite: 13]*/
        .profile-icon { width: 90px; height: 90px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 35px; margin: auto; border: 4px solid #bfdbfe; } /*[cite: 13]*/
        
        .form-control, .form-select { border-radius: 12px; padding: 12px 16px; border: 1px solid #cbd5e1; background-color: #f8fafc; } /*[cite: 13]*/
        .form-control:focus, .form-select:focus { background-color: #fff; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); } /*[cite: 13]*/
        
        .btn-save { background: linear-gradient(135deg, #2563eb, #7c3aed); border: none; color: white; padding: 12px 30px; border-radius: 12px; font-weight: 600; transition: 0.3s; } /*[cite: 13]*/
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2); } /*[cite: 13]*/
        
        .form-label { font-weight: 600; color: #475569; margin-bottom: 8px; } /*[cite: 13]*/
        .section-title { font-size: 18px; font-weight: 700; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px; margin-top: 25px; } /*[cite: 13]*/
    </style>
</head>

<!-- Added Bootstrap flex utility classes to ensure full height and sticky footer -->
<body class="d-flex flex-column min-vh-100">

<!-- Added flex-grow-1 to push the footer to the bottom -->
<div class="container py-5 flex-grow-1">
    
    <!-- Page Header[cite: 13] -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title"><i class="fa-solid fa-gear text-primary me-2"></i> Account Settings</h1>
            <p class="text-muted mb-0 mt-1">Manage profile, security credentials, and system preferences</p>
        </div>
        <div>
            <a href="<?php echo $is_admin ? 'admin/dashboard.php' : 'user/dashboard.php'; ?>" class="btn btn-light border shadow-sm rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card settings-card">
                <div class="settings-header">
                    <h3 class="fw-bold mb-1"><i class="fa-solid fa-sliders me-2"></i> Control Center</h3>
                    <p class="mb-0 text-white-50">Customized configuration panel based on your clearance level</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <div class="profile-box text-center mb-4">
                        <div class="profile-icon mb-3">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($user['name']); ?></h4>
                        <p class="text-muted small mb-0"><?php echo htmlspecialchars($user['email']); ?> | <span class="badge bg-primary text-uppercase"><?php echo htmlspecialchars($current_role); ?></span></p>
                    </div>

                    <?php if ($message != "") { ?>
                        <div class="alert alert-<?php echo $msg_type; ?> rounded-3 shadow-sm mb-4">
                            <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">
                        
                        <!-- Personal Information Section[cite: 13] -->
                        <div class="section-title">
                            <i class="fas fa-id-card text-primary me-2"></i> Personal Information
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                            </div>
                        </div>

                        <!-- System Preferences Section[cite: 13] -->
                        <div class="section-title">
                            <i class="fas fa-sliders-h text-primary me-2"></i> User Preferences
                        </div>

                        <div class="row mb-4">
                            <!-- Theme Preference[cite: 13] -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label">Default Theme Mode</label>
                                <select name="theme_preference" class="form-select">
                                    <?php 
                                        $t_pref = 'light';
                                        if (isset($user['theme_preference'])) {
                                            $t_pref = $user['theme_preference'];
                                        }
                                    ?>
                                    <option value="light" <?php if($t_pref == 'light') echo 'selected'; ?>>Light Mode</option>
                                    <option value="dark" <?php if($t_pref == 'dark') echo 'selected'; ?>>Dark Mode</option>
                                </select>
                            </div>

                            <!-- Email Notification Toggle[cite: 13] -->
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-4 pt-2">
                                    <?php 
                                        $e_notif = 1;
                                        if (isset($user['email_notifications'])) {
                                            $e_notif = $user['email_notifications'];
                                        }
                                    ?>
                                    <input class="form-check-input" type="checkbox" role="switch" id="emailNotif" name="email_notifications" value="1" <?php if($e_notif == 1) echo 'checked'; ?> style="width: 50px; height: 25px; cursor: pointer;">
                                    <label class="form-check-label ms-3 fw-semibold text-secondary" for="emailNotif" style="cursor: pointer;">Receive Email Alerts</label>
                                </div>
                            </div>
                        </div>

                        <!-- ADMIN HIGH-LEVEL CONFIGURATIONS[cite: 13] -->
                        <?php if ($is_admin) { ?>
                        <div class="section-title text-danger">
                            <i class="fas fa-shield-halved me-2"></i> High-Level Admin Configurations
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">System Platform Name</label>
                                <input type="text" name="system_name" class="form-control" value="Complaint Management System">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Default Role for New Registrations</label>
                                <select name="default_user_role" class="form-select">
                                    <option value="user" selected>User / Citizen</option>
                                    <option value="staff">Staff Member</option>
                                    <option value="manager">Manager</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border">
                                    <input class="form-check-input ms-0 me-3" type="checkbox" role="switch" id="maintMode" name="maintenance_mode" value="1" style="width: 50px; height: 25px; cursor: pointer;">
                                    <div>
                                        <label class="form-check-label fw-bold text-danger d-block" for="maintMode" style="cursor: pointer;">Enable System Maintenance Mode</label>
                                        <small class="text-muted">When enabled, regular users will temporarily be unable to log complaints or access standard dashboards.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                            <a href="<?php echo $is_admin ? 'admin/dashboard.php' : 'user/dashboard.php'; ?>" class="text-decoration-none text-muted fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                            <button type="submit" name="save" class="btn btn-save">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save All Changes
                            </button>
                        </div>

                    </form>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>