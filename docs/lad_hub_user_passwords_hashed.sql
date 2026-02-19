-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2026 at 04:47 PM
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
-- Database: `lad_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `role` varchar(20) NOT NULL,
  `prefix` varchar(20) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `suffix` varchar(10) NOT NULL,
  `middleInitial` varchar(10) NOT NULL,
  `position` varchar(100) NOT NULL,
  `agency` varchar(100) NOT NULL,
  `initials` varchar(10) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `role`, `prefix`, `firstName`, `lastName`, `suffix`, `middleInitial`, `position`, `agency`, `initials`, `username`, `password`) VALUES
(0, 'general', '', 'user', 'user', '', 'user', 'user', '19', 'USER', 'user', '$2y$12$Hy/u/SfSgphcnFY7OQt2d.jSM8T/Dj46HL/L/GHEdY.hot5NmVIyW'),
(1, 'admin', '', 'Chad Gian', 'Villanueva', '', 'G.', 'Administrative Aide III', '19', 'CGV', 'chadgian', '$2y$12$bSbLYHCHGHxEtvhj4FzCZOCedQCGg2grhV2nKD5z87/8TXPAsLHTO'),
(2, 'admin', '', 'Nicca Mae', 'Senato', '', 'B.', 'GIP - Intern', '19', 'NMBS', 'niccamae', '$2y$12$Xz/yTqpE8w1lfkpj18O4ZuvSOzzxwFpvcVXucsfcY69anYm.3iYOO'),
(11, 'general', '', 'Chad Gian', 'Villanueva', '', ' G.', 'GIP Intern', '30', NULL, 'chadgianvillanueva17@gmail.com', '$2y$12$dr2YVLn2OkCL4Bl7owJEi.XgViGFphyuxq8qDP.4eKAAU/cjdZMBG'),
(14, 'general', '', 'Nicca Mae', 'Senato', '', 'B.', 'GIP Intern', '30', NULL, 'niccamae.senato@students.isatu.edu.ph', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(16, 'general', '', 'Juan', 'Dela Cruz', ' Jr. ', ' B.', 'GIP Intern', '19', NULL, 'jdelacruz@gmail.com', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(17, 'general', '', 'John', 'Doe', '', ' B.', 'Software Engineer I', '19', NULL, 'jdoe@gmail.com', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(19, 'general', '', 'Jane', 'Doe', '', ' S.', 'Software Analyst', '19', NULL, 'janedoe@gmail.com', '$2y$12$kWWULZFC0EwW8So4PCn2bOg.NGYeWRlU85150O2MYzJTKNWo9AgD.'),
(23, 'general', '', 'Crist', 'Mauno', '', ' V.', 'GIP Intern', '19', NULL, 'arvel@gmail.com', '$2y$12$Acw8cPyjzcEmb8LOZgPEIOjxKyrpFqYUSlwSPOxVf7tezQ8CIzcLe'),
(24, 'general', ' ', 'Jon', 'Snow', '', ' S.', 'Bastard of Winterfell', '19', NULL, 'jonsnow@gmail.com', '$2y$12$LvFmZik.wzaJ1oaEnrLk0e1/AySf69c1Vwt1gwOIyjywZ/VJgQuSq'),
(25, 'general', ' ', 'Maverick', 'Caballes', '', ' V.', 'HRS', '19', NULL, 'mav@gmail.com', '$2y$12$RotRWVtNw2YG9nZ5m0U9pe5f3KamsV3sY4OdkNBItMIJBv4XueYDS'),
(26, 'general', ' ', 'jy', 'fffff', '', ' .', 'eee', '19', NULL, 'fff', '$2y$12$JCyKimdNev/GHEybxn29le/72C4Hy42dJd1ZD3ZTC3R9dyT84Yq4S'),
(27, 'payment', '', '', '', '', '', '', '19', 'FO-AKLAN', 'aklan', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(29, 'general', ' ', 'g', 'd', '', ' h.', 'cs', '29', NULL, 'de@gmail.com', '$2y$12$.rmsgNtjIbwG3RvJz7md5eS/Gshp5JdCKdO9N15jmK2VNsjKLnRXK'),
(30, 'general', 'Mx. ', 'MJ', 'P', '', ' L.', 'HRS III', '19', NULL, 'chikkiybiernas@outlook.com', '$2y$12$CN8LYyjlSy.TlmTW.PTjVuX.V6.6DBKShOX3Hjz0E4YyPiFGUWu82'),
(31, 'payment', '', '', '', '', '', '', '19', 'FO-ANTIQUE', 'antique', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(32, 'payment', '', '', '', '', '', '', '19', 'FO-CAPIZ', 'capiz', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(33, 'payment', '', '', '', '', '', '', '19', 'FO-GUIMARA', 'guimaras', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(34, 'payment', '', '', '', '', '', '', '19', 'FO-ILOILO', 'iloilo', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(35, 'payment', '', '', '', '', '', '', '19', 'FO-NEGOCC', 'negros', '$2y$12$YLiKc91tDUyMXPttaDw6TOGPchmhAY7ZMZ2.A.VVhQsdJh7NgDOT2'),
(36, 'general', 'Gov. ', 'Emily', 'Sinclair', '', '', 'Governor', '20', NULL, 'esinclair@gmail.com', '$2y$12$dr2YVLn2OkCL4Bl7owJEi.XgViGFphyuxq8qDP.4eKAAU/cjdZMBG'),
(37, 'general', ' ', 'Juan', 'Dela Cruz', '', ' B.', 'Human Resource Specialist', '19', NULL, 'juandelacruz@gmail.com', '$2y$12$ibCMEUQF2zyWaAoO2bqkYurOTyV5HBetzNBmSULtVTOnoxCgzFGye'),
(38, 'general', ' ', 'Rayvil', 'Cordero', '', '', 'Student Intern', '32', NULL, 'rave@gmail.com', '$2y$12$qJH5JVeoyZKSfg.d/DI5oeeIdUqVsXeyv8dLvqUfUp5Mmp5V6cCra'),
(39, 'payment', '', '', '', '', '', '', '19', 'MSD', 'cashier', '$2y$12$dr2YVLn2OkCL4Bl7owJEi.XgViGFphyuxq8qDP.4eKAAU/cjdZMBG'),
(40, 'admin', '', 'Christopher', 'Cang ', '', 'Z.', 'Human Resource Specialist I', '19', 'CZC', 'HR-chris', '$2y$12$dr2YVLn2OkCL4Bl7owJEi.XgViGFphyuxq8qDP.4eKAAU/cjdZMBG'),
(41, 'general', 'Atty. ', 'Arnel', 'Fernandez', '', 'B.', 'Director V', '35', NULL, 'mlpiansay@csc.gov.ph', '$2y$12$XdEsi9Kfy3W..xYAZR5BHu87/EZwCoQ1adFCiI45cEvGclCm.xMbe'),
(42, 'admin', '', 'Sheila', 'Arendain', '', 'P.', 'Supervising Human Resource Specialist', '19', 'SPA', 'sparendain', '$2y$12$dr2YVLn2OkCL4Bl7owJEi.XgViGFphyuxq8qDP.4eKAAU/cjdZMBG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `userID` (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
