<?php
// Mulai sesi
session_start();

// Koneksi ke database
require 'koneksi.php';

// Periksa apakah ID konser diberikan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID konser tidak ditemukan.";
    exit();
}

// Ambil ID konser dari URL
$id = intval($_GET['id']);

// Query untuk mendapatkan detail konser
$sql = "SELECT * FROM concerts WHERE id_concert = $id";
$result = $conn->query($sql);

// Periksa apakah data ditemukan
if ($result->num_rows == 0) {
    echo "Konser tidak ditemukan.";
    exit();
}

// Ambil data konser
$konser = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konser - <?= htmlspecialchars($konser['nama_konser']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            background: url('uploads/<?= htmlspecialchars($konser['gambar']); ?>') no-repeat center center/cover;
            height: 300px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        .hero::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .hero h1 {
            z-index: 1;
            font-size: 3rem;
        }
        .hero p {
            z-index: 1;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="hero">
    <div>
        <h1><?= htmlspecialchars($konser['nama_konser']); ?></h1>
        <p><?= htmlspecialchars($konser['tanggal']); ?> | <?= htmlspecialchars($konser['lokasi']); ?></p>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?= htmlspecialchars($konser['gambar']); ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($konser['nama_konser']); ?>">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($konser['nama_konser']); ?></h2>
            <p><strong>Tanggal:</strong> <?= htmlspecialchars($konser['tanggal']); ?></p>
            <p><strong>Lokasi:</strong> <?= htmlspecialchars($konser['lokasi']); ?></p>
            <p><strong>Harga Tiket:</strong> Rp <?= number_format($konser['harga_tiket'], 0, ',', '.'); ?></p>
            <p><strong>Deskripsi:</strong></p>
            <p><?= nl2br(htmlspecialchars($konser['deskripsi'])); ?></p>
            <a href="pesan.php?id=<?= $konser['id_concert']; ?>" class="btn btn-primary">Pesan Tiket</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
