<?php
include 'koneksiku.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $koneksi->query("SELECT file FROM tb_siswa WHERE id = $id");
    
    if ($row = $res->fetch_assoc()) {
        $filepath = 'uploads/' . $row['file'];
        if (!empty($row['file']) && file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $row['file'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit();
        }
    }
}

header("Location: index.php");
exit();
?>
