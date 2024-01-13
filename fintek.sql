-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2024 at 04:01 PM
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
-- Database: `fintek`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblagency`
--

CREATE TABLE `tblagency` (
  `AgencyId` int(11) NOT NULL,
  `AgencyName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblagency`
--

INSERT INTO `tblagency` (`AgencyId`, `AgencyName`) VALUES
(2, 'Happy Faces Agency'),
(1, 'Tekhnologia Agency');

-- --------------------------------------------------------

--
-- Table structure for table `tblauction`
--

CREATE TABLE `tblauction` (
  `AuctionId` int(11) NOT NULL,
  `GroupId` int(11) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `AmountAuctioned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblauction`
--

INSERT INTO `tblauction` (`AuctionId`, `GroupId`, `StartDate`, `EndDate`, `AmountAuctioned`) VALUES
(1, 2, '2023-10-25', '2023-11-09', 35000),
(2, 2, '2023-11-11', '2023-11-20', 50000),
(3, 1, '2023-11-11', '2023-11-20', 50000);

-- --------------------------------------------------------

--
-- Table structure for table `tblbids`
--

CREATE TABLE `tblbids` (
  `BidId` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `RequestedAmount` int(11) DEFAULT NULL,
  `Reason` text DEFAULT NULL,
  `BidDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbids`
--

INSERT INTO `tblbids` (`BidId`, `UserId`, `RequestedAmount`, `Reason`, `BidDate`) VALUES
(1, 1, 30000, 'Education', '2024-01-10 15:01:03'),
(2, 2, 20000, 'Surgery', '2024-01-10 15:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `tblgroup`
--

CREATE TABLE `tblgroup` (
  `GroupId` int(11) NOT NULL,
  `GroupName` varchar(50) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `Amount` int(11) DEFAULT NULL,
  `AgencyPercentage` decimal(5,2) DEFAULT NULL,
  `AgencyId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblgroup`
--

INSERT INTO `tblgroup` (`GroupId`, `GroupName`, `StartDate`, `Amount`, `AgencyPercentage`, `AgencyId`) VALUES
(1, 'UI Designers', '2023-01-01', 100000, 5.00, 1),
(2, 'Developers', '2023-09-08', 450000, 6.00, 1),
(3, 'Teachers', '2024-02-01', 350000, 7.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbltransaction`
--

CREATE TABLE `tbltransaction` (
  `TransactionId` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `AmountPaid` int(11) DEFAULT NULL,
  `TransactionTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltransaction`
--

INSERT INTO `tbltransaction` (`TransactionId`, `UserId`, `AmountPaid`, `TransactionTime`) VALUES
(1, 1, 20000, '2024-01-10 14:50:10'),
(2, 2, 20000, '2024-01-10 14:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `UserId` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserTypeId` int(11) DEFAULT NULL,
  `GroupId` int(11) DEFAULT NULL,
  `AgencyId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`UserId`, `UserName`, `Password`, `UserTypeId`, `GroupId`, `AgencyId`) VALUES
(1, 'Hritwika', 'hrit123', NULL, 2, 1),
(2, 'Sakshi', 'sak123', NULL, 1, 1),
(3, 'Admin', 'admin', 2, NULL, 1),
(4, 'SuperAdmin', 'superadmin', NULL, NULL, NULL),
(5, 'Hritwika', 'hrit123', NULL, 2, 1),
(6, 'Sakshi', 'sak123', NULL, 1, 1),
(7, 'Admin', 'admin', 2, NULL, 1),
(8, 'SuperAdmin', 'superadmin', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblusergroup`
--

CREATE TABLE `tblusergroup` (
  `UserGroupId` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `GroupId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusergroup`
--

INSERT INTO `tblusergroup` (`UserGroupId`, `UserId`, `GroupId`) VALUES
(1, 2, 1),
(2, 6, 1),
(3, 1, 2),
(4, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tblusertype`
--

CREATE TABLE `tblusertype` (
  `UserTypeId` int(11) NOT NULL,
  `UserType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusertype`
--

INSERT INTO `tblusertype` (`UserTypeId`, `UserType`) VALUES
(2, 'Admin'),
(3, 'Agency'),
(4, 'Group'),
(1, 'Super Admin'),
(5, 'Users');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblagency`
--
ALTER TABLE `tblagency`
  ADD PRIMARY KEY (`AgencyId`),
  ADD UNIQUE KEY `AgencyName` (`AgencyName`);

--
-- Indexes for table `tblauction`
--
ALTER TABLE `tblauction`
  ADD PRIMARY KEY (`AuctionId`),
  ADD KEY `GroupId` (`GroupId`);

--
-- Indexes for table `tblbids`
--
ALTER TABLE `tblbids`
  ADD PRIMARY KEY (`BidId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `tblgroup`
--
ALTER TABLE `tblgroup`
  ADD PRIMARY KEY (`GroupId`),
  ADD UNIQUE KEY `GroupName` (`GroupName`),
  ADD KEY `AgencyId` (`AgencyId`);

--
-- Indexes for table `tbltransaction`
--
ALTER TABLE `tbltransaction`
  ADD PRIMARY KEY (`TransactionId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`UserId`),
  ADD KEY `UserTypeId` (`UserTypeId`),
  ADD KEY `GroupId` (`GroupId`),
  ADD KEY `AgencyId` (`AgencyId`);

--
-- Indexes for table `tblusergroup`
--
ALTER TABLE `tblusergroup`
  ADD PRIMARY KEY (`UserGroupId`);

--
-- Indexes for table `tblusertype`
--
ALTER TABLE `tblusertype`
  ADD PRIMARY KEY (`UserTypeId`),
  ADD UNIQUE KEY `UserType` (`UserType`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblagency`
--
ALTER TABLE `tblagency`
  MODIFY `AgencyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblauction`
--
ALTER TABLE `tblauction`
  MODIFY `AuctionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblbids`
--
ALTER TABLE `tblbids`
  MODIFY `BidId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblgroup`
--
ALTER TABLE `tblgroup`
  MODIFY `GroupId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbltransaction`
--
ALTER TABLE `tbltransaction`
  MODIFY `TransactionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblusergroup`
--
ALTER TABLE `tblusergroup`
  MODIFY `UserGroupId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblusertype`
--
ALTER TABLE `tblusertype`
  MODIFY `UserTypeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblauction`
--
ALTER TABLE `tblauction`
  ADD CONSTRAINT `tblauction_ibfk_1` FOREIGN KEY (`GroupId`) REFERENCES `tblgroup` (`GroupId`);

--
-- Constraints for table `tblbids`
--
ALTER TABLE `tblbids`
  ADD CONSTRAINT `tblbids_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `tbluser` (`UserId`);

--
-- Constraints for table `tblgroup`
--
ALTER TABLE `tblgroup`
  ADD CONSTRAINT `tblgroup_ibfk_1` FOREIGN KEY (`AgencyId`) REFERENCES `tblagency` (`AgencyId`);

--
-- Constraints for table `tbltransaction`
--
ALTER TABLE `tbltransaction`
  ADD CONSTRAINT `tbltransaction_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `tbluser` (`UserId`);

--
-- Constraints for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD CONSTRAINT `tbluser_ibfk_1` FOREIGN KEY (`UserTypeId`) REFERENCES `tblusertype` (`UserTypeId`),
  ADD CONSTRAINT `tbluser_ibfk_2` FOREIGN KEY (`GroupId`) REFERENCES `tblgroup` (`GroupId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbluser_ibfk_3` FOREIGN KEY (`AgencyId`) REFERENCES `tblagency` (`AgencyId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
