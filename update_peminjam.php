<?php
include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($connection, $_POST['id']);
    $nama_peminjam = mysqli_real_escape_string($connection, $_POST['nama_peminjam']);
    $telepon = mysqli_real_escape_string($connection, $_POST['telepon']);
    
    $query = "UPDATE peminjam SET nama_peminjam='$nama_peminjam', telepon='$telepon' WHERE id='$id'";
    
    if (mysqli_query($connection, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>