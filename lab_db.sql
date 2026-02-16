-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 07:48 AM
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
-- Database: `lab_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `abdul_kalam_lab`
--

CREATE TABLE `abdul_kalam_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `abdul_kalam_lab`
--

INSERT INTO `abdul_kalam_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(73, 'ABK01', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(74, 'ABK02', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(75, 'ABK03', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(76, 'ABK04', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(77, 'ABK05', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(78, 'ABK06', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(79, 'ABK07', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(80, 'ABK08', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(81, 'ABK09', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(82, 'ABK10', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(83, 'ABK11', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(84, 'ABK12', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(85, 'ABK13', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(86, 'ABK14', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(87, 'ABK15', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(88, 'ABK16', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(89, 'ABK17', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(90, 'ABK18', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(91, 'ABK19', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(92, 'ABK20', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(93, 'ABK21', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(94, 'ABK22', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(95, 'ABK23', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(96, 'ABK24', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(97, 'ABK25', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(98, 'ABK26', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(99, 'ABK27', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(100, 'ABK28', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(101, 'ABK29', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(102, 'ABK30', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(103, 'ABK31', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(104, 'ABK32', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(105, 'ABK33', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(106, 'ABK34', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(107, 'ABK35', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(108, 'ABK36', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(109, 'ABK37', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(110, 'ABK38', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(111, 'ABK39', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(112, 'ABK40', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(113, 'ABK41', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(114, 'ABK42', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(115, 'ABK43', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(116, 'ABK44', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(117, 'ABK45', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(118, 'ABK46', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(119, 'ABK47', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(120, 'ABK48', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(121, 'ABK49', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(122, 'ABK50', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(123, 'ABK51', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(124, 'ABK52', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(125, 'ABK53', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(126, 'ABK54', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(127, 'ABK55', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(128, 'ABK56', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(129, 'ABK57', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(130, 'ABK58', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(131, 'ABK59', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(132, 'ABK60', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(133, 'ABK61', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(134, 'ABK62', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(135, 'ABK63', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(136, 'ABK64', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(137, 'ABK65', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(138, 'ABK66', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(139, 'ABK67', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(140, 'ABK68', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(141, 'ABK69', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(142, 'ABK70', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(143, 'ABK71', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2'),
(144, 'ABK72', 'Windows 10', 'Assembled - i3 10th Gen , 16 GB Ram, 500 GB M.2');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_by` varchar(50) DEFAULT 'System',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `permissions` varchar(50) DEFAULT 'Full'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_by`, `created_at`, `permissions`) VALUES
(1, 'admin', 'admin', 'System', '2026-01-21 10:08:52', 'Full'),
(2, 'uday', 'uday', 'admin', '2026-01-21 10:18:42', 'Full'),
(5, 'bhanu', 'bhanu', 'admin', '2026-02-04 17:06:22', 'Partial'),
(6, 'syamprasad', 'syamprasad', 'admin', '2026-02-16 06:31:50', 'Partial'),
(7, 'pandurangarao', 'pandurangarao', 'admin', '2026-02-16 06:32:21', 'Partial'),
(8, 'krishnakishore', 'krishnakishore', 'admin', '2026-02-16 06:32:53', 'Partial');

-- --------------------------------------------------------

--
-- Table structure for table `agastya_lab`
--

CREATE TABLE `agastya_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agastya_lab`
--

INSERT INTO `agastya_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(217, 'AGL01', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(218, 'AGL02', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(219, 'AGL03', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(220, 'AGL04', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(221, 'AGL05', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(222, 'AGL06', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(223, 'AGL07', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(224, 'AGL08', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(225, 'AGL09', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(226, 'AGL10', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(227, 'AGL11', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(228, 'AGL12', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(229, 'AGL13', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(230, 'AGL14', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(231, 'AGL15', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(232, 'AGL16', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(233, 'AGL17', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(234, 'AGL18', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(235, 'AGL19', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(236, 'AGL20', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(237, 'AGL21', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(238, 'AGL22', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(239, 'AGL23', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(240, 'AGL24', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(241, 'AGL25', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(242, 'AGL26', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(243, 'AGL27', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(244, 'AGL28', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(245, 'AGL29', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(246, 'AGL30', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(247, 'AGL31', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(248, 'AGL32', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(249, 'AGL33', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(250, 'AGL34', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(251, 'AGL35', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(252, 'AGL36', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(253, 'AGL37', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(254, 'AGL38', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(255, 'AGL39', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(256, 'AGL40', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(257, 'AGL41', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(258, 'AGL42', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(259, 'AGL43', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(260, 'AGL44', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(261, 'AGL45', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(262, 'AGL46', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(263, 'AGL47', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(264, 'AGL48', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(265, 'AGL49', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(266, 'AGL50', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(267, 'AGL51', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(268, 'AGL52', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(269, 'AGL53', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(270, 'AGL54', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(271, 'AGL55', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(272, 'AGL56', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(273, 'AGL57', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(274, 'AGL58', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(275, 'AGL59', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(276, 'AGL60', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(277, 'AGL61', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(278, 'AGL62', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(279, 'AGL63', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(280, 'AGL64', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(281, 'AGL65', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(282, 'AGL66', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(283, 'AGL67', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(284, 'AGL68', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(285, 'AGL69', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(286, 'AGL70', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(287, 'AGL71', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2'),
(288, 'AGL72', 'Windows 11 / Ubutu 24', 'Assembled - i5 14th Gen , 32 GB Ram, 500 GB M.2');

-- --------------------------------------------------------

--
-- Table structure for table `aryabhatta_lab`
--

CREATE TABLE `aryabhatta_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aryabhatta_lab`
--

INSERT INTO `aryabhatta_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(268, 'ARY001', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(269, 'ARY002', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(270, 'ARY003', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(271, 'ARY004', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(272, 'ARY005', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(273, 'ARY006', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(274, 'ARY007', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(275, 'ARY008', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(276, 'ARY009', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(277, 'ARY010', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(278, 'ARY011', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(279, 'ARY012', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(280, 'ARY013', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(281, 'ARY014', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(282, 'ARY015', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(283, 'ARY016', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(284, 'ARY017', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(285, 'ARY018', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(286, 'ARY019', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(287, 'ARY020', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(288, 'ARY021', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(289, 'ARY022', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(290, 'ARY023', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(291, 'ARY024', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(292, 'ARY025', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(293, 'ARY026', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(294, 'ARY027', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(295, 'ARY028', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(296, 'ARY029', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(297, 'ARY030', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(298, 'ARY031', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(299, 'ARY032', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(300, 'ARY033', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(301, 'ARY034', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(302, 'ARY035', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(303, 'ARY036', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(304, 'ARY037', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(305, 'ARY038', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(306, 'ARY039', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(307, 'ARY040', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(308, 'ARY041', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(309, 'ARY042', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(310, 'ARY043', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(311, 'ARY044', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(312, 'ARY045', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(313, 'ARY046', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(314, 'ARY047', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(315, 'ARY048', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(316, 'ARY049', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(317, 'ARY050', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(318, 'ARY051', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(319, 'ARY052', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(320, 'ARY053', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(321, 'ARY054', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(322, 'ARY055', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(323, 'ARY056', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(324, 'ARY057', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(325, 'ARY058', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(326, 'ARY059', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(327, 'ARY060', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(328, 'ARY061', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(329, 'ARY062', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(330, 'ARY063', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(331, 'ARY064', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(332, 'ARY065', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(333, 'ARY066', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(334, 'ARY067', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(335, 'ARY068', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(336, 'ARY069', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(337, 'ARY070', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(338, 'ARY071', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(339, 'ARY072', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(340, 'ARY073', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(341, 'ARY074', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(342, 'ARY075', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(343, 'ARY076', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(344, 'ARY077', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(345, 'ARY078', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(346, 'ARY079', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(347, 'ARY080', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(348, 'ARY081', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(349, 'ARY082', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(350, 'ARY083', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(351, 'ARY084', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(352, 'ARY085', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(353, 'ARY086', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(354, 'ARY087', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(355, 'ARY088', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(356, 'ARY089', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(357, 'ARY090', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(358, 'ARY091', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(359, 'ARY092', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(360, 'ARY093', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(361, 'ARY094', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(362, 'ARY095', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(363, 'ARY096', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(364, 'ARY097', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(365, 'ARY098', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(366, 'ARY099', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(367, 'ARY100', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(368, 'ARY101', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(369, 'ARY102', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(370, 'ARY103', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(371, 'ARY104', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(372, 'ARY105', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(373, 'ARY106', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(374, 'ARY107', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(375, 'ARY108', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(376, 'ARY109', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(377, 'ARY110', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(378, 'ARY111', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(379, 'ARY112', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(380, 'ARY113', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(381, 'ARY114', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(382, 'ARY115', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(383, 'ARY116', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(384, 'ARY117', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(385, 'ARY118', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(386, 'ARY119', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(387, 'ARY120', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(388, 'ARY121', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(389, 'ARY122', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(390, 'ARY123', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(391, 'ARY124', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(392, 'ARY125', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(393, 'ARY126', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(394, 'ARY127', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(395, 'ARY128', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(396, 'ARY129', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(397, 'ARY130', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(398, 'ARY131', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(399, 'ARY132', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(400, 'ARY133', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA'),
(401, 'ARY134', 'Ubuntu 14.04', 'DELL Optiplux 790 - i3 2 & 3 Genration, 4 GB Ram, 500 GB HDD(Problum) SATA');

-- --------------------------------------------------------

--
-- Table structure for table `bhaskara_charya_lab`
--

CREATE TABLE `bhaskara_charya_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bhaskara_charya_lab`
--

INSERT INTO `bhaskara_charya_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(73, 'BSK01', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(74, 'BSK02', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(75, 'BSK03', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(76, 'BSK04', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(77, 'BSK05', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(78, 'BSK06', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(79, 'BSK07', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(80, 'BSK08', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(81, 'BSK09', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(82, 'BSK10', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(83, 'BSK11', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(84, 'BSK12', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(85, 'BSK13', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(86, 'BSK14', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(87, 'BSK15', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(88, 'BSK16', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(89, 'BSK17', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(90, 'BSK18', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(91, 'BSK19', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(92, 'BSK20', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(93, 'BSK21', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(94, 'BSK22', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(95, 'BSK23', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(96, 'BSK24', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(97, 'BSK25', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(98, 'BSK26', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(99, 'BSK27', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(100, 'BSK28', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(101, 'BSK29', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(102, 'BSK30', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(103, 'BSK31', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(104, 'BSK32', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(105, 'BSK33', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(106, 'BSK34', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(107, 'BSK35', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(108, 'BSK36', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(109, 'BSK37', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(110, 'BSK38', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(111, 'BSK39', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(112, 'BSK40', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(113, 'BSK41', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(114, 'BSK42', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(115, 'BSK43', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(116, 'BSK44', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(117, 'BSK45', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(118, 'BSK46', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(119, 'BSK47', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(120, 'BSK48', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(121, 'BSK49', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(122, 'BSK50', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(123, 'BSK51', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(124, 'BSK52', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(125, 'BSK53', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(126, 'BSK54', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(127, 'BSK55', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(128, 'BSK56', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(129, 'BSK57', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(130, 'BSK58', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(131, 'BSK59', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(132, 'BSK60', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(133, 'BSK61', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(134, 'BSK62', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(135, 'BSK63', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(136, 'BSK64', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(137, 'BSK65', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(138, 'BSK66', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(139, 'BSK67', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(140, 'BSK68', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(141, 'BSK69', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(142, 'BSK70', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(143, 'BSK71', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(144, 'BSK72', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2');

-- --------------------------------------------------------

--
-- Table structure for table `chanakya_lab`
--

CREATE TABLE `chanakya_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chanakya_lab`
--

INSERT INTO `chanakya_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(1, 'CKL01', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(2, 'CKL02', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(3, 'CKL03', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(4, 'CKL04', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(5, 'CKL05', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(6, 'CKL06', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(7, 'CKL07', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(8, 'CKL08', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(9, 'CKL09', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(10, 'CKL10', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(11, 'CKL11', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(12, 'CKL12', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(13, 'CKL13', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(14, 'CKL14', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(15, 'CKL15', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(16, 'CKL16', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(17, 'CKL17', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(18, 'CKL18', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(19, 'CKL19', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(20, 'CKL20', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(21, 'CKL21', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(22, 'CKL22', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(23, 'CKL23', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(24, 'CKL24', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(25, 'CKL25', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(26, 'CKL26', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(27, 'CKL27', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(28, 'CKL28', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(29, 'CKL29', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(30, 'CKL30', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(31, 'CKL31', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(32, 'CKL32', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(33, 'CKL33', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(34, 'CKL34', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(35, 'CKL35', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(36, 'CKL36', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(37, 'CKL37', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(38, 'CKL38', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(39, 'CKL39', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(40, 'CKL40', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(41, 'CKL41', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(42, 'CKL42', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(43, 'CKL43', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(44, 'CKL44', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(45, 'CKL45', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(46, 'CKL46', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(47, 'CKL47', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(48, 'CKL48', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(49, 'CKL49', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(50, 'CKL50', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(51, 'CKL51', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(52, 'CKL52', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(53, 'CKL53', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(54, 'CKL54', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(55, 'CKL55', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(56, 'CKL56', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(57, 'CKL57', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(58, 'CKL58', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(59, 'CKL59', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(60, 'CKL60', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(61, 'CKL61', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(62, 'CKL62', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(63, 'CKL63', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(64, 'CKL64', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(65, 'CKL65', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(66, 'CKL66', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(67, 'CKL67', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(68, 'CKL68', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(69, 'CKL69', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(70, 'CKL70', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(71, 'CKL71', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(72, 'CKL72', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `other_details` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `issues` varchar(255) DEFAULT NULL,
  `room_no` varchar(255) DEFAULT NULL,
  `issue_fixed_at` datetime DEFAULT NULL,
  `partially_completed_at` datetime DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lab_name` varchar(255) DEFAULT NULL,
  `system_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `complaint_modified_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `other_details`, `status`, `created_at`, `issues`, `room_no`, `issue_fixed_at`, `partially_completed_at`, `parent_id`, `lab_name`, `system_number`, `email`, `complaint_modified_by`) VALUES
(80, '', 'Pending', '2026-02-04 12:01:36', 'Mouse, Software', '204', NULL, NULL, 80, 'Aryabhatta Lab', 'ARY103', 'udaystark50@gmail.com', NULL),
(81, '', 'Pending', '2026-02-04 12:02:44', 'Software', '206', NULL, NULL, 81, 'Dr C R Rao Lab', 'CRR16', 'udaystark50@gmail.com', NULL),
(83, '', 'Pending', '2026-02-04 12:03:50', 'Mouse, OS', 'Computer Labs', NULL, NULL, 83, 'J R D Tata Lab', 'JRD18', 'lsjvhoasdus@gmail.com', NULL),
(84, '', 'Pending', '2026-02-04 12:04:16', 'Monitor', '204', NULL, NULL, 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(85, '', 'Partially Completed: Monitor Required', '2026-02-04 12:04:16', 'Monitor', '204', NULL, '2026-02-04 17:35:12', 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(86, '', 'Partially Completed: Fixing Monitor', '2026-02-04 12:04:16', 'Monitor', '204', NULL, '2026-02-04 17:35:49', 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(88, '', 'Completed', '2026-02-04 12:02:44', 'Software', '206', '2026-02-04 17:36:26', NULL, 81, 'Dr C R Rao Lab', 'CRR16', 'udaystark50@gmail.com', NULL),
(97, '', 'Completed', '2026-02-04 12:03:50', 'Mouse, OS', 'Computer Labs', '2026-02-08 10:25:17', NULL, 83, 'J R D Tata Lab', 'JRD18', 'lsjvhoasdus@gmail.com', NULL),
(98, '', 'Partially Completed: Mouse fixed', '2026-02-04 12:01:36', 'Mouse, Software', '204', NULL, '2026-02-10 10:10:14', 80, 'Aryabhatta Lab', 'ARY103', 'udaystark50@gmail.com', NULL),
(99, '', 'Completed', '2026-02-04 12:04:16', 'Monitor', '204', '2026-02-10 10:10:30', NULL, 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(110, 'asdkjfabsif', 'Pending', '2026-02-10 05:28:51', 'Monitor', '206', NULL, NULL, 110, 'Bhaskara Charya Lab', 'BCL68', 'udaystark50@gmail.com', NULL),
(111, '', 'Partially Completed: Software need license', '2026-02-04 12:01:36', 'Mouse, Software', '204', NULL, '2026-02-16 10:53:39', 80, 'Aryabhatta Lab', 'ARY103', 'udaystark50@gmail.com', 'admin'),
(112, 'asdkjfabsif', 'Partially Completed: Monitor needed', '2026-02-10 05:28:51', 'Monitor', '206', NULL, '2026-02-16 12:16:47', 110, 'Bhaskara Charya Lab', 'BCL68', 'udaystark50@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `complaints_log`
--

CREATE TABLE `complaints_log` (
  `id` int(11) NOT NULL,
  `other_details` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `issues` varchar(255) DEFAULT NULL,
  `room_no` varchar(255) DEFAULT NULL,
  `issue_fixed_at` datetime DEFAULT NULL,
  `partially_completed_at` datetime DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lab_name` varchar(255) DEFAULT NULL,
  `system_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `complaint_modified_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints_log`
--

INSERT INTO `complaints_log` (`id`, `other_details`, `status`, `created_at`, `issues`, `room_no`, `issue_fixed_at`, `partially_completed_at`, `parent_id`, `lab_name`, `system_number`, `email`, `complaint_modified_by`) VALUES
(36, '', 'Pending', '2026-02-04 12:01:36', 'Mouse, Software', '204', NULL, NULL, 80, 'Aryabhatta Lab', 'ARY103', 'udaystark50@gmail.com', NULL),
(37, '', 'Pending', '2026-02-04 12:02:44', 'Software', '206', NULL, NULL, 81, 'Dr C R Rao Lab', 'CRR16', 'udaystark50@gmail.com', NULL),
(39, '', 'Pending', '2026-02-04 12:03:50', 'Mouse, OS', 'Computer Labs', NULL, NULL, 83, 'J R D Tata Lab', 'JRD18', 'lsjvhoasdus@gmail.com', NULL),
(40, '', 'Pending', '2026-02-04 12:04:16', 'Monitor', '204', NULL, NULL, 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(41, '', 'Partially Completed: Monitor Required', '2026-02-04 12:04:16', 'Monitor', '204', NULL, '2026-02-04 17:35:12', 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(42, '', 'Partially Completed: Fixing Monitor', '2026-02-04 12:04:16', 'Monitor', '204', NULL, '2026-02-04 17:35:49', 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(44, '', 'Completed', '2026-02-04 12:02:44', 'Software', '206', '2026-02-04 17:36:26', NULL, 81, 'Dr C R Rao Lab', 'CRR16', 'udaystark50@gmail.com', NULL),
(53, '', 'Completed', '2026-02-04 12:03:50', 'Mouse, OS', 'Computer Labs', '2026-02-08 10:25:17', NULL, 83, 'J R D Tata Lab', 'JRD18', 'lsjvhoasdus@gmail.com', NULL),
(54, '', 'Partially Completed: Mouse fixed', '2026-02-04 12:01:36', 'Mouse, Software', '204', NULL, '2026-02-10 10:10:14', 80, 'Aryabhatta Lab', 'ARY103', 'udaystark50@gmail.com', NULL),
(55, '', 'Completed', '2026-02-04 12:04:16', 'Monitor', '204', '2026-02-10 10:10:30', NULL, 84, 'Narayana Murthi Lab', 'NML09', 'tonystark20201919@gmail.com', NULL),
(66, 'asdkjfabsif', 'Pending', '2026-02-10 05:28:51', 'Monitor', '206', NULL, NULL, 110, 'Bhaskara Charya Lab', 'BCL68', 'udaystark50@gmail.com', NULL),
(67, '', 'Partially Completed: Software need license', '2026-02-04 12:01:36', 'Mouse, Software', '204', NULL, '2026-02-16 10:53:39', 80, 'Aryabhatta Lab', 'ARY103', 'udaystark50@gmail.com', 'admin'),
(68, 'asdkjfabsif', 'Partially Completed: Monitor needed', '2026-02-10 05:28:51', 'Monitor', '206', NULL, '2026-02-16 12:16:47', 110, 'Bhaskara Charya Lab', 'BCL68', 'udaystark50@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `c_v_raman_lab`
--

CREATE TABLE `c_v_raman_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `c_v_raman_lab`
--

INSERT INTO `c_v_raman_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(91, 'CVR001', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(92, 'CVR002', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(93, 'CVR003', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(94, 'CVR004', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(95, 'CVR005', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(96, 'CVR006', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(97, 'CVR007', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(98, 'CVR008', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(99, 'CVR009', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(100, 'CVR010', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(101, 'CVR011', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(102, 'CVR012', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(103, 'CVR013', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(104, 'CVR014', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(105, 'CVR015', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(106, 'CVR016', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(107, 'CVR017', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(108, 'CVR018', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(109, 'CVR019', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(110, 'CVR020', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(111, 'CVR021', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(112, 'CVR022', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(113, 'CVR023', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(114, 'CVR024', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(115, 'CVR025', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(116, 'CVR026', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(117, 'CVR027', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(118, 'CVR028', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(119, 'CVR029', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(120, 'CVR030', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(121, 'CVR031', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(122, 'CVR032', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(123, 'CVR033', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(124, 'CVR034', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(125, 'CVR035', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(126, 'CVR036', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(127, 'CVR037', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(128, 'CVR038', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(129, 'CVR039', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(130, 'CVR040', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(131, 'CVR041', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(132, 'CVR042', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(133, 'CVR043', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(134, 'CVR044', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(135, 'CVR045', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(136, 'CVR046', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(137, 'CVR047', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(138, 'CVR048', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(139, 'CVR049', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(140, 'CVR050', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(141, 'CVR051', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(142, 'CVR052', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(143, 'CVR053', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(144, 'CVR054', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(145, 'CVR055', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(146, 'CVR056', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(147, 'CVR057', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(148, 'CVR058', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(149, 'CVR059', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(150, 'CVR060', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(151, 'CVR061', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(152, 'CVR062', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(153, 'CVR063', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(154, 'CVR064', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(155, 'CVR065', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(156, 'CVR066', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(157, 'CVR067', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(158, 'CVR068', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(159, 'CVR069', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(160, 'CVR070', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(161, 'CVR071', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(162, 'CVR072', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(163, 'CVR073', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(164, 'CVR074', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(165, 'CVR075', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(166, 'CVR076', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(167, 'CVR077', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(168, 'CVR078', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(169, 'CVR079', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(170, 'CVR080', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(171, 'CVR081', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(172, 'CVR082', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(173, 'CVR083', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(174, 'CVR084', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(175, 'CVR085', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(176, 'CVR086', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(177, 'CVR087', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(178, 'CVR088', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(179, 'CVR089', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA'),
(180, 'CVR090', 'Ubuntu 14.04', 'DELL Vastro - i3 & i5 2 & 3 Gen, 4 GB Ram, 500 GB HDD SATA');

-- --------------------------------------------------------

--
-- Table structure for table `dr_c_r_rao_lab`
--

CREATE TABLE `dr_c_r_rao_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dr_c_r_rao_lab`
--

INSERT INTO `dr_c_r_rao_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(1, 'CRR01', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(2, 'CRR02', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(3, 'CRR03', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(4, 'CRR04', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(5, 'CRR05', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(6, 'CRR06', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(7, 'CRR07', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(8, 'CRR08', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(9, 'CRR09', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(10, 'CRR10', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(11, 'CRR11', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(12, 'CRR12', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(13, 'CRR13', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(14, 'CRR14', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(15, 'CRR15', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(16, 'CRR16', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(17, 'CRR17', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(18, 'CRR18', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(19, 'CRR19', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(20, 'CRR20', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(21, 'CRR21', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(22, 'CRR22', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(23, 'CRR23', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(24, 'CRR24', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(25, 'CRR25', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(26, 'CRR26', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(27, 'CRR27', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(28, 'CRR28', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(29, 'CRR29', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(30, 'CRR30', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(31, 'CRR31', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(32, 'CRR32', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(33, 'CRR33', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(34, 'CRR34', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(35, 'CRR35', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(36, 'CRR36', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(37, 'CRR37', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(38, 'CRR38', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(39, 'CRR39', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(40, 'CRR40', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(41, 'CRR41', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(42, 'CRR42', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(43, 'CRR43', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(44, 'CRR44', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(45, 'CRR45', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(46, 'CRR46', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(47, 'CRR47', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(48, 'CRR48', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(49, 'CRR49', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(50, 'CRR50', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(51, 'CRR51', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(52, 'CRR52', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(53, 'CRR53', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(54, 'CRR54', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(55, 'CRR55', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(56, 'CRR56', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(57, 'CRR57', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(58, 'CRR58', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(59, 'CRR59', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(60, 'CRR60', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(61, 'CRR61', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(62, 'CRR62', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(63, 'CRR63', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(64, 'CRR64', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(65, 'CRR65', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(66, 'CRR66', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(67, 'CRR67', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(68, 'CRR68', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(69, 'CRR69', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(70, 'CRR70', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(71, 'CRR71', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD'),
(72, 'CRR72', 'Windows 11', 'Assembled - i3 2 & 3rd Gen, 8GB RAM, 500 GB SSD');

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_sections`
--

CREATE TABLE `dynamic_sections` (
  `id` int(11) NOT NULL,
  `section_title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `input_type` varchar(50) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_unique` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dynamic_sections`
--

INSERT INTO `dynamic_sections` (`id`, `section_title`, `column_name`, `input_type`, `display_order`, `is_unique`, `created_at`) VALUES
(9, 'Issues', 'issues', 'checkbox', 7, 0, '2026-01-23 14:59:49'),
(10, 'Room No', 'room_no', 'dropdown', 4, 0, '2026-01-23 15:00:07'),
(12, 'Lab Name', 'lab_name', 'dropdown', 3, 0, '2026-02-04 10:05:40'),
(13, 'System Number', 'system_number', 'dropdown', 6, 0, '2026-02-04 10:45:00'),
(16, 'Email', 'email', 'email', 8, 1, '2026-02-04 11:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `invoices_edit_options`
--

CREATE TABLE `invoices_edit_options` (
  `id` int(11) NOT NULL,
  `edit_options` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices_edit_options`
--

INSERT INTO `invoices_edit_options` (`id`, `edit_options`) VALUES
(1, 'items'),
(2, 'total_no_of_units');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`id`, `name`) VALUES
(3, 'Internet'),
(2, 'Monitor'),
(1, 'Mouse'),
(6, 'OS'),
(4, 'Software');

-- --------------------------------------------------------

--
-- Table structure for table `j_r_d_tata_lab`
--

CREATE TABLE `j_r_d_tata_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `j_r_d_tata_lab`
--

INSERT INTO `j_r_d_tata_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(1, 'JRD01', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(2, 'JRD02', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(3, 'JRD03', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(4, 'JRD04', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(5, 'JRD05', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(6, 'JRD06', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(7, 'JRD07', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(8, 'JRD08', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(9, 'JRD09', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(10, 'JRD10', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(11, 'JRD11', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(12, 'JRD12', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(13, 'JRD13', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(14, 'JRD14', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(15, 'JRD15', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(16, 'JRD16', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(17, 'JRD17', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(18, 'JRD18', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(19, 'JRD19', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(20, 'JRD20', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(21, 'JRD21', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(22, 'JRD22', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(23, 'JRD23', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(24, 'JRD24', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(25, 'JRD25', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(26, 'JRD26', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(27, 'JRD27', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(28, 'JRD28', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(29, 'JRD29', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(30, 'JRD30', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(31, 'JRD31', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(32, 'JRD32', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(33, 'JRD33', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(34, 'JRD34', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(35, 'JRD35', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(36, 'JRD36', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(37, 'JRD37', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(38, 'JRD38', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(39, 'JRD39', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD'),
(40, 'JRD40', 'Windows 10', 'DELL Vastro & assembled i3, i5 & i7 - 5 & 7th Gen, 8 gb RAM, 500 GB SSD');

-- --------------------------------------------------------

--
-- Table structure for table `labs_edit_options`
--

CREATE TABLE `labs_edit_options` (
  `id` int(11) NOT NULL,
  `edit_options` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labs_edit_options`
--

INSERT INTO `labs_edit_options` (`id`, `edit_options`) VALUES
(1, 'no_of_systems_present'),
(2, 'lab_incharge'),
(3, 'programmer'),
(4, 'projector'),
(5, 'building_name'),
(6, 'room_no'),
(7, 'no_of_system_capacity');

-- --------------------------------------------------------

--
-- Table structure for table `labs_sections`
--

CREATE TABLE `labs_sections` (
  `id` int(11) NOT NULL,
  `section_title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `input_type` varchar(50) NOT NULL,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labs_sections`
--

INSERT INTO `labs_sections` (`id`, `section_title`, `column_name`, `input_type`, `display_order`) VALUES
(29, 'Room No', 'room_no', 'alphanumeric', 2),
(30, 'Lab Incharge', 'lab_incharge', 'alphanumeric', 6),
(31, 'Programmer', 'programmer', 'alphanumeric', 7),
(32, 'No of System Capacity', 'no_of_system_capacity', 'alphanumeric', 8),
(33, 'No of Systems Present', 'no_of_systems_present', 'alphanumeric', 9),
(34, 'Projector', 'projector', 'alphanumeric', 10),
(35, 'Lab Name', 'lab_name', 'alphanumeric', 4),
(36, 'Building Name', 'building_name', 'alphanumeric', 3),
(37, 'Lab Code', 'lab_code', 'alphanumeric', 5);

-- --------------------------------------------------------

--
-- Table structure for table `labs_unit`
--

CREATE TABLE `labs_unit` (
  `id` int(11) NOT NULL,
  `room_no` varchar(255) DEFAULT NULL,
  `lab_incharge` varchar(255) DEFAULT NULL,
  `programmer` varchar(255) DEFAULT NULL,
  `no_of_system_capacity` varchar(255) DEFAULT NULL,
  `no_of_systems_present` varchar(255) DEFAULT NULL,
  `projector` varchar(255) DEFAULT NULL,
  `lab_name` varchar(255) DEFAULT NULL,
  `building_name` varchar(255) DEFAULT NULL,
  `lab_code` varchar(255) DEFAULT NULL,
  `lab_name_table` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labs_unit`
--

INSERT INTO `labs_unit` (`id`, `room_no`, `lab_incharge`, `programmer`, `no_of_system_capacity`, `no_of_systems_present`, `projector`, `lab_name`, `building_name`, `lab_code`, `lab_name_table`) VALUES
(97, 'Computer Labs', 'B Hanumantha Rao', 'P D Lakshmi', '136', '134 (ARY001 to ARY134)', 'Yes (2)', 'Aryabhatta Lab', 'Sheed 1', 'ARY', 'aryabhatta_lab'),
(98, 'Computer Labs', 'B Hanumantha Rao', 'Sarayu', '112', '90 (CVR001 to CVR090)', 'Yes', 'C V Raman Lab', 'Sheed 1', 'CVR', 'c_v_raman_lab'),
(99, 'Computer Labs', 'B Hanumantha Rao', 'P D Lakshmi', '72', '72 (SDL01 to SDL72)', 'Yes', 'Sakunthala Devi Lab', 'Sheed 1', 'SDL', 'sakunthala_devi_lab'),
(100, 'Computer Labs', 'B Hanumantha Rao', 'Akhil', '72', '72 (ABK01 to ABK72)', 'Yes', 'Abdul Kalam Lab', 'Sheed 1', 'ABK', 'abdul_kalam_lab'),
(101, 'Computer Labs', 'B Hanumantha Rao', 'Yaswini', '66', '66 (NML01 to NML66)', 'Yes', 'Narayana Murthi Lab', 'Sheed 1', 'NML', 'narayana_murthi_lab'),
(102, 'Computer Labs', 'B Hanumantha Rao', 'Yaswini', '44', '40 (JRD01 to JRD40)', 'Yes', 'J R D Tata Lab', 'Sheed 1', 'JRD', 'j_r_d_tata_lab'),
(103, 'Computer Labs', 'B Hanumantha Rao', 'Sunkanya R', '77', '72 (SRL01 to SRL72)', 'Yes', 'Srinivasa Ramanujan Lab', 'Sheed 1', 'SRL', 'srinivasa_ramanujan_lab'),
(104, '109', 'B Hanumantha Rao', 'Sai Lakshmi', '72', '72 (CRR01 to CRR72)', 'Yes', 'Dr C R Rao Lab', 'Main Building', 'CRR', 'dr_c_r_rao_lab'),
(105, '220', 'B Hanumantha Rao', 'Durga Radha Devi', '72', '72 (CKL01 to CKL72)', 'Yes', 'Chanakya Lab', 'Main Building', 'CKL', 'chanakya_lab'),
(106, '206', 'B Hanumantha Rao', 'M Poorvaja', '72', '72 (BSK01 to BSK72)', 'Yes', 'Bhaskara Charya Lab', 'Main Building', 'BSK', 'bhaskara_charya_lab'),
(107, '314', 'B Hanumantha Rao', 'Harika', '72', '72 (NNL01 to NNL72)', 'Yes', 'Nambi Narayana Lab', 'Main Building', 'NNL', 'nambi_narayana_lab'),
(108, '204', 'B. Hanumantha Rao', 'Bya Reddy Navya', '72', '72 (AGL01 to AGL72)', 'Yes', 'Agastya Lab', 'Main Building', 'AGL', 'agastya_lab');

-- --------------------------------------------------------

--
-- Table structure for table `lab_series_config`
--

CREATE TABLE `lab_series_config` (
  `id` int(11) NOT NULL,
  `lab_name` varchar(255) DEFAULT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `start_no` int(11) DEFAULT NULL,
  `end_no` int(11) DEFAULT NULL,
  `padding` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_series_config`
--

INSERT INTO `lab_series_config` (`id`, `lab_name`, `prefix`, `start_no`, `end_no`, `padding`) VALUES
(2, 'Aryabhatta Lab', 'ARY', 1, 134, 3),
(3, 'C V Raman Lab', 'CVR', 1, 90, 2),
(4, 'Sakunthala Devi Lab', 'SDL', 1, 72, 2),
(5, 'Abdul Kalam Lab', 'APJ', 1, 72, 2),
(6, 'Narayana Murthi Lab', 'NML', 1, 66, 2),
(7, 'J R D Tata Lab', 'JRD', 1, 40, 2),
(8, 'Srinivasa Ramanujan Lab', 'SRL', 1, 72, 2),
(9, 'Dr C R Rao Lab', 'CRR', 1, 72, 2),
(10, 'Chanakya Lab', 'CKL', 1, 72, 2),
(11, 'Bhaskara Charya Lab', 'BCL', 1, 72, 2),
(12, 'Nambi Narayana Lab', 'NNL', 1, 72, 2),
(13, 'Agastya Lab', 'ATL', 1, 72, 2),
(20, 'Nikson Lab', 'NSL', 1, 76, 2);

-- --------------------------------------------------------

--
-- Table structure for table `nambi_narayana_lab`
--

CREATE TABLE `nambi_narayana_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nambi_narayana_lab`
--

INSERT INTO `nambi_narayana_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(1, 'NNL01', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(2, 'NNL02', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(3, 'NNL03', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(4, 'NNL04', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(5, 'NNL05', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(6, 'NNL06', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(7, 'NNL07', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(8, 'NNL08', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(9, 'NNL09', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(10, 'NNL10', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(11, 'NNL11', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(12, 'NNL12', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(13, 'NNL13', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(14, 'NNL14', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(15, 'NNL15', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(16, 'NNL16', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(17, 'NNL17', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(18, 'NNL18', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(19, 'NNL19', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(20, 'NNL20', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(21, 'NNL21', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(22, 'NNL22', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(23, 'NNL23', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(24, 'NNL24', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(25, 'NNL25', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(26, 'NNL26', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(27, 'NNL27', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(28, 'NNL28', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(29, 'NNL29', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(30, 'NNL30', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(31, 'NNL31', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(32, 'NNL32', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(33, 'NNL33', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(34, 'NNL34', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(35, 'NNL35', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(36, 'NNL36', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(37, 'NNL37', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(38, 'NNL38', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(39, 'NNL39', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(40, 'NNL40', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(41, 'NNL41', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(42, 'NNL42', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(43, 'NNL43', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(44, 'NNL44', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(45, 'NNL45', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(46, 'NNL46', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(47, 'NNL47', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(48, 'NNL48', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(49, 'NNL49', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(50, 'NNL50', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(51, 'NNL51', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(52, 'NNL52', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(53, 'NNL53', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(54, 'NNL54', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(55, 'NNL55', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(56, 'NNL56', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(57, 'NNL57', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(58, 'NNL58', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(59, 'NNL59', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(60, 'NNL60', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(61, 'NNL61', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(62, 'NNL62', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(63, 'NNL63', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(64, 'NNL64', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(65, 'NNL65', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(66, 'NNL66', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(67, 'NNL67', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(68, 'NNL68', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(69, 'NNL69', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(70, 'NNL70', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(71, 'NNL71', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2'),
(72, 'NNL72', 'Windows 11', 'Assembled - i5 12th Gen , 16 GB Ram, 500 GB M.2');

-- --------------------------------------------------------

--
-- Table structure for table `narayana_murthi_lab`
--

CREATE TABLE `narayana_murthi_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `narayana_murthi_lab`
--

INSERT INTO `narayana_murthi_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(1, 'NML01', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(2, 'NML02', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(3, 'NML03', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(4, 'NML04', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(5, 'NML05', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(6, 'NML06', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(7, 'NML07', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(8, 'NML08', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(9, 'NML09', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(10, 'NML10', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(11, 'NML11', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(12, 'NML12', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(13, 'NML13', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(14, 'NML14', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(15, 'NML15', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(16, 'NML16', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(17, 'NML17', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(18, 'NML18', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(19, 'NML19', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(20, 'NML20', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(21, 'NML21', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(22, 'NML22', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(23, 'NML23', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(24, 'NML24', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(25, 'NML25', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(26, 'NML26', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(27, 'NML27', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(28, 'NML28', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(29, 'NML29', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(30, 'NML30', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(31, 'NML31', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(32, 'NML32', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(33, 'NML33', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(34, 'NML34', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(35, 'NML35', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(36, 'NML36', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(37, 'NML37', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(38, 'NML38', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(39, 'NML39', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(40, 'NML40', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(41, 'NML41', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(42, 'NML42', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(43, 'NML43', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(44, 'NML44', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(45, 'NML45', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(46, 'NML46', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(47, 'NML47', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(48, 'NML48', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(49, 'NML49', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(50, 'NML50', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(51, 'NML51', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(52, 'NML52', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(53, 'NML53', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(54, 'NML54', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(55, 'NML55', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(56, 'NML56', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(57, 'NML57', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(58, 'NML58', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(59, 'NML59', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(60, 'NML60', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(61, 'NML61', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(62, 'NML62', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(63, 'NML63', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(64, 'NML64', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(65, 'NML65', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA'),
(66, 'NML66', 'Ubitu 14.04', 'DELL Optiplux - core 2 due & quad core, 4 gb ram, 320 gb HDD SATA');

-- --------------------------------------------------------

--
-- Table structure for table `sakunthala_devi_lab`
--

CREATE TABLE `sakunthala_devi_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sakunthala_devi_lab`
--

INSERT INTO `sakunthala_devi_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(91, 'SDL01', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(92, 'SDL02', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(93, 'SDL03', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(94, 'SDL04', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(95, 'SDL05', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(96, 'SDL06', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(97, 'SDL07', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(98, 'SDL08', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(99, 'SDL09', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(100, 'SDL10', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(101, 'SDL11', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(102, 'SDL12', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(103, 'SDL13', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(104, 'SDL14', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(105, 'SDL15', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(106, 'SDL16', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(107, 'SDL17', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(108, 'SDL18', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(109, 'SDL19', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(110, 'SDL20', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(111, 'SDL21', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(112, 'SDL22', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(113, 'SDL23', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(114, 'SDL24', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(115, 'SDL25', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(116, 'SDL26', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(117, 'SDL27', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(118, 'SDL28', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(119, 'SDL29', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(120, 'SDL30', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(121, 'SDL31', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(122, 'SDL32', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(123, 'SDL33', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(124, 'SDL34', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(125, 'SDL35', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(126, 'SDL36', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(127, 'SDL37', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(128, 'SDL38', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(129, 'SDL39', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(130, 'SDL40', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(131, 'SDL41', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(132, 'SDL42', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(133, 'SDL43', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(134, 'SDL44', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(135, 'SDL45', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(136, 'SDL46', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(137, 'SDL47', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(138, 'SDL48', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(139, 'SDL49', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(140, 'SDL50', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(141, 'SDL51', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(142, 'SDL52', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(143, 'SDL53', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(144, 'SDL54', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(145, 'SDL55', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(146, 'SDL56', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(147, 'SDL57', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(148, 'SDL58', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(149, 'SDL59', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(150, 'SDL60', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(151, 'SDL61', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(152, 'SDL62', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(153, 'SDL63', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(154, 'SDL64', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(155, 'SDL65', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(156, 'SDL66', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(157, 'SDL67', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(158, 'SDL68', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(159, 'SDL69', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(160, 'SDL70', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(161, 'SDL71', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD'),
(162, 'SDL72', 'Windows 10 & 11', 'DELL - 30, ASUS - 12, assembled- 30, i3 9 & 10 th Gen, 16 GB Ram- 43, 8GB ram - 29, 500 SSD');

-- --------------------------------------------------------

--
-- Table structure for table `srinivasa_ramanujan_lab`
--

CREATE TABLE `srinivasa_ramanujan_lab` (
  `id` int(11) NOT NULL,
  `system_number` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `config_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `srinivasa_ramanujan_lab`
--

INSERT INTO `srinivasa_ramanujan_lab` (`id`, `system_number`, `os`, `config_details`) VALUES
(1, 'SRL01', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(2, 'SRL02', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(3, 'SRL03', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(4, 'SRL04', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(5, 'SRL05', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(6, 'SRL06', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(7, 'SRL07', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(8, 'SRL08', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(9, 'SRL09', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(10, 'SRL10', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(11, 'SRL11', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(12, 'SRL12', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(13, 'SRL13', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(14, 'SRL14', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(15, 'SRL15', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(16, 'SRL16', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(17, 'SRL17', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(18, 'SRL18', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(19, 'SRL19', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(20, 'SRL20', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(21, 'SRL21', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(22, 'SRL22', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(23, 'SRL23', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(24, 'SRL24', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(25, 'SRL25', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(26, 'SRL26', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(27, 'SRL27', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(28, 'SRL28', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(29, 'SRL29', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(30, 'SRL30', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(31, 'SRL31', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(32, 'SRL32', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(33, 'SRL33', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(34, 'SRL34', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(35, 'SRL35', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(36, 'SRL36', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(37, 'SRL37', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(38, 'SRL38', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(39, 'SRL39', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(40, 'SRL40', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(41, 'SRL41', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(42, 'SRL42', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(43, 'SRL43', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(44, 'SRL44', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(45, 'SRL45', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(46, 'SRL46', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(47, 'SRL47', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(48, 'SRL48', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(49, 'SRL49', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(50, 'SRL50', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(51, 'SRL51', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(52, 'SRL52', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(53, 'SRL53', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(54, 'SRL54', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(55, 'SRL55', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(56, 'SRL56', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(57, 'SRL57', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(58, 'SRL58', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(59, 'SRL59', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(60, 'SRL60', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(61, 'SRL61', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(62, 'SRL62', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(63, 'SRL63', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(64, 'SRL64', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(65, 'SRL65', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(66, 'SRL66', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(67, 'SRL67', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(68, 'SRL68', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(69, 'SRL69', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(70, 'SRL70', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(71, 'SRL71', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA'),
(72, 'SRL72', 'Windows 11', 'ASUS Expert Center D 500MA - i3 10th Gen, 8 GB Ram, 1 TB HDD SATA');

-- --------------------------------------------------------

--
-- Table structure for table `storage_sections`
--

CREATE TABLE `storage_sections` (
  `id` int(11) NOT NULL,
  `section_title` varchar(255) NOT NULL,
  `column_name` varchar(255) NOT NULL,
  `input_type` varchar(50) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_unique` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storage_sections`
--

INSERT INTO `storage_sections` (`id`, `section_title`, `column_name`, `input_type`, `display_order`, `is_unique`, `created_at`) VALUES
(9, 'Invoice', 'invoice', 'alphanumeric', 1, 0, '2026-01-23 15:06:58'),
(12, 'Invoice Date', 'invoice_date', 'date', 3, 0, '2026-01-23 15:07:54'),
(13, 'Total no of units', 'total_no_of_units', 'numeric', 6, 0, '2026-01-23 15:20:30'),
(14, 'Stack Received Date', 'stack_received_date', 'date', 7, 0, '2026-02-04 05:05:09'),
(15, 'Vender Name', 'vender_name', 'alphanumeric', 9, 0, '2026-02-04 05:05:49'),
(16, 'Vender Address', 'vender_address', 'alphanumeric', 8, 0, '2026-02-04 05:05:58'),
(17, 'Items', 'items', 'alphanumeric', 5, 0, '2026-02-05 03:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `storage_unit`
--

CREATE TABLE `storage_unit` (
  `id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Logged',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `invoice` varchar(255) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `total_no_of_units` int(11) DEFAULT 0,
  `stack_received_date` date DEFAULT NULL,
  `vender_name` varchar(255) DEFAULT NULL,
  `vender_address` varchar(255) DEFAULT NULL,
  `items` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storage_unit`
--

INSERT INTO `storage_unit` (`id`, `status`, `created_at`, `invoice`, `invoice_date`, `total_no_of_units`, `stack_received_date`, `vender_name`, `vender_address`, `items`) VALUES
(4, 'Logged', '2026-01-23 15:12:09', 'Invoice00', '2026-01-14', 0, NULL, NULL, NULL, NULL),
(5, 'Logged', '2026-01-23 15:18:58', 'dsfgsdfg', '2026-01-30', 0, NULL, NULL, NULL, NULL),
(6, 'Logged', '2026-01-23 15:19:10', 'dghsdhdhf', '2026-01-09', 0, NULL, NULL, NULL, NULL),
(7, 'Logged', '2026-01-23 15:19:29', '23dcgdfg5', '2026-01-19', 0, NULL, NULL, NULL, NULL),
(8, 'Logged', '2026-01-23 15:19:47', 'cvbfdsgevs', '2026-01-27', 0, NULL, NULL, NULL, NULL),
(9, 'Logged', '2026-01-23 15:20:59', 'sdfg segber', '2026-01-10', 6, NULL, NULL, NULL, NULL),
(10, 'Logged', '2026-01-23 15:23:09', 'dgbdvbcvnrt', '2026-01-10', 25, NULL, NULL, NULL, NULL),
(11, 'Logged', '2026-02-02 13:07:14', 'dfgndfgserg', '2026-02-20', 5, NULL, NULL, NULL, NULL),
(12, 'Logged', '2026-02-03 14:28:41', 'sdfdsafljfb324', '2026-02-12', 5, NULL, NULL, NULL, NULL),
(13, 'Logged', '2026-02-03 14:33:08', 'sdkfjdsl', '2026-02-21', 7, NULL, NULL, NULL, NULL),
(14, 'Logged', '2026-02-03 14:38:14', 'skdfobsdoif', '2026-02-18', 58, NULL, NULL, NULL, NULL),
(15, 'Logged', '2026-02-03 14:46:09', 'sdjlfbdslf', '2026-02-27', 75, NULL, NULL, NULL, 'test1');

-- --------------------------------------------------------

--
-- Table structure for table `systems_edit_options`
--

CREATE TABLE `systems_edit_options` (
  `id` int(11) NOT NULL,
  `edit_options` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `systems_edit_options`
--

INSERT INTO `systems_edit_options` (`id`, `edit_options`) VALUES
(2, 'os'),
(3, 'config_details');

-- --------------------------------------------------------

--
-- Table structure for table `systems_sections`
--

CREATE TABLE `systems_sections` (
  `id` int(11) NOT NULL,
  `section_title` varchar(255) DEFAULT NULL,
  `column_name` varchar(255) DEFAULT NULL,
  `input_type` varchar(50) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `systems_sections`
--

INSERT INTO `systems_sections` (`id`, `section_title`, `column_name`, `input_type`, `display_order`) VALUES
(12, 'System Number', 'system_number', 'text', 4),
(13, 'OS', 'os', 'text', 5),
(14, 'Config Details', 'config_details', 'text', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abdul_kalam_lab`
--
ALTER TABLE `abdul_kalam_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `agastya_lab`
--
ALTER TABLE `agastya_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aryabhatta_lab`
--
ALTER TABLE `aryabhatta_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bhaskara_charya_lab`
--
ALTER TABLE `bhaskara_charya_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chanakya_lab`
--
ALTER TABLE `chanakya_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints_log`
--
ALTER TABLE `complaints_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `c_v_raman_lab`
--
ALTER TABLE `c_v_raman_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dr_c_r_rao_lab`
--
ALTER TABLE `dr_c_r_rao_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dynamic_sections`
--
ALTER TABLE `dynamic_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices_edit_options`
--
ALTER TABLE `invoices_edit_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `j_r_d_tata_lab`
--
ALTER TABLE `j_r_d_tata_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labs_edit_options`
--
ALTER TABLE `labs_edit_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labs_sections`
--
ALTER TABLE `labs_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labs_unit`
--
ALTER TABLE `labs_unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_name` (`lab_name`),
  ADD UNIQUE KEY `lab_code` (`lab_code`),
  ADD UNIQUE KEY `lab_name_table` (`lab_name_table`);

--
-- Indexes for table `lab_series_config`
--
ALTER TABLE `lab_series_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_name` (`lab_name`);

--
-- Indexes for table `nambi_narayana_lab`
--
ALTER TABLE `nambi_narayana_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `narayana_murthi_lab`
--
ALTER TABLE `narayana_murthi_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sakunthala_devi_lab`
--
ALTER TABLE `sakunthala_devi_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `srinivasa_ramanujan_lab`
--
ALTER TABLE `srinivasa_ramanujan_lab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storage_sections`
--
ALTER TABLE `storage_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storage_unit`
--
ALTER TABLE `storage_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `systems_edit_options`
--
ALTER TABLE `systems_edit_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `systems_sections`
--
ALTER TABLE `systems_sections`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abdul_kalam_lab`
--
ALTER TABLE `abdul_kalam_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `agastya_lab`
--
ALTER TABLE `agastya_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `aryabhatta_lab`
--
ALTER TABLE `aryabhatta_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- AUTO_INCREMENT for table `bhaskara_charya_lab`
--
ALTER TABLE `bhaskara_charya_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `chanakya_lab`
--
ALTER TABLE `chanakya_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `complaints_log`
--
ALTER TABLE `complaints_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `c_v_raman_lab`
--
ALTER TABLE `c_v_raman_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `dr_c_r_rao_lab`
--
ALTER TABLE `dr_c_r_rao_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `dynamic_sections`
--
ALTER TABLE `dynamic_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `invoices_edit_options`
--
ALTER TABLE `invoices_edit_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `j_r_d_tata_lab`
--
ALTER TABLE `j_r_d_tata_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `labs_edit_options`
--
ALTER TABLE `labs_edit_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `labs_sections`
--
ALTER TABLE `labs_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `labs_unit`
--
ALTER TABLE `labs_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `lab_series_config`
--
ALTER TABLE `lab_series_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `nambi_narayana_lab`
--
ALTER TABLE `nambi_narayana_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `narayana_murthi_lab`
--
ALTER TABLE `narayana_murthi_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `sakunthala_devi_lab`
--
ALTER TABLE `sakunthala_devi_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `srinivasa_ramanujan_lab`
--
ALTER TABLE `srinivasa_ramanujan_lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `storage_sections`
--
ALTER TABLE `storage_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `storage_unit`
--
ALTER TABLE `storage_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `systems_edit_options`
--
ALTER TABLE `systems_edit_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `systems_sections`
--
ALTER TABLE `systems_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
