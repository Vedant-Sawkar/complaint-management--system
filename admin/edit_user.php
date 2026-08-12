<?php
include "../includes/session.php";
include "../includes/connection.php";

if (!isset($_GET['id'])) {
    die("Invalid request");
}
$id = $_GET['id'];

// Secure Fetch
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Secure Update
    $stmt_update = $conn->prepare("UPDATE users SET name=?, email=?, role=?, status=? WHERE id=?");
    $stmt_update->bind_param("ssssi", $name, $email, $role, $status, $id);
    $stmt_update->execute();

    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body style="background: #f8fafc;">
<div class="container mt-5">
    <div class="card border-0 shadow-sm rounded-4 col-md-8 mx-auto">
        <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold"><i class="fas fa-user-edit text-primary me-2"></i> Edit User</h4>
            <a href="users.php" class="btn btn-light border rounded-pill btn-sm px-3"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Name</label>
                    <input type="text" name="name" class="form-control rounded-3" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Role</label>
                        <select name="role" class="form-select rounded-3">
                            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="manager" <?php if ($user['role'] == 'manager') echo 'selected'; ?>>Manager</option>
                            <option value="staff" <?php if ($user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold text-secondary">Status</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="active" <?php if ($user['status'] == 'active') echo 'selected'; ?>>Active</option>
                            <option value="inactive" <?php if ($user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="update" class="btn btn-primary w-100 rounded-3 py-2 fw-bold"><i class="fas fa-save me-2"></i> Update User</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>