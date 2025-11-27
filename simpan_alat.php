<?php
include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_alat = mysqli_real_escape_string($connection, $_POST['nama_alat']);
    $stok = mysqli_real_escape_string($connection, $_POST['stok']);
    
    $query = "INSERT INTO alat_olahraga (nama_alat, stok) VALUES ('$nama_alat', '$stok')";
    
    if (mysqli_query($connection, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>