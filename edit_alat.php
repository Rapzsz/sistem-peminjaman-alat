<?php
include('koneksi.php');
$id = $_GET['id'];
$query = "SELECT * FROM alat_olahraga WHERE id = $id";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat Olahraga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Edit Alat Olahraga</h5>
                    </div>
                    <div class="card-body">
                        <form action="update_alat.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Nama Alat</label>
                                <input type="text" name="nama_alat" value="<?php echo $row['nama_alat']; ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok Total</label>
                                <input type="number" name="stok" value="<?php echo $row['stok']; ?>" class="form-control" min="1" required>
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