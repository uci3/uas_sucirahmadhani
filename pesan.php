<?php
session_start();
require 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki role 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Proses pemesanan tiket
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_concert = $_POST['id_concert'];
    $jenis_tiket = $_POST['jenis_tiket'];
    $jumlah_tiket = $_POST['jumlah_tiket'];
    $catatan = $_POST['catatan'];

    // Proses upload bukti pembayaran
    $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];
    $tmp_name = $_FILES['bukti_pembayaran']['tmp_name'];
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($bukti_pembayaran);

    // Pindahkan file yang diupload ke folder tujuan
    if (move_uploaded_file($tmp_name, $upload_file)) {
        $sql = "INSERT INTO registrations (id_user, id_concert, jenis_tiket, jumlah_tiket, bukti_pembayaran, catatan) 
                VALUES ('$id_user', '$id_concert', '$jenis_tiket', '$jumlah_tiket', '$bukti_pembayaran', '$catatan')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Pemesanan berhasil!'); window.location='user.php';</script>";
        } else {
            echo "<script>alert('Gagal memesan tiket: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengupload bukti pembayaran.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Pesan Tiket</title>
</head>
<body>
<div class="container mt-5">
    <h3>Pesan Tiket</h3>
    <a href="user.php" class="btn btn-secondary mb-3">Kembali</a>
    <?php
    // Ambil detail konser berdasarkan ID
    if (isset($_GET['id'])) {
        $id_concert = $_GET['id'];
        $result = $conn->query("SELECT * FROM concerts WHERE id_concert = '$id_concert'");
        if ($result->num_rows > 0) {
            $concert = $result->fetch_assoc();
            ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $concert['nama_konser']; ?></h5>
                    <p class="card-text"><?= $concert['tanggal']; ?> | <?= $concert['lokasi']; ?></p>
                    <p class="card-text">Harga Tiket: Rp <?= number_format($concert['harga_tiket'], 0, ',', '.'); ?></p>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_concert" value="<?= $concert['id_concert']; ?>">
                        <div class="mb-3">
                            <label for="jenis_tiket" class="form-label">Jenis Tiket</label>
                            <select name="jenis_tiket" id="jenis_tiket" class="form-select" required>
                                <option value="Regular">Regular</option>
                                <option value="VIP">VIP</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_tiket" class="form-label">Jumlah Tiket</label>
                            <input type="number" name="jumlah_tiket" id="jumlah_tiket" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control" placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Pesan Tiket</button>
                    </form>
                </div>
            </div>
            <?php
        } else {
            echo "<p>Konser tidak ditemukan.</p>";
        }
    } else {
        echo "<p>ID konser tidak ditemukan.</p>";
    }
    ?>
</div>
</body>
</html>
