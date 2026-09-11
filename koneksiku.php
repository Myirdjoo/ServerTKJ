<?php
$host = "localhost";
$user = "root";
$pass = ""; // Tanpa password
$db   = "db_kelas";

// Mencoba koneksi
$koneksi = @new mysqli($host, $user, $pass, $db);

// Jika gagal via 'localhost', coba lewat UNIX Socket (Standar MariaDB CentOS)
if ($koneksi->connect_error) {
    $koneksi = new mysqli("127.0.0.1", $user, $pass, $db);
}

if ($koneksi->connect_error) {
    die("Koneksi Database Gagal: " . $koneksi->connect_error);
}
?>
