<?php
include('koneksi.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM alat_olahraga WHERE id = $id";
    
    if (mysqli_query($connection, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>