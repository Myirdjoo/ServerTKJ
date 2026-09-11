<?php
$host = "localhost";
$user = "root";
$pass = "kali";
$db   = "db_kelas";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi ke db_kelas gagal: " . $conn->connect_error);
}
?>
