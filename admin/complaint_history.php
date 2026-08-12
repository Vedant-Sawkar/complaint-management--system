<?php
include "../includes/session.php";
include "../includes/connection.php";
include "../includes/header.php";

$result = mysqli_query(
    $conn,
    "SELECT complaints.*, users.name
     FROM complaints
     INNER JOIN users
     ON complaints.user_id = users.id
     WHERE complaints.deleted_at IS NOT NULL
     ORDER BY complaints.id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Deleted Complaints</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --gold-primary: #d4af37;
            --gold-secondary: #aa771c;
            --gold-gradient: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
        }

        body { 
            background: #f8fafc; 
            font-family: 'Segoe UI', Roboto, sans-serif; 
            margin: 0; 
            overflow-x: hidden; 
        }
        
        /* Layout Wrapper Styles */
        .layout-wrapper { display: flex; width: 100%; min-height: 100vh; }
        
        /* Sidebar Styles */
        .sidebar-container { 
            width: 260px; 
            min-width: 260px;
            background: white; 
            border-right: 1px solid #e2e8f0; 
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
            z-index: 10;
        }
        
        /* Main Content Styles */
        .main-content { 
            flex-grow: 1; 
            width: calc(100% - 260px);
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .content-body { flex: 1; }

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
            margin-bottom: 30px;
        }

        .table-card { 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
            border: 1px solid #e2e8f0; 
            overflow: hidden; 
            transition: all 0.3s ease;
        }
        .table-card:hover {
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
        }

        .table { margin-bottom: 0; }
        .table thead { background: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; }
        .table th { border-bottom: 2px solid #e2e8f0; padding: 16px 20px; }
        .table td { padding: 16px 20px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .table tbody tr:hover { background-color: #f8fafc; }
        
        .action-btn { border-radius: 10px; padding: 6px 14px; font-weight: 600; font-size: 13px; }
    </style>
</head>
<body>

<div class="layout-wrapper">
    
    <!-- Sidebar Section -->
    <aside class="sidebar-container d-none d-md-block">
        <?php 
            $sidebar_path = "../includes/sidebar.php";
            if (file_exists($sidebar_path)) {
                include $sidebar_path;
            } else {
                echo '
                <div class="p-4">
                    <h5 class="fw-bold text-primary mb-4"><i class="fas fa-bars me-2"></i> Menu</h5>
                    <ul class="nav flex-column gap-2">
                        <li class="nav-item">
                            <a class="nav-link text-secondary bg-light rounded" href="#"><i class="fas fa-home me-2"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="view_complaints.php"><i class="fas fa-list me-2"></i> Complaints</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark fw-bold bg-light border rounded" href="complaint_history.php"><i class="fas fa-archive text-warning me-2"></i> Deleted</a>
                        </li>
                    </ul>
                </div>';
            }
        ?>
    </aside>

    <!-- Main Content Section -->
    <main class="main-content">
        <div class="container-fluid py-4 px-4 content-body">
            
            <!-- Page Header Banner -->
            <div class="gold-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1"><i class="fas fa-archive text-warning me-2"></i> Deleted Complaints</h2>
                    <p class="text-white-50 mb-0">Review previously deleted complaints and restore them if necessary</p>
                </div>
                <div>
                    <a href="view_complaints.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i> Back to Complaints
                    </a>
                </div>
            </div>

            <!-- Table Card Container -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th class="px-4">ID</th>
                            <th>User</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Deleted Date</th>
                            <th class="text-end px-4">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td class="px-4 fw-bold text-primary">#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="fw-semibold text-dark"><i class="fas fa-user-circle text-muted me-1"></i> <?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td>
                                    <?php 
                                        $status = htmlspecialchars($row['status']);
                                        $badgeClass = 'bg-secondary';
                                        if ($status === 'Pending') {
                                            $badgeClass = 'bg-warning bg-opacity-10 text-warning border border-warning';
                                        } elseif ($status === 'In Progress') {
                                            $badgeClass = 'bg-info bg-opacity-10 text-info border border-info';
                                        } elseif ($status === 'Resolved') {
                                            $badgeClass = 'bg-success bg-opacity-10 text-success border border-success';
                                        }
                                    ?>
                                    <span class="badge px-3 py-2 rounded-pill fw-semibold <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td class="text-muted small"><i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($row['deleted_at']); ?></td>
                                <td class="text-end px-4">
                                    <a href="restore_complaint.php?id=<?php echo urlencode($row['id']); ?>" 
                                       class="btn btn-success btn-sm action-btn shadow-sm"
                                       onclick="return confirm('Are you sure you want to restore this complaint?')">
                                        <i class="fas fa-trash-restore me-1"></i> Restore
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-muted opacity-50 mb-3"></i>
                                    <h5 class="text-muted fw-semibold">No deleted complaints found</h5>
                                    <p class="text-muted small mb-0">The trash bin is currently empty.</p>
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
        
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>