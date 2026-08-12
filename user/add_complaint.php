<?php
include "../includes/session.php";
include "../includes/connection.php";

$message = "";
$msg_type = "";

if (isset($_POST['submit'])) {

    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $priority = trim($_POST['priority']);

    // Secure Insert Complaint using Prepared Statements
    $stmt1 = $conn->prepare("INSERT INTO complaints (user_id, title, description, category, priority) VALUES (?, ?, ?, ?, ?)");
    $stmt1->bind_param("issss", $user_id, $title, $description, $category, $priority);

    if ($stmt1->execute()) {

        // Secure Insert Notification
        $notify_message = "Your complaint has been submitted successfully.";
        $stmt2 = $conn->prepare("INSERT INTO notifications (user_id, message, status) VALUES (?, ?, 'Unread')");
        $stmt2->bind_param("is", $user_id, $notify_message);
        $stmt2->execute();

        // Secure Insert Activity Log
        $activity = "New complaint added";
        $stmt3 = $conn->prepare("INSERT INTO activity_logs (user_id, activity) VALUES (?, ?)");
        $stmt3->bind_param("is", $user_id, $activity);
        $stmt3->execute();

        $message = "Your complaint has been submitted successfully! We will look into it shortly.";
        $msg_type = "success";

    } else {
        $message = "Error submitting complaint. Please try again.";
        $msg_type = "danger";
    }
}

// Include Global Header and Sidebar
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!-- Main Content Wrapper to accommodate the fixed sidebar and sticky footer -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">

    <div class="container-fluid py-4 flex-grow-1">

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title"><i class="fas fa-file-signature text-primary me-2"></i> Add New Complaint</h1>
                <p class="text-muted mb-0 mt-1">Please provide the details of your issue below.</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light fw-semibold rounded-pill px-4 border shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Form Container -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-card">

                    <?php if ($message != "") { ?>
                        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-3" role="alert">
                            <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <form method="POST">
                        
                        <div class="mb-4">
                            <label class="form-label">Complaint Title <span class="text-danger">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-heading"></i>
                                <input type="text" name="title" class="form-control" placeholder="Briefly summarize your issue" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-layer-group"></i>
                                    <select name="category" class="form-select" required>
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="Technical">Technical</option>
                                        <option value="Electricity">Electricity</option>
                                        <option value="Water">Water</option>
                                        <option value="Internet">Internet</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt-4 mt-md-0">
                                <label class="form-label">Priority Level <span class="text-danger">*</span></label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <select name="priority" class="form-select" required>
                                        <option value="Low">Low (General Query)</option>
                                        <option value="Medium" selected>Medium (Standard Issue)</option>
                                        <option value="High">High (Urgent / Emergency)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Detailed Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" placeholder="Please describe your issue in detail so we can help you better..." required></textarea>
                        </div>

                        <div class="text-end mt-5">
                            <button type="reset" class="btn btn-light fw-bold px-4 py-2 me-2 border rounded-3">Clear</button>
                            <button type="submit" name="submit" class="btn btn-primary btn-custom">
                                <i class="fas fa-paper-plane me-2"></i> Submit Complaint
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Centered White Footer -->
    <footer class="bg-white border-top py-3 mt-auto w-100">
        <div class="container text-center text-muted small">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>