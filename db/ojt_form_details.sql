-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 21, 2026 at 08:34 AM
-- Server version: 5.7.12
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tnrmssks25_sksucampman`
--

-- --------------------------------------------------------

--
-- Table structure for table `ojt_form_details`
--

CREATE TABLE `ojt_form_details` (
  `ojt_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `citizenship` varchar(100) DEFAULT NULL,
  `contactno` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `dialect` varchar(100) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `datestart` date DEFAULT NULL,
  `ojthours` int(11) DEFAULT '240',
  `father` varchar(150) DEFAULT NULL,
  `fatheroccupation` varchar(150) DEFAULT NULL,
  `fatheraddress` text,
  `mother` varchar(150) DEFAULT NULL,
  `motheroccupation` varchar(150) DEFAULT NULL,
  `motheraddress` text,
  `guardian` varchar(150) DEFAULT NULL,
  `guardianaddress` text,
  `agency` varchar(255) DEFAULT NULL,
  `representative` varchar(150) DEFAULT NULL,
  `agencycontact` varchar(100) DEFAULT NULL,
  `rep_position` varchar(150) DEFAULT NULL,
  `agencyaddress1` varchar(255) DEFAULT NULL,
  `agencyaddress2` varchar(255) DEFAULT NULL,
  `agencyaddress3` varchar(255) DEFAULT NULL,
  `agencyaddress4` varchar(255) DEFAULT NULL,
  `agencyaddress5` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ojt_form_details`
--

INSERT INTO `ojt_form_details` (`ojt_id`, `user_id`, `lastname`, `firstname`, `middlename`, `municipality`, `province`, `dob`, `birthplace`, `height`, `weight`, `religion`, `marital_status`, `gender`, `citizenship`, `contactno`, `email`, `dialect`, `course`, `major`, `datestart`, `ojthours`, `father`, `fatheroccupation`, `fatheraddress`, `mother`, `motheroccupation`, `motheraddress`, `guardian`, `guardianaddress`, `agency`, `representative`, `agencycontact`, `rep_position`, `agencyaddress1`, `agencyaddress2`, `agencyaddress3`, `agencyaddress4`, `agencyaddress5`, `created_at`, `updated_at`) VALUES
(14, NULL, 'CATAJAY', 'LENMAR', 'asd', 'asda', 'Sultan Kudarat', '2000-05-24', 'asd', '1.00', '1.00', 'asd', 'Single', 'Male', 'asd', '09444555668', 'lenmarcatajay@sksu.edu.ph', 'asd', 'Bachelor of Science in Civil Engineering', 'Structural Engineering', NULL, 240, 'asda', 'asd', '', '', '', 'aaa', 'Vladimir K. Putin', 'wahahahaha', 'Sultan Kudarat State University', 'Engr. ROMMEL M. LAGUMEN', '098877445566', 'Campus Director', 'Isulan Campus', 'Kalawag II, Isulan, Sultan Kudarat', '', '', '', '2026-05-14 11:52:10', '2026-05-21 05:19:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ojt_form_details`
--
ALTER TABLE `ojt_form_details`
  ADD PRIMARY KEY (`ojt_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ojt_form_details`
--
ALTER TABLE `ojt_form_details`
  MODIFY `ojt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
