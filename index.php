<?php
session_start();
include 'koneksiku.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$result = $koneksi->query("SELECT * FROM tb_siswa ORDER BY no_absen ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BUKU REGISTER SISWA - ARSIP RESMI</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,700;1,400&family=Special+Elite&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #2b211b radial-gradient(circle, #3d3027 0%, #1a130f 100%);
            font-family: 'Courier Prime', monospace;
            color: #2c221e;
            padding: 20px 0;
        }
        .vintage-paper {
            background-color: #f2e6ce;
            background-image: 
                radial-gradient(#d1bf9d 1px, transparent 0),
                radial-gradient(circle, rgba(0,0,0,0) 60%, rgba(87,63,45,0.3) 100%);
            background-size: 20px 20px, 100% 100%;
            border: 12px double #4a3425;
            box-shadow: 0 0 25px rgba(0,0,0,0.8), inset 0 0 80px rgba(100,70,40,0.25);
            padding: 40px;
            position: relative;
        }
        .header-title {
            font-family: 'Special Elite', cursive;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .stamp-mark {
            border: 3px dashed #8b2500;
            color: #8b2500;
            font-family: 'Special Elite', cursive;
            padding: 5px 12px;
            text-transform: uppercase;
            transform: rotate(-5deg);
            display: inline-block;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .stamp-user {
            border: 3px dashed #2c4d63;
            color: #2c4d63;
            font-family: 'Special Elite', cursive;
            padding: 5px 12px;
            text-transform: uppercase;
            transform: rotate(3deg);
            display: inline-block;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .table-vintage {
            border: 3px solid #3d2a1d;
            background-color: rgba(253, 248, 236, 0.7);
        }
        .table-vintage th {
            background-color: #3d2a1d;
            color: #f2e6ce;
            font-family: 'Special Elite', cursive;
            border-bottom: 3px double #1a130f;
            letter-spacing: 1px;
        }
        .table-vintage td {
            border-color: #c4b092;
        }
        
        /* Frame Polaroid Mini */
        .photo-polaroid {
            background: #fff;
            padding: 6px 6px 14px 6px;
            border: 1px solid #b39f82;
            box-shadow: 4px 4px 8px rgba(0,0,0,0.25);
            transform: rotate(-1.5deg);
            display: inline-block;
            cursor: zoom-in;
            transition: transform 0.2s ease-in-out;
        }
        .photo-polaroid:hover {
            transform: scale(1.08) rotate(0deg);
        }
        .photo-polaroid img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            filter: sepia(0.15) contrast(1.05);
            border: 1px solid #ddd;
        }

        .btn-vintage {
            font-family: 'Special Elite', cursive;
            border: 2px solid #2c221e;
            border-radius: 0;
            box-shadow: 3px 3px 0px #2c221e;
            font-weight: bold;
            transition: all 0.15s ease-in-out;
        }
        .btn-vintage:hover {
            transform: translate(-2px, -2px);
            box-shadow: 5px 5px 0px #2c221e;
        }
        .btn-add { background-color: #3b5a32; color: #f2e6ce; }
        .btn-del { background-color: #8b2500; color: #f2e6ce; }
        .btn-edit { background-color: #c28829; color: #fff; }
        .btn-dl { background-color: #2c4d63; color: #fff; }

        /* Overlay Lightbox Fullscreen Zoom */
        .zoom-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 10, 7, 0.88);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            cursor: zoom-out;
        }
        .zoom-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }
        .zoom-box {
            background: #fff;
            padding: 15px 15px 40px 15px;
            border: 1px solid #b39f82;
            box-shadow: 0 0 30px rgba(0,0,0,0.8);
            transform: scale(0.7);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-width: 90vw;
            max-height: 85vh;
            text-align: center;
        }
        .zoom-overlay.active .zoom-box {
            transform: scale(1);
        }
        .zoom-box img {
            max-width: 100%;
            max-height: 65vh;
            object-fit: contain;
            border: 1px solid #ccc;
        }
        .zoom-caption {
            font-family: 'Special Elite', cursive;
            color: #2c221e;
            margin-top: 15px;
            font-size: 1.2rem;
            letter-spacing: 1px;
        }

        /* Style Footer Kreator / Instagram */
        .creator-footer {
            margin-top: 35px;
            padding-top: 15px;
            border-top: 2px dashed #4a3425;
            font-family: 'Special Elite', cursive;
            color: #3d2a1d;
        }
        .creator-link {
            color: #8b2500;
            text-decoration: none;
            font-weight: bold;
            padding: 2px 6px;
            border: 1px dashed #8b2500;
            background-color: rgba(139, 37, 0, 0.08);
            transition: all 0.2s ease;
        }
        .creator-link:hover {
            background-color: #8b2500;
            color: #f2e6ce;
        }
        .wife-link {
            color: #a020f0;
            text-decoration: none;
            font-weight: bold;
            padding: 2px 6px;
            border: 1px dashed #a020f0;
            background-color: rgba(160, 32, 240, 0.08);
            transition: all 0.2s ease;
        }
        .wife-link:hover {
            background-color: #a020f0;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container my-4">
    <div class="vintage-paper">
        <div class="row align-items-center mb-4 pb-3 border-bottom border-2 border-dark">
            <div class="col-md-8">
                <h1 class="header-title mb-1"><i class="fa-solid fa-folder-closed me-2"></i>BUKU INDUK REGISTRASI</h1>
                <p class="mb-0 text-muted fst-italic">
                    Pengakses: <u><?= htmlspecialchars($_SESSION['user']); ?></u> 
                    (Status: <b><?= $is_admin ? 'PETUGAS ARSIP / ADMIN' : 'PEMBACA / SISWA'; ?></b>)
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <?php if ($is_admin): ?>
                    <span class="stamp-mark"><i class="fa-solid fa-user-gear me-1"></i> MODE ADMIN</span>
                <?php else: ?>
                    <span class="stamp-user"><i class="fa-solid fa-eye me-1"></i> MODE PRATINJAU</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <div>
                <?php if ($is_admin): ?>
                    <a href="tambah.php" class="btn btn-vintage btn-add"><i class="fa-solid fa-plus me-1"></i> TAMBAH SISWA BARU</a>
                <?php else: ?>
                    <span class="badge p-2 font-monospace border border-dark" style="background:#d6c3a3; color:#3d2a1d;">
                        <i class="fa-solid fa-lock me-1"></i> Hak Akses Baca Saja (Read-Only)
                    </span>
                <?php endif; ?>
            </div>
            <a href="logout.php" class="btn btn-vintage btn-del" onclick="return confirm('Akhiri sesi pemeriksaan arsip?')"><i class="fa-solid fa-power-off me-1"></i> KELUAR ARSIP</a>
        </div>

        <div class="table-responsive">
            <table class="table table-vintage align-middle text-nowrap mb-0">
                <thead>
                    <tr>
                        <th class="text-center">NO. ABSEN</th>
                        <th class="text-center">PASFOTO SISWA</th>
                        <th>NAMA LENGKAP SISWA</th>
                        <th class="text-center">KELAS</th>
                        <th class="text-center">LAMPIRAN</th>
                        <?php if ($is_admin): ?>
                            <th class="text-center">OPSI PERUBAHAN</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center fw-bold fs-5" style="font-family:'Special Elite';">#<?= sprintf("%02d", $row['no_absen']); ?></td>
                        <td class="text-center">
                            <?php 
                            $ext = pathinfo($row['file'], PATHINFO_EXTENSION);
                            $is_img = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            if (!empty($row['file']) && $is_img && file_exists('uploads/' . $row['file'])): 
                            ?>
                                <div class="photo-polaroid" onclick="bukaZoom('uploads/<?= $row['file']; ?>', '<?= htmlspecialchars($row['nama'], ENT_QUOTES); ?>')" title="Klik untuk memperbesar foto">
                                    <img src="uploads/<?= $row['file']; ?>" alt="Foto">
                                </div>
                            <?php else: ?>
                                <span class="badge bg-dark text-warning border border-secondary rounded-0 font-monospace">NIL / NO FOTO</span>
                            <?php endif; ?>
                        </td>

                        <td class="fw-bold fs-5" style="font-family: 'Playfair Display', serif; color: #1a130f;">
                            <?= htmlspecialchars($row['nama']); ?>
                        </td>

                        <td class="text-center">
                            <span class="badge border border-dark text-dark px-3 py-2 fs-6" style="background:#e8d7b7; font-family:'Special Elite';">
                                <?= htmlspecialchars($row['kelas']); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if (!empty($row['file'])): ?>
                                <a href="download.php?id=<?= $row['id']; ?>" class="btn btn-vintage btn-dl btn-sm">
                                    <i class="fa-solid fa-paperclip me-1"></i> BERKAS
                                </a>
                            <?php else: ?>
                                <span class="text-muted fst-italic small">Tidak ada</span>
                            <?php endif; ?>
                        </td>
                        <?php if ($is_admin): ?>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-vintage btn-edit btn-sm me-1"><i class="fa-solid fa-pen-nib"></i> REVISI</a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-vintage btn-del btn-sm" onclick="return confirm('Hapus lembaran arsip ini secara permanen?')"><i class="fa-solid fa-trash-can"></i> MUSNAHKAN</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="<?= $is_admin ? '6' : '5'; ?>" class="text-center py-5 text-muted fst-italic fs-5">
                            Belum ada lembaran data siswa yang tercatat dalam dokumen ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER PEMBUAT & INSTAGRAM -->
        <div class="creator-footer text-center">
            <p class="mb-1">
                <i class="fa-solid fa-code me-1"></i> Dibuat oleh: 
                <a href="https://www.instagram.com/myirdjo" target="_blank" class="creator-link">
                    <i class="fa-brands fa-instagram me-1"></i>@myirdjo
                </a>
            </p>
            <p class="mb-0 small">
                Jangan lupa follow ya! Follow juga IG bini gwehh: 
                <a href="https://www.instagram.com/octaviaaaa_12/" target="_blank" class="wife-link">
                    <i class="fa-brands fa-instagram me-1"></i>@octaviaaaa_12
                </a>
            </p>
        </div>

    </div>
</div>

<!-- OVERLAY ZOOM -->
<div class="zoom-overlay" id="zoomOverlay" onclick="tutupZoom()">
    <div class="zoom-box" onclick="event.stopPropagation()">
        <img id="zoomImage" src="" alt="Zoom Foto">
        <div class="zoom-caption" id="zoomCaption"></div>
        <small class="text-muted d-block mt-2" style="font-size: 11px;">(Klik sembarang tempat untuk menutup)</small>
    </div>
</div>

<script>
function bukaZoom(src, nama) {
    document.getElementById('zoomImage').src = src;
    document.getElementById('zoomCaption').innerText = nama;
    document.getElementById('zoomOverlay').classList.add('active');
}

function tutupZoom() {
    document.getElementById('zoomOverlay').classList.remove('active');
}
</script>

</body>
</html>
