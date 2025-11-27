<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Alat Olahraga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <header class="text-center mb-4">
            <h1>Sistem Peminjaman Alat Olahraga</h1>
            <p class="lead">SMK AK Nusa Bangsa</p>
        </header>

        <!-- Tombol Aksi -->
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <a href="tambah_alat.php" class="btn btn-success w-100">Tambah Alat</a>
            </div>
            <div class="col-md-3 mb-2">
                <a href="tambah_peminjam.php" class="btn btn-info w-100">Tambah Peminjam</a>
            </div>
            <div class="col-md-3 mb-2">
                <a href="transaksi.php" class="btn btn-warning w-100">Transaksi</a>
            </div>
            <div class="col-md-3 mb-2">
                <a href="laporan.php" class="btn btn-secondary w-100">Laporan</a>
            </div>
        </div>

        <!-- Form Search -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari alat olahraga atau nama peminjam..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                    <div class="col-md-2">
                        <a href="index.php" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <?php
        include('koneksi.php');

        // search buat alat olahraga
        $search_condition_alat = "";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = mysqli_real_escape_string($connection, $_GET['search']);
            $search_condition_alat = " WHERE nama_alat LIKE '%$search%'";
        }

        // search buat peminjam
        $search_condition_peminjam = "";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = mysqli_real_escape_string($connection, $_GET['search']);
            $search_condition_peminjam = " WHERE nama_peminjam LIKE '%$search%' OR kelas LIKE '%$search%'";
        }

        $query_alat = mysqli_query($connection, "SELECT * FROM alat_olahraga $search_condition_alat ORDER BY nama_alat");
        $query_peminjam = mysqli_query($connection, "SELECT * FROM peminjam $search_condition_peminjam ORDER BY nama_peminjam");
        ?>

        <!-- Data Alat Olahraga -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Alat Olahraga</h5>
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <span class="badge bg-info">Hasil pencarian: "<?php echo htmlspecialchars($_GET['search']); ?>"</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Alat</th>
                                <th>Stok Total</th>
                                <th>Dipinjam</th>
                                <th>Tersedia</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_array($query_alat)) {
                                $tersedia = $row['stok'] - $row['dipinjam'];
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['nama_alat']; ?></td>
                                    <td><?php echo $row['stok']; ?></td>
                                    <td><?php echo $row['dipinjam']; ?></td>
                                    <td><?php echo $tersedia; ?></td>
                                    <td>
                                        <?php if($tersedia > 0): ?>
                                            <span class="badge bg-success">Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Kosong</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_alat.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="hapus_alat.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Sudah yakin mau di hapus?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <?php if (mysqli_num_rows($query_alat) == 0): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Alat belum ada di nubas.</p>
                            <?php if (isset($_GET['search'])): ?>
                                <a href="index.php" class="btn btn-primary">Tampilkan Semua Data</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Data Peminjam -->
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Peminjam</h5>
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <span class="badge bg-info">Hasil pencarian: "<?php echo htmlspecialchars($_GET['search']); ?>"</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_array($query_peminjam)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['nama_peminjam']; ?></td>
                                    <td><?php echo $row['kelas']; ?></td>
                                    <td>
                                        <a href="edit_peminjam.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="hapus_peminjam.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin hapus?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <?php if (mysqli_num_rows($query_peminjam) == 0 && isset($_GET['search'])): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Tidak ada data peminjam yang ditemukan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>