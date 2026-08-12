<?php
include "../includes/session.php";
include "../includes/connection.php";

if (isset($_POST['add'])) {
    $category_name = trim($_POST['category_name']);

    $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->bind_param("s", $category_name);
    $stmt->execute();

    header("Location: categories.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");

include "../includes/header.php";
include "../includes/sidebar.php";
?>

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

    .category-card { 
        border: none; 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        border: 1px solid #e2e8f0;
        background: #ffffff;
        overflow: hidden;
    }

    .form-control { 
        border-radius: 12px; 
        padding: 12px; 
        border-color: #e2e8f0;
    }

    .btn-gold-action {
        background: linear-gradient(135deg, #bf953f, #aa771c);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 12px;
        padding: 12px 25px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(170, 119, 28, 0.2);
    }
    .btn-gold-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(170, 119, 28, 0.4);
        color: white;
        background: linear-gradient(135deg, #aa771c, #8a5e13);
    }

    .table thead { 
        background: #f8fafc; 
        color: #475569; 
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }
    .table th { border-bottom: 2px solid #e2e8f0; padding: 16px 20px; }
    .table td { padding: 16px 20px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
</style>

<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="fa-solid fa-layer-group text-warning me-2"></i> Manage Categories</h2>
                <p class="text-white-50 mb-0">Create and oversee complaint system categories</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                    <i class="fa-solid fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Add Category Form Card -->
        <div class="category-card mb-4 p-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-plus-circle text-warning me-2"></i> Add New Category</h5>
            <form method="POST">
                <div class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <input type="text" name="category_name" class="form-control shadow-sm" placeholder="Enter category name" required>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" name="add" class="btn btn-gold-action"><i class="fas fa-plus me-1"></i> Add Category</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Categories Table Card -->
        <div class="category-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">ID</th>
                            <th>Category Name</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td class="px-4 fw-bold text-primary">#<?php echo htmlspecialchars($row['id']); ?></td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill fw-semibold">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </span>
                            </td>
                            <td class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?php echo htmlspecialchars($row['created_at']); ?></td>
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