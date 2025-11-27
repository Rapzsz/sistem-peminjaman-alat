<?php
include('koneksi.php');

// Statistik
$total_alat = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM alat_olahraga"))['total'];
$total_peminjam = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM peminjam"))['total'];
$peminjaman_aktif = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM peminjaman WHERE status='Aktif'"))['total'];
$peminjaman_selesai = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM peminjaman WHERE status='Selesai'"))['total'];

// Riwayat peminjaman
$riwayat = mysqli_query($connection, 
    "SELECT p.*, a.nama_alat, pm.nama_peminjam, pm.kelas 
     FROM peminjaman p 
     JOIN alat_olahraga a ON p.alat_id = a.id 
     JOIN peminjam pm ON p.peminjam_id = pm.id 
     ORDER BY p.tanggal_pinjam DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laporan Peminjaman</h5>
                <button onclick="window.print()" class="btn btn-light btn-sm no-print">Print Laporan</button>
            </div>
            <div class="card-body">
                <!-- Header buat print -->
                <div class="text-center mb-4 d-none d-print-block">
                    <h3>SMK AK Nusa Bangsa</h3>
                    <h4>Laporan Peminjaman Alat Olahraga</h4>
                    <p>Periode: <?php echo date('d F Y'); ?></p>
                    <hr>
                </div>

                <!-- tabel statistik -->
              <div class="row mb-4">
                   <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <h4><?php echo $total_alat; ?></h4>
                                <p>Total Alat</p>
                            </div>
                        </div>
               </div>
               <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <h4><?php echo $total_peminjam; ?></h4>
                                <p>Total Peminjam</p>
                            </div>
                        </div>
               </div>
             <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <h4><?php echo $peminjaman_aktif; ?></h4>
                                <p>Sedang Dipinjam</p>
                            </div>
                        </div>
             </div>
              <div class="col-md-3">
                        <div class="card text-white bg-secondary">
                            <div class="card-body text-center">
                                <h4><?php echo $peminjaman_selesai; ?></h4>
                                <p>Selesai</p>
                            </div>
                 </div>
            </div>
          </div>

                <!-- Riwayat -->
                <h5>Riwayat Semua Peminjaman</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Alat</th>
                                <th>Peminjam</th>
                                <th>Kelas</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Durasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_array($riwayat)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nama_alat']; ?></td>
                                <td><?php echo $row['nama_peminjam']; ?></td>
                                <td><?php echo $row['kelas']; ?></td>
                                <td><?php echo $row['tanggal_pinjam']; ?></td>
                                <td><?php echo $row['tanggal_kembali'] ?: '-'; ?></td>
                                <td><?php echo $row['durasi'] ? $row['durasi'] . ' hari' : '-'; ?></td>
                                <td>
                                    <?php if($row['status'] == 'Aktif'): ?>
                                        <span class="badge bg-warning">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="no-print">
                    <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function printLaporan() {
        window.print();
    }
    </script>
</body>
</html>