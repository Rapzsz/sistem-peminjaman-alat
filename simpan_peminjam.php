<?php
include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_peminjam = mysqli_real_escape_string($connection, $_POST['nama_peminjam']);
    $kelas = mysqli_real_escape_string($connection, $_POST['kelas']);
    
    $query = "INSERT INTO peminjam (nama_peminjam, kelas) VALUES ('$nama_peminjam', '$kelas')";
    
    if (mysqli_query($connection, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>