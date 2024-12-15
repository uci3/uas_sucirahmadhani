<?php
require 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query delete
    $sql = "DELETE FROM concerts WHERE id_concert = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Data konser berhasil dihapus!');
                window.location.href = 'admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data konser.');
                window.location.href = 'admin.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admin.php");
    exit;
}
?>
