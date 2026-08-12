<?php
include "../includes/session.php";
include "../includes/connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Secure Update
    $stmt = $conn->prepare("UPDATE complaints SET deleted_at = NOW() WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: view_complaints.php");
exit();
?>