<?php
session_start();
include 'koneksiku.php';

// Proteksi Login & Admin
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses Ditolak: Khusus Admin/Petugas!'); window.location='index.php';</script>";
    exit();
}

// Ambil Data Berdasarkan ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$res = $koneksi->query("SELECT * FROM tb_siswa WHERE id = $id");

if (!$res || $res->num_rows == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

$data = $res->fetch_assoc();

// Proses Simpan Perubahan (Revisi)
if (isset($_POST['submit'])) {
    $nama     = $koneksi->real_escape_string($_POST['nama']);
    $kelas    = $koneksi->real_escape_string($_POST['kelas']);
    $no_absen = intval($_POST['no_absen']);

    $filename = $_FILES['file']['name'];
    $filetmp  = $_FILES['file']['tmp_name'];

    if (!empty($filename)) {
        // Jika ada file baru diunggah
        $new_filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $filename);
        move_uploaded_file($filetmp, 'uploads/' . $new_filename);

        // Hapus file lama jika ada
        if (!empty($data['file']) && file_exists('uploads/' . $data['file'])) {
            unlink('uploads/' . $data['file']);
        }

        $sql = "UPDATE tb_siswa SET nama='$nama', kelas='$kelas', no_absen='$no_absen', file='$new_filename' WHERE id=$id";
    } else {
        // Jika file tidak diganti
        $sql = "UPDATE tb_siswa SET nama='$nama', kelas='$kelas', no_absen='$no_absen' WHERE id=$id";
    }

    if ($koneksi->query($sql)) {
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>REVISI WARKAT SISWA</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Special+Elite&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #2b211b radial-gradient(circle, #3d3027 0%, #1a130f 100%);
            font-family: 'Courier Prime', monospace;
            color: #2c221e;
            padding: 30px 0;
        }
        .vintage-card {
            background-color: #f2e6ce;
            background-image: radial-gradient(circle, rgba(0,0,0,0) 60%, rgba(87,63,45,0.3) 100%);
            border: 8px double #4a3425;
            box-shadow: 0 0 20px rgba(0,0,0,0.8);
            padding: 35px;
        }
        .title-form {
            font-family: 'Special Elite', cursive;
            border-bottom: 2px dashed #4a3425;
            letter-spacing: 2px;
        }
        .form-control {
            background-color: #e8d7b7;
            border: 2px solid #4a3425;
            color: #1a130f;
            border-radius: 0;
            font-family: 'Courier Prime', monospace;
            font-weight: bold;
        }
        .form-control:focus {
            background-color: #fff8eb;
            border-color: #8b2500;
            box-shadow: none;
        }
        .pasfoto-box {
            width: 150px;
            height: 180px;
            border: 3px dashed #4a3425;
            background-color: #ded0b6;
            margin: 10px auto;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.15);
        }
        .pasfoto-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: sepia(0.2) contrast(1.1);
        }
        .btn-vintage {
            font-family: 'Special Elite', cursive;
            border: 2px solid #2c221e;
            border-radius: 0;
            box-shadow: 3px 3px 0px #2c221e;
            font-weight: bold;
            padding: 10px 20px;
        }
        .btn-save { background-color: #c28829; color: #fff; }
        .btn-save:hover { background-color: #9c6c1d; color: #fff; }
        .btn-back { background-color: #5c5248; color: #fff; }
    </style>
</head>
<body>

<div class="container" style="max-width: 600px;">
    <div class="vintage-card">
        <h3 class="title-form text-center pb-2 mb-4">REVISI DATA SISWA</h3>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">NAMA LENGKAP SISWA</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">KELAS</label>
                    <input type="text" name="kelas" class="form-control" value="<?= htmlspecialchars($data['kelas']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NOMOR ABSEN</label>
                    <input type="number" name="no_absen" class="form-control" value="<?= htmlspecialchars($data['no_absen']); ?>" required>
                </div>
            </div>

            <div class="mb-3 text-center">
                <label class="form-label fw-bold d-block text-start">PASFOTO / LAMPIRAN BERKAS</label>
                <div class="pasfoto-box">
                    <?php 
                    $ext = pathinfo($data['file'], PATHINFO_EXTENSION);
                    $is_img = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    if (!empty($data['file']) && $is_img && file_exists('uploads/' . $data['file'])): 
                    ?>
                        <img id="imgPreview" src="uploads/<?= $data['file']; ?>" alt="Pasfoto">
                        <span id="textPlaceholder" style="display:none;" class="text-muted small fst-italic p-2"></span>
                    <?php else: ?>
                        <span id="textPlaceholder" class="text-muted small fst-italic p-2">
                            <?= !empty($data['file']) ? 'Berkas: ' . $data['file'] : 'Belum Ada Foto'; ?>
                        </span>
                        <img id="imgPreview" style="display:none;" alt="Pasfoto">
                    <?php endif; ?>
                </div>
                <small class="text-muted d-block mb-2">* Biarkan kosong jika tidak ingin mengganti berkas/foto.</small>
                <input type="file" name="file" class="form-control" onchange="previewImage(event)">
            </div>

            <div class="d-flex justify-content-between mt-4 pt-3 border-top border-2 border-dark">
                <a href="index.php" class="btn btn-vintage btn-back">&larr; BATAL</a>
                <button type="submit" name="submit" class="btn btn-vintage btn-save">SIMPAN PERUBAHAN &rarr;</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imgPreview');
    const placeholder = document.getElementById('textPlaceholder');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if(placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            if(placeholder) {
                placeholder.style.display = 'block';
                placeholder.innerText = "Dokumen Non-Gambar (" + file.name.split('.').pop().toUpperCase() + ")";
            }
        }
    }
}
</script>

</body>
</html>
