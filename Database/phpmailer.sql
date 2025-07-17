-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 04:55 PM
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
-- Database: `phpmailer`
--

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nohp` varchar(13) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verifikasi_code` varchar(255) NOT NULL,
  `is_verif` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `nama`, `email`, `nohp`, `password`, `verifikasi_code`, `is_verif`) VALUES
(14, '0', 'ff@haha.com', '2147483647', 'Dasadaesf', 'de884203a2dd8f963b547d1903d3642c', 0),
(15, '0', 'melco05lauwento.1@gmail.com', '2147483647', 'Satria12345', 'be3416d0265259c61367823649f12bda', 1),
(16, '0', 'melco05lauwento.1@gmail.com', '0', 'Satria12345', '6f9867c0983216a5330da347567051ff', 0),
(17, '0', 'melco05lauwento.1@gmail.com', '0', 'Satria12345', 'dda3db42f4adc3e08bf42dc9b2428b1e', 0),
(18, '0', 'sdfsef@gmail.com', '0', 'sadaewdaws', 'b97977ef8736baf8767593569dd5f396', 0),
(19, '0', 'fslevns@gmail.com', '0', 'J3ro4nsap1!', 'faf525d92967f07b0365d20514174335', 0),
(20, '0', '2vnsjkn@gmail.com', '0', 'Satria12345', '06a6e85715f0da4188a2db3900f22726', 0),
(21, 'Jajangg suenja', 'Dasar@gmail.com', '0', 'Satria12345', '2ba1f90e82487ae535673c9d5df65a98', 0),
(22, 'Sekali lagi', 'lagi@gmail.com', '0', 'Satria12345', '2552bcbed54dc24620a1cecdd24590dd', 0),
(23, 'NO hp benerin', 'hp@gmail.com', '08135153546', '$2y$10$IlI.MfJt9/Tj7ct.oa2QleBn6m7qbsFaOIv1deQ01eFt6/QRoiR2S', '07c642635ecbf5b8127da62c50e39d9d', 0);

-- --------------------------------------------------------

--
-- Table structure for table `harga`
--

CREATE TABLE `harga` (
  `id` int(11) NOT NULL,
  `layanan` enum('Cuci Biasa','Cuci Express') NOT NULL,
  `harga_per_kg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `harga`
--

INSERT INTO `harga` (`id`, `layanan`, `harga_per_kg`) VALUES
(1, 'Cuci Biasa', 7000),
(2, 'Cuci Express', 35000);

-- --------------------------------------------------------

--
-- Table structure for table `harga_item`
--

CREATE TABLE `harga_item` (
  `id` int(11) NOT NULL,
  `item` enum('Bed Cover','Sprei','Selimut','Karpet') NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `harga_item`
--

INSERT INTO `harga_item` (`id`, `item`, `harga`) VALUES
(1, 'Bed Cover', 30000),
(2, 'Sprei', 8000),
(3, 'Selimut', 8000),
(4, 'Karpet', 45000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(7) NOT NULL,
  `layanan` enum('Cuci Biasa','Cuci Express') NOT NULL,
  `berat` int(11) NOT NULL,
  `bed_cover` int(11) DEFAULT 0,
  `sprei` int(11) DEFAULT 0,
  `selimut` int(11) DEFAULT 0,
  `karpet` int(11) DEFAULT 0,
  `total_harga` int(11) NOT NULL,
  `tanggal` date NOT NULL DEFAULT curdate(),
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `layanan`, `berat`, `bed_cover`, `sprei`, `selimut`, `karpet`, `total_harga`, `tanggal`, `nama`, `no_hp`) VALUES
(23, 'Cuci Express', 1, 1, 1, 1, 1, 126000, '2025-06-23', '', ''),
(24, 'Cuci Express', 1, 1, 1, 1, 1, 126000, '2025-06-23', '', ''),
(25, 'Cuci Express', 1, 1, 1, 1, 1, 126000, '2025-06-23', '', ''),
(26, 'Cuci Express', 1, 1, 1, 3, 3, 232000, '2025-06-23', '', ''),
(27, 'Cuci Biasa', 1, 1, 1, 0, 4, 225000, '2025-06-23', '', ''),
(1489676, 'Cuci Biasa', 1, 1, 1, 1, 5, 278000, '2025-06-23', '', ''),
(1641790, 'Cuci Express', 1, 1, 0, 0, 0, 65000, '2025-06-23', 'barokah meubel', '08123568645'),
(1692289, 'Cuci Biasa', 0, 1, 0, 0, 4, 210000, '2025-06-23', 'Silvia anggraini', '08131546846'),
(2459710, 'Cuci Express', 1, 1, 2, 2, 1, 142000, '2025-06-26', 'Nanda', '05415321651'),
(3526581, 'Cuci Biasa', 1, 1, 1, 2, 1, 106700, '2025-06-23', 'barokah', '1241258456'),
(3577592, 'Cuci Biasa', 1, 1, 0, 1, 0, 45000, '2025-06-23', '', ''),
(5477222, 'Cuci Biasa', 0, 1, 0, 0, 0, 30000, '2025-06-23', 'sahas', '08123568645'),
(5477271, 'Cuci Express', 1, 1, 5, 3, 3, 264000, '2025-06-23', '', ''),
(7060503, 'Cuci Express', 1, 1, 4, 4, 1, 174000, '2025-06-23', 'Nadhila', '082153465'),
(7391923, 'Cuci Biasa', 0, 1, 1, 0, 0, 38000, '2025-06-23', '', ''),
(9592267, 'Cuci Express', 2, 5, 3, 2, 3, 395000, '2025-06-23', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `harga_item`
--
ALTER TABLE `harga_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `harga_item`
--
ALTER TABLE `harga_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
