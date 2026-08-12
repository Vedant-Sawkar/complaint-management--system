<?php
include "../includes/session.php";
include "../includes/connection.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// STRICT Validation for Sorting
$sort = (isset($_GET['sort']) && strtoupper($_GET['sort']) === 'ASC') ? 'ASC' : 'DESC';

$sql = "SELECT complaints.*, users.name FROM complaints INNER JOIN users ON complaints.user_id = users.id WHERE complaints.deleted_at IS NULL";
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $sql .= " AND complaints.title LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
    $types .= "s";
}
if (!empty($_GET['status'])) {
    $sql .= " AND complaints.status=?";
    $params[] = $_GET['status'];
    $types .= "s";
}
if (!empty($_GET['category'])) {
    $sql .= " AND complaints.category=?";
    $params[] = $_GET['category'];
    $types .= "s";
}

$countSql = $sql;
$sql .= " ORDER BY complaints.id $sort LIMIT ?, ?";
$params[] = $start;
$params[] = $limit;
$types .= "ii";

// Count Query
$stmtCount = $conn->prepare($countSql);
if(strlen($types) > 2) { 
    $countTypes = substr($types, 0, -2);
    $countParams = array_slice($params, 0, -2);
    $stmtCount->bind_param($countTypes, ...$countParams);
}
$stmtCount->execute();
$totalRows = $stmtCount->get_result()->num_rows;
$totalPages = ceil($totalRows / $limit);

// Main Query
$stmt = $conn->prepare($sql);
if(!empty($types)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Ensure header is included for global styling
include "../includes/header.php";
?>

<!-- Include the sidebar here -->
<?php include "../includes/sidebar.php"; ?>

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
        border-radius: 12px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(170, 119, 28, 0.2);
    }
    .btn-gold-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(170, 119, 28, 0.4);
        color: white;
        background: linear-gradient(135deg, #aa771c, #8a5e13);
    }

    /* Filter Card & Table Card with Gold Accents */
    .filter-card, .table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    .filter-card:hover, .table-card:hover {
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }

    /* Pagination Customization */
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #bf953f, #aa771c);
        border-color: #aa771c;
        color: white;
    }
    .pagination .page-link {
        color: #1e293b;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
</style>

<!-- Main Content Wrapper to prevent sidebar overlap -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid py-4 flex-grow-1">

        <!-- Header Section -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="fas fa-clipboard-list text-warning me-2"></i> All Complaints</h2>
                <p class="text-white-50 mb-0">Manage, filter and track all system complaints</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="complaint_history.php" class="btn btn-dark border shadow-sm rounded-pill px-4 text-white">
                    <i class="fas fa-trash-alt text-warning me-1"></i> Deleted Complaints
                </a>
                <a href="dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Filters & Search Form -->
        <div class="filter-card">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Search Title</label>
                    <input type="text" name="search" class="form-control" placeholder="Search complaint..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Pending" <?php if (($_GET['status'] ?? '') == "Pending") echo "selected"; ?>>Pending</option>
                        <option value="In Progress" <?php if (($_GET['status'] ?? '') == "In Progress") echo "selected"; ?>>In Progress</option>
                        <option value="Resolved" <?php if (($_GET['status'] ?? '') == "Resolved") echo "selected"; ?>>Resolved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary small">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <option value="Technical" <?php if (($_GET['category'] ?? '') == "Technical") echo "selected"; ?>>Technical</option>
                        <option value="Network" <?php if (($_GET['category'] ?? '') == "Network") echo "selected"; ?>>Network</option>
                        <option value="Software" <?php if (($_GET['category'] ?? '') == "Software") echo "selected"; ?>>Software</option>
                        <option value="Hardware" <?php if (($_GET['category'] ?? '') == "Hardware") echo "selected"; ?>>Hardware</option>
                        <option value="Other" <?php if (($_GET['category'] ?? '') == "Other") echo "selected"; ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary small">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="DESC" <?php if (($sort) == "DESC") echo "selected"; ?>>Newest First</option>
                        <option value="ASC" <?php if (($sort) == "ASC") echo "selected"; ?>>Oldest First</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-gold-action flex-grow-1"><i class="fas fa-search me-1"></i> Search</button>
                    <a href="view_complaints.php" class="btn btn-light border flex-grow-1 d-flex align-items-center justify-content-center text-dark"><i class="fas fa-redo me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <!-- Complaints Table Card -->
        <div class="table-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th class="py-3 ps-4">ID</th>
                        <th class="py-3">User</th>
                        <th class="py-3">Title</th>
                        <th class="py-3">Category</th>
                        <th class="py-3">Priority</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Date</th>
                        <th class="py-3 text-end pe-4">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="fw-bold text-primary ps-4">#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="fw-semibold text-dark"><i class="fas fa-user-circle text-muted me-1"></i> <?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><span class="text-secondary"><i class="fas fa-tag me-1 opacity-50"></i> <?php echo htmlspecialchars($row['category']); ?></span></td>
                                <td>
                                    <?php
                                    if ($row['priority'] == "High") echo "<span class='badge bg-danger px-3 py-2 rounded-pill'>High</span>";
                                    elseif ($row['priority'] == "Medium") echo "<span class='badge bg-warning text-dark px-3 py-2 rounded-pill'>Medium</span>";
                                    else echo "<span class='badge bg-success px-3 py-2 rounded-pill'>Low</span>";
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($row['status'] == "Pending") echo "<span class='badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill'>Pending</span>";
                                    elseif ($row['status'] == "In Progress") echo "<span class='badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 rounded-pill'>In Progress</span>";
                                    else echo "<span class='badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill'>Resolved</span>";
                                    ?>
                                </td>
                                <td class="text-muted small"><i class="far fa-calendar-alt me-1"></i> <?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                                <td class="text-end pe-4">
                                    <a href="delete_complaint.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm rounded-pill px-3 py-1 shadow-sm" onclick="return confirm('Are you sure you want to delete this complaint?')">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted opacity-50 mb-3"></i>
                                <h5 class="text-muted fw-semibold">No complaints found</h5>
                                <p class="text-muted small mb-0">Try adjusting your search or filter parameters.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="mt-4 text-center">
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link rounded-3 mx-1 shadow-sm" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&status=<?php echo urlencode($_GET['status'] ?? ''); ?>&category=<?php echo urlencode($_GET['category'] ?? ''); ?>&sort=<?php echo $sort; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>

    </div> <!-- End Container Fluid -->

    <!-- CENTERED WHITE FOOTER -->
    <footer class="bg-white border-top py-3 mt-auto w-100">
        <div class="container text-center text-muted small">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>
</div>
</body>
</html>