<?php
include "../includes/connection.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=complaint_report.xls");

// Function to prevent CSV/Excel Formula Injection
function sanitizeExcel($data) {
    // Remove newlines and tabs to maintain table structure
    $data = preg_replace("/\t|\n|\r|\n\r/", " ", $data);
    // If data starts with dangerous Excel characters, prepend a single quote
    if (preg_match('/^[\-+=@]/', $data)) {
        $data = "'" . $data;
    }
    return $data;
}

echo "ID\tTitle\tCategory\tPriority\tStatus\tDate\n";

$result = mysqli_query($conn, "SELECT * FROM complaints ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($result)) {
    echo sanitizeExcel($row['id']) . "\t";
    echo sanitizeExcel($row['title']) . "\t";
    echo sanitizeExcel($row['category']) . "\t";
    echo sanitizeExcel($row['priority']) . "\t";
    echo sanitizeExcel($row['status']) . "\t";
    echo sanitizeExcel($row['created_at']) . "\n";
}
?>