<?php
include "includes/connection.php";

$message = "";

if (isset($_POST['submit'])) {

    $email = trim($_POST['email']);

    // Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {

        // Secure Token Generation
        $token = bin2hex(random_bytes(32));

        $stmt_update = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt_update->bind_param("ss", $token, $email);
        $stmt_update->execute();

        // Dynamic URL instead of hardcoded localhost
        $host = $_SERVER['HTTP_HOST'];
        $link = "http://$host/complaint-management-system/reset_password.php?token=$token";

        $message = "Reset link generated: <br><a href='$link' class='alert-link'>$link</a>";

    } else {
        $message = "Email not found in our system.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password - Complaint CMS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f8fafc; font-family: "Segoe UI", Roboto, sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .card-header { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border-radius: 20px 20px 0 0 !important; padding: 25px; text-align: center; }
        .form-control { border-radius: 12px; padding: 12px 16px; border: 1px solid #cbd5e1; background-color: #f8fafc; }
        .form-control:focus { background-color: #fff; border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .btn-primary { border-radius: 12px; padding: 12px; font-weight: 600; background: #2563eb; border: none; }
        .btn-primary:hover { background: #1d4ed8; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-key me-2"></i> Forgot Password</h3>
                    <p class="mb-0 text-white-50 small">Enter your email to reset your password</p>
                </div>
                <div class="card-body p-4">
                    <?php if ($message != "") { ?>
                        <div class="alert alert-info rounded-3 shadow-sm"><?php echo $message; ?></div>
                    <?php } ?>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100 mb-3">
                            Send Reset Link
                        </button>
                        <div class="text-center">
                            <a href="login.php" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i> Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>