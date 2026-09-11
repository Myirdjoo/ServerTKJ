<?php

$koneksi = new mysqli(
    "localhost",
    "root",
    "kali",
    "db_web"
);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$data = $koneksi->query("SELECT * FROM biodata");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Biodata</title>
</head>

<body>

<h1>BIODATA</h1>

<?php while ($row = $data->fetch_assoc()) { ?>

    <p>NAMA: <?= $row['nama']; ?></p>
    <p>KELAS: <?= $row['kelas']; ?></p>
    <p>NO ABSEN: <?= $row['no_absen']; ?></p>

<?php } ?>

<p>-- UJIAN WEBSERVER --</p>

</body>
</html>
