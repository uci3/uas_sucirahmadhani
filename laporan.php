<?php
session_start();
require 'koneksi.php'; // Pastikan koneksi ke database sudah benar

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Query untuk mendapatkan daftar konser
$sql = "SELECT * FROM concerts";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Laporan Daftar Konser</h1>
    <button class="btn btn-primary mb-3" onclick="printReport()">Cetak Laporan</button>
    <a href="admin.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Konser</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Harga Tiket</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_concert']}</td>
                            <td>{$row['nama_konser']}</td>
                            <td>{$row['tanggal']}</td>
                            <td>{$row['lokasi']}</td>
                            <td>Rp " . number_format($row['harga_tiket'], 0, ',', '.') . "</td>
                            <td>{$row['deskripsi']}</td>
                            <td><img src='uploads/{$row['gambar']}' alt='Gambar Konser' width='100'></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Tidak ada data konser ditemukan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>