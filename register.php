<?php
include "includes/connection.php"; 
include "includes/header.php"; 

$message = ""; 

if (isset($_POST['register'])) { 

    $name = trim($_POST['name']); 
    $email = trim($_POST['email']); 
    $mobile = trim($_POST['mobile']); 
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password']; 

    if ($password != $confirm_password) { 
        $message = "Passwords do not match"; 
    } else {
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

        // Check if email exists securely
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?"); 
        $stmt_check->bind_param("s", $email); 
        $stmt_check->execute(); 
        $stmt_check->store_result(); 

        if ($stmt_check->num_rows > 0) { 
            $message = "Email already exists"; 
        } else {
            // Insert secure query
            $stmt_insert = $conn->prepare("INSERT INTO users (name, email, mobile, password) VALUES (?, ?, ?, ?)"); 
            $stmt_insert->bind_param("ssss", $name, $email, $mobile, $hashed_password); 

            if ($stmt_insert->execute()) { 
                $message = "Registration Successful"; 
            } else {
                $message = "Registration Failed"; 
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
            min-height: 100vh; 
            /* Animated Gradient Overlay + Background Image */
            background: linear-gradient(-45deg, rgba(248, 250, 252, 0.95), rgba(226, 232, 240, 0.85), rgba(248, 250, 252, 0.95)), 
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1920&auto=format&fit=crop') no-repeat center center fixed;
            background-size: 400% 400%, cover;
            animation: gradientShift 15s ease infinite;
            
            font-family: 'Inter', sans-serif; 
            display: flex; 
            flex-direction: column; 
            margin: 0; 
        }
        
        .main-content { 
            flex: 1; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 40px 15px; 
        }

        .floating-icon {
            animation: float 4s ease-in-out infinite;
        }
        
        .card { 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px); 
            border-radius: 1rem; 
            border: none; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden; 
            /* Slide up effect on load */
            opacity: 0;
            animation: slideUpFade 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            animation-delay: 0.1s;
        }
        
        .card-body { 
            padding: 40px 35px; 
        }
        
        label { 
            color: #4b5563; 
            font-weight: 500; 
            margin-bottom: 8px; 
            font-size: 0.95rem; 
        }
        
        .form-control { 
            background: #f9fafb; 
            border: 1px solid #e5e7eb; 
            padding: 12px 15px; 
            border-radius: 10px; 
            color: #1f2937; 
            transition: all 0.3s ease; 
        }
        
        .form-control:focus { 
            background: #ffffff; 
            border-color: #4f46e5; 
            box-shadow: 0 0 0 4px rgba(79,70,229,0.15); 
            transform: translateY(-1px);
        }

        /* Improved eye icon button */
        .input-group .btn {
            border: 1px solid #e5e7eb; 
            border-left: none; 
            background: #f9fafb; 
            color: #6b7280; 
            border-radius: 0 10px 10px 0; 
            transition: all 0.3s ease; 
        }

        .input-group .form-control {
            border-right: none; 
            border-radius: 10px 0 0 10px; 
        }

        .input-group .form-control:focus + .btn {
            border-color: #4f46e5; 
            background: #ffffff; 
            color: #4f46e5; 
        }
        
        .btn-register { 
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: #fff; 
            border: none; 
            width: 100%; 
            padding: 14px; 
            border-radius: 10px; 
            font-weight: 600; 
            font-size: 1rem; 
            margin-top: 15px; 
            transition: all 0.3s ease;
            background-size: 200% auto;
        }
        
        .btn-register:hover { 
            background-position: right center;
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
            transform: translateY(-2px); 
            color: #fff; 
        }
        
        .login-link { 
            color: #6b7280; 
            font-size: 0.95rem; 
        }

        .login-link a {
            color: #2563eb; 
            font-weight: 600; 
            text-decoration: none; 
            transition: color 0.3s ease; 
        }

        .login-link a:hover {
            color: #1e3a8a; 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
<script>
function togglePassword() { 
    let pass = document.getElementById("password"); 
    let icon = document.getElementById("toggleIcon"); 
    
    if (pass.type === "password") { 
        pass.type = "text"; 
        icon.classList.remove("fa-eye"); 
        icon.classList.add("fa-eye-slash"); 
    } else {
        pass.type = "password"; 
        icon.classList.remove("fa-eye-slash"); 
        icon.classList.add("fa-eye"); 
    }
}
</script>

<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                
                <!-- Match Logo/Header Title spacing to Login -->
                <div class="text-center mb-4">
                    <div class="floating-icon d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-lg mb-3" style="width: 64px; height: 64px;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h1 class="h3 fw-bold text-dark tracking-tight">Create Account</h1>
                    <p class="text-muted small">Join the Complaint CMS portal today</p>
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if ($message != "") { ?>
                            <div class="alert alert-info text-center"><?php echo $message; ?></div>
                        <?php } ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase tracking-wider text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control shadow-sm" placeholder="John Doe" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase tracking-wider text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control shadow-sm" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase tracking-wider text-muted">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control shadow-sm" placeholder="+91 555 000-0000" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase tracking-wider text-muted">Password</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                                    <button type="button" class="btn" onclick="togglePassword()">
                                        <i id="toggleIcon" class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase tracking-wider text-muted">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control shadow-sm" placeholder="••••••••" required>
                            </div>
                            <button type="submit" name="register" class="btn btn-register shadow-sm mt-3">
                                <i class="fa-solid fa-user-plus me-2"></i> Register
                            </button>
                        </form>

                        <hr class="my-4 text-muted opacity-15">

                        <p class="mb-0 text-center login-link">
                            Already have an account? <a href="login.php">Log in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php include "includes/footer.php"; ?>