<?php
include 'koneksi.php';

$upload_dir = 'uploads/';

// 1. TAMBAH SISWA + UPLOAD FILE
if (isset($_POST['tambah'])) {
    $nama     = $conn->real_escape_string($_POST['nama']);
    $kelas    = $conn->real_escape_string($_POST['kelas']);
    $no_absen = intval($_POST['no_absen']);

    $filename = $_FILES['file']['name'];
    $filetmp  = $_FILES['file']['tmp_name'];

    $new_filename = "";
    if (!empty($filename)) {
        $new_filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $filename);
        move_uploaded_file($filetmp, $upload_dir . $new_filename);
    }

    $sql = "INSERT INTO tb_siswa (nama, kelas, no_absen, file) VALUES ('$nama', '$kelas', '$no_absen', '$new_filename')";
    $conn->query($sql);

    header("Location: index.php");
    exit();
}

// 2. EDIT SISWA + GANTI FILE (OPSIONAL)
if (isset($_POST['edit'])) {
    $id       = intval($_POST['id']);
    $nama     = $conn->real_escape_string($_POST['nama']);
    $kelas    = $conn->real_escape_string($_POST['kelas']);
    $no_absen = intval($_POST['no_absen']);

    if (!empty($_FILES['file']['name'])) {
        // Hapus file lama jika ada
        $res = $conn->query("SELECT file FROM tb_siswa WHERE id = $id");
        if ($row = $res->fetch_assoc()) {
            if (!empty($row['file'])) @unlink($upload_dir . $row['file']);
        }

        $filename     = $_FILES['file']['name'];
        $filetmp      = $_FILES['file']['tmp_name'];
        $new_filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $filename);

        move_uploaded_file($filetmp, $upload_dir . $new_filename);

        $sql = "UPDATE tb_siswa SET nama='$nama', kelas='$kelas', no_absen='$no_absen', file='$new_filename' WHERE id=$id";
    } else {
        $sql = "UPDATE tb_siswa SET nama='$nama', kelas='$kelas', no_absen='$no_absen' WHERE id=$id";
    }

    $conn->query($sql);
    header("Location: index.php");
    exit();
}

// 3. HAPUS SISWA & FILE
if (isset($_GET['hapus'])) {
    $id  = intval($_GET['hapus']);
    $res = $conn->query("SELECT file FROM tb_siswa WHERE id = $id");
    if ($row = $res->fetch_assoc()) {
        if (!empty($row['file'])) @unlink($upload_dir . $row['file']);
    }
    $conn->query("DELETE FROM tb_siswa WHERE id = $id");

    header("Location: index.php");
    exit();
}

// 4. DOWNLOAD FILE
if (isset($_GET['download'])) {
    $id  = intval($_GET['download']);
    $res = $conn->query("SELECT file FROM tb_siswa WHERE id = $id");
    if ($row = $res->fetch_assoc()) {
        $filepath = $upload_dir . $row['file'];
        if (!empty($row['file']) && file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $row['file'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }
    }
}
?>
