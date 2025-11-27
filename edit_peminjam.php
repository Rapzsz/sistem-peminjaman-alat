<?php
include('koneksi.php');
$id = $_GET['id'];
$query = "SELECT * FROM peminjam WHERE id = $id";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peminjam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Edit Peminjam</h5>
                    </div>
                    <div class="card-body">
                        <form action="update_peminjam.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Nama Peminjam</label>
                                <input type="text" name="nama_peminjam" value="<?php echo $row['nama_peminjam']; ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" value="<?php echo $row['kelas']; ?>" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Update</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>