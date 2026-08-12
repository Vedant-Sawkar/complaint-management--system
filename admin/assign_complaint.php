<?php
include "../includes/session.php";
include "../includes/connection.php";

$message = "";
$msg_type = "";

// 1. Handle the Assignment Form Submission
if (isset($_POST['assign_staff'])) {
    $complaint_id = $_POST['complaint_id'];
    $staff_id = $_POST['staff_id'];

    if (!empty($staff_id)) {
        $stmt = $conn->prepare("UPDATE complaints SET assigned_to = ?, status = 'In Progress' WHERE id = ?");
        $stmt->bind_param("ii", $staff_id, $complaint_id);
        
        if ($stmt->execute()) {
            $message = "Staff successfully assigned to complaint #$complaint_id!";
            $msg_type = "success";
        } else {
            $message = "Error assigning staff. Please try again.";
            $msg_type = "danger";
        }
    } else {
        $message = "Please select a staff member from the dropdown before assigning.";
        $msg_type = "warning";
    }
}

// 2. Fetch all Staff Members to populate the dropdowns
$staff_list = [];
$staff_query = "SELECT id, name FROM users WHERE role = 'staff'";
$staff_result = $conn->query($staff_query);
if ($staff_result) {
    while ($row = $staff_result->fetch_assoc()) {
        $staff_list[] = $row;
    }
}

// 3. Fetch Complaints
$complaints_query = "SELECT c.id, c.title, c.status, c.assigned_to, u.name AS user_name 
                     FROM complaints c 
                     LEFT JOIN users u ON c.user_id = u.id 
                     WHERE c.status != 'Resolved' AND c.status != 'Closed'
                     ORDER BY c.id DESC";
$complaints_result = $conn->query($complaints_query);

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!-- Custom Premium Gold UI Styles -->
<style>
    :root {
        --gold-primary: #d4af37;
        --gold-secondary: #aa771c;
        --gold-gradient: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
    }

    body {
        background: #f8fafc;
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    /* Premium Gold Header Banner */
    .gold-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 30px;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.3);
        position: relative;
        overflow: hidden;
    }
    .gold-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(212,175,55,0.07) 0%, transparent 60%);
        pointer-events: none;
    }

    /* Luxury Action Buttons */
    .btn-gold-action {
        background: linear-gradient(135deg, #bf953f, #aa771c);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        padding: 8px 18px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(170, 119, 28, 0.2);
    }
    .btn-gold-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(170, 119, 28, 0.4);
        color: white;
        background: linear-gradient(135deg, #aa771c, #8a5e13);
    }

    /* Table & Card UI Styling */
    .table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .table-card:hover {
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }
</style>

<!-- Main Content Wrapper to prevent sidebar overlap -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Page Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="fas fa-tasks text-warning me-2"></i> Assign Complaints</h2>
                <p class="text-white-50 mb-0">Assign incoming complaints to available staff members</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if ($message != ""): ?>
            <div class="alert alert-<?php echo $msg_type; ?> border-0 bg-<?php echo $msg_type; ?> bg-opacity-10 text-<?php echo $msg_type; ?> rounded-4 shadow-sm mb-4 d-flex align-items-center p-3">
                <i class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> fs-5 me-3"></i>
                <div class="fw-medium"><?php echo htmlspecialchars($message); ?></div>
            </div>
        <?php endif; ?>

        <!-- Complaints Table Card -->
        <div class="table-card p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="table-light text-uppercase text-secondary small border-bottom">
                        <tr>
                            <th class="py-3 ps-4" width="5%">ID</th>
                            <th class="py-3" width="20%">User</th>
                            <th class="py-3" width="30%">Title</th>
                            <th class="py-3" width="15%">Status</th>
                            <th class="py-3 pe-4 text-end" width="30%">Assign Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($complaints_result && $complaints_result->num_rows > 0): ?>
                            <?php while ($complaint = $complaints_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="py-3 ps-4 fw-bold text-primary">#<?php echo htmlspecialchars($complaint['id']); ?></td>
                                    
                                    <td class="py-3 fw-semibold text-dark">
                                        <i class="fas fa-user-circle text-muted me-2"></i> 
                                        <?php echo htmlspecialchars($complaint['user_name'] ?? 'Unknown User'); ?>
                                    </td>
                                    
                                    <td class="py-3 text-muted">
                                        <?php echo htmlspecialchars($complaint['title']); ?>
                                    </td>
                                    
                                    <td class="py-3">
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill fw-semibold">
                                            <?php echo htmlspecialchars($complaint['status']); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="py-3 pe-4 text-end">
                                        <!-- Assignment Form -->
                                        <form method="POST" class="d-inline-flex gap-2 align-items-center justify-content-end mb-0">
                                            <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                                            
                                            <select name="staff_id" class="form-select form-select-sm shadow-sm w-auto" style="border-radius: 8px;" required>
                                                <option value="">Select Staff</option>
                                                <?php foreach ($staff_list as $staff): ?>
                                                    <option value="<?php echo $staff['id']; ?>" <?php echo ($complaint['assigned_to'] == $staff['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($staff['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            
                                            <button type="submit" name="assign_staff" class="btn btn-gold-action btn-sm">
                                                <i class="fas fa-check me-1"></i> Assign
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                    <h5 class="fw-bold text-dark mb-0">No complaints found</h5>
                                    <p class="small">There are currently no complaints needing assignment.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div> 

    <!-- CENTERED WHITE FOOTER -->
    <footer class="bg-white border-top py-3 mt-auto w-100">
        <div class="container text-center text-muted small">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>
</div>

</body>
</html>