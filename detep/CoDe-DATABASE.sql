-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 05, 2021 at 09:42 AM
-- Server version: 5.7.35-cll-lve
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jovinkam_detep`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) UNSIGNED NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `cat_slug` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_slug`) VALUES
(1, 'Medical or Pharmaceutical Products', 'medical-products'),
(2, 'Foood and Nutrition Products', 'food-and-nutrition-products');

-- --------------------------------------------------------

--
-- Table structure for table `consumers`
--

CREATE TABLE `consumers` (
  `consumer_id` int(11) UNSIGNED NOT NULL,
  `consumer_device` varchar(50) DEFAULT NULL,
  `consumer_usage` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `down_id` int(11) UNSIGNED NOT NULL,
  `down_count` int(11) DEFAULT '0',
  `down_last` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`down_id`, `down_count`, `down_last`) VALUES
(1, 17, '2021-10-21 12:57:49');

-- --------------------------------------------------------

--
-- Table structure for table `intermediaries`
--

CREATE TABLE `intermediaries` (
  `int_id` int(11) UNSIGNED NOT NULL,
  `int_fname` varchar(255) NOT NULL,
  `int_lname` varchar(255) NOT NULL,
  `int_phone` varchar(15) NOT NULL,
  `int_mail` varchar(255) NOT NULL,
  `int_password` varchar(255) NOT NULL,
  `int_station` int(11) UNSIGNED NOT NULL,
  `int_device` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `intermediaries`
--

INSERT INTO `intermediaries` (`int_id`, `int_fname`, `int_lname`, `int_phone`, `int_mail`, `int_password`, `int_station`, `int_device`) VALUES
(1, 'Manufacturer', 'Manufacturer1', '+255765764538', 'manufacturer1@code.com', '$2y$10$2vbT/BtLQufTFfYpw6MUH.G3vW/qBtz2gGVo09X3iKfVNmiulU1kC', 1, NULL),
(5, 'SWS1', 'SWS1', '0744829001', 'sws1@code.com', '$2y$10$8MwzP2fmu3CXCjEpBfKdEuH4BQvq7FUIOtj36AsMfYNMP8wGhFW6C', 3, NULL),
(4, 'WS1', 'WS1', '08765456700', 'ws1@code.com', '$2y$10$clVVCbQmwSiVFP70Q2Kjk.uUuK.vPUPmhi6H4D4nsNQVTk47bdoIy', 2, NULL),
(6, 'RT1', 'RT1', '0744829000', 'rt1@code.com', '$2y$10$8CMjpaewANGB6R4bUMt/xeVq7jqwt2a0lmL.8D7nA5WIyp7.Qw2xK', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `lv_id` int(11) UNSIGNED NOT NULL,
  `lv_title` varchar(255) NOT NULL,
  `lv_int` int(11) UNSIGNED NOT NULL,
  `lv_description` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`lv_id`, `lv_title`, `lv_int`, `lv_description`) VALUES
(1, 'first', 1, 'First distribution level.'),
(2, 'second', 2, 'Second distribution level.'),
(3, 'third-pub-prv', 3, 'Third distribution level.'),
(4, 'fourth', 4, 'Fourth distribution level.'),
(5, 'fifth', 5, 'Fifth distribution level.'),
(6, 'sixth', 6, 'Sixth distribution level.'),
(7, 'last', 100, 'Final distribution level for all product systems, e.g., health-centres, pharmacies, or any other retail point.');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2021-04-30-141512', 'App\\Database\\Migrations\\AddParcel', 'default', 'App', 1624372060, 1),
(2, '2021-04-30-145129', 'App\\Database\\Migrations\\AddCategory', 'default', 'App', 1624372060, 1),
(3, '2021-04-30-145743', 'App\\Database\\Migrations\\AddProduct', 'default', 'App', 1624372060, 1),
(4, '2021-04-30-150903', 'App\\Database\\Migrations\\AddSystem', 'default', 'App', 1624372060, 1),
(5, '2021-04-30-151705', 'App\\Database\\Migrations\\AddStation', 'default', 'App', 1624372060, 1),
(6, '2021-04-30-152650', 'App\\Database\\Migrations\\AddIntermediary', 'default', 'App', 1624372060, 1),
(7, '2021-04-30-153147', 'App\\Database\\Migrations\\AddLevel', 'default', 'App', 1624372060, 1),
(8, '2021-05-14-150232', 'App\\Database\\Migrations\\AddConsumer', 'default', 'App', 1624372060, 1),
(9, '2021-06-22-123947', 'App\\Database\\Migrations\\AddDownload', 'default', 'App', 1624372060, 1),
(10, '2021-06-22-124501', 'App\\Database\\Migrations\\AddResult', 'default', 'App', 1624372060, 1);

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `parc_id` varchar(40) NOT NULL,
  `parc_title` varchar(255) DEFAULT NULL,
  `parc_parent` varchar(40) DEFAULT NULL,
  `parc_product` int(11) UNSIGNED DEFAULT NULL,
  `parc_level` varchar(255) DEFAULT NULL,
  `parc_station_path` varchar(255) DEFAULT NULL,
  `parc_next_station` varchar(255) DEFAULT NULL,
  `parc_sent_dates` varchar(255) DEFAULT NULL,
  `parc_arrival_dates` varchar(255) DEFAULT NULL,
  `parc_qrcodelink` varchar(255) NOT NULL,
  `parc_sold` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parcels`
--

INSERT INTO `parcels` (`parc_id`, `parc_title`, `parc_parent`, `parc_product`, `parc_level`, `parc_station_path`, `parc_next_station`, `parc_sent_dates`, `parc_arrival_dates`, `parc_qrcodelink`, `parc_sold`) VALUES
('b8dfba6c-43cd-4676-a19a-fbb6c2548763', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562375_azuma2.png', 0),
('2c7c061c-79f6-469b-840c-874e9f0606c0', 'azuma_1624372259', '56c14b2b-741f-4fab-ab2d-0751bb513058', 1, '1', '1', NULL, NULL, '1625044566', 'azuma_1624372259/azuma_1624372259.png', 0),
('e3a9e49b-37a8-45fc-a7c2-37496f5eb81e', 'azuma', '2c7c061c-79f6-469b-840c-874e9f0606c0', 1, '1', '1', NULL, NULL, '1625044566', 'azuma_1624372259/1624372259_azuma1.png', 0),
('e99c0184-82a4-4ffd-994a-eb6b1ef5ab7a', 'azuma', '2c7c061c-79f6-469b-840c-874e9f0606c0', 1, '1', '1', NULL, NULL, '1625044566', 'azuma_1624372259/1624372260_azuma2.png', 0),
('8c8e8de7-3f4a-4f54-a902-90f1038efd3a', 'azuma', '2c7c061c-79f6-469b-840c-874e9f0606c0', 1, '1', '1', NULL, NULL, '1625044566', 'azuma_1624372259/1624372260_azuma3.png', 0),
('f78914f8-79e4-4de5-83db-bea8cd41876c', 'azuma', '2c7c061c-79f6-469b-840c-874e9f0606c0', 1, '1', '1', NULL, NULL, '1625044566', 'azuma_1624372259/1624372260_azuma4.png', 0),
('3f536cec-878f-40d6-a3f4-773f56eaef32', 'azuma', '2c7c061c-79f6-469b-840c-874e9f0606c0', 1, '1', '1', NULL, NULL, '1625044566', 'azuma_1624372259/1624372261_azuma5.png', 0),
('b65458f6-f7be-4fc4-a601-223b171de243', 'tinidazole', '5f751277-325a-487d-a8ea-d760c5fc901e', 3, '1', '1', NULL, NULL, '1627468585', 'tinidazole_1626691413/1626691414_tinidazole1.png', 0),
('34cc0a92-0584-4125-9931-434f31e445e5', 'prestige-margarine', '7d95091a-0c74-44d2-99b0-5db47110d8b4', 2, '1', '1', NULL, NULL, '1626691127', 'prestige-margarine_1625221311/1625221312_prestige-margarine1.png', 0),
('81838300-e085-491b-84ed-c21211eaef9f', 'tinidazole', '5f751277-325a-487d-a8ea-d760c5fc901e', 3, '1', '1', NULL, NULL, '1627468585', 'tinidazole_1626691413/1626691414_tinidazole2.png', 0),
('eb70ad6d-7792-4c9f-abb6-1531ba682fe8', 'tinidazole_1628080231', NULL, 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080231/tinidazole_1628080231.png', 0),
('f8ee8522-d7a6-471e-98be-efa9ee0ea203', 'prestige-margarine', '7ba84662-3eb7-415a-b7a8-1bfbddd8d954', 2, '1', '1', NULL, NULL, '1626691146', 'prestige-margarine_1624482126/1624482127_prestige-margarine1.png', 0),
('9a956f6a-af82-4376-86eb-ecbce3fd31d9', 'prestige-margarine', '7ba84662-3eb7-415a-b7a8-1bfbddd8d954', 2, '1', '1', NULL, NULL, '1626691146', 'prestige-margarine_1624482126/1624482128_prestige-margarine2.png', 0),
('4b87eb90-b833-472e-b887-338d420d96be', 'prestige-margarine', '7ba84662-3eb7-415a-b7a8-1bfbddd8d954', 2, '1', '1', NULL, NULL, '1626691146', 'prestige-margarine_1624482126/1624482128_prestige-margarine3.png', 0),
('bfa24545-9d8e-41cc-ae96-1671953fbfd8', 'prestige-margarine', '7ba84662-3eb7-415a-b7a8-1bfbddd8d954', 2, '1', '1', NULL, NULL, '1626691146', 'prestige-margarine_1624482126/1624482128_prestige-margarine4.png', 0),
('f278810e-d933-4c8a-8667-73f5810b74b9', 'prestige-margarine', '7d95091a-0c74-44d2-99b0-5db47110d8b4', 2, '1', '1', NULL, NULL, '1626691127', 'prestige-margarine_1625221311/1625221313_prestige-margarine2.png', 0),
('ae63dea4-1bb8-485e-8f3d-2df8d107f121', 'prestige-margarine', '7d95091a-0c74-44d2-99b0-5db47110d8b4', 2, '1', '1', NULL, NULL, '1626691127', 'prestige-margarine_1625221311/1625221313_prestige-margarine3.png', 0),
('c42e204d-4b3b-4414-bdba-f06172059383', 'prestige-margarine', '7d95091a-0c74-44d2-99b0-5db47110d8b4', 2, '1', '1', NULL, NULL, '1626691127', 'prestige-margarine_1625221311/1625221313_prestige-margarine4.png', 0),
('129ddd3d-64ca-42ca-899c-dfdbd0fa1ce7', 'prestige-margarine', '7d95091a-0c74-44d2-99b0-5db47110d8b4', 2, '1', '1', NULL, NULL, '1626691127', 'prestige-margarine_1625221311/1625221314_prestige-margarine5.png', 0),
('3c74c4f4-ab41-4c72-b947-1987e394445a', 'tinidazole', '5f751277-325a-487d-a8ea-d760c5fc901e', 3, '1', '1', NULL, NULL, '1627468585', 'tinidazole_1626691413/1626691415_tinidazole4.png', 0),
('f5724533-09c2-410a-b811-a8bd8a9fdcc7', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562374_azuma1.png', 0),
('2a9503e2-12c7-4245-b8dd-c72927e26f5e', 'tinidazole', '5f751277-325a-487d-a8ea-d760c5fc901e', 3, '1', '1', NULL, NULL, '1627468585', 'tinidazole_1626691413/1626691414_tinidazole3.png', 0),
('aea892c2-c7d1-4e32-97b1-124ee4e36e3c', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562375_azuma3.png', 0),
('1d5b576a-c5ed-42a8-bc35-5990f8380e6f', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562375_azuma4.png', 0),
('fc5e0472-1c0d-40ef-80e2-45633d919340', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562376_azuma5.png', 0),
('caa82bd2-14d6-400c-989e-9b31907432e1', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562376_azuma6.png', 0),
('d3968041-fe93-4ca7-a70a-971ea1f49fdc', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562376_azuma7.png', 0),
('cb3f6a16-7e54-4a99-b112-e9134e78184e', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562377_azuma8.png', 0),
('b0cb2990-88ce-4014-bad0-bdf5f571cfaf', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562377_azuma9.png', 0),
('cc20ad12-4502-4697-8507-958a851c4dfb', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562377_azuma10.png', 0),
('ee88301d-1d4d-44d2-87b3-576100ab48cb', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562378_azuma11.png', 0),
('0a67f633-0599-43ef-a166-b97343c76ef0', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562378_azuma12.png', 0),
('5228efb9-4f92-4811-b598-40edc31fb66b', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562378_azuma13.png', 0),
('bbbfd4c2-2e1e-41ed-8311-966de9b29f20', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562379_azuma14.png', 0),
('a2a9e8f8-b639-4a5b-a137-2d77f88d5ef0', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562379_azuma15.png', 0),
('7ea2cfb0-020d-475c-b0fc-3f8db7353373', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562379_azuma16.png', 0),
('9a271a67-090f-4ca3-a223-dac25997206c', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562380_azuma17.png', 0),
('78ebb77b-ac4e-455c-b288-340a0902dc4c', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562380_azuma18.png', 0),
('81288294-cea7-4cbd-b58a-050cae8cc221', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562380_azuma19.png', 0),
('46746b0c-96c6-4e45-9e6e-ee472f2b80bb', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562381_azuma20.png', 0),
('cabe05e6-db83-4901-b535-de5032b8a98f', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562381_azuma21.png', 0),
('59e062e2-770a-41bb-b9ff-9ee99ac7b784', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562381_azuma22.png', 0),
('8b861ebf-4110-499e-aaf4-60b35b948728', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562382_azuma23.png', 0),
('faca6bb0-5674-4bcf-a8d3-0f7b812eff4c', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562382_azuma24.png', 0),
('19f2bc33-9d2e-4547-bb7e-0ece39c53644', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562382_azuma25.png', 0),
('93086e43-e706-4895-8ef9-ad44c5decd87', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562383_azuma26.png', 0),
('98e59100-f204-4e2c-8853-61f8783df9ae', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562383_azuma27.png', 0),
('3763fcdd-d0db-43ed-84cd-3fe65689de66', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562383_azuma28.png', 0),
('89cf54e9-8ea7-4142-a5c0-ff1a774e5b6e', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562384_azuma29.png', 0),
('d6370a86-27f8-4e18-bf54-bac0d3b2f9e3', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562384_azuma30.png', 0),
('3c56f826-982e-438f-834b-f0575933a84a', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562384_azuma31.png', 0),
('469efab2-a6b8-4768-af71-f1948f5fdc96', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562385_azuma32.png', 0),
('c4426534-ca1e-463b-b901-231e86add083', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562385_azuma33.png', 0),
('c8b24b73-9d93-45a0-8ad3-460580ef05c8', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562385_azuma34.png', 0),
('313edaf5-10dd-424c-bc63-3bd223839e71', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562386_azuma35.png', 0),
('7cbe1a40-6931-4564-901d-0569163e176f', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562386_azuma36.png', 0),
('0d232985-9663-4e3c-8271-475e65d0afa5', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562386_azuma37.png', 0),
('e1cc570d-fcdb-4cc9-ac5b-c8b14fe64ed4', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562387_azuma38.png', 0),
('0944f662-77d0-4a26-861f-11a8e3abf3f1', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562387_azuma39.png', 0),
('47cf99c6-5a5f-4278-8d29-96d729e6ceed', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562388_azuma40.png', 0),
('4e79f98b-da97-48d3-b6ee-6b00cde9df4b', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562388_azuma41.png', 0),
('4d51a635-59d2-442d-a2a2-dc141c134cf2', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562388_azuma42.png', 0),
('d3112251-e07e-4979-9a80-b9a8bb156151', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562389_azuma43.png', 0),
('785889e9-ea1f-4245-ab27-16e10b44d288', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562389_azuma44.png', 0),
('8e13ae8a-2343-4619-a84e-b5b0622c9809', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562389_azuma45.png', 0),
('b92776de-1cd7-496b-8502-5860693f95c2', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562389_azuma46.png', 0),
('26960d1f-9c62-4961-b3e6-8f241236eac6', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562390_azuma47.png', 0),
('63b3267f-ca75-4c4c-be0f-52e806ff163a', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562390_azuma48.png', 0),
('6bd9d075-5eea-4d8b-95eb-63aa4987de5e', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562390_azuma49.png', 0),
('23858026-813f-4266-9bc7-427cd50a3785', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562391_azuma50.png', 0),
('d4a07ea6-a2e0-4016-8a98-f0b14f03d165', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562391_azuma51.png', 0),
('492673ab-5855-43bf-89d4-0c3a635794e1', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562391_azuma52.png', 0),
('19abc1a3-5772-48a8-92e2-bab82cd95322', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562392_azuma53.png', 0),
('05abb330-bd7c-4d95-9f59-26688df39b91', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562392_azuma54.png', 0),
('a3837ab5-d8f1-4ba1-b1df-83a847090b59', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562392_azuma55.png', 0),
('e41c70da-346b-404f-afda-b40aab856a1d', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562393_azuma56.png', 0),
('adb7ce9d-2156-4379-aff1-80860b8e2f13', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562393_azuma57.png', 0),
('c31d00a1-5a23-4417-b2b5-5149f3d2deba', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562393_azuma58.png', 0),
('cf10ddd2-18b8-4c4e-93b0-a362aad923ec', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562394_azuma59.png', 0),
('edae9236-0c03-408e-8123-49a4e6ea24a4', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562394_azuma60.png', 0),
('1feb8e06-395c-4e88-a842-5e1eb5b8d0db', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562394_azuma61.png', 0),
('c3f36cc0-08fb-44c3-9ec7-46afab5aa430', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562395_azuma62.png', 0),
('34313c0c-03e0-4bb6-858b-42868695ed53', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562395_azuma63.png', 0),
('dfe89281-d7ce-4922-9e46-d04f3730e360', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562395_azuma64.png', 0),
('201e5921-d9bd-4550-8fbf-ad27a00f01e3', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562395_azuma65.png', 0),
('05a11dce-45a4-4438-a60a-199611ee94c8', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562396_azuma66.png', 0),
('1cb20518-38b2-48af-a032-ad7b433916c3', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562396_azuma67.png', 0),
('6ef3f737-6fce-4a4f-aebe-2805989af674', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562396_azuma68.png', 0),
('4eb7debe-ef7f-41b7-8227-2b90aca1ad14', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562397_azuma69.png', 0),
('42f071ea-4511-42b0-ae0d-f0e0db2c71f6', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562397_azuma70.png', 0),
('a5e08d6a-bc29-4dd8-afbe-1726682a0c10', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562397_azuma71.png', 0),
('364e46f4-e69e-4128-98c2-c2cb7d09b588', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562398_azuma72.png', 0),
('4373d963-0940-4e40-b2c7-225b61e01f3e', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562398_azuma73.png', 0),
('8e5a452c-0ec6-4e77-ab8d-0a5d99c17909', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562398_azuma74.png', 0),
('fad44a3b-8864-4b75-a260-7c137e9d5c6b', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562399_azuma75.png', 0),
('b1076e68-11e3-4659-90a4-d067695ee190', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562399_azuma76.png', 0),
('fc3d596d-6e62-4845-a106-e9953bf9b821', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562399_azuma77.png', 0),
('280c9f4b-1b2f-454c-8de9-233e242e675c', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562400_azuma78.png', 0),
('cf06c903-a62d-450e-8cdc-682c6f9f2212', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562400_azuma79.png', 0),
('ff0e7425-d160-4881-8874-0f3effb236bf', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562400_azuma80.png', 0),
('75e84812-86af-48f0-8695-ef57252306aa', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562400_azuma81.png', 0),
('2ddd7a62-aa1d-4d43-9542-9a5f4e75c0c1', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562401_azuma82.png', 0),
('b87408a5-3a30-4bbc-8f6f-3e4e84542d88', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562401_azuma83.png', 0),
('e90dbc8d-6e26-4742-b2a0-c1c2b9318914', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562401_azuma84.png', 0),
('37c04d12-493e-42d7-8bc0-8f4295bd6a81', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562402_azuma85.png', 0),
('ef36cd7e-e87e-461e-b7c8-d33d490aa660', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562402_azuma86.png', 0),
('13cefd4f-f935-4abc-be6d-df3ef2c14427', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562402_azuma87.png', 0),
('3fd68dd8-92e2-4267-9ecf-826153122771', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562403_azuma88.png', 0),
('d215baad-75e6-4c78-bcdf-01e4f3887b0e', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562403_azuma89.png', 0),
('fb543e47-393b-4902-b4c3-7d373e8b824c', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562403_azuma90.png', 0),
('4f0481bf-322b-48b8-befb-d7c2faac10a7', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562404_azuma91.png', 0),
('5f339ca8-1f57-4766-bd9c-cb6bece1e2f3', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562404_azuma92.png', 0),
('ead38d31-b98a-4955-8d8a-54522ce1e280', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562404_azuma93.png', 0),
('a6f4fadb-0f07-48af-8351-5cb32473ba61', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562405_azuma94.png', 0),
('c303a98f-085f-42d0-9c1b-bde6b0191de2', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562405_azuma95.png', 0),
('5650e233-0199-4091-92d2-4a214a5a5354', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562405_azuma96.png', 0),
('d37754ab-d28c-4763-b432-b888a7df2073', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562405_azuma97.png', 0),
('06847a59-a191-456a-ac28-59952a299447', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562406_azuma98.png', 0),
('661eb788-b760-4850-842e-9df14716afa1', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562406_azuma99.png', 0),
('005bd02c-a96b-4d58-846b-97336ae467c9', 'azuma', 'b85fe10a-ea4a-497c-9a8f-c184dd885e02', 1, '1', '1', NULL, NULL, '1626691113', 'azuma_1625562373/1625562406_azuma100.png', 0),
('75fc38a3-96ff-4825-b922-4e6b846d3315', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763052_azuma9.png', 0),
('d4f44c91-d48f-44d8-b7b7-889334f29089', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763051_azuma8.png', 0),
('c1ce8d4e-3901-4a74-8755-218e43649e12', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763051_azuma7.png', 0),
('84ab595e-a165-4727-8455-5a0c5cda221d', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763050_azuma6.png', 0),
('7199c981-3429-41ca-bd48-f035df6c4682', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763050_azuma5.png', 0),
('74e3752b-2d93-42e0-ac14-3e7984333632', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763050_azuma4.png', 0),
('7907b569-33d1-4a41-86f2-824c80807029', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763049_azuma3.png', 0),
('d4383f0c-8145-4605-9aec-6386f4440476', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763049_azuma2.png', 0),
('0fccb73c-75ac-423f-a711-796b7ae07268', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763048_azuma1.png', 0),
('ea34e623-3ab8-4ec7-8983-01cea1617598', 'tinidazole', '5f751277-325a-487d-a8ea-d760c5fc901e', 3, '1', '1', NULL, NULL, '1627468585', 'tinidazole_1626691413/1626691415_tinidazole5.png', 0),
('c26bd131-8184-4e38-a543-bac7225ce633', 'azuma', '7a5bcf71-578f-4297-8cdd-56badb919f42', 1, '1', '1', NULL, NULL, '1626763045', 'azuma_1626763045/1626763052_azuma10.png', 0),
('22aebead-dffd-41de-ad3d-7b9c0e36a831', 'tinidazole', '024a413a-8d1e-45a5-a000-7d413f3806fb', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080357/1628080358_tinidazole3.png', 0),
('49c9ed7e-7d38-423f-9b3e-b95c21c41270', 'tinidazole', '024a413a-8d1e-45a5-a000-7d413f3806fb', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080357/1628080357_tinidazole1.png', 0),
('46e689e3-bbd7-4382-9ace-6546154356bd', 'tinidazole_1628080388', 'eb70ad6d-7792-4c9f-abb6-1531ba682fe8', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080388/tinidazole_1628080388.png', 0),
('86fcc11a-615e-4900-88b9-30da399c2375', 'tinidazole', '024a413a-8d1e-45a5-a000-7d413f3806fb', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080357/1628080357_tinidazole2.png', 0),
('024a413a-8d1e-45a5-a000-7d413f3806fb', 'tinidazole_1628080357', 'eb70ad6d-7792-4c9f-abb6-1531ba682fe8', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080357/tinidazole_1628080357.png', 0),
('5f8ea2ee-056c-47c9-b4e1-d1f60d7cbd0d', 'tinidazole', '46e689e3-bbd7-4382-9ace-6546154356bd', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080388/1628080389_tinidazole1.png', 0),
('318f4aa0-fb5e-4d5b-bef6-78b312c8aebc', 'tinidazole', '46e689e3-bbd7-4382-9ace-6546154356bd', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080388/1628080389_tinidazole2.png', 0),
('a764a495-3506-47a3-8a93-79f04383c412', 'tinidazole', '46e689e3-bbd7-4382-9ace-6546154356bd', 3, '1', '1', NULL, NULL, '1634657052', 'tinidazole_1628080388/1628080389_tinidazole3.png', 0),
('d8bbe9c2-d3cc-4587-9c9c-654443c72494', 'azuma', 'edcffdad-07ab-49e6-9264-74c60f6e1b74', 1, '1', '1', NULL, NULL, '1634808591', 'azuma_1634657150/1634657151_azuma1.png', 0),
('edcffdad-07ab-49e6-9264-74c60f6e1b74', 'azuma_1634657150', NULL, 1, '1', '1', NULL, NULL, '1634808591', 'azuma_1634657150/azuma_1634657150.png', 0),
('3e10a679-84a5-40b9-bfbf-3ef22f1c2624', 'azuma', 'edcffdad-07ab-49e6-9264-74c60f6e1b74', 1, '1', '1', NULL, NULL, '1634808591', 'azuma_1634657150/1634657151_azuma2.png', 0),
('5bcf28f1-4bfe-4e7f-bca9-baa0ab5be65f', 'azuma', 'edcffdad-07ab-49e6-9264-74c60f6e1b74', 1, '1', '1', NULL, NULL, '1634808591', 'azuma_1634657150/1634657151_azuma3.png', 0),
('7c1a59d5-711f-4ecf-a552-5d71551601dc', 'azuma', 'edcffdad-07ab-49e6-9264-74c60f6e1b74', 1, '1', '1', NULL, NULL, '1634808591', 'azuma_1634657150/1634657152_azuma4.png', 0),
('e4c29833-368f-4bab-8b48-d6d3b1b027af', 'azuma', 'edcffdad-07ab-49e6-9264-74c60f6e1b74', 1, '1', '1', NULL, NULL, '1634808591', 'azuma_1634657150/1634657152_azuma5.png', 0),
('30c99f36-a6c7-4cfa-bec5-a31ed93ddf65', 'azuma', 'edcffdad-07ab-49e6-9264-74c60f6e1b74', 1, '1', '1', NULL, NULL, '1634808591', 'azuma_1634657150/1634657152_azuma6.png', 0),
('7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 'tinidazole_1634657254', NULL, 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/tinidazole_1634657254.png', 0),
('3d1aec13-93c7-4ad3-9a64-c4cd8405808e', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657254_tinidazole1.png', 0),
('bf20bd79-5dd0-4832-af9b-0cd3ec1fba06', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657255_tinidazole2.png', 0),
('e42d6d6d-0730-49d2-b13b-b8b51e03bccd', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657255_tinidazole3.png', 0),
('2c6083ad-c8f2-4ce2-958c-472c0bb59e67', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657255_tinidazole4.png', 0),
('243a6344-1394-4c20-b734-422f41b47e3f', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657256_tinidazole5.png', 0),
('2766ccb8-400f-4af9-a61b-64128fa68f6d', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657256_tinidazole6.png', 0),
('3a73e40b-8eea-46e2-8912-3c7698c11d37', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657257_tinidazole7.png', 0),
('f764e280-b946-42bc-8b8b-d26480cf1cbf', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657257_tinidazole8.png', 0),
('2f5dd909-89e1-4256-99ac-e760fb456a6b', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657257_tinidazole9.png', 0),
('e963d3ef-84b2-49b8-aa63-ef88bc1c4165', 'tinidazole', '7fe9ebb8-6d05-4eda-9885-4c7d5f76465b', 3, '1', '1', NULL, NULL, '1634657254', 'tinidazole_1634657254/1634657258_tinidazole10.png', 0),
('142207fa-8c42-4421-a9bb-bdefd912edc0', 'tinidazole', '710a0d4b-0b0a-42e5-969b-e9d267adbb80', 3, '1', '1', NULL, NULL, '1634808141', 'tinidazole_1634808141/1634808143_tinidazole1.png', 0),
('710a0d4b-0b0a-42e5-969b-e9d267adbb80', 'tinidazole_1634808141', NULL, 3, '1', '1', NULL, NULL, '1634808141', 'tinidazole_1634808141/tinidazole_1634808141.png', 0),
('9400f3f0-621a-4eb8-8b07-19e7badec204', 'tinidazole', '710a0d4b-0b0a-42e5-969b-e9d267adbb80', 3, '1', '1', NULL, NULL, '1634808141', 'tinidazole_1634808141/1634808143_tinidazole2.png', 0),
('352cf80b-011d-4f98-9c58-d3f1165cc4ce', 'tinidazole', '710a0d4b-0b0a-42e5-969b-e9d267adbb80', 3, '1', '1', NULL, NULL, '1634808141', 'tinidazole_1634808141/1634808144_tinidazole3.png', 0),
('344a1004-1071-4a48-82a3-4b7eea5669b6', 'tinidazole', '710a0d4b-0b0a-42e5-969b-e9d267adbb80', 3, '1', '1', NULL, NULL, '1634808141', 'tinidazole_1634808141/1634808144_tinidazole4.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `prod_id` int(11) UNSIGNED NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `prod_slug` text,
  `prod_category` int(11) UNSIGNED DEFAULT NULL,
  `prod_system` int(11) UNSIGNED NOT NULL,
  `prod_regdate` datetime DEFAULT CURRENT_TIMESTAMP,
  `prod_updatedat` datetime DEFAULT NULL,
  `prod_deletedat` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_id`, `prod_name`, `prod_slug`, `prod_category`, `prod_system`, `prod_regdate`, `prod_updatedat`, `prod_deletedat`) VALUES
(1, 'AZUMA', 'azuma', 1, 2, '2021-06-22 16:54:49', '2021-06-22 16:54:49', NULL),
(3, 'Tinidazole', 'tinidazole', 1, 2, '2021-07-19 13:43:07', '2021-07-19 13:43:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `res_id` int(11) UNSIGNED NOT NULL,
  `res_device` varchar(50) DEFAULT NULL,
  `res_station` varchar(50) DEFAULT NULL,
  `res_action` varchar(255) DEFAULT NULL,
  `res_responce` text,
  `res_time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`res_id`, `res_device`, `res_station`, `res_action`, `res_responce`, `res_time`) VALUES
(1, 'f0a42530-9748-4a87-a543-f5348af5a9af', 'Retailer', 'End-consumer', 'Product arleady sold or removed', '2021-06-22 17:28:32'),
(2, 'f0a42530-9748-4a87-a543-f5348af5a9af', 'SubWholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-22 17:28:40'),
(3, 'f0a42530-9748-4a87-a543-f5348af5a9af', 'Retailer', 'NULL', 'The content did not created by this system', '2021-06-22 17:28:57'),
(77, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:17:15'),
(76, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:15:26'),
(75, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:15:18'),
(74, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:15:07'),
(8, 'f0a42530-9748-4a87-a543-f5348af5a9af', 'SubWholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-22 17:53:22'),
(9, 'f0a42530-9748-4a87-a543-f5348af5a9af', 'SubWholeSaler', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-06-22 17:53:47'),
(10, 'f0a42530-9748-4a87-a543-f5348af5a9af', 'Retailer', 'Receiving a product', 'The products was not sent to this station', '2021-06-22 17:54:04'),
(73, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:14:59'),
(72, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:14:38'),
(13, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'SubWholeSaler', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-06-22 18:02:23'),
(14, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'Receiving a product', 'The products was received', '2021-06-22 18:02:59'),
(71, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:14:30'),
(70, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:14:20'),
(69, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:14:12'),
(68, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:13:52'),
(19, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-30 11:55:43'),
(20, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-30 11:55:53'),
(21, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The products was received', '2021-06-30 11:56:56'),
(22, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Selling products', 'The products was sold', '2021-06-30 11:58:45'),
(23, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The products was not sent to this station', '2021-06-30 11:59:19'),
(24, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-30 12:03:09'),
(25, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Receiving a product', 'The products was received', '2021-06-30 12:03:26'),
(26, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Selling products', 'The products was sold', '2021-06-30 12:03:45'),
(27, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-06-30 12:04:44'),
(28, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'Receiving a product', 'The products was received', '2021-06-30 12:05:10'),
(29, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-30 12:06:11'),
(30, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-30 12:06:14'),
(31, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-30 12:06:21'),
(80, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-10-18 16:20:22'),
(79, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:18:11'),
(78, 'f08833de-109d-479c-af2d-e43b1432d4e4', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-10-18 16:17:28'),
(35, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-06-30 12:06:57'),
(36, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'End-consumer', 'The product was valid and removed from sell-group', '2021-06-30 12:07:05'),
(37, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'End-consumer', 'Product arleady sold or removed', '2021-06-30 12:07:25'),
(81, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'Retailer', 'NULL', 'The content did not created by this system', '2021-10-18 16:48:35'),
(39, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-07-02 13:32:34'),
(40, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-07-02 13:33:18'),
(41, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The products was received', '2021-07-02 13:33:40'),
(42, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Selling products', 'The products was sold', '2021-07-02 13:34:46'),
(43, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Selling products', 'The products was not available at this station', '2021-07-02 13:35:06'),
(44, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Receiving a product', 'The products was received', '2021-07-02 13:37:08'),
(45, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'NULL', 'The content did not created by this system', '2021-07-02 13:38:02'),
(46, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-07-02 13:38:23'),
(47, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-07-02 13:38:46'),
(48, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Receiving a product', 'The products was received', '2021-07-02 13:38:55'),
(49, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'NULL', 'The content did not created by this system', '2021-07-02 13:39:09'),
(50, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Receiving a product', 'The products was received', '2021-07-02 13:39:43'),
(51, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Receiving a product', 'The products was received', '2021-07-02 13:39:53'),
(52, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-07-04 15:59:38'),
(53, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'End-consumer', 'Assuming a counterfeit or misplaced', '2021-07-04 16:01:55'),
(54, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The products was received', '2021-07-06 12:16:01'),
(55, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'NULL', 'The content did not created by this system', '2021-07-19 22:51:40'),
(56, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'End-consumer', 'The found station was not registered to sell products to the final consumers', '2021-07-19 22:52:20'),
(57, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The products was not sent to this station', '2021-07-19 22:53:51'),
(58, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Selling products', 'The products was not available at this station', '2021-07-19 22:54:09'),
(59, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'End-consumer', 'Assuming a counterfeit or misplaced', '2021-07-20 08:39:05'),
(60, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Receiving a product', 'The products was received', '2021-07-20 09:41:56'),
(61, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Selling products', 'The products was sold', '2021-07-20 09:43:02'),
(62, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Receiving a product', 'The products was received', '2021-07-20 09:44:04'),
(63, 'f0088dfd-a172-4496-9969-711efa36e7df', 'WholeSaler', 'Selling products', 'The user is using invalid beacon or not at his place', '2021-07-20 09:44:30'),
(64, 'f0088dfd-a172-4496-9969-711efa36e7df', 'SubWholeSaler', 'Selling products', 'The products was sold', '2021-07-20 09:44:40'),
(65, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'Receiving a product', 'The products was received', '2021-07-20 09:45:25'),
(66, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'End-consumer', 'The product was valid and removed from sell-group', '2021-07-20 09:47:08'),
(67, 'f0088dfd-a172-4496-9969-711efa36e7df', 'Retailer', 'End-consumer', 'Product arleady sold or removed', '2021-07-20 09:48:36'),
(82, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'Retailer', 'End-consumer', 'Assuming a counterfeit or misplaced', '2021-10-21 09:29:47'),
(83, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'WholeSaler', 'Receiving a product', 'The products was received', '2021-10-21 10:21:00'),
(84, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'SubWholeSaler', 'Receiving a product', 'The products was not sent to this station', '2021-10-21 10:22:30'),
(85, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'Retailer', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-10-21 10:23:21'),
(86, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'WholeSaler', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-10-21 10:23:36'),
(87, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'WholeSaler', 'Receiving a product', 'The user is using invalid beacon or not at his place', '2021-10-21 10:33:37'),
(88, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'SubWholeSaler', 'NULL', 'The content did not created by this system', '2021-10-21 10:33:59'),
(89, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'WholeSaler', 'Receiving a product', 'The products was received', '2021-10-21 12:25:03'),
(90, '0f852c6a-dc82-447b-be60-2e10a36535b4', 'Retailer', 'End-consumer', 'Assuming a counterfeit or misplaced', '2021-10-21 13:00:11'),
(91, 'afa92198-8e0d-4808-861b-8ace39d4e08d', 'Retailer', 'End-consumer', 'Assuming a counterfeit or misplaced', '2021-10-22 11:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `st_id` int(11) UNSIGNED NOT NULL,
  `st_title` varchar(255) NOT NULL,
  `st_description` text,
  `st_level` int(11) UNSIGNED NOT NULL,
  `st_system` int(11) UNSIGNED NOT NULL,
  `st_beacon` varchar(50) DEFAULT NULL,
  `st_lat` varchar(50) DEFAULT NULL,
  `st_lon` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`st_id`, `st_title`, `st_description`, `st_level`, `st_system`, `st_beacon`, `st_lat`, `st_lon`) VALUES
(1, 'Manufacturer', 'Manufacturer', 1, 2, NULL, NULL, NULL),
(2, 'WholeSaler', 'WholeSaler', 2, 2, 'E9:98:49:5A:AC:E6', NULL, NULL),
(3, 'SubWholeSaler', 'SubWholeSaler', 3, 2, 'C6:7D:14:24:93:F9', NULL, NULL),
(4, 'Retailer', 'Retailer', 7, 2, 'D0:B2:7C:9E:2D:87', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `systems`
--

CREATE TABLE `systems` (
  `sy_id` int(11) UNSIGNED NOT NULL,
  `sy_title` varchar(255) NOT NULL,
  `sy_slug` varchar(255) DEFAULT NULL,
  `sy_description` varchar(255) DEFAULT NULL,
  `sy_action` int(11) DEFAULT '0',
  `sy_mg` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `systems`
--

INSERT INTO `systems` (`sy_id`, `sy_title`, `sy_slug`, `sy_description`, `sy_action`, `sy_mg`) VALUES
(1, 'Public Health', 'pub', 'This is a medical dsitribution system for the public health.', 1, NULL),
(2, 'Private Health', 'prv', 'This is a medical dsitribution system for the private health.', 1, NULL),
(3, 'Private Sector', 'prvs', 'This is a dsitribution system for the private sector.', 1, NULL),
(4, 'TMDA', 'tmda', 'Regulators of all medical systems.', 0, 'pub-prv');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`);

--
-- Indexes for table `consumers`
--
ALTER TABLE `consumers`
  ADD PRIMARY KEY (`consumer_id`),
  ADD UNIQUE KEY `consumer_device` (`consumer_device`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`down_id`);

--
-- Indexes for table `intermediaries`
--
ALTER TABLE `intermediaries`
  ADD PRIMARY KEY (`int_id`),
  ADD UNIQUE KEY `int_mail` (`int_mail`),
  ADD UNIQUE KEY `int_device` (`int_device`),
  ADD KEY `intermediaries_int_station_foreign` (`int_station`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`lv_id`),
  ADD UNIQUE KEY `lv_title` (`lv_title`),
  ADD UNIQUE KEY `lv_int` (`lv_int`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`parc_id`),
  ADD UNIQUE KEY `parc_id` (`parc_id`),
  ADD KEY `parcels_parc_parent_foreign` (`parc_parent`),
  ADD KEY `parcels_parc_product_foreign` (`parc_product`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prod_id`),
  ADD KEY `products_prod_category_foreign` (`prod_category`),
  ADD KEY `products_prod_system_foreign` (`prod_system`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`res_id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`st_id`),
  ADD KEY `stations_st_level_foreign` (`st_level`),
  ADD KEY `stations_st_system_foreign` (`st_system`);

--
-- Indexes for table `systems`
--
ALTER TABLE `systems`
  ADD PRIMARY KEY (`sy_id`),
  ADD UNIQUE KEY `sy_title` (`sy_title`),
  ADD UNIQUE KEY `sy_slug` (`sy_slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `consumers`
--
ALTER TABLE `consumers`
  MODIFY `consumer_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `down_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `intermediaries`
--
ALTER TABLE `intermediaries`
  MODIFY `int_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `lv_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prod_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `res_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `st_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `systems`
--
ALTER TABLE `systems`
  MODIFY `sy_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
