<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "complaint"
);

if (!$conn) {
    die("Database connection failed.");
}

?>