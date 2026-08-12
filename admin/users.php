<?php
include "../includes/session.php";
include "../includes/connection.php";

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("UPDATE users SET deleted_at = NOW() WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: users.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users");
$total_users = mysqli_num_rows($result);

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<style>
    :root {
        --gold-primary: #d4af37;
        --gold-secondary: #aa771c;
        --gold-gradient: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
    }

    body { background: #f8fafc; font-family: 'Segoe UI', Roboto, sans-serif; }

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

    .stats-box { 
        background: white; 
        border-radius: 20px; 
        padding: 25px; 
        margin-bottom: 25px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        border: 1px solid #e2e8f0; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
    }
    
    .user-card { 
        border: none; 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        border: 1px solid #e2e8f0; 
        overflow: hidden; 
        background: white; 
    }
    
    .table { margin-bottom: 0; }
    .table thead { background: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; }
    .table th { border-bottom: 2px solid #e2e8f0; padding: 16px 20px; }
    .table td { padding: 16px 20px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    
    .badge-role { color: white; padding: 6px 14px; border-radius: 30px; font-size: 12px; font-weight: 600; display: inline-block; text-align: center; }
    .role-admin { background: #ef4444; }
    .role-manager { background: #8b5cf6; }
    .role-staff { background: #3b82f6; }
    .role-user { background: #10b981; }

    .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
    .status-active { background: #dcfce7; color: #16a34a; }
    .status-inactive { background: #fee2e2; color: #dc2626; }

    .action-btn { border-radius: 10px; padding: 6px 12px; font-size: 13px; }
</style>

<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="fas fa-users text-warning me-2"></i> Users Management</h2>
                <p class="text-white-50 mb-0">View, edit and manage system user accounts securely</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Stats Box -->
        <div class="stats-box">
            <div>
                <span class="text-muted text-uppercase small fw-bold tracking-wider">Total Registered Users</span>
                <h3 class="fw-bold text-dark mb-0 mt-1"><?php echo $total_users; ?></h3>
            </div>
            <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle">
                <i class="fas fa-user-friends fa-2x"></i>
            </div>
        </div>

        <!-- User Card Table Container -->
        <div class="user-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="px-4">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php mysqli_data_seek($result, 0); ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td class="px-4 fw-bold text-primary">#<?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="fw-semibold text-dark">
                                <i class="fas fa-user-circle text-muted me-1 fs-5 align-middle"></i> 
                                <?php echo htmlspecialchars($row['name']); ?>
                            </td>
                            <td class="text-secondary"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <span class="badge-role role-<?php echo htmlspecialchars($row['role']); ?>">
                                    <?php echo ucfirst(htmlspecialchars($row['role'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    $status = htmlspecialchars($row['status']);
                                    $statusClass = ($status === 'active') ? 'status-active' : 'status-inactive';
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td class="text-end px-4">
                                <a href="edit_user.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning btn-sm action-btn text-dark shadow-sm me-1" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="users.php?delete=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm action-btn shadow-sm" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete User">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>