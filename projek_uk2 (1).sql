-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 07, 2026 at 01:15 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projek_uk2`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int NOT NULL,
  `nama_jenis` varchar(100) NOT NULL,
  `kode_jenis` varchar(50) NOT NULL,
  `keterangan` text,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `nama_jenis`, `kode_jenis`, `keterangan`, `image`) VALUES
(1, 'Buku cerita ', 'JNS001', 'buku cerita anak ayam', '1775443984.png');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id_level` int NOT NULL,
  `nama_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id_level`, `nama_level`) VALUES
(3, 'operator'),
(5, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date NOT NULL,
  `status_peminjaman` enum('Dipinjam','Dikembalikan') DEFAULT 'Dipinjam',
  `id_pegawai` int NOT NULL,
  `kode_jenis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `tanggal_pinjam`, `tanggal_kembali`, `status_peminjaman`, `id_pegawai`, `kode_jenis`) VALUES
(18, '2026-04-06', '2026-04-06', 'Dikembalikan', 14, 'JNS001'),
(19, '2026-04-06', '2026-04-08', 'Dipinjam', 14, 'JNS001');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int NOT NULL,
  `id_peminjaman` int DEFAULT NULL,
  `tanggal_kembali_real` date DEFAULT NULL,
  `kondisi_barang` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL,
  `denda` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_petugas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `id_level` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `id_level`) VALUES
(2, 'angga', '$2y$10$KXMIxPyl1UU1DxP.qA5vruysNRdoZYijNjg26iO9OFYe3gjhqOiQS', 'angga', 3),
(4, 'alip', '$2y$10$ujEWMKwVAJM6wT6gWDnnlOCd1zU5Wf4YZiYfPkhjXI0RETuRc1kq6', 'Alifi', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_pegawai` int NOT NULL,
  `nama_pegawai` varchar(100) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_pegawai`, `nama_pegawai`, `nip`, `alamat`) VALUES
(14, 'alip', 'PGW001', 'keweden Trirenggo Bantul'),
(16, 'moko45', 'PGW003', 'sanden'),
(17, 'pak bk', 'PGW004', 'sanden');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`),
  ADD UNIQUE KEY `kode_jenis` (`kode_jenis`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `kode_jenis` (`kode_jenis`),
  ADD KEY `tanggal_pinjam` (`tanggal_pinjam`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_level` (`id_level`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id_jenis` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id_level` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_pegawai` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `users` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`kode_jenis`) REFERENCES `jenis` (`kode_jenis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengembalian_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `petugas`
--
ALTER TABLE `petugas`
  ADD CONSTRAINT `petugas_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
