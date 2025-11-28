<?php
include('koneksi.php');

// Fungsi hitung durasi
function hitungDurasi($tanggal_pinjam, $tanggal_kembali) {
    if (!$tanggal_pinjam || !$tanggal_kembali) return 0;
    
    $start = new DateTime($tanggal_pinjam);
    $end = new DateTime($tanggal_kembali);
    
    // Validasi: tanggal kembali tidak boleh sebelum tanggal pinjam
    if ($end < $start) {
        return -1; 
    }
    
    $diff = $start->diff($end);
    return $diff->days;
}

// Proses peminjaman
if ($_POST && isset($_POST['pinjam'])) {
    $alat_id = $_POST['alat_id'];
    $peminjam_id = $_POST['peminjam_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    
    // Get data alat dan peminjam
    $alat_data = mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM alat_olahraga WHERE id='$alat_id'"));
    $peminjam_data = mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM peminjam WHERE id='$peminjam_id'"));
    
    $tersedia = $alat_data['stok'] - $alat_data['dipinjam'];
    
    if ($tersedia > 0) {
        // Update jumlah dipinjam
        $new_dipinjam = $alat_data['dipinjam'] + 1;
        $query1 = "UPDATE alat_olahraga SET dipinjam='$new_dipinjam' WHERE id='$alat_id'";
        
        // Simpan data peminjaman
        $query2 = "INSERT INTO peminjaman (alat_id, peminjam_id, nama_peminjam, kelas_peminjam, tanggal_pinjam) 
                  VALUES ('$alat_id', '$peminjam_id', '{$peminjam_data['nama_peminjam']}', '{$peminjam_data['kelas']}', '$tanggal_pinjam')";
        
        if (mysqli_query($connection, $query1) && mysqli_query($connection, $query2)) {
            $success = "Peminjaman berhasil!";
        } else {
            $error = "Gagal meminjam alat!";
        }
    } else {
        $error = "Stok alat tidak tersedia!";
    }
}

// Proses pengembalian
if ($_POST && isset($_POST['kembali'])) {
    $peminjaman_id = $_POST['peminjaman_id'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    
    // Get data peminjaman
    $query_peminjaman = "SELECT * FROM peminjaman WHERE id='$peminjaman_id'";
    $result = mysqli_query($connection, $query_peminjaman);
    $peminjaman = mysqli_fetch_array($result);
    $alat_id = $peminjaman['alat_id'];
    
    // Hitung durasi dengan validasi
    $durasi = hitungDurasi($peminjaman['tanggal_pinjam'], $tanggal_kembali);
    
    if ($durasi == -1) {
        $error = "Tanggal kembali tidak boleh sebelum tanggal pinjam!";
    } else {
        // Update jumlah dipinjam (dikurangi 1)
        $alat_data = mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM alat_olahraga WHERE id='$alat_id'"));
        $new_dipinjam = $alat_data['dipinjam'] - 1;
        if ($new_dipinjam < 0) $new_dipinjam = 0;
        
        $query1 = "UPDATE alat_olahraga SET dipinjam='$new_dipinjam' WHERE id='$alat_id'";
        $query2 = "UPDATE peminjaman SET tanggal_kembali='$tanggal_kembali', durasi='$durasi', status='Selesai' WHERE id='$peminjaman_id'";
        
        if (mysqli_query($connection, $query1) && mysqli_query($connection, $query2)) {
            $success = "Pengembalian berhasil! Durasi: $durasi hari";
        } else {
            $error = "Gagal mengembalikan alat!";
        }
    }
}

// Query data
$alat_tersedia = mysqli_query($connection, 
    "SELECT * FROM alat_olahraga 
     WHERE (stok - dipinjam) > 0 
     ORDER BY nama_alat"
);

$peminjam = mysqli_query($connection, "SELECT * FROM peminjam ORDER BY nama_peminjam");

$peminjaman_aktif = mysqli_query($connection, 
    "SELECT p.*, a.nama_alat, a.stok, a.dipinjam, pm.nama_peminjam, pm.kelas 
     FROM peminjaman p 
     JOIN alat_olahraga a ON p.alat_id = a.id 
     JOIN peminjam pm ON p.peminjam_id = pm.id 
     WHERE p.status = 'Aktif' 
     ORDER BY p.tanggal_pinjam DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Transaksi Peminjaman</h5>
            </div>
            <div class="card-body">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Form Peminjaman -->
                <h5>Peminjaman Baru</h5>
                <form method="POST" class="mb-4 p-3 border rounded">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Pilih Alat</label>
                            <select name="alat_id" class="form-select" required>
                                <option value="">Pilih Alat</option>
                                <?php while($alat = mysqli_fetch_array($alat_tersedia)): 
                                    $tersedia = $alat['stok'] - $alat['dipinjam'];
                                ?>
                                    <option value="<?php echo $alat['id']; ?>">
                                        <?php echo $alat['nama_alat'] . " (Tersedia: $tersedia)"; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pilih Peminjam</label>
                            <select name="peminjam_id" class="form-select" required>
                                <option value="">Pilih Peminjam</option>
                                <?php while($p = mysqli_fetch_array($peminjam)): ?>
                                    <option value="<?php echo $p['id']; ?>">
                                        <?php echo $p['nama_peminjam'] . ' - ' . $p['kelas']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" value="<?php echo date('Y-m-d'); ?>" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" name="pinjam" class="btn btn-success w-100">Pinjam</button>
                        </div>
                    </div>
                </form>

                <!-- Daftar Alat dengan Info Peminjaman -->
                <h5>Status Alat Olahraga</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th>Stok Total</th>
                                <th>Dipinjam</th>
                                <th>Tersedia</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($alat_tersedia, 0);
                            while($alat = mysqli_fetch_array($alat_tersedia)): 
                                $tersedia = $alat['stok'] - $alat['dipinjam'];
                            ?>
                            <tr>
                                <td><?php echo $alat['nama_alat']; ?></td>
                                <td><?php echo $alat['stok']; ?></td>
                                <td><?php echo $alat['dipinjam']; ?></td>
                                <td><?php echo $tersedia; ?></td>
                                <td>
                                    <?php if($tersedia > 0): ?>
                                        <span class="badge bg-success">Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Kosong</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Daftar Peminjaman Aktif -->
                <h5>Peminjaman Aktif</h5>
                <?php if (mysqli_num_rows($peminjaman_aktif) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Alat</th>
                                    <th>Peminjam</th>
                                    <th>Kelas</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; 
                                mysqli_data_seek($peminjaman_aktif, 0);
                                while($row = mysqli_fetch_array($peminjaman_aktif)): 
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['nama_alat']; ?></td>
                                    <td><?php echo $row['nama_peminjam']; ?></td>
                                    <td><?php echo $row['kelas']; ?></td>
                                    <td><?php echo $row['tanggal_pinjam']; ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="peminjaman_id" value="<?php echo $row['id']; ?>">
                                            <div class="input-group">
                                                <input type="date" name="tanggal_kembali" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" required>
                                                <button type="submit" name="kembali" class="btn btn-success btn-sm">Kembalikan</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Gaada peminjam aktif.</p>
                <?php endif; ?>

                <a href="index.php" class="btn btn-secondary mt-3">Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Validasi JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            if (form.querySelector('input[name="tanggal_kembali"]')) {
                form.addEventListener('submit', function(e) {
                    const tanggalKembali = form.querySelector('input[name="tanggal_kembali"]').value;
                    const today = new Date().toISOString().split('T')[0];
                    
                    if (tanggalKembali < today) {
                        alert('Tanggal kembali tidak boleh di masa lalu!');
                        e.preventDefault();
                    }
                });
            }
        });
    });
    </script>
</body>
</html>