<?php
include "../includes/session.php";
include "../includes/connection.php";

// Fetch current user details from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// Handle Profile Update (Basic Example)
$message = "";
if (isset($_POST['update_profile'])) {
    $newName = trim($_POST['name']);
    $newEmail = trim($_POST['email']);
    
    $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $updateStmt->bind_param("ssi", $newName, $newEmail, $user_id);
    
    if ($updateStmt->execute()) {
        $_SESSION['name'] = $newName; // Update session variable
        $message = "<div class='alert alert-success rounded-3 shadow-sm'><i class='fas fa-check-circle me-2'></i> Profile updated successfully!</div>";
        $userData['name'] = $newName;
        $userData['email'] = $newEmail;
    } else {
        $message = "<div class='alert alert-danger rounded-3 shadow-sm'><i class='fas fa-exclamation-circle me-2'></i> Failed to update profile.</div>";
    }
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!-- Main Content Wrapper to prevent sidebar overlap -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark"><i class="fas fa-user-circle text-primary me-2"></i> My Profile</h2>
                <p class="text-muted">Manage your account settings and personal information</p>
            </div>
        </div>

        <?php echo $message; ?>

        <div class="row g-4">
            <!-- Left Column: User Card -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #1e293b, #3b82f6); height: 120px;"></div>
                    <div class="card-body text-center position-relative pt-0 pb-4">
                        
                        <!-- Avatar -->
                        <div class="d-inline-block position-relative" style="margin-top: -50px;">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userData['name']); ?>&background=ffffff&color=3b82f6&size=120&bold=true" 
                                 alt="Profile Avatar" 
                                 class="rounded-circle border border-4 border-white shadow-sm">
                        </div>
                        
                        <h4 class="fw-bold mt-3 mb-1"><?php echo htmlspecialchars($userData['name']); ?></h4>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2 mb-3">
                            <i class="fas fa-shield-alt me-1"></i> <?php echo ucfirst(htmlspecialchars($userData['role'])); ?>
                        </span>
                        
                        <ul class="list-group list-group-flush text-start mt-3 border-top pt-3">
                            <li class="list-group-item border-0 px-0 d-flex align-items-center text-muted">
                                <i class="fas fa-envelope text-primary me-3 w-20px text-center"></i> 
                                <?php echo htmlspecialchars($userData['email']); ?>
                            </li>
                            <li class="list-group-item border-0 px-0 d-flex align-items-center text-muted">
                                <i class="fas fa-calendar-alt text-primary me-3 w-20px text-center"></i> 
                                Account Active
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Form -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold text-dark">Edit Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" name="name" class="form-control form-control-lg border-start-0 bg-light" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control form-control-lg border-start-0 bg-light" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                                    </div>
                                </div>

                                <!-- Note: Password update logic would require a separate query with password_hash() -->
                                <div class="col-12 mb-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Role <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg bg-light text-muted" value="<?php echo ucfirst(htmlspecialchars($userData['role'])); ?>" disabled readonly>
                                    <small class="form-text text-muted"><i class="fas fa-info-circle me-1"></i> Roles cannot be changed from this panel.</small>
                                </div>
                            </div>

                            <hr class="text-muted opacity-15 mb-4">
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="update_profile" class="btn btn-primary btn-lg rounded-3 fw-bold shadow-sm px-4">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- INCLUDED FOOTER -->
    <?php include "../includes/footer.php"; ?>
</div>

</body>
</html>