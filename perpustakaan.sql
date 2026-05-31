-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Bulan Mei 2026 pada 06.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'tersedia',
  `stok` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id`, `judul`, `penulis`, `penerbit`, `tahun`, `status`, `stok`, `created_at`) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'tersedia', 5, '2026-05-28 14:56:06'),
(2, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, 'tersedia', 3, '2026-05-28 14:56:06'),
(3, 'Filosofi Teras', 'Henry Manampiring', 'Kompas', 2018, 'tersedia', 6, '2026-05-28 14:56:06'),
(8, 'superhero', 'bintang', NULL, 2022, 'tersedia', 8, '2026-05-31 02:02:49'),
(10, 'peperangan', 'eki', NULL, 2021, 'tersedia', 9, '2026-05-31 02:17:22'),
(11, 'mobil', 'akmal', NULL, 2012, 'tersedia', 0, '2026-05-31 02:31:40'),
(12, 'alam semesta', 'eki', NULL, 2024, 'tersedia', 2, '2026-05-31 02:58:35'),
(13, 'otomotif', 'fitra eri', NULL, 2018, 'tersedia', 7, '2026-05-31 02:59:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` enum('dipinjam','dikembalikan','terlambat') NOT NULL DEFAULT 'dipinjam',
  `tanggal_dikembalikan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user_id`, `buku_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `tanggal_dikembalikan`) VALUES
(1, 4, 3, '2026-05-28', '2026-06-04', 'dikembalikan', '2026-05-29'),
(3, 4, 3, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(4, 4, 3, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(5, 8, 3, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(6, 4, 3, '2026-05-29', '2026-06-05', 'dikembalikan', NULL),
(7, 4, 2, '2026-05-29', '2026-06-01', 'dikembalikan', NULL),
(8, 4, 3, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(9, 4, 2, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(10, 4, 3, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(11, 4, 3, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(12, 4, 3, '2026-05-29', '2026-06-05', 'dikembalikan', '2026-05-29'),
(13, 4, 2, '0000-00-00', '2026-05-30', 'dikembalikan', NULL),
(14, 4, 2, '0000-00-00', '2026-05-30', 'dikembalikan', NULL),
(15, 4, 2, '0000-00-00', '2026-05-31', 'dikembalikan', NULL),
(16, 8, 3, '0000-00-00', '2026-05-31', 'dikembalikan', NULL),
(18, 8, 3, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(19, 4, 8, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(20, 8, 8, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(21, 8, 10, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(22, 4, 10, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(23, 4, 8, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(24, 8, 10, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(25, 8, 8, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(26, 8, 10, '2026-05-31', '2026-05-31', 'dikembalikan', NULL),
(27, 8, 8, '2026-05-31', '2026-05-31', 'dikembalikan', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `level` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(3, 'bintang', 'coba@gmail.com', '$2y$10$/WmVSfb4CLS0fT80eul/CuhqeQUoQUGvfydPHO3OJeIS8LXtdG8vy', '', '2026-05-28 17:18:45'),
(4, 'alfin', 'alfin@gmail.com', '$2y$10$5gDV3aN0Kue9VPecgeUczO8amE40VU33NEtrMhOWIJyl2sbwJCg.a', 'user', '2026-05-28 17:49:10'),
(7, 'Admin', 'admin@mail.com', '$2y$10$xnt1vNtkSB.3z1QgEWyV1.Jki/eygzCrgRq3/w//vJAQ0/Go1WB3q', 'admin', '2026-05-28 18:16:51'),
(8, 'ziaulhaq', 'ziakerasakti@gmail.com', '$2y$10$DBfen0Gs4jU28O5.QDNqP.j4HnNl7p94Q5.geX38cl/NQDI0xts8e', 'user', '2026-05-29 15:30:00'),
(11, 'morgan', 'morgran@gmail.com', '$2y$10$qZl/LqnapLuZn7SXX9CqRujuVwI66UUze1/tndIEB7Gbq7F/Nn5T2', 'user', '2026-05-29 20:19:12');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_buku` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
