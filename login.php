<?php
session_start(); 
include "includes/connection.php"; 

$message = ""; 
$message_type = "danger"; // Default alert type

if (isset($_POST['login'])) { 

    $email = trim($_POST['email']); 
    $password = $_POST['password']; 

    // Prepared Statement for Security
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?"); 
    $stmt->bind_param("s", $email); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $user = $result->fetch_assoc(); 

    if ($user) { 
        if (password_verify($password, $user['password'])) { 

            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['name'] = $user['name']; 
            $_SESSION['role'] = $user['role']; 

            $user_id = $user['id']; 
            $activity = "User logged in"; 

            // Prepared Statement for Logging
            $stmt_log = $conn->prepare("INSERT INTO activity_logs (user_id, activity) VALUES (?, ?)"); 
            $stmt_log->bind_param("is", $user_id, $activity); 
            $stmt_log->execute(); 

            switch ($user['role']) { 
                case 'admin': 
                    header("Location: admin/dashboard.php"); 
                    exit(); 
                case 'manager': 
                    header("Location: manager/dashboard.php"); 
                    exit(); 
                case 'staff': 
                    header("Location: staff/dashboard.php"); 
                    exit(); 
                default: 
                    header("Location: user/dashboard.php"); 
                    exit(); 
            }
        } else {
            $message = "Invalid email or password entered."; 
        }
    } else {
        $message = "Invalid email or password entered."; // Generic message prevents user enumeration attacks
    }
}

include "includes/header.php"; 
?>

<!-- Custom CSS for enhanced UI polish & Special Effects -->
<style>
    /* CSS Animations */
    @keyframes slideUpFade {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    body {
        /* Animated Gradient Overlay + Background Image */
        background: linear-gradient(-45deg, rgba(248, 250, 252, 0.95), rgba(226, 232, 240, 0.85), rgba(248, 250, 252, 0.95)), 
                    url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1920&auto=format&fit=crop') no-repeat center center fixed;
        background-size: 400% 400%, cover;
        animation: gradientShift 15s ease infinite;
        
        min-height: 100vh; 
        display: flex; 
        flex-direction: column; 
        justify-content: space-between; 
    }
    
    .floating-icon {
        animation: float 4s ease-in-out infinite;
    }

    .login-card {
        border: none; 
        border-radius: 1rem; 
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(10px); 
        /* Slide up effect on load */
        opacity: 0;
        animation: slideUpFade 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        animation-delay: 0.1s;
    }
    
    .form-control {
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #2563eb; 
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15); 
        transform: translateY(-1px);
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #1e3a8a, #2563eb); 
        border: none; 
        transition: all 0.3s ease; 
        background-size: 200% auto;
    }
    
    .btn-primary-custom:hover {
        background-position: right center;
        box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px); 
    }
</style>

<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-9">
            
            <!-- Brand Logo / Header Title -->
            <div class="text-center mb-4">
                <div class="floating-icon d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-lg mb-3" style="width: 64px; height: 64px;">
                    <i class="fas fa-layer-group fa-2x"></i>
                </div>
                <h1 class="h3 fw-bold text-dark tracking-tight">Welcome Back</h1>
                <p class="text-muted small">Please sign in to access the Complaint CMS portal</p>
            </div>

            <div class="card login-card bg-white overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- Alert Notification -->
                    <?php if ($message != ""): ?>
                        <div class="alert alert-danger d-flex align-items-center p-3 mb-4 rounded-3 border-0 bg-danger bg-opacity-10 text-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2 flex-shrink-0"></i>
                            <div class="small fw-medium"><?php echo $message; ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase tracking-wider text-muted">Email Address</label>
                            <div class="input-group shadow-sm rounded-3">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3 ps-3"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control form-control-lg border-start-0 bg-light fs-6 rounded-end-3" placeholder="name@example.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label small fw-bold text-uppercase tracking-wider text-muted">Password</label>
                                <a href="forgot_password.php" class="text-decoration-none small text-primary fw-semibold">Forgot?</a>
                            </div>
                            <div class="input-group shadow-sm rounded-3">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3 ps-3"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control form-control-lg border-start-0 bg-light fs-6 rounded-end-3" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-primary-custom w-100 btn-lg rounded-3 py-3 fw-bold text-white shadow-sm mt-2">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </button>
                    </form>

                    <hr class="my-4 text-muted opacity-15">

                    <div class="text-center">
                        <p class="small text-muted mb-0">Don't have an account yet? <a href="register.php" class="text-decoration-none text-primary fw-bold">Create Account</a></p>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>