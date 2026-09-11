<?php
session_start();
include 'koneksiku.php';

$error = '';
if (isset($_POST['login'])) {
    $username = $koneksi->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $password_md5 = md5($password);

    $res = $koneksi->query("SELECT * FROM users WHERE username = '$username' AND (password = '$password_md5' OR password = '$password')");
    
    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'admin'; // Simpan role
        header("Location: index.php");
        exit();
    } else {
        $error = 'Akses Ditolak: Pengguna / Sandi Tidak Valid!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>OTENTIKASI ARSIP RAHASIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Special+Elite&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #2b211b radial-gradient(circle, #3d3027 0%, #1a130f 100%);
            font-family: 'Courier Prime', monospace;
            color: #2c221e;
        }
        .login-card {
            background-color: #f2e6ce;
            border: 8px double #4a3425;
            box-shadow: 0 0 25px rgba(0,0,0,0.9);
            padding: 40px;
            border-radius: 2px;
        }
        .login-title {
            font-family: 'Special Elite', cursive;
            letter-spacing: 2px;
            border-bottom: 2px solid #4a3425;
            padding-bottom: 10px;
        }
        .form-control {
            background-color: #e8d7b7;
            border: 2px solid #4a3425;
            color: #1a130f;
            border-radius: 0;
            font-weight: bold;
        }
        .form-control:focus {
            background-color: #fff8eb;
            border-color: #8b2500;
            box-shadow: none;
        }
        .btn-vintage {
            background-color: #8b2500;
            color: #f2e6ce;
            border: 2px solid #2c221e;
            font-family: 'Special Elite', cursive;
            padding: 12px;
            box-shadow: 4px 4px 0px #2c221e;
            font-size: 16px;
            transition: all 0.15s ease-in-out;
        }
        .btn-vintage:hover {
            background-color: #5c1800;
            color: #fff;
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px #2c221e;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

<div class="container" style="max-width: 420px;">
    <div class="login-card text-center">
        <h3 class="login-title mb-2">OTENTIKASI ARSIP</h3>
        <p class="text-muted small fst-italic mb-4">[ LOG MASUK ARSIP RESMI ]</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small border border-dark rounded-0 fw-bold" style="background:#e8a4a4; color:#4a0d0d;"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label fw-bold small">NAMA PENGGUNA</label>
                <input type="text" name="username" class="form-control" required placeholder="Masukkan ID...">
            </div>
            <div class="mb-4 text-start">
                <label class="form-label fw-bold small">KATA SANDI</label>
                <input type="password" name="password" class="form-control" required placeholder="Masukkan Sandi...">
            </div>
            <button type="submit" name="login" class="btn btn-vintage w-100">BUKA DOKUMEN &rarr;</button>
        </form>
    </div>
</div>

</body>
</html>
