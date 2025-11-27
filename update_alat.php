<?php
include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($connection, $_POST['id']);
    $nama_alat = mysqli_real_escape_string($connection, $_POST['nama_alat']);
    $stok = mysqli_real_escape_string($connection, $_POST['stok']);
    
    // Validasi: stok tidak boleh kurang dari jumlah yang sedang dipinjam
    $current = mysqli_fetch_array(mysqli_query($connection, "SELECT dipinjam FROM alat_olahraga WHERE id='$id'"));
    if ($stok < $current['dipinjam']) {
        echo "<script>alert('Error: Stok tidak boleh kurang dari jumlah yang sedang dipinjam!'); window.history.back();</script>";
        exit;
    }
    
    $query = "UPDATE alat_olahraga SET nama_alat='$nama_alat', stok='$stok' WHERE id='$id'";
    
    if (mysqli_query($connection, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>