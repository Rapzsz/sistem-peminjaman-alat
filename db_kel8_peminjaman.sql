-- =============================================
-- DATABASE: sistem_peminjaman_olahraga
-- AUTHOR: [Nama Kamu]
-- CREATED: 2024
-- =============================================

-- Buat database
CREATE DATABASE IF NOT EXISTS db_peminjaman;
USE db_peminjaman;

-- =============================================
-- TABEL: alat_olahraga
-- =============================================
CREATE TABLE alat_olahraga (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_alat VARCHAR(100) NOT NULL,
    stok INT DEFAULT 1,
    dipinjam INT DEFAULT 0
);

-- =============================================
-- TABEL: peminjam  
-- =============================================
CREATE TABLE peminjam (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_peminjam VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL
);

-- =============================================
-- TABEL: peminjaman
-- =============================================
CREATE TABLE peminjaman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    alat_id INT,
    peminjam_id INT,
    nama_peminjam VARCHAR(100),
    kelas_peminjam VARCHAR(20),
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE,
    durasi INT,
    status ENUM('Aktif', 'Selesai') DEFAULT 'Aktif'
);

-- =============================================
-- SAMPLE DATA: alat_olahraga
-- =============================================
INSERT INTO alat_olahraga (nama_alat, stok) VALUES 
('Bola Basket', 5),
('Bola Voli', 3),
('Bola Sepak', 4),
('Raket Badminton', 8),
('Matras Senam', 2),
('Tali Skipping', 10);

-- =============================================
-- SAMPLE DATA: peminjam
-- =============================================
INSERT INTO peminjam (nama_peminjam, kelas) VALUES 
('Ahmad Rizki', '9A'),
('Siti Nurhaliza', '9B'),
('Budi Santoso', '9C'),
('Dewi Lestari', '8A'),
('Rudi Hermawan', '8B');

-- =============================================
-- SAMPLE DATA: peminjaman (opsional)
-- =============================================
INSERT INTO peminjaman (alat_id, peminjam_id, nama_peminjam, kelas_peminjam, tanggal_pinjam, status) VALUES 
(1, 1, 'Ahmad Rizki', '9A', '2024-01-20', 'Aktif'),
(2, 2, 'Siti Nurhaliza', '9B', '2024-01-21', 'Aktif');

-- =============================================
-- QUERY CONTOH
-- =============================================

-- Cek alat yang tersedia
SELECT 
    nama_alat,
    stok,
    dipinjam,
    (stok - dipinjam) as tersedia,
    CASE 
        WHEN (stok - dipinjam) > 0 THEN 'Tersedia'
        ELSE 'Kosong'
    END as status
FROM alat_olahraga;

-- Cek peminjaman aktif
SELECT 
    p.tanggal_pinjam,
    a.nama_alat,
    pm.nama_peminjam,
    pm.kelas
FROM peminjaman p
JOIN alat_olahraga a ON p.alat_id = a.id
JOIN peminjam pm ON p.peminjam_id = pm.id
WHERE p.status = 'Aktif';