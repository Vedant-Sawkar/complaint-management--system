<?php
include "../includes/session.php";
include "../includes/connection.php";

$user_id = $_SESSION['user_id'];

// Secure Query using Prepared Statements[cite: 23]
$stmt = $conn->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

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
                <h1 class="page-title"><i class="fas fa-list-alt text-primary me-2"></i> My Complaints</h1>
                <p class="text-muted mb-0 mt-1">Track and manage your submitted issues</p>
            </div>
            <div class="d-flex gap-2">
                <a href="add_complaint.php" class="btn btn-primary fw-semibold rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-2"></i> New Complaint
                </a>
                <a href="dashboard.php" class="btn btn-light fw-semibold rounded-pill px-4 border shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="complaint-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Complaint ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            
                            // Status Badge Logic
                            $status = htmlspecialchars($row['status']);
                            $statusClass = 'status-closed';
                            if ($status === 'Pending') $statusClass = 'status-pending';
                            if ($status === 'In Progress') $statusClass = 'status-progress';
                            if ($status === 'Resolved') $statusClass = 'status-resolved';

                            // Priority Logic
                            $priority = htmlspecialchars($row['priority']);
                            $priorityClass = "priority-" . $priority;
                    ?>
                        <tr>
                            <td>
                                <span class="complaint-id">#CMP-<?php echo str_pad(htmlspecialchars($row['id']), 4, '0', STR_PAD_LEFT); ?></span>
                            </td>
                            <td class="fw-semibold text-dark">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </td>
                            <td>
                                <span class="text-secondary"><i class="fas fa-tag me-1 opacity-50"></i> <?php echo htmlspecialchars($row['category']); ?></span>
                            </td>
                            <td>
                                <span class="<?php echo $priorityClass; ?>">
                                    <i class="fas fa-circle priority-dot"></i><?php echo $priority; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td class="text-secondary fw-medium">
                                <i class="far fa-calendar-alt me-1 opacity-50"></i> <?php echo date("d M Y", strtotime($row['created_at'])); ?>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <!-- Beautiful Empty State -->
                        <tr>
                            <td colspan="6" class="border-0">
                                <div class="empty-state">
                                    <i class="fas fa-folder-open empty-icon"></i>
                                    <h4 class="fw-bold text-dark mb-2">No complaints found</h4>
                                    <p class="text-muted mb-4">You haven't submitted any complaints yet. If you are facing an issue, let us know!</p>
                                    <a href="add_complaint.php" class="btn btn-primary rounded-pill px-4">
                                        Submit a Complaint
                                    </a>
                                </div>
                            </td>
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