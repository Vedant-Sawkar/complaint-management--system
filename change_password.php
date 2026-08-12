<?php
include "includes/session.php"; //[cite: 14]
include "includes/connection.php"; //[cite: 14]

$user_id = $_SESSION['user_id']; //[cite: 14]
$message = ""; //[cite: 14]
$msg_type = "info"; //[cite: 14]

if (isset($_POST['change_password'])) { //[cite: 14]

    $current_password = $_POST['current_password']; //[cite: 14]
    $new_password = $_POST['new_password']; //[cite: 14]
    $confirm_password = $_POST['confirm_password']; //[cite: 14]

    // Prepared Statement[cite: 14]
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?"); //[cite: 14]
    $stmt->bind_param("i", $user_id); //[cite: 14]
    $stmt->execute(); //[cite: 14]
    $result = $stmt->get_result(); //[cite: 14]
    $user = $result->fetch_assoc(); //[cite: 14]

    if ($user && password_verify($current_password, $user['password'])) { //[cite: 14]

        if ($new_password === $confirm_password) { //[cite: 14]
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); //[cite: 14]

            $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?"); //[cite: 14]
            $stmt_update->bind_param("si", $hashed_password, $user_id); //[cite: 14]
            $stmt_update->execute(); //[cite: 14]

            $message = "Password changed successfully."; //[cite: 14]
            $msg_type = "success"; //[cite: 14]
        } else {
            $message = "New password and confirm password do not match."; //[cite: 14]
            $msg_type = "danger"; //[cite: 14]
        }
    } else {
        $message = "Current password is incorrect."; //[cite: 14]
        $msg_type = "danger"; //[cite: 14]
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change Password - Complaint CMS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f8fafc; font-family: "Segoe UI", Roboto, sans-serif; } /*[cite: 14]*/
        
        .page-header { background: white; padding: 25px 30px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #e2e8f0; } /*[cite: 14]*/
        .page-title { font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; } /*[cite: 14]*/

        .form-card { border: none; border-radius: 20px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; overflow: hidden; } /*[cite: 14]*/
        
        .form-control { border-radius: 12px; padding: 12px 16px; border: 1px solid #cbd5e1; background-color: #f8fafc; } /*[cite: 14]*/
        .form-control:focus { background-color: #fff; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); } /*[cite: 14]*/
        
        .btn-custom { background: linear-gradient(135deg, #2563eb, #7c3aed); border: none; color: white; padding: 12px 30px; border-radius: 12px; font-weight: 600; transition: 0.3s; } /*[cite: 14]*/
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2); } /*[cite: 14]*/
        
        .form-label { font-weight: 600; color: #475569; margin-bottom: 8px; } /*[cite: 14]*/
    </style>
</head>
<!-- Added flexbox layout to the body to ensure the footer sticks to the bottom -->
<body class="d-flex flex-column min-vh-100">

<!-- Added flex-grow-1 to allow the content to push the footer down -->
<div class="container py-5 flex-grow-1">
    
    <!-- Header Section[cite: 14] -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title"><i class="fas fa-key text-primary me-2"></i> Change Password</h1>
            <p class="text-muted mb-0 mt-1">Update your account security password</p>
        </div>
        <div>
            <a href="user/dashboard.php" class="btn btn-light border shadow-sm rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Form Card Container[cite: 14] -->
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="form-card p-4 p-md-5">
                <h4 class="fw-bold text-dark mb-4"><i class="fas fa-shield-alt text-primary me-2"></i> Security Credentials</h4>
                
                <?php if ($message != "") { ?>
                    <div class="alert alert-<?php echo $msg_type; ?> rounded-3 shadow-sm mb-4">
                        <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter new password" required>
                    </div>
                    
                    <div class="text-end mt-4">
                        <button type="submit" name="change_password" class="btn btn-custom w-150">
                            <i class="fa-solid fa-save me-2"></i> Update Password
                        </button>
                    </div>
                </form>
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