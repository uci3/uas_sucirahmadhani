<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .hero {
            background: url('https://i.pinimg.com/736x/db/67/24/db6724389a22e2cfe3011cc1adde790e.jpg') no-repeat center center/cover;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
        .hero p {
            font-size: 1.2rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        }
        .event-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .event-card img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }
        .btn-primary {
            background-color: #1E3A8A;
            border-color: #1E3A8A;
        }
        .btn-primary:hover {
            background-color: #14285D;
            border-color: #14285D;
        }
    </style>
</head>
<body>

<header class="hero">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h1>Informasi Konser</h1>
            <p>Temukan dan pesan tiket konser terbaik Anda di sini!</p>
        </div>
        <div>
            <?php
            session_start();
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                echo '<a href="logout.php" class="btn btn-light">Logout</a>';
            } else {
                echo '<a href="login.php" class="btn btn-light">Login</a>';
            }
            ?>
        </div>
    </div>
</header>

<div class="container my-5">
    <!-- Pencarian -->
    <form action="search.php" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Cari event seru di sini">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>

    <!-- Daftar Konser -->
    <div class="row">
        <?php
        // Koneksi database
        require 'koneksi.php';

        // Query data konser
        $result = $conn->query("SELECT * FROM concerts ORDER BY tanggal ASC");
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-md-4 mb-4">';
            echo '    <div class="card event-card">';
            echo '        <img src="uploads/' . $row['gambar'] . '" class="card-img-top" alt="' . $row['nama_konser'] . '">';
            echo '        <div class="card-body">';
            echo '            <h5 class="card-title">' . $row['nama_konser'] . '</h5>';
            echo '            <p class="card-text">' . $row['tanggal'] . ' | ' . $row['lokasi'] . '</p>';
            echo '            <p class="text-primary">Rp ' . number_format($row['harga_tiket'], 0, ",", ".") . '</p>';
            echo '            <a href="detail.php?id=' . $row['id_concert'] . '" class="btn btn-primary">Lihat Detail</a>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>