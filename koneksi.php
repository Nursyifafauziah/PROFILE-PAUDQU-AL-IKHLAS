<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "paudqu";

// Disable mysqli exception mode temporarily if it's on to avoid fatal errors during setup
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
