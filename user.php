<?php
session_start();
require 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki role 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Tambah Pendaftaran
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['daftar_konser'])) {
    $id_concert = $_POST['id_concert'];
    $jenis_tiket = $_POST['jenis_tiket'];
    $jumlah_tiket = $_POST['jumlah_tiket'];
    $catatan = $_POST['catatan'];

    // Upload Bukti Pembayaran
    $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];
    $tmp_name = $_FILES['bukti_pembayaran']['tmp_name'];
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($bukti_pembayaran);

    if (move_uploaded_file($tmp_name, $upload_file)) {
        $sql = "INSERT INTO registrations (id_user, id_concert, jenis_tiket, jumlah_tiket, bukti_pembayaran, catatan) 
                VALUES ('$id_user', '$id_concert', '$jenis_tiket', '$jumlah_tiket', '$bukti_pembayaran', '$catatan')";
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Dashboard</title>
</head>
<body>
<script>
        function printReport() {
            window.print();
        }
    </script>
<div class="container mt-5">
    <h3>Selamat Datang, User</h3>
    <a href="logout.php" class="btn btn-danger">Logout</a>

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
                            <form method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='id_concert' value='{$row['id_concert']}'>
                                <select name='jenis_tiket' class='form-select mb-2' required>
                                    <option value='Regular'>Regular</option>
                                    <option value='VIP'>VIP</option>
                                </select>
                                <input type='number' name='jumlah_tiket' class='form-control mb-2' placeholder='Jumlah Tiket' required>
                                <input type='file' name='bukti_pembayaran' class='form-control mb-2' required>
                                <textarea name='catatan' class='form-control mb-2' placeholder='Catatan Tambahan'></textarea>
                                <button type='submit' name='daftar_konser' class='btn btn-primary'>Daftar</button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <h4 class="mt-4">Pendaftaran Saya</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Konser</th>
                <th>Jenis Tiket</th>
                <th>Jumlah Tiket</th>
                <th>Bukti Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT r.*, c.nama_konser 
                                    FROM registrations r 
                                    JOIN concerts c ON r.id_concert = c.id_concert 
                                    WHERE r.id_user = '$id_user'");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nama_konser']}</td>
                        <td>{$row['jenis_tiket']}</td>
                        <td>{$row['jumlah_tiket']}</td>
                        <td><a href='uploads/{$row['bukti_pembayaran']}' target='_blank'>Lihat</a></td>
                        <td>
                            <a href='edit.php.php?id={$row['id_registrasi']}' class='btn btn-warning'>Edit</a>
                            <a href='deleteuser.php?id={$row['id_registrasi']}' class='btn btn-danger'>Hapus</a>
                            <a href='laporanuser.php?id={$row['id_concert']}' class='btn btn-info'>Laporan</a>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
