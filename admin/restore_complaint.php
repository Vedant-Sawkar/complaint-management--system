<?php
include "../includes/session.php";
include "../includes/connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Secure Update
    $stmt = $conn->prepare("UPDATE complaints SET deleted_at = NULL WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: complaint_history.php");
exit();
?>