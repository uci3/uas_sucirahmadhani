<?php
session_start();
require 'koneksi.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Tambah Konser
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_konser'])) {
    $nama_konser = $_POST['nama_konser'];
    $tanggal = $_POST['tanggal'];
    $lokasi = $_POST['lokasi'];
    $harga_tiket = $_POST['harga_tiket'];
    $deskripsi = $_POST['deskripsi'];

    // Upload gambar konser
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($gambar);

    if (move_uploaded_file($tmp_name, $upload_file)) {
        $sql = "INSERT INTO concerts (nama_konser, tanggal, lokasi, harga_tiket, deskripsi, gambar) 
                VALUES ('$nama_konser', '$tanggal', '$lokasi', '$harga_tiket', '$deskripsi', '$gambar')";
        $conn->query($sql);
    }
}

// Hapus Konser
if (isset($_GET['hapus_konser'])) {
    $id_concert = $_GET['hapus_konser'];

    // Hapus file gambar dari folder uploads
    $result = $conn->query("SELECT gambar FROM concerts WHERE id_concert = '$id_concert'");
    $row = $result->fetch_assoc();
    if ($row && file_exists("uploads/" . $row['gambar'])) {
        unlink("uploads/" . $row['gambar']);
    }

    // Hapus konser dari database
    $conn->query("DELETE FROM concerts WHERE id_concert = '$id_concert'");
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body>
<div class="container mt-5">
    <h3>Selamat Datang, Admin</h3>
    <a href="logout.php" class="btn btn-danger">Logout</a>

    <h4 class="mt-4">Tambah Konser</h4>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label for="nama_konser" class="form-label">Nama Konser</label>
            <input type="text" class="form-control" id="nama_konser" name="nama_konser" required>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
        </div>
        <div class="mb-3">
            <label for="harga_tiket" class="form-label">Harga Tiket</label>
            <input type="number" class="form-control" id="harga_tiket" name="harga_tiket" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Upload Gambar</label>
            <input type="file" class="form-control" id="gambar" name="gambar" required>
        </div>
        <button type="submit" name="tambah_konser" class="btn btn-primary">Tambah Konser</button>
    </form>

    <h4 class="mt-4">Daftar Konser</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Konser</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Harga Tiket</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM concerts");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td><img src='uploads/{$row['gambar']}' alt='Gambar Konser' width='100'></td>
                        <td>{$row['nama_konser']}</td>
                        <td>{$row['tanggal']}</td>
                        <td>{$row['lokasi']}</td>
                        <td>Rp " . number_format($row['harga_tiket'], 0, ',', '.') . "</td>
                        <td>
                        <a href='edit.php?id={$row['id_concert']}' class='btn btn-warning'>Edit</a>
                        <a href='delete.php?id={$row['id_concert']}' class='btn btn-danger'>Hapus</a>
                        <a href='laporan.php?id={$row['id_concert']}' class='btn btn-info'>Laporan</a>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    
</div>
</body>
</html>

</div>
</body>
</html> 