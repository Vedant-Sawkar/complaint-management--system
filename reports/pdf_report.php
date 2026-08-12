<?php

include "../includes/connection.php";

require("../fpdf/fpdf.php");

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont("Arial", "B", 16);

$pdf->Cell(
    190,
    10,
    "Complaint Management System Report",
    0,
    1,
    "C"
);

$pdf->SetFont("Arial", "", 10);

$pdf->Cell(
    190,
    8,
    "Generated On: " . date("d-m-Y h:i A"),
    0,
    1,
    "R"
);

$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 10);

$pdf->Cell(10, 10, "ID", 1);
$pdf->Cell(50, 10, "Title", 1);
$pdf->Cell(35, 10, "Category", 1);
$pdf->Cell(25, 10, "Priority", 1);
$pdf->Cell(30, 10, "Status", 1);
$pdf->Cell(40, 10, "Date", 1);

$pdf->Ln();

$pdf->SetFont("Arial", "", 9);

$result = mysqli_query(
    $conn,
    "SELECT * FROM complaints ORDER BY id DESC"
);

while ($row = mysqli_fetch_assoc($result)) {

    $pdf->Cell(
        10,
        10,
        $row['id'],
        1
    );

    $pdf->Cell(
        50,
        10,
        substr($row['title'], 0, 25),
        1
    );

    $pdf->Cell(
        35,
        10,
        $row['category'],
        1
    );

    $pdf->Cell(
        25,
        10,
        $row['priority'],
        1
    );

    $pdf->Cell(
        30,
        10,
        $row['status'],
        1
    );

    $pdf->Cell(
        40,
        10,
        date(
            "d-m-Y",
            strtotime($row['created_at'])
        ),
        1
    );

    $pdf->Ln();
}

$pdf->Ln(10);

$pdf->SetFont("Arial", "I", 9);

$pdf->Cell(
    190,
    10,
    "Complaint Management System",
    0,
    1,
    "C"
);

$pdf->Output(
    "D",
    "complaint_report.pdf"
);

?>