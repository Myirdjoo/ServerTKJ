<?php
include 'koneksiku.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $res = $koneksi->query("SELECT file FROM tb_siswa WHERE id = $id");
    if ($row = $res->fetch_assoc()) {
        if (!empty($row['file'])) @unlink('uploads/' . $row['file']);
    }

    $koneksi->query("DELETE FROM tb_siswa WHERE id = $id");
}

header("Location: index.php");
exit();
?>
