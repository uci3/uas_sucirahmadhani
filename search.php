<?php
session_start();
require 'koneksi.php'; // Koneksi ke database

// Cek apakah parameter pencarian tersedia
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%'; // Tambahkan wildcard untuk pencarian LIKE

    // Siapkan pernyataan SQL dengan prepared statement
    $stmt = $conn->prepare("SELECT * FROM concerts WHERE nama_konser LIKE ? OR lokasi LIKE ? ORDER BY tanggal ASC");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Jika tidak ada query pencarian, redirect ke halaman utama atau tampilkan pesan error
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            font-family: 'Poppins', sans-serif;
            color: #343a40;
        }
        .event-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .event-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .event-card img {
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #0056b3, #004085);
        }
        .no-results {
            text-align: center;
            margin-top: 80px;
            color: #6c757d;
        }
        .no-results h3 {
            font-size: 1.5rem;
        }
        .no-results p {
            font-size: 1rem;
        }
        .search-title {
            text-align: center;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h1 class="search-title">Hasil Pencarian</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card event-card">
                        <img src="<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="Poster <?= htmlspecialchars($row['gambar']) ?>">
                        <div class="card-body">
                            <h5 class="card-title text-center fw-bold"><?= htmlspecialchars($row['nama_konser']) ?></h5>
                            <p class="card-text text-center">
                                <small class="text-muted">
                                    <?= date("d M Y", strtotime($row['tanggal'])) ?> | <?= htmlspecialchars($row['lokasi']) ?>
                                </small>
                            </p>
                            <p class="text-center text-primary fw-bold fs-5">Rp <?= number_format($row['harga_tiket'], 0, ",", ".") ?></p>
                            <div class="d-flex justify-content-center">
                                <a href="detail.php?id=<?= $row['id_concert'] ?>" class="btn btn-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-results">
            <h3>Tidak ada hasil ditemukan untuk "<strong><?= htmlspecialchars($_GET['query']) ?></strong>"</h3>
            <p>Coba masukkan kata kunci yang berbeda atau lihat konser lainnya.</p>
            <a href="index.php" class="btn btn-secondary mt-3">Kembali ke Beranda</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>