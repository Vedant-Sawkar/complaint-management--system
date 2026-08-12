<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();
}

$current_folder = basename(dirname($_SERVER['PHP_SELF']));

$user_role = $_SESSION['role'];

if ($current_folder == "admin" && $user_role != "admin") {

    header("Location: ../login.php");

    exit();
}

if ($current_folder == "manager" && $user_role != "manager") {

    header("Location: ../login.php");

    exit();
}

if ($current_folder == "staff" && $user_role != "staff") {

    header("Location: ../login.php");

    exit();
}

if ($current_folder == "user" && $user_role != "user") {

    header("Location: ../login.php");

    exit();
}

?>