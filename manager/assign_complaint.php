<?php
include "../includes/session.php";
include "../includes/connection.php";

// Handle Staff Assignment
if (isset($_POST['assign'])) {
    $complaint_id = $_POST['complaint_id'];
    $staff_id = $_POST['staff_id'];

    $stmt1 = $conn->prepare("UPDATE complaints SET assigned_to=?, status='In Progress' WHERE id=?");
    $stmt1->bind_param("ii", $staff_id, $complaint_id);
    $stmt1->execute();

    $stmt2 = $conn->prepare("INSERT INTO complaint_history (complaint_id, user_id, status, remarks) VALUES (?, ?, 'In Progress', 'Complaint assigned to staff')");
    $stmt2->bind_param("ii", $complaint_id, $staff_id);
    $stmt2->execute();

    $message = "A new complaint has been assigned to you.";
    $stmt3 = $conn->prepare("INSERT INTO notifications (user_id, message, status) VALUES (?, ?, 'Unread')");
    $stmt3->bind_param("is", $staff_id, $message);
    $stmt3->execute();

    header("Location: assign_complaint.php");
    exit();
}

// Handle Mark as Resolved
if (isset($_POST['resolve_complaint'])) {
    $complaint_id = $_POST['complaint_id'];

    $stmt_res = $conn->prepare("UPDATE complaints SET status='Resolved' WHERE id=?");
    $stmt_res->bind_param("i", $complaint_id);
    $stmt_res->execute();

    header("Location: assign_complaint.php");
    exit();
}

$complaints = mysqli_query(
    $conn,
    "SELECT complaints.*, users.name
     FROM complaints
     INNER JOIN users
     ON complaints.user_id = users.id
     ORDER BY complaints.id DESC"
);

$staff = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE role='staff'"
);

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!-- FontAwesome & Bootstrap -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f8fafc;
        font-family: 'Segoe UI', Roboto, sans-serif;
    }
    .gold-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 30px;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.3);
    }
    .btn-gold-action {
        background: linear-gradient(135deg, #bf953f, #aa771c);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        padding: 8px 15px;
        transition: all 0.3s ease;
    }
    .btn-gold-action:hover {
        transform: translateY(-2px);
        color: white;
        background: linear-gradient(135deg, #aa771c, #8a5e13);
    }
    .table-box {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
    }
</style>

<!-- Main Content Wrapper -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: calc(100vh - 65px);">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="fas fa-tasks text-warning me-2"></i> Manage & Assign Complaints</h2>
                <p class="text-white-50 mb-0">Assign queries to staff or mark active complaints as resolved</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Complaints Table Card -->
        <div class="table-box p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase text-secondary small">
                        <tr>
                            <th class="py-3">ID</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Title</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end">Actions / Assign Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(mysqli_num_rows($complaints) > 0) {
                        while ($row = mysqli_fetch_assoc($complaints)) { ?>
                        <tr>
                            <td class="fw-bold text-primary">#<?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>
                                <?php 
                                    $st = $row['status'];
                                    $badgeBg = 'bg-warning text-dark';
                                    if ($st === 'In Progress') $badgeBg = 'bg-info text-white';
                                    if ($st === 'Resolved') $badgeBg = 'bg-success text-white';
                                ?>
                                <span class="badge <?php echo $badgeBg; ?> px-3 py-2 rounded-pill">
                                    <?php echo htmlspecialchars($st); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                                    <!-- Assign Form -->
                                    <form method="POST" class="d-inline-flex gap-1 align-items-center mb-0">
                                        <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <select name="staff_id" class="form-select form-select-sm w-auto" required>
                                            <option value="">Select Staff</option>
                                            <?php
                                            mysqli_data_seek($staff, 0);
                                            while ($s = mysqli_fetch_assoc($staff)) {
                                            ?>
                                            <option value="<?php echo htmlspecialchars($s['id']); ?>">
                                                <?php echo htmlspecialchars($s['name']); ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" name="assign" class="btn btn-gold-action btn-sm">Assign</button>
                                    </form>

                                    <!-- Quick Resolve Button -->
                                    <?php if ($row['status'] !== 'Resolved'): ?>
                                    <form method="POST" class="d-inline mb-0">
                                        <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="submit" name="resolve_complaint" class="btn btn-success btn-sm" title="Mark as Resolved">
                                            <i class="fas fa-check"></i> Resolve
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php } 
                    } else { ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No complaints available.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
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