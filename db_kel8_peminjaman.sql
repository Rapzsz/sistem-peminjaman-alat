-- =============================================
-- DATABASE: sistem_peminjaman_olahraga
-- CREATED: 2025-11-25
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
('Bola Futsal', 6),
('Bola volly', 9),
('Bola Basket', 4),
('Lempar Lembing', 10); 

-- =============================================
-- SAMPLE DATA: peminjam
-- =============================================

INSERT INTO peminjam (nama_peminjam, kelas) VALUES 
('Rafandra Pramudya Alfarise', '11 PPLG'),
('Moch David Anggara', '11 AK 1'),
('Zaky Daffa', '11 PPLG');

-- =============================================
-- SAMPLE DATA: peminjaman 
-- =============================================

INSERT INTO peminjaman (alat_id, peminjam_id, nama_peminjam, kelas_peminjam, tanggal_pinjam, status) VALUES 
(1, 1, 'Rafandra Pramudya Alafarisie', '11 PPLG', '2025-11-25', 'Aktif'),
(2, 2, 'Zaky Daffa', '11 PPLG', '2025-11-25', 'Aktif');

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