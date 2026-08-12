<?php
// Removed ../ because this file is in the main root directory
include "includes/session.php"; 
include "includes/connection.php"; 

$user_id = $_SESSION['user_id']; 
$message = ""; 
$msg_type = "success"; 

/* User Data - Prepared Statement */
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (isset($_POST['update'])) { 

    $name = trim($_POST['name']); 
    $email = trim($_POST['email']); 
    $mobile = trim($_POST['mobile']); 
    $address = trim($_POST['address'] ?? ''); 
    $image_name = $user['image'] ?? ''; 

    /* Secure Image Upload Validation */
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, $allowed_types)) {
            $image_name = time() . "_" . basename($_FILES['image']['name']);
            // Removed ../ from upload path
            move_uploaded_file($file_tmp, "assets/uploads/" . $image_name); 
        } else {
            $message = "Invalid file type. Only JPG, PNG, and GIF allowed."; 
            $msg_type = "danger"; 
        }
    }

    if($msg_type != "danger") {  
        $stmt_update = $conn->prepare("UPDATE users SET name=?, email=?, mobile=?, address=?, image=? WHERE id=?");
        $stmt_update->bind_param("sssssi", $name, $email, $mobile, $address, $image_name, $user_id);

        if ($stmt_update->execute()) {
            $_SESSION['name'] = $name; 
            $message = "Profile updated successfully!"; 
            $msg_type = "success"; 
            
            // Update local array so UI reflects changes immediately
            $user['name'] = $name; 
            $user['email'] = $email;
            $user['mobile'] = $mobile;
            $user['address'] = $address;
            $user['image'] = $image_name;
        } else {
            $message = "Failed to update profile. Please try again."; 
            $msg_type = "danger"; 
        }
    }
}

// Removed ../ from header and sidebar includes
include "includes/header.php";
include "includes/sidebar.php";
?>

<!-- Main Content Wrapper to prevent sidebar overlap -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border border-light">
            <div>
                <h2 class="fw-bold text-dark mb-1"><i class="fas fa-user-circle text-primary me-2"></i> My Profile</h2> 
                <p class="text-muted mb-0 small">View and update your personal account information</p> 
            </div>
            <!-- Ensure this points to the correct dashboard (e.g., admin/dashboard.php or user/dashboard.php) based on user role -->
            <a href="javascript:history.back()" class="btn btn-light border fw-bold shadow-sm rounded-3 px-4">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="row g-4">
            <!-- Left Column: User Summary Card -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #1e293b, #3b82f6); height: 130px;"></div>
                    <div class="card-body text-center position-relative pt-0 pb-4">
                        
                        <!-- Dynamic Avatar Logic -->
                        <div class="d-inline-block position-relative" style="margin-top: -65px;">
                            <?php if (!empty($user['image'])) { ?> 
                                <!-- Removed ../ from image source path -->
                                <img src="assets/uploads/<?php echo htmlspecialchars($user['image']); ?>" 
                                     alt="Profile Avatar" 
                                     class="rounded-circle border border-4 border-white shadow-sm"
                                     style="width: 130px; height: 130px; object-fit: cover;">
                            <?php } else { ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=ffffff&color=3b82f6&size=130&bold=true" 
                                     alt="Profile Avatar" 
                                     class="rounded-circle border border-4 border-white shadow-sm">
                            <?php } ?>
                        </div>
                        
                        <h4 class="fw-bold mt-3 mb-1 text-dark"><?php echo htmlspecialchars($user['name']); ?></h4> 
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2 mb-4">
                            <i class="fas fa-shield-alt me-1"></i> <?php echo ucfirst(htmlspecialchars($user['role'] ?? 'User')); ?> 
                        </span>
                        
                        <!-- Contact Info Boxes -->
                        <div class="text-start border-top pt-4">
                            <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-3 border border-light">
                                <div class="bg-white p-2 rounded shadow-sm me-3 text-primary">
                                    <i class="fas fa-envelope fa-fw"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Email Address</small>
                                    <span class="text-dark fw-medium"><?php echo htmlspecialchars($user['email']); ?></span> 
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center p-3 bg-light rounded-3 border border-light">
                                <div class="bg-white p-2 rounded shadow-sm me-3 text-primary">
                                    <i class="fas fa-phone fa-fw"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Mobile Number</small>
                                    <span class="text-dark fw-medium"><?php echo htmlspecialchars($user['mobile'] ?? 'Not Provided'); ?></span> 
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Profile Edit Form -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-user-edit text-primary me-2"></i> Edit Details</h5> 
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        
                        <!-- Alert Message Logic -->
                        <?php if ($message != "") { ?> 
                            <div class="alert alert-<?php echo $msg_type; ?> border-0 bg-<?php echo $msg_type; ?> bg-opacity-10 text-<?php echo $msg_type; ?> rounded-3 shadow-sm mb-4 d-flex align-items-center">
                                <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> fs-5 me-3"></i>
                                <div class="fw-medium"><?php echo htmlspecialchars($message); ?></div> 
                            </div>
                        <?php } ?>

                        <form method="POST" enctype="multipart/form-data"> 
                            
                            <div class="row g-4 mb-4">
                                <!-- Name Input -->
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Full Name</label> 
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" name="name" class="form-control form-control-lg border-start-0 bg-light" value="<?php echo htmlspecialchars($user['name']); ?>" required> 
                                    </div>
                                </div>
                                
                                <!-- Email Input -->
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Email Address</label> 
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control form-control-lg border-start-0 bg-light" value="<?php echo htmlspecialchars($user['email']); ?>" required> 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4 mb-4">
                                <!-- Mobile Input -->
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Mobile Number</label> 
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                                        <input type="text" name="mobile" class="form-control form-control-lg border-start-0 bg-light" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>" required> 
                                    </div>
                                </div>
                                
                                <!-- Image Upload Input -->
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Profile Photo</label> 
                                    <input type="file" name="image" class="form-control form-control-lg bg-light shadow-sm" accept="image/*"> 
                                    <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i> Max size 2MB (JPG, PNG, GIF)</small>
                                </div>
                            </div>
                            
                            <!-- Address Input -->
                            <div class="mb-5">
                                <label class="form-label text-muted small fw-bold text-uppercase tracking-wider">Residential Address</label> 
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-light border-end-0 align-items-start pt-3"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                    <textarea name="address" rows="3" class="form-control form-control-lg border-start-0 bg-light" placeholder="Enter your full address..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea> 
                                </div>
                            </div>

                            <hr class="text-muted opacity-15 mb-4">
                            
                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="update" class="btn btn-primary btn-lg rounded-3 fw-bold shadow px-5">
                                    <i class="fas fa-save me-2"></i> Update Profile 
                                </button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Container Fluid -->

    <!-- INCLUDED FOOTER: Removed ../ -->
    <?php include "includes/footer.php"; ?>
</div>