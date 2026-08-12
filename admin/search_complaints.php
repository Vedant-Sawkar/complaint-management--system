<?php
include "../includes/session.php";
include "../includes/connection.php";

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

    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .form-control, .form-select {
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
        padding: 12px 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(170, 119, 28, 0.2);
    }
    .btn-gold-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(170, 119, 28, 0.4);
        color: white;
        background: linear-gradient(135deg, #aa771c, #8a5e13);
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #475569;
        background-color: #f8fafc;
        padding: 16px 20px;
        border-bottom: 2px solid #e2e8f0;
    }
    .table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
</style>

<div class="content d-flex flex-column" style="margin-left: 260px; background: #f8fafc; min-height: 100vh;">
    
    <div class="container-fluid p-4 flex-grow-1">
        
        <!-- Header Banner -->
        <div class="gold-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="fas fa-search text-warning me-2"></i> Search Complaints</h2>
                <p class="text-white-50 mb-0">Filter and locate user complaints quickly in the system</p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light shadow-sm rounded-pill px-4 fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Advanced Filters Card -->
        <div class="card mb-4 p-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-filter text-warning me-2"></i> Advanced Filters</h5>
            
            <div class="mb-4">
                <label for="search" class="form-label small fw-bold text-muted text-uppercase">Live Quick Search</label>
                <div class="input-group shadow-sm rounded-3">
                    <span class="input-group-text bg-white border-end-0 text-muted ps-3 rounded-start-3"><i class="fas fa-bolt text-warning"></i></span>
                    <input type="text" id="search" class="form-control border-start-0 ps-0" placeholder="Type here to live search complaints...">
                </div>
            </div>

            <hr class="text-muted opacity-25 my-4">

            <label class="form-label small fw-bold text-muted text-uppercase mb-3">Or Apply Standard Filters</label>
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control shadow-sm" placeholder="Keyword, title, or user" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select shadow-sm">
                        <option value="">All Statuses</option>
                        <option value="Pending" <?php if(isset($_GET['status']) && $_GET['status']=="Pending") echo "selected"; ?>>Pending</option>
                        <option value="In Progress" <?php if(isset($_GET['status']) && $_GET['status']=="In Progress") echo "selected"; ?>>In Progress</option>
                        <option value="Resolved" <?php if(isset($_GET['status']) && $_GET['status']=="Resolved") echo "selected"; ?>>Resolved</option>
                        <option value="Closed" <?php if(isset($_GET['status']) && $_GET['status']=="Closed") echo "selected"; ?>>Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-select shadow-sm">
                        <option value="">All Priorities</option>
                        <option value="Low" <?php if(isset($_GET['priority']) && $_GET['priority']=="Low") echo "selected"; ?>>Low</option>
                        <option value="Medium" <?php if(isset($_GET['priority']) && $_GET['priority']=="Medium") echo "selected"; ?>>Medium</option>
                        <option value="High" <?php if(isset($_GET['priority']) && $_GET['priority']=="High") echo "selected"; ?>>High</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-gold-action"><i class="fas fa-filter me-1"></i> Apply</button>
                </div>
            </form>
        </div>

        <!-- Results Table Card -->
        <div class="card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="8%">ID</th>
                            <th width="15%">User</th>
                            <th width="32%">Title</th>
                            <th width="12%">Priority</th>
                            <th width="15%">Status</th>
                            <th width="18%">Date</th>
                        </tr>
                    </thead>
                    <tbody id="result">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-warning me-2" role="status"></div> Loading complaints...
                            </td>
                        </tr>
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

<script>
window.onload = function () {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../ajax/search_complaints.php", true);
    xhr.onload = function () {
        document.getElementById("result").innerHTML = this.responseText;
    };
    xhr.send();
};

document.getElementById("search").addEventListener("keyup", function () {
    let search = this.value;
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../ajax/search_complaints.php?search=" + encodeURIComponent(search), true);
    xhr.onload = function () {
        document.getElementById("result").innerHTML = this.responseText;
    };
    xhr.send();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>