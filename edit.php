<?php
session_start();
require 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil ID konser dari URL
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id_concert = $_GET['id'];

// Ambil data konser berdasarkan ID
$result = $conn->query("SELECT * FROM concerts WHERE id_concert = '$id_concert'");
if ($result->num_rows == 0) {
    echo "<script>alert('Data konser tidak ditemukan!'); window.location='admin.php';</script>";
    exit();
}
$concert = $result->fetch_assoc();

// Proses update data konser
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_konser = $_POST['nama_konser'];
    $tanggal = $_POST['tanggal'];
    $lokasi = $_POST['lokasi'];
    $harga_tiket = $_POST['harga_tiket'];
    $deskripsi = $_POST['deskripsi'];

    // Proses upload gambar
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != '') {
        $gambar = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($gambar);

        if (move_uploaded_file($tmp_name, $upload_file)) {
            $gambar_sql = ", image_url = '$gambar'";
        } else {
            echo "<script>alert('Gagal mengupload gambar!');</script>";
        }
    } else {
        $gambar_sql = '';
    }

    $sql = "UPDATE concerts SET 
            nama_konser = '$nama_konser', 
            tanggal = '$tanggal', 
            lokasi = '$lokasi', 
            harga_tiket = '$harga_tiket', 
            deskripsi = '$deskripsi' 
            $gambar_sql
            WHERE id_concert = '$id_concert'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data konser berhasil diupdate!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data konser: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Konser</title>
</head>
<body>
<div class="container mt-5">
    <h3>Edit Data Konser</h3>
    <a href="admin.php" class="btn btn-secondary mb-3">Kembali</a>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama_konser" class="form-label">Nama Konser</label>
            <input type="text" name="nama_konser" id="nama_konser" class="form-control" value="<?= $concert['nama_konser']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= $concert['tanggal']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" value="<?= $concert['lokasi']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="harga_tiket" class="form-label">Harga Tiket</label>
            <input type="number" name="harga_tiket" id="harga_tiket" class="form-control" value="<?= $concert['harga_tiket']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required><?= $concert['deskripsi']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
            <?php if ($concert['image_url']): ?>
                <img src="uploads/<?= $concert['image_url']; ?>" alt="<?= $concert['nama_konser']; ?>" class="img-thumbnail mt-2" width="200">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
