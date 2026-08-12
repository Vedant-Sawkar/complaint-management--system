<?php
include "../includes/session.php";
include "../includes/connection.php";

$staff_id = $_SESSION['user_id'];

if (isset($_POST['update'])) {
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];

    // Secure Update Status
    $stmt1 = $conn->prepare("UPDATE complaints SET status=? WHERE id=?");
    $stmt1->bind_param("si", $status, $complaint_id);
    $stmt1->execute();

    // Secure Get Complaint Details
    $stmt2 = $conn->prepare("SELECT * FROM complaints WHERE id=?");
    $stmt2->bind_param("i", $complaint_id);
    $stmt2->execute();
    $complaint = $stmt2->get_result()->fetch_assoc();

    $user_id = $complaint['user_id'];

    // Secure Get User Email
    $stmt3 = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt3->bind_param("i", $user_id);
    $stmt3->execute();
    $user = $stmt3->get_result()->fetch_assoc();

    // Include PHPMailer to send notification
    include "../mail/PHPMailer/send_mail.php"; 
    
    sendMail(
        $user['email'],
        "Complaint Status Updated",
        "<h2>Status Updated</h2><p>Your complaint status is now: <b>" . htmlspecialchars($status) . "</b></p>"
    );

    header("Location: my_tasks.php");
    exit();
}

// Secure Main Select Query
$sql = "SELECT complaints.*, users.name
        FROM complaints
        INNER JOIN users
        ON complaints.user_id = users.id
        WHERE complaints.assigned_to=?
        ORDER BY complaints.id DESC";

$stmt_main = $conn->prepare($sql);
$stmt_main->bind_param("i", $staff_id);
$stmt_main->execute();
$result = $stmt_main->get_result();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<style>
    :root {
        --gold-primary: #d4af37;
        --gold-secondary: #aa771c;
        --sidebar-width: 260px;
    }
    body { 
        background: #f8fafc; 
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        overflow-x: hidden;
    }
    
    /* Responsive Content Wrapper */
    .content-wrapper {
        margin-left: var(--sidebar-width);
        background: #f8fafc;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    @media (max-width: 991.98px) {
        .content-wrapper {
            margin-left: 0 !important;
        }
    }

    /* Premium Gold Banner */
    .gold-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 30px;
        color: #ffffff;
        border: 1px solid rgba(212, 175, 55, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .gold-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%);
        border-radius: 50%;
        z-index: -1;
    }

    /* Table Card Styling */
    .table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }
    .table-card:hover {
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }
    .table th {
        font-weight: 600;
        letter-spacing: 0.5px;
        background-color: #f8fafc;
    }

    /* Premium Action Button */
    .btn-gold-action {
        background: linear-gradient(135deg, #bf953f, #aa771c);
        border: none;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(170, 119, 28, 0.2);
        transition: all 0.3s ease;
    }
    .btn-gold-action:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(170, 119, 28, 0.4);
        background: linear-gradient(135deg, #aa771c, #8a5e13);
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid p-4 p-lg-5 flex-grow-1">

        <!-- Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 tracking-tight"><i class="fas fa-tasks text-warning me-2"></i> My Tasks</h2>
                <p class="text-white-50 mb-0">View and update the status of complaints assigned to you.</p>
            </div>
            <a href="dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>

        <!-- Tasks Table Card -->
        <div class="table-card p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="text-uppercase text-secondary small border-bottom">
                        <tr>
                            <th class="py-4 ps-4" width="5%">ID</th>
                            <th class="py-4" width="15%">User</th>
                            <th class="py-4" width="20%">Title</th>
                            <th class="py-4" width="25%">Description</th>
                            <th class="py-4" width="10%">Status</th>
                            <th class="py-4 pe-4 text-end" width="25%">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="py-3 ps-4 fw-bold text-primary">#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="py-3 fw-semibold text-dark"><i class="fas fa-user-circle text-muted me-1 fs-5 align-middle"></i> <?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="py-3 fw-medium text-dark"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td class="py-3 text-muted small text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($row['description']); ?></td>
                                <td class="py-3">
                                    <?php
                                        $status = htmlspecialchars($row['status']);
                                        $badge = "bg-secondary";
                                        if($status == 'Pending') $badge = "bg-warning bg-opacity-10 text-warning border border-warning";
                                        if($status == 'In Progress') $badge = "bg-info bg-opacity-10 text-info border border-info";
                                        if($status == 'Resolved') $badge = "bg-success bg-opacity-10 text-success border border-success";
                                        if($status == 'Closed') $badge = "bg-dark bg-opacity-10 text-dark border border-dark";
                                    ?>
                                    <span class="badge <?php echo $badge; ?> px-3 py-2 rounded-pill fw-bold tracking-wider">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td class="py-3 pe-4 text-end">
                                    <form method="POST" class="d-inline-flex gap-2 align-items-center mb-0">
                                        <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <select name="status" class="form-select form-select-sm shadow-sm w-auto rounded-3 fw-medium">
                                            <option value="Pending" <?php if($status=="Pending") echo "selected"; ?>>Pending</option>
                                            <option value="In Progress" <?php if($status=="In Progress") echo "selected"; ?>>In Progress</option>
                                            <option value="Resolved" <?php if($status=="Resolved") echo "selected"; ?>>Resolved</option>
                                            <option value="Closed" <?php if($status=="Closed") echo "selected"; ?>>Closed</option>
                                        </select>
                                        <button type="submit" name="update" class="btn btn-gold-action btn-sm rounded-3 px-3">
                                            <i class="fas fa-save me-1"></i> Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-clipboard-check fa-2x text-muted opacity-50"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">No Tasks Assigned</h5>
                                    <p class="small mt-1">You have caught up with all your work!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
    <footer class="bg-white border-top py-4 mt-auto w-100">
        <div class="container text-center text-muted small fw-medium">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>