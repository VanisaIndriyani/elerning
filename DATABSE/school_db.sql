-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 26, 2026 at 08:49 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('hadir','alpa','sakit','izin') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text,
  `subject` varchar(50) NOT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `title`, `description`, `subject`, `content`, `created_at`) VALUES
(1, 'Pengenalan Jaringan Komputer', 'Materi dasar tentang konsep jaringan, topologi, dan protokol komunikasi.', 'Informatika', 'https://www.dicoding.com/blog/apa-itu-jaringan-komputer/', '2026-02-26 07:51:53'),
(2, 'Algoritma dan Pemrograman Dasar', 'Belajar logika pemrograman menggunakan flowchart dan pseudocode.', 'Informatika', 'https://www.dicoding.com/blog/apa-itu-algoritma-pemrograman/', '2026-02-26 07:51:53'),
(3, 'Perakitan Komputer (Hardware)', 'Panduan lengkap merakit komputer dari awal hingga menyala.', 'KKA', 'https://www.youtube.com/watch?v=gT8_F8qZzXw', '2026-02-26 07:51:53'),
(4, 'Instalasi Sistem Operasi Windows 10', 'Langkah-langkah menginstal OS Windows menggunakan flashdisk bootable.', 'KKA', 'https://www.microsoft.com/id-id/software-download/windows10', '2026-02-26 07:51:53'),
(5, 'Konfigurasi Mikrotik Dasar', 'Setting dasar router Mikrotik untuk koneksi internet.', 'Dasar Kejuruan 2', 'https://citraweb.com/artikel_lihat.php?id=123', '2026-02-26 07:51:53'),
(6, 'Pengenalan Fiber Optik', 'Memahami teknologi serat optik dan cara penyambungan (splicing).', 'Dasar Kejuruan 2', 'https://id.wikipedia.org/wiki/Serat_optik', '2026-02-26 07:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `class` varchar(50) NOT NULL DEFAULT 'Umum',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `class`, `created_at`) VALUES
(2, 'Afemusanis Bin Oni\'s Tupa', 'X TJKT 1', '2026-02-26 08:35:39'),
(3, 'Albi Landjar', 'X TJKT 1', '2026-02-26 08:35:39'),
(4, 'ALLDY CRISTOVEL HERE', 'X TJKT 1', '2026-02-26 08:35:39'),
(5, 'Ariel Anselmus Therik', 'X TJKT 1', '2026-02-26 08:35:39'),
(6, 'Arina Yayu Manes', 'X TJKT 1', '2026-02-26 08:35:39'),
(7, 'Bryan Jession Alfiano Toma', 'X TJKT 1', '2026-02-26 08:35:39'),
(8, 'CHATERINE MANSUITA WIE LAWA', 'X TJKT 1', '2026-02-26 08:35:39'),
(9, 'COSTANDJI JOHANIS LATUMAHINA', 'X TJKT 1', '2026-02-26 08:35:39'),
(10, 'Cresentia Regina Petronela Fanggi', 'X TJKT 1', '2026-02-26 08:35:39'),
(11, 'DIDIMUS UNTUNG P. ROHI KANA', 'X TJKT 1', '2026-02-26 08:35:39'),
(12, 'DIJAN STAR KAPRICON ULY', 'X TJKT 1', '2026-02-26 08:35:39'),
(13, 'Emil Evalina Nara', 'X TJKT 1', '2026-02-26 08:35:39'),
(14, 'ERWIN TRENDY GABRIEL HANA', 'X TJKT 1', '2026-02-26 08:35:39'),
(15, 'FEBRYANO JACOB KOAMESAH', 'X TJKT 1', '2026-02-26 08:35:39'),
(16, 'GALANG ZLATAN MAHARDIKA', 'X TJKT 1', '2026-02-26 08:35:39'),
(17, 'Hesti Elsadai Malafu', 'X TJKT 1', '2026-02-26 08:35:39'),
(18, 'JANSUND LORDLY MIHA DIMU', 'X TJKT 1', '2026-02-26 08:35:39'),
(19, 'Jericho Anthonio Missa', 'X TJKT 1', '2026-02-26 08:35:39'),
(20, 'Jibrel Ronal Lopu', 'X TJKT 1', '2026-02-26 08:35:39'),
(21, 'Jilda Anastasia Ratu', 'X TJKT 1', '2026-02-26 08:35:39'),
(22, 'KAJOL CHARITAS CRISTI ANGELIKA TABUN', 'X TJKT 1', '2026-02-26 08:35:39'),
(23, 'KEYGEN ARYA DJAMI', 'X TJKT 1', '2026-02-26 08:35:39'),
(24, 'LIONEL GABRIEL ELIK', 'X TJKT 1', '2026-02-26 08:35:39'),
(25, 'Marco Alexandro Kofi', 'X TJKT 1', '2026-02-26 08:35:39'),
(26, 'Margareth Juliana Syalomi Polly', 'X TJKT 1', '2026-02-26 08:35:39'),
(27, 'Mariana Sagala', 'X TJKT 1', '2026-02-26 08:35:39'),
(28, 'Marvel De Aprilo Tael', 'X TJKT 1', '2026-02-26 08:35:39'),
(29, 'Novriyanti Sarlota Molum', 'X TJKT 1', '2026-02-26 08:35:39'),
(30, 'Qn Gratian Mauring', 'X TJKT 1', '2026-02-26 08:35:39'),
(31, 'REYNER FRANCIS EUGINIO LINGU', 'X TJKT 1', '2026-02-26 08:35:39'),
(32, 'RYCKO REAHAN MARKUS GA', 'X TJKT 1', '2026-02-26 08:35:39'),
(33, 'Sheren Donamarshinta Lay Doma', 'X TJKT 1', '2026-02-26 08:35:39'),
(34, 'Steven William Saduk', 'X TJKT 1', '2026-02-26 08:35:39'),
(35, 'Theresia R Wiku Epa', 'X TJKT 1', '2026-02-26 08:35:39'),
(36, 'VINZEN ROWLAND YEVERS TIMO', 'X TJKT 1', '2026-02-26 08:35:39'),
(37, 'YORLISBET MEDIAN PUTRI PULANGA', 'X TJKT 1', '2026-02-26 08:35:39'),
(38, 'ADYTIA MARVELINO RENDY LETMAI', 'X TJKT 2', '2026-02-26 08:38:58'),
(39, 'AIRYN VENI SOI', 'X TJKT 2', '2026-02-26 08:38:58'),
(40, 'ALBINUS GREGORIUS FORLAN PORWATA', 'X TJKT 2', '2026-02-26 08:38:58'),
(41, 'ALVIANO GIOVANNI FRANS', 'X TJKT 2', '2026-02-26 08:38:58'),
(42, 'ARTHA MYLANDRY P\'TROWL SUPENO', 'X TJKT 2', '2026-02-26 08:38:58'),
(43, 'AUREL JEWELIS SINLAELOE', 'X TJKT 2', '2026-02-26 08:38:58'),
(44, 'CHAIRIL ARIFIN MAY LOIS', 'X TJKT 2', '2026-02-26 08:38:58'),
(45, 'CHERYL CASYAFANY ERY KOBIS', 'X TJKT 2', '2026-02-26 08:38:58'),
(46, 'DANIEL TULU GA', 'X TJKT 2', '2026-02-26 08:38:58'),
(47, 'DAWYA GRACE RIANTI RAME HUKI', 'X TJKT 2', '2026-02-26 08:38:58'),
(48, 'DON RAFA AYDIN YUSUF KHALFANI DVG', 'X TJKT 2', '2026-02-26 08:38:58'),
(49, 'ENJELLIA FERONIKA PUTRI BANGNGU', 'X TJKT 2', '2026-02-26 08:38:58'),
(50, 'EVAN LIONEL MARCO', 'X TJKT 2', '2026-02-26 08:38:58'),
(51, 'FERNANDO RAFFAEL CHRISTIAN AMABI', 'X TJKT 2', '2026-02-26 08:38:58'),
(52, 'GEORGE MARCHELO FALLO', 'X TJKT 2', '2026-02-26 08:38:58'),
(53, 'HIERONIMUS KEVIN SNEIJDER BAUN', 'X TJKT 2', '2026-02-26 08:38:58'),
(54, 'JANUAR IMANUEL KADJA', 'X TJKT 2', '2026-02-26 08:38:58'),
(55, 'JERLEN SAMUEL ROMEN NALLE', 'X TJKT 2', '2026-02-26 08:38:58'),
(56, 'JESIKA APRILIANI BRIGITA MONE', 'X TJKT 2', '2026-02-26 08:38:58'),
(57, 'JORDY HERMAN SINE', 'X TJKT 2', '2026-02-26 08:38:58'),
(58, 'KATARINA TALAN', 'X TJKT 2', '2026-02-26 08:38:58'),
(59, 'KOKO THIO MARULI', 'X TJKT 2', '2026-02-26 08:38:58'),
(60, 'LORENSIUS MARVELL NARA WATU', 'X TJKT 2', '2026-02-26 08:38:58'),
(61, 'MARDAN KI\'IK SAFRIN', 'X TJKT 2', '2026-02-26 08:38:58'),
(62, 'MARIA AGUSTINA LIDIAWATI AMBROS', 'X TJKT 2', '2026-02-26 08:38:58'),
(63, 'MARVELLA NDUN', 'X TJKT 2', '2026-02-26 08:38:58'),
(64, 'MATHEOS SOLEMAN MOTONG OPENG', 'X TJKT 2', '2026-02-26 08:38:58'),
(65, 'MUHAMMAD ALAMSYAH AMIN', 'X TJKT 2', '2026-02-26 08:38:58'),
(66, 'NURALDA RIHLA RAHIM', 'X TJKT 2', '2026-02-26 08:38:58'),
(67, 'RADIN MARTEN WEO', 'X TJKT 2', '2026-02-26 08:38:58'),
(68, 'RISKY PRAWIRYOKUSWANTO MALLE', 'X TJKT 2', '2026-02-26 08:38:58'),
(69, 'SALFREDO BUNGSU WADU NELI', 'X TJKT 2', '2026-02-26 08:38:58'),
(70, 'SHERLITA MARGARET DETHAN', 'X TJKT 2', '2026-02-26 08:38:58'),
(71, 'THESALONIKA YESRIYANTI PUTRI LESIANGI', 'X TJKT 2', '2026-02-26 08:38:58'),
(72, 'TRISTAN MILANO LADO', 'X TJKT 2', '2026-02-26 08:38:58'),
(73, 'WANDRI YACOB ROHI', 'X TJKT 2', '2026-02-26 08:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`) VALUES
(1, 'javierdima', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Javiardi Dima');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`date`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
