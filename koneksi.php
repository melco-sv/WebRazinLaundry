<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "phpmailer"; // Gantilah nama database sesuai kebutuhan Anda

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

