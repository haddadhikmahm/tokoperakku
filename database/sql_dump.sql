-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2026 at 10:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sitokoperak`
--

-- --------------------------------------------------------

--
-- Table structure for table `foto_produk`
--

CREATE TABLE `foto_produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_foto_produk` varchar(255) NOT NULL,
  `produk_id` bigint(20) UNSIGNED NOT NULL,
  `file_foto_produk` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foto_produk`
--

INSERT INTO `foto_produk` (`id`, `kode_foto_produk`, `produk_id`, `file_foto_produk`, `created_at`, `updated_at`) VALUES
(10, 'FT0010', 5, 'foto_produk/wedding-ring1.jpg', '2025-05-01 22:31:43', '2025-05-01 22:31:43'),
(11, 'FT0011', 6, 'foto_produk/kalung1.jpg', '2025-05-01 22:48:29', '2025-05-01 22:48:29'),
(12, 'FT0012', 6, 'foto_produk/kalung2.jpg', '2025-05-01 22:52:44', '2025-05-01 22:52:44'),
(13, 'FT0001', 7, 'foto_produk/cincin1.jpg', '2025-05-01 23:14:04', '2025-05-01 23:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_usaha`
--

CREATE TABLE `jenis_usaha` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_jenis_usaha` varchar(255) NOT NULL,
  `nama_jenis_usaha` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_usaha`
--

INSERT INTO `jenis_usaha` (`id`, `kode_jenis_usaha`, `nama_jenis_usaha`, `created_at`, `updated_at`) VALUES
(1, 'JNS001', 'Logam', '2025-04-30 09:56:37', '2025-04-30 09:56:37'),
(2, 'JNS002', 'Emas', '2025-04-30 09:56:46', '2025-04-30 09:56:46'),
(3, 'JNS003', 'Perak', '2025-04-30 09:56:58', '2025-04-30 09:56:58'),
(4, 'JNS004', 'Tembaga', '2025-04-30 09:57:11', '2025-04-30 09:57:11'),
(5, 'JNS005', 'Kuningan', '2025-04-30 09:57:21', '2025-04-30 09:57:21');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_kategori_produk` varchar(255) NOT NULL,
  `nama_kategori_produk` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_produk`
--

INSERT INTO `kategori_produk` (`id`, `kode_kategori_produk`, `nama_kategori_produk`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'KTG001', 'Tatah', 'tatah', '2025-04-30 10:01:09', '2025-04-30 10:01:09'),
(2, 'KTG002', 'Filigeri', 'filigeri', '2025-04-30 10:01:16', '2025-05-01 23:28:46'),
(3, 'KTG003', 'Wedding Ring', 'wedding-ring', '2025-04-30 10:01:23', '2025-04-30 10:01:23'),
(4, 'KTG004', 'CIncin Akik', 'cincin-akik', '2025-04-30 10:01:31', '2025-04-30 10:01:31'),
(5, 'KTG005', 'Aksesoris Manten', 'aksesoris-manten', '2025-04-30 10:01:40', '2025-04-30 10:01:40'),
(6, 'KTG006', 'Souvenir', 'souvenir', '2025-04-30 10:01:48', '2025-04-30 10:01:48');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_04_11_041024_create_sessions_tables', 1),
(2, '2025_04_17_150301_create_users_table', 1),
(3, '2025_04_17_151707_create_pengerajin_table', 1),
(4, '2025_04_17_151725_create_jenis_usaha_table', 1),
(5, '2025_04_17_151730_create_usaha_table', 1),
(6, '2025_04_17_151736_create_kategori_produk_table', 1),
(7, '2025_04_17_151750_create_produk_table', 1),
(8, '2025_04_17_151803_create_foto_produk_table', 1),
(9, '2025_04_17_151850_create_usaha_produk_table', 1),
(10, '2025_04_17_151851_create_usaha_jenis_table', 1),
(11, '2025_04_17_151852_create_usaha_pengerajin_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengerajin`
--

CREATE TABLE `pengerajin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_pengerajin` varchar(255) NOT NULL,
  `nama_pengerajin` varchar(255) DEFAULT NULL,
  `jk_pengerajin` enum('P','W') DEFAULT NULL,
  `usia_pengerajin` int(11) DEFAULT NULL,
  `telp_pengerajin` varchar(255) DEFAULT NULL,
  `email_pengerajin` varchar(255) DEFAULT NULL,
  `alamat_pengerajin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengerajin`
--

INSERT INTO `pengerajin` (`id`, `kode_pengerajin`, `nama_pengerajin`, `jk_pengerajin`, `usia_pengerajin`, `telp_pengerajin`, `email_pengerajin`, `alamat_pengerajin`, `created_at`, `updated_at`) VALUES
(1, 'PRJ001', 'Bambang Mursanyoto', 'P', 20, '0882319319391', 'bambang@gmail.com', 'Basen KG III / 161, RT 10 RW 04', '2025-04-30 09:52:20', '2025-04-30 09:52:20'),
(2, 'PRJ002', 'Ari Jatmiko', 'P', 30, '08343141431', 'ari@gmail.com', 'Basen RT 10 RW 04', '2025-04-30 09:53:47', '2025-04-30 09:53:47'),
(3, 'PRJ004', 'Sagiyo', 'P', 25, '087437747114124', 'sagiyo@gmail.com', 'Basen RT 12 RW 04', '2025-04-30 09:55:22', '2025-04-30 09:55:22'),
(4, 'PRJ003', 'Ike Yulianti', 'W', 27, '0873183818731', 'ika@gmail.com', 'Basen KG III / 199, RT 12 RW 04', '2025-04-30 09:56:15', '2025-04-30 09:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_produk` varchar(255) NOT NULL,
  `kategori_produk_id` bigint(20) UNSIGNED NOT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kode_produk`, `kategori_produk_id`, `nama_produk`, `deskripsi`, `harga`, `stok`, `slug`, `created_at`, `updated_at`) VALUES
(5, 'PRD005', 3, 'Cincin kawin berlian', 'Terbuat dari berlian murni', 20000000, 2, 'cincin-kawin-berlian', '2025-05-01 22:31:10', '2025-05-01 22:31:10'),
(6, 'PRD006', 5, 'Kalung Emas', 'Terbuat dari 10 gram emas  24 karat', 25000000, 2, 'kalung-emas', '2025-05-01 22:48:01', '2025-05-01 22:48:01'),
(7, 'PRD001', 3, 'Cincin Berlian', 'Terbuat dari berlian murni', 90000000, 1, 'cincin-berlian', '2025-05-01 23:13:46', '2025-05-01 23:13:46');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ASoevfUHPbL606WiGT1rYPzbgW7YkThjkl2l7cBj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUw3QXVycDFGZUpOVmZhc1hEWVVNSGtYMWk4d3VBeWNsTGNkbnNmNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZXRhaWwtdXNhaGEvMT9mcm9tX3Byb2R1Y3Q9Y2luY2luLWthd2luLWJlcmxpYW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776239431),
('ozT2gNztfsKxok0A6C1oSDgu8Wh5tqxOAW4Xr63p', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiekxPN21DQzk4bGZ4QVRNaXNKaGtFSWVDdkloalR3N3cwMk52ZGtoQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWsva2F0ZWdvcmkvZmlsaWdlcmkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776236666),
('t3EfQWQr0CkXJiXFhd8woi5th255ZokKiLv8PGXP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiam9JTGF3WmFicnptd0VzMnRsNVpQT1VmTDFFMXVoQ0dGMndoUkhKcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWsva2F0ZWdvcmkvZmlsaWdlcmkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776237561);

-- --------------------------------------------------------

--
-- Table structure for table `usaha`
--

CREATE TABLE `usaha` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_usaha` varchar(255) NOT NULL,
  `nama_usaha` varchar(255) DEFAULT NULL,
  `telp_usaha` varchar(255) DEFAULT NULL,
  `email_usaha` varchar(255) DEFAULT NULL,
  `deskripsi_usaha` varchar(255) DEFAULT NULL,
  `foto_usaha` varchar(255) DEFAULT NULL,
  `link_gmap_usaha` varchar(255) DEFAULT NULL,
  `status_usaha` enum('aktif','nonaktif','tutup','pending','dibekukan') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usaha`
--

INSERT INTO `usaha` (`id`, `kode_usaha`, `nama_usaha`, `telp_usaha`, `email_usaha`, `deskripsi_usaha`, `foto_usaha`, `link_gmap_usaha`, `status_usaha`, `created_at`, `updated_at`) VALUES
(1, 'USH001', 'G - Silver', '0831921930108', 'jaya@gmail.com', 'Berdiri sejak 1990, bergerak di bidang kuningan', 'foto_usaha/kerajinan-perak-kota-ged.jpeg', 'https://maps.app.goo.gl/NY4TZwgqztCJvEQx5', 'aktif', '2025-04-30 09:58:36', '2025-04-30 09:58:36'),
(2, 'USH002', 'Jaya Sentosa', '08319219301090', 'sentosa@gmail.com', 'Berdiri sejak 2009, bergerak di bidang emas', 'foto_usaha/usaha3.jpg', 'https://maps.app.goo.gl/wpQK3CL3BTeijsb49', 'nonaktif', '2025-04-30 10:00:03', '2025-04-30 10:00:03'),
(3, 'USH003', 'Jaya Hoki Abis', '08319219301044', 'hoki@gmail.com', 'Berdiri sejak 2005, bergerak di bidang perak', 'foto_usaha/usaha2.jpeg', 'https://maps.app.goo.gl/wpQK3CL3BTeijsb49', 'aktif', '2025-04-30 10:00:57', '2025-04-30 10:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `usaha_jenis`
--

CREATE TABLE `usaha_jenis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usaha_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_usaha_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usaha_jenis`
--

INSERT INTO `usaha_jenis` (`id`, `usaha_id`, `jenis_usaha_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-04-30 10:10:34', '2025-04-30 10:10:34'),
(2, 1, 3, '2025-04-30 10:10:41', '2025-04-30 10:10:41'),
(3, 2, 5, '2025-04-30 10:10:49', '2025-04-30 10:10:49'),
(4, 1, 5, '2025-04-30 10:10:58', '2025-04-30 10:10:58'),
(5, 2, 2, '2025-04-30 10:11:08', '2025-04-30 10:11:08'),
(6, 3, 2, '2025-04-30 10:11:14', '2025-04-30 10:11:14'),
(7, 3, 3, '2025-04-30 10:11:24', '2025-04-30 10:11:24'),
(8, 3, 4, '2025-04-30 10:11:34', '2025-04-30 10:11:34'),
(9, 1, 5, '2025-05-01 05:22:54', '2025-05-01 05:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `usaha_pengerajin`
--

CREATE TABLE `usaha_pengerajin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usaha_id` bigint(20) UNSIGNED NOT NULL,
  `pengerajin_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usaha_pengerajin`
--

INSERT INTO `usaha_pengerajin` (`id`, `usaha_id`, `pengerajin_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-04-30 10:09:42', '2025-04-30 10:09:42'),
(2, 1, 2, '2025-04-30 10:09:49', '2025-04-30 10:09:49'),
(3, 1, 3, '2025-04-30 10:09:56', '2025-04-30 10:09:56'),
(4, 2, 1, '2025-04-30 10:10:02', '2025-04-30 10:10:02'),
(5, 2, 3, '2025-04-30 10:10:10', '2025-04-30 10:10:10'),
(6, 3, 4, '2025-04-30 10:10:19', '2025-04-30 10:10:19'),
(7, 3, 2, '2025-04-30 10:10:26', '2025-04-30 10:10:26');

-- --------------------------------------------------------

--
-- Table structure for table `usaha_produk`
--

CREATE TABLE `usaha_produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usaha_id` bigint(20) UNSIGNED NOT NULL,
  `produk_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usaha_produk`
--

INSERT INTO `usaha_produk` (`id`, `usaha_id`, `produk_id`, `created_at`, `updated_at`) VALUES
(10, 1, 5, '2025-10-07 22:58:42', '2025-10-07 22:58:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guest') NOT NULL DEFAULT 'guest',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123@example.com', '$2y$12$009.trbsYSPUPvfdbHso1O7S8pJQUeaoG9HdtdNuuquMtFikgXIR6', 'admin', '2025-04-30 09:20:48', '2025-04-30 09:20:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `foto_produk`
--
ALTER TABLE `foto_produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `foto_produk_kode_foto_produk_unique` (`kode_foto_produk`),
  ADD KEY `foto_produk_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `jenis_usaha`
--
ALTER TABLE `jenis_usaha`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis_usaha_kode_jenis_usaha_unique` (`kode_jenis_usaha`);

--
-- Indexes for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_produk_kode_kategori_produk_unique` (`kode_kategori_produk`),
  ADD UNIQUE KEY `kategori_produk_slug_unique` (`slug`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengerajin`
--
ALTER TABLE `pengerajin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengerajin_kode_pengerajin_unique` (`kode_pengerajin`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produk_kode_produk_unique` (`kode_produk`),
  ADD UNIQUE KEY `produk_slug_unique` (`slug`),
  ADD KEY `produk_kategori_produk_id_foreign` (`kategori_produk_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `usaha`
--
ALTER TABLE `usaha`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usaha_kode_usaha_unique` (`kode_usaha`);

--
-- Indexes for table `usaha_jenis`
--
ALTER TABLE `usaha_jenis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usaha_jenis_usaha_id_foreign` (`usaha_id`),
  ADD KEY `usaha_jenis_jenis_usaha_id_foreign` (`jenis_usaha_id`);

--
-- Indexes for table `usaha_pengerajin`
--
ALTER TABLE `usaha_pengerajin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usaha_pengerajin_usaha_id_foreign` (`usaha_id`),
  ADD KEY `usaha_pengerajin_pengerajin_id_foreign` (`pengerajin_id`);

--
-- Indexes for table `usaha_produk`
--
ALTER TABLE `usaha_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usaha_produk_usaha_id_foreign` (`usaha_id`),
  ADD KEY `usaha_produk_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `foto_produk`
--
ALTER TABLE `foto_produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jenis_usaha`
--
ALTER TABLE `jenis_usaha`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pengerajin`
--
ALTER TABLE `pengerajin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `usaha`
--
ALTER TABLE `usaha`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usaha_jenis`
--
ALTER TABLE `usaha_jenis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `usaha_pengerajin`
--
ALTER TABLE `usaha_pengerajin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `usaha_produk`
--
ALTER TABLE `usaha_produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foto_produk`
--
ALTER TABLE `foto_produk`
  ADD CONSTRAINT `foto_produk_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_kategori_produk_id_foreign` FOREIGN KEY (`kategori_produk_id`) REFERENCES `kategori_produk` (`id`);

--
-- Constraints for table `usaha_jenis`
--
ALTER TABLE `usaha_jenis`
  ADD CONSTRAINT `usaha_jenis_jenis_usaha_id_foreign` FOREIGN KEY (`jenis_usaha_id`) REFERENCES `jenis_usaha` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usaha_jenis_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usaha` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `usaha_pengerajin`
--
ALTER TABLE `usaha_pengerajin`
  ADD CONSTRAINT `usaha_pengerajin_pengerajin_id_foreign` FOREIGN KEY (`pengerajin_id`) REFERENCES `pengerajin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usaha_pengerajin_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usaha` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `usaha_produk`
--
ALTER TABLE `usaha_produk`
  ADD CONSTRAINT `usaha_produk_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usaha_produk_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usaha` (`id`) ON DELETE CASCADE;
COMMIT;
