<?php
session_start();
include "../includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $priority = trim($_POST['priority']);
    $status = 'Pending';

    // Secure Insert using Prepared Statement
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, title, description, category, priority, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $title, $description, $category, $priority, $status);

    if ($stmt->execute()) {
        echo "Complaint Added Successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>