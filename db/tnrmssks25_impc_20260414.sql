-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2026 at 01:19 AM
-- Server version: 10.3.39-MariaDB
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tnrmssks25_impc`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL,
  `activity_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `activity_type`, `description`, `reference_id`, `created_by`, `created_at`) VALUES
(1, 'user_login', 'ltcatajay@sksu.edu.ph', 0, 14, '2026-04-03 10:59:21'),
(2, 'add_user', 'Member user was added', 0, 1, '2026-04-03 11:30:28'),
(3, 'add_user', 'Member user juandelacruz@sksu.edu.ph was added', 0, 1, '2026-04-03 11:42:15'),
(4, 'add_user', 'Member user analopez@sksu.edu.ph was added', 0, 1, '2026-04-03 11:44:45'),
(5, 'delete_user', 'Member user analopez@sksu.edu.ph was deleted', 18, 1, '2026-04-03 11:44:57'),
(6, 'user_login', 'lenmarcatajay@sksu.edu.ph', 1, 14, '2026-04-10 16:03:08'),
(7, 'loan_created', 'New loan created', 1, 14, '2026-04-10 16:04:15'),
(8, 'add_cbu', 'CBU transaction added', 5, 1, '2026-04-10 16:28:32'),
(9, 'loan_created', 'New loan created', 1, 14, '2026-04-10 17:09:36'),
(10, 'loan_created', 'New loan created', 2, 14, '2026-04-10 17:10:56'),
(11, 'loan_created', 'New loan created', 2, 14, '2026-04-10 17:42:46'),
(12, 'loan_created', 'New loan created', 2, 14, '2026-04-10 17:48:15'),
(13, 'loan_created', 'New loan created', 2, 14, '2026-04-10 17:56:04'),
(14, 'loan_created', 'New loan created', 1, 14, '2026-04-10 18:17:23'),
(15, 'vote_cast', 'Member submitted votes', 5, 14, '2026-04-10 19:15:21'),
(16, 'vote_cast', 'Member submitted votes', 5, 14, '2026-04-10 19:15:21'),
(17, 'vote_cast', 'Member submitted votes', 5, 14, '2026-04-10 19:15:21'),
(18, 'vote_cast', 'Member submitted votes', 5, 14, '2026-04-10 19:16:21'),
(19, 'vote_cast', 'Member submitted votes', 5, 14, '2026-04-10 19:16:21'),
(20, 'vote_cast', 'Member submitted votes', 5, 14, '2026-04-10 19:16:21'),
(21, 'vote_cast', 'Member submitted votes', 5, 14, '2026-04-10 19:24:19'),
(22, 'loan_created', 'New loan created', 2, 14, '2026-04-11 11:11:03'),
(23, 'loan_created', 'New loan created', 1, 14, '2026-04-11 11:23:13'),
(24, 'loan_created', 'New loan created', 1, 14, '2026-04-11 11:26:40'),
(25, 'loan_created', 'New loan created', 1, 14, '2026-04-11 11:28:04'),
(26, 'loan_created', 'New loan created', 1, 14, '2026-04-11 11:28:54'),
(27, 'loan_approved', 'Loan approval processed', 25, 14, '2026-04-11 13:28:32'),
(28, 'loan_approved', 'Loan approval processed', 25, 14, '2026-04-11 14:00:17'),
(29, 'add_user', 'Member user engineering@sksu.edu.ph was added', 0, 1, '2026-04-11 14:44:25'),
(30, 'loan_approved', 'Loan approval processed', 27, 14, '2026-04-11 15:13:02'),
(31, 'loan_approved', 'Loan approval processed', 27, 14, '2026-04-11 15:13:44'),
(32, 'loan_approved', 'Loan approval processed', 27, 14, '2026-04-11 15:17:53'),
(33, 'loan_approved', 'Loan approval processed', 27, 19, '2026-04-11 15:38:17'),
(34, 'loan_approved', 'Loan approval processed', 27, 19, '2026-04-11 15:39:30'),
(35, 'loan_approved', 'Loan approval processed', 27, 19, '2026-04-11 15:40:29'),
(36, 'loan_approved', 'Loan approval processed', 27, 14, '2026-04-11 15:54:09'),
(37, 'loan_approved', 'Loan approval processed', 27, 19, '2026-04-11 15:54:49'),
(38, 'user_login', 'engineering@sksu.edu.ph', 19, 14, '2026-04-11 09:35:55'),
(39, 'add_user', 'Member user meilaflorpaclibar@sksu.edu.ph was added', 0, 1, '2026-04-11 09:37:01'),
(40, 'loan_approved', 'Loan approval processed', 30, 20, '2026-04-11 10:21:18'),
(41, 'loan_approved', 'Loan approval processed', 30, 14, '2026-04-14 00:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `candidate_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`candidate_id`, `election_id`, `position_id`, `member_id`, `photo`, `status`, `created_at`) VALUES
(1, 2, 2, 2, NULL, 'active', '2026-03-29 07:18:15'),
(3, 1, 1, 1, NULL, 'active', '2026-03-29 07:32:19'),
(4, 2, 1, 4, NULL, 'active', '2026-03-29 11:20:41'),
(5, 2, 1, 1, NULL, 'active', '2026-03-29 11:57:31'),
(18, 2, 1, 97, NULL, 'active', '2026-04-10 11:12:37'),
(19, 2, 1, 98, NULL, 'active', '2026-04-10 11:12:37'),
(20, 2, 1, 99, NULL, 'active', '2026-04-10 11:12:37'),
(21, 2, 1, 100, NULL, 'active', '2026-04-10 11:12:37'),
(22, 2, 1, 101, NULL, 'active', '2026-04-10 11:12:37'),
(23, 2, 1, 102, NULL, 'active', '2026-04-10 11:12:37'),
(24, 2, 1, 103, NULL, 'active', '2026-04-10 11:12:37'),
(25, 2, 1, 104, NULL, 'active', '2026-04-10 11:12:37'),
(26, 2, 1, 105, NULL, 'active', '2026-04-10 11:12:37'),
(27, 2, 1, 106, NULL, 'active', '2026-04-10 11:12:37'),
(28, 2, 2, 87, NULL, 'active', '2026-04-10 11:13:01'),
(29, 2, 2, 88, NULL, 'active', '2026-04-10 11:13:01'),
(30, 2, 2, 89, NULL, 'active', '2026-04-10 11:13:01'),
(31, 2, 2, 90, NULL, 'active', '2026-04-10 11:13:01'),
(32, 2, 2, 91, NULL, 'active', '2026-04-10 11:13:01'),
(33, 2, 2, 92, NULL, 'active', '2026-04-10 11:13:01'),
(34, 2, 2, 93, NULL, 'active', '2026-04-10 11:13:01'),
(35, 2, 2, 94, NULL, 'active', '2026-04-10 11:13:01'),
(36, 2, 2, 95, NULL, 'active', '2026-04-10 11:13:01'),
(37, 2, 2, 96, NULL, 'active', '2026-04-10 11:13:01'),
(38, 2, 3, 77, NULL, 'active', '2026-04-10 11:13:01'),
(39, 2, 3, 78, NULL, 'active', '2026-04-10 11:13:01'),
(40, 2, 3, 79, NULL, 'active', '2026-04-10 11:13:01'),
(41, 2, 3, 80, NULL, 'active', '2026-04-10 11:13:01'),
(42, 2, 3, 81, NULL, 'active', '2026-04-10 11:13:01'),
(43, 2, 3, 82, NULL, 'active', '2026-04-10 11:13:01'),
(44, 2, 3, 83, NULL, 'active', '2026-04-10 11:13:01'),
(45, 2, 3, 84, NULL, 'active', '2026-04-10 11:13:01'),
(46, 2, 3, 85, NULL, 'active', '2026-04-10 11:13:01'),
(47, 2, 3, 86, NULL, 'active', '2026-04-10 11:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `election_id` int(11) NOT NULL,
  `election_year` year(4) NOT NULL,
  `election_name` varchar(150) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Open','Close') NOT NULL DEFAULT 'Close',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`election_id`, `election_year`, `election_name`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, '2024', 'IMPC Election 2024', '2024-03-01', '2024-03-05', 'Close', '2026-03-29 04:48:08'),
(2, '2025', 'IMPC Election 2025', '2025-03-01', '2025-03-05', 'Close', '2026-03-29 04:48:08');

-- --------------------------------------------------------

--
-- Table structure for table `loan_approvals`
--

CREATE TABLE `loan_approvals` (
  `approval_id` int(11) NOT NULL,
  `member_loan_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `sequence_order` int(11) NOT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_approvals`
--

INSERT INTO `loan_approvals` (`approval_id`, `member_loan_id`, `position_id`, `sequence_order`, `approver_id`, `approval_date`, `status`, `remarks`, `created_at`) VALUES
(1, 30, 1, 1, 107, NULL, 'Pending', NULL, '2026-04-11 05:50:17'),
(2, 30, 4, 2, 5, '2026-04-14 00:11:54', 'Approved', NULL, '2026-04-11 05:50:17'),
(3, 30, 4, 2, 3, NULL, 'Pending', NULL, '2026-04-11 05:50:17'),
(4, 30, 4, 2, 108, '2026-04-11 10:21:18', 'Approved', NULL, '2026-04-11 05:50:17');

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `payment_id` int(11) NOT NULL,
  `member_loan_id` int(11) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` decimal(12,2) DEFAULT NULL,
  `payment_type` enum('salary','otc') DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `loan_type_id` int(11) NOT NULL,
  `loan_type_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `max_loan_amount` int(11) NOT NULL,
  `max_month_duration` int(11) NOT NULL,
  `service_fee` int(11) NOT NULL,
  `insurance_fee` enum('Yes','No') DEFAULT 'No',
  `notary_fee` int(11) NOT NULL,
  `cbu_percentage` decimal(5,2) DEFAULT 2.00,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`loan_type_id`, `loan_type_name`, `description`, `max_loan_amount`, `max_month_duration`, `service_fee`, `insurance_fee`, `notary_fee`, `cbu_percentage`, `status`) VALUES
(1, 'STL', 'Short Term Loan', 15000, 12, 300, 'No', 0, 0.00, 'active'),
(2, 'REGULAR', 'Regular Loan', 200000, 60, 300, 'Yes', 200, 2.00, 'active'),
(3, 'COMMODITY', 'Commodity Loan', 5000, 6, 300, 'No', 0, 0.00, 'active'),
(5, 'test', 'asd', 0, 0, 0, 'No', 0, 2.00, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `loan_type_details`
--

CREATE TABLE `loan_type_details` (
  `loan_type_detail_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `has_term_years` varchar(3) DEFAULT NULL,
  `has_mode_of_payment` varchar(3) DEFAULT NULL,
  `has_purpose` varchar(3) DEFAULT NULL,
  `has_standing_balance` varchar(3) DEFAULT NULL,
  `has_previous_nthp` varchar(3) DEFAULT NULL,
  `has_amortization` varchar(3) DEFAULT NULL,
  `has_notarial_fee` varchar(3) DEFAULT NULL,
  `has_insurance_fee` varchar(3) DEFAULT NULL,
  `has_service_fee` varchar(3) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_type_details`
--

INSERT INTO `loan_type_details` (`loan_type_detail_id`, `loan_type_id`, `has_term_years`, `has_mode_of_payment`, `has_purpose`, `has_standing_balance`, `has_previous_nthp`, `has_amortization`, `has_notarial_fee`, `has_insurance_fee`, `has_service_fee`, `status`) VALUES
(1, 1, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'active'),
(2, 2, 'Yes', 'No', NULL, 'No', 'No', 'No', 'No', 'No', 'No', 'active'),
(3, 3, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `loan_type_signatories`
--

CREATE TABLE `loan_type_signatories` (
  `loan_type_signatory_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `sequence_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_type_signatories`
--

INSERT INTO `loan_type_signatories` (`loan_type_signatory_id`, `loan_type_id`, `position_id`, `sequence_order`) VALUES
(1, 1, 1, 1),
(2, 1, 4, 2),
(3, 3, 1, 1),
(4, 2, 1, 1),
(5, 2, 4, 2),
(7, 3, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('Active','Separated','Retired','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `full_name`, `email`, `contact_no`, `address`, `status`, `created_at`) VALUES
(1, 'Juan Dela Cruz', 'juandelacruz@sksu.edu.ph', '09123456789', 'Tacurong City', 'Active', '2026-03-29 04:29:09'),
(2, 'Maria Santos', 'mariasantos@sksu.edu.ph', '09123456780', 'Isulan, Sultan Kudarat', 'Active', '2026-03-29 04:29:09'),
(3, 'Pedro Reyes', 'lenmarcatajay@sksu.edu.ph', '09123456781', 'Lutayan', 'Active', '2026-03-29 04:29:09'),
(4, 'Ana Lopez', 'analopez@sksu.edu.ph', '09123456782', 'Esperanza', 'Active', '2026-03-29 04:29:09'),
(5, 'Jose Ramos1', 'ltcatajay@sksu.edu.ph', '09123456783', 'Lebak', 'Active', '2026-03-29 04:29:09'),
(7, 'Member 001', 'member001@email.com', '09170000001', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(8, 'Member 002', 'member002@email.com', '09170000002', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(9, 'Member 003', 'member003@email.com', '09170000003', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(10, 'Member 004', 'member004@email.com', '09170000004', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(11, 'Member 005', 'member005@email.com', '09170000005', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(12, 'Member 006', 'member006@email.com', '09170000006', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(13, 'Member 007', 'member007@email.com', '09170000007', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(14, 'Member 008', 'member008@email.com', '09170000008', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(15, 'Member 009', 'member009@email.com', '09170000009', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(16, 'Member 010', 'member010@email.com', '09170000010', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(17, 'Member 011', 'member011@email.com', '09170000011', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(18, 'Member 012', 'member012@email.com', '09170000012', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(19, 'Member 013', 'member013@email.com', '09170000013', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(20, 'Member 014', 'member014@email.com', '09170000014', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(21, 'Member 015', 'member015@email.com', '09170000015', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(22, 'Member 016', 'member016@email.com', '09170000016', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(23, 'Member 017', 'member017@email.com', '09170000017', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(24, 'Member 018', 'member018@email.com', '09170000018', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(25, 'Member 019', 'member019@email.com', '09170000019', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(26, 'Member 020', 'member020@email.com', '09170000020', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(27, 'Member 021', 'member021@email.com', '09170000021', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(28, 'Member 022', 'member022@email.com', '09170000022', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(29, 'Member 023', 'member023@email.com', '09170000023', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(30, 'Member 024', 'member024@email.com', '09170000024', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(31, 'Member 025', 'member025@email.com', '09170000025', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(32, 'Member 026', 'member026@email.com', '09170000026', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(33, 'Member 027', 'member027@email.com', '09170000027', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(34, 'Member 028', 'member028@email.com', '09170000028', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(35, 'Member 029', 'member029@email.com', '09170000029', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(36, 'Member 030', 'member030@email.com', '09170000030', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(37, 'Member 031', 'member031@email.com', '09170000031', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(38, 'Member 032', 'member032@email.com', '09170000032', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(39, 'Member 033', 'member033@email.com', '09170000033', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(40, 'Member 034', 'member034@email.com', '09170000034', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(41, 'Member 035', 'member035@email.com', '09170000035', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(42, 'Member 036', 'member036@email.com', '09170000036', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(43, 'Member 037', 'member037@email.com', '09170000037', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(44, 'Member 038', 'member038@email.com', '09170000038', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(45, 'Member 039', 'member039@email.com', '09170000039', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(46, 'Member 040', 'member040@email.com', '09170000040', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(47, 'Member 041', 'member041@email.com', '09170000041', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(48, 'Member 042', 'member042@email.com', '09170000042', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(49, 'Member 043', 'member043@email.com', '09170000043', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(50, 'Member 044', 'member044@email.com', '09170000044', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(51, 'Member 045', 'member045@email.com', '09170000045', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(52, 'Member 046', 'member046@email.com', '09170000046', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(53, 'Member 047', 'member047@email.com', '09170000047', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(54, 'Member 048', 'member048@email.com', '09170000048', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(55, 'Member 049', 'member049@email.com', '09170000049', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(56, 'Member 050', 'member050@email.com', '09170000050', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(57, 'Member 051', 'member051@email.com', '09170000051', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(58, 'Member 052', 'member052@email.com', '09170000052', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(59, 'Member 053', 'member053@email.com', '09170000053', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(60, 'Member 054', 'member054@email.com', '09170000054', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(61, 'Member 055', 'member055@email.com', '09170000055', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(62, 'Member 056', 'member056@email.com', '09170000056', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(63, 'Member 057', 'member057@email.com', '09170000057', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(64, 'Member 058', 'member058@email.com', '09170000058', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(65, 'Member 059', 'member059@email.com', '09170000059', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(66, 'Member 060', 'member060@email.com', '09170000060', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(67, 'Member 061', 'member061@email.com', '09170000061', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(68, 'Member 062', 'member062@email.com', '09170000062', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(69, 'Member 063', 'member063@email.com', '09170000063', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(70, 'Member 064', 'member064@email.com', '09170000064', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(71, 'Member 065', 'member065@email.com', '09170000065', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(72, 'Member 066', 'member066@email.com', '09170000066', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(73, 'Member 067', 'member067@email.com', '09170000067', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(74, 'Member 068', 'member068@email.com', '09170000068', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(75, 'Member 069', 'member069@email.com', '09170000069', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(76, 'Member 070', 'member070@email.com', '09170000070', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(77, 'Member 071', 'member071@email.com', '09170000071', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(78, 'Member 072', 'member072@email.com', '09170000072', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(79, 'Member 073', 'member073@email.com', '09170000073', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(80, 'Member 074', 'member074@email.com', '09170000074', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(81, 'Member 075', 'member075@email.com', '09170000075', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(82, 'Member 076', 'member076@email.com', '09170000076', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(83, 'Member 077', 'member077@email.com', '09170000077', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(84, 'Member 078', 'member078@email.com', '09170000078', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(85, 'Member 079', 'member079@email.com', '09170000079', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(86, 'Member 080', 'member080@email.com', '09170000080', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(87, 'Member 081', 'member081@email.com', '09170000081', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(88, 'Member 082', 'member082@email.com', '09170000082', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(89, 'Member 083', 'member083@email.com', '09170000083', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(90, 'Member 084', 'member084@email.com', '09170000084', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(91, 'Member 085', 'member085@email.com', '09170000085', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(92, 'Member 086', 'member086@email.com', '09170000086', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(93, 'Member 087', 'member087@email.com', '09170000087', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(94, 'Member 088', 'member088@email.com', '09170000088', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(95, 'Member 089', 'member089@email.com', '09170000089', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(96, 'Member 090', 'member090@email.com', '09170000090', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(97, 'Member 091', 'member091@email.com', '09170000091', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(98, 'Member 092', 'member092@email.com', '09170000092', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(99, 'Member 093', 'member093@email.com', '09170000093', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(100, 'Member 094', 'member094@email.com', '09170000094', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(101, 'Member 095', 'member095@email.com', '09170000095', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(102, 'Member 096', 'member096@email.com', '09170000096', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(103, 'Member 097', 'member097@email.com', '09170000097', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(104, 'Member 098', 'member098@email.com', '09170000098', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(105, 'Member 099', 'member099@email.com', '09170000099', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(106, 'Member 100', 'member100@email.com', '09170000100', 'Tacurong City', 'Active', '2026-04-10 11:02:09'),
(107, 'test101', 'engineering@sksu.edu.ph', '123123123123', 'asdasd', 'Active', '2026-04-11 02:50:42'),
(108, 'Meilaflor Paclibar', 'meilaflorpaclibar@sksu.edu.ph', '', 'Tacurong City', 'Active', '2026-04-11 09:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `member_cbu`
--

CREATE TABLE `member_cbu` (
  `member_cbu_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `transaction_type` enum('Deposit','Loan Deduction','Adjustment') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('Active','Void') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_cbu`
--

INSERT INTO `member_cbu` (`member_cbu_id`, `member_id`, `transaction_date`, `amount`, `transaction_type`, `reference_id`, `remarks`, `created_at`, `status`) VALUES
(1, 4, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(2, 107, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(3, 1, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(4, 3, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(5, 5, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(6, 2, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(7, 7, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(8, 8, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(9, 9, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(10, 10, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(11, 11, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(12, 12, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(13, 13, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(14, 14, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(15, 15, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(16, 16, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(17, 17, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(18, 18, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(19, 19, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(20, 20, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(21, 21, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(22, 22, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(23, 23, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(24, 24, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(25, 25, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(26, 26, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(27, 27, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(28, 28, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(29, 29, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(30, 30, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(31, 31, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(32, 32, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(33, 33, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(34, 34, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(35, 35, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(36, 36, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(37, 37, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(38, 38, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(39, 39, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(40, 40, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(41, 41, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(42, 42, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(43, 43, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(44, 44, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(45, 45, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(46, 46, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(47, 47, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(48, 48, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(49, 49, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(50, 50, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(51, 51, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(52, 52, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(53, 53, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(54, 54, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(55, 55, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(56, 56, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(57, 57, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(58, 58, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(59, 59, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(60, 60, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(61, 61, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(62, 62, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(63, 63, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(64, 64, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(65, 65, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(66, 66, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(67, 67, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(68, 68, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(69, 69, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(70, 70, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(71, 71, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(72, 72, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(73, 73, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(74, 74, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(75, 75, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(76, 76, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(77, 77, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(78, 78, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(79, 79, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(80, 80, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(81, 81, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(82, 82, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(83, 83, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(84, 84, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(85, 85, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(86, 86, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(87, 87, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(88, 88, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(89, 89, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(90, 90, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(91, 91, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(92, 92, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(93, 93, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(94, 94, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(95, 95, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(96, 96, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(97, 97, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(98, 98, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(99, 99, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(100, 100, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(101, 101, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(102, 102, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(103, 103, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(104, 104, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(105, 105, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(106, 106, '2026-04-11', 3000.00, 'Deposit', 1, 'Initial capital build-up', '2026-04-11 16:41:04', 'Active'),
(128, 5, '2026-04-11', 0.00, 'Loan Deduction', 1, 'Loan Deduction', '2026-04-11 05:50:17', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `member_loans`
--

CREATE TABLE `member_loans` (
  `member_loan_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `application_date` date DEFAULT NULL,
  `mode_of_payment` enum('Salary','OTC') DEFAULT NULL,
  `loan_term_months` int(11) NOT NULL,
  `loan_term_years` int(11) NOT NULL,
  `loan_purpose` varchar(255) DEFAULT NULL,
  `amount_applied` decimal(12,2) DEFAULT NULL,
  `amount_granted` decimal(12,2) DEFAULT NULL,
  `capital_build_up` decimal(12,2) DEFAULT NULL,
  `service_fee` decimal(12,2) DEFAULT NULL,
  `insurance_fee` decimal(12,2) DEFAULT NULL,
  `notarial_fee` decimal(12,2) DEFAULT NULL,
  `standing_balance` decimal(12,2) DEFAULT NULL,
  `previous_nthp` decimal(12,2) DEFAULT NULL,
  `amortization` decimal(12,2) DEFAULT NULL,
  `total_deductions` decimal(12,2) DEFAULT NULL,
  `net_proceeds` decimal(12,2) DEFAULT NULL,
  `loan_status` enum('Pending Approval','Approved','Released','On-going','Paid','Disapproved') NOT NULL DEFAULT 'Pending Approval',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_loans`
--

INSERT INTO `member_loans` (`member_loan_id`, `member_id`, `loan_type_id`, `application_date`, `mode_of_payment`, `loan_term_months`, `loan_term_years`, `loan_purpose`, `amount_applied`, `amount_granted`, `capital_build_up`, `service_fee`, `insurance_fee`, `notarial_fee`, `standing_balance`, `previous_nthp`, `amortization`, `total_deductions`, `net_proceeds`, `loan_status`, `remarks`, `created_at`) VALUES
(30, 5, 1, '2026-04-11', 'Salary', 12, 0, 'Test', 10000.00, NULL, 0.00, 300.00, 0.00, 0.00, NULL, NULL, NULL, 300.00, 9700.00, 'Pending Approval', NULL, '2026-04-11 09:50:17');

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `official_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `election_id` int(11) DEFAULT NULL,
  `appointment_type` enum('elected','appointed') NOT NULL,
  `term_start` date DEFAULT NULL,
  `term_end` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`official_id`, `member_id`, `position_id`, `election_id`, `appointment_type`, `term_start`, `term_end`, `status`) VALUES
(1, 5, 4, 1, 'appointed', '2026-04-01', '2026-06-30', 'active'),
(154, 107, 1, 1, 'elected', '2026-04-01', '2026-05-31', 'active'),
(155, 3, 4, 1, 'appointed', '2026-04-01', '2026-06-30', 'active'),
(156, 108, 4, 2, 'appointed', '2026-04-01', '2026-05-31', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(100) NOT NULL,
  `position_type` enum('Elected','Appointed','Membership') NOT NULL,
  `ordinal_no` int(11) NOT NULL,
  `max_vote` int(11) DEFAULT 1,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`position_id`, `position_name`, `position_type`, `ordinal_no`, `max_vote`, `status`, `created_at`) VALUES
(1, 'Board of Director', 'Elected', 100, 5, 'active', '2026-03-29 07:05:37'),
(2, 'Audit Committee', 'Elected', 110, 3, 'active', '2026-03-29 07:05:37'),
(3, 'Election Committee', 'Elected', 120, 3, 'active', '2026-03-29 07:05:37'),
(4, 'Credit Committee', 'Appointed', 400, 0, 'active', '2026-03-29 07:05:37'),
(5, 'Ethics Committee', 'Appointed', 500, 0, 'active', '2026-03-29 07:05:37'),
(7, 'Member', 'Membership', 1000, 0, 'active', '2026-04-11 02:19:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `google_id`, `full_name`, `profile_picture`, `category_id`, `status`, `created_at`, `last_login`) VALUES
(1, 'lenmarcatajay@sksu.edu.ph', '105027035548748984220', 'LENMAR CATAJAY', 'https://lh3.googleusercontent.com/a/ACg8ocIy59L00zCEQDsv-HbNsgWVzhllGnn_TIazH-tzh1A-EtNMXOj6=s96-c', 1, 'active', '2026-03-29 03:05:00', '2026-04-11 13:36:30'),
(14, 'ltcatajay@sksu.edu.ph', '109463789598670667469', 'Lenmar Catajay', 'https://lh3.googleusercontent.com/a/ACg8ocLQaN26XlmSbGBOy72Cm63CY0H0OaeSlk5RSObOw-1HLwOE_A=s96-c', 2, 'active', '2026-03-29 04:15:38', '2026-04-14 04:09:21'),
(15, 'mariasantos@sksu.edu.ph', NULL, NULL, NULL, 2, 'active', '2026-04-03 03:26:37', NULL),
(19, 'engineering@sksu.edu.ph', '103751038289494104185', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJGqcWJwqZRiXNgtSEIUTvJszJ7mlIwPd7xV9uoiHLjhx5ir0U=s96-c', 2, 'active', '2026-04-11 06:44:25', '2026-04-11 13:35:55'),
(20, 'meilaflorpaclibar@sksu.edu.ph', '107274563455319842258', 'MEILAFLOR PACLIBAR', 'https://lh3.googleusercontent.com/a/ACg8ocJXNVxrW6h5VeuIMjYtdvLmmxvuoVWkkiT4ZaNiTs7O5wA0OXM=s96-c', 2, 'active', '2026-04-11 13:37:01', '2026-04-11 14:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `user_categories`
--

CREATE TABLE `user_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_categories`
--

INSERT INTO `user_categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'admin', 'System Administrator', '2026-03-29 03:01:35'),
(2, 'member', 'Member User', '2026-03-29 03:01:35'),
(3, 'bod', 'Chairman - Board of Director', '2026-03-29 03:02:14'),
(4, 'eleccom', 'Election Committee', '2026-03-29 03:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `voter_member_id` int(11) NOT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`vote_id`, `election_id`, `position_id`, `candidate_id`, `voter_member_id`, `voted_at`) VALUES
(7, 2, 1, 5, 5, '2026-04-10 11:24:19'),
(8, 2, 1, 4, 5, '2026-04-10 11:24:19'),
(9, 2, 1, 18, 5, '2026-04-10 11:24:19'),
(10, 2, 1, 19, 5, '2026-04-10 11:24:19'),
(11, 2, 1, 21, 5, '2026-04-10 11:24:19'),
(12, 2, 2, 1, 5, '2026-04-10 11:24:19'),
(13, 2, 2, 28, 5, '2026-04-10 11:24:19'),
(14, 2, 2, 30, 5, '2026-04-10 11:24:19'),
(15, 2, 3, 38, 5, '2026-04-10 11:24:19'),
(16, 2, 3, 39, 5, '2026-04-10 11:24:19'),
(17, 2, 3, 41, 5, '2026-04-10 11:24:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`candidate_id`),
  ADD UNIQUE KEY `unique_candidate` (`election_id`,`position_id`,`member_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`election_id`);

--
-- Indexes for table `loan_approvals`
--
ALTER TABLE `loan_approvals`
  ADD PRIMARY KEY (`approval_id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `member_loan_id` (`member_loan_id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`loan_type_id`);

--
-- Indexes for table `loan_type_details`
--
ALTER TABLE `loan_type_details`
  ADD PRIMARY KEY (`loan_type_detail_id`),
  ADD KEY `loan_type_id` (`loan_type_id`);

--
-- Indexes for table `loan_type_signatories`
--
ALTER TABLE `loan_type_signatories`
  ADD PRIMARY KEY (`loan_type_signatory_id`),
  ADD KEY `loan_type_id` (`loan_type_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `member_cbu`
--
ALTER TABLE `member_cbu`
  ADD PRIMARY KEY (`member_cbu_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `member_loans`
--
ALTER TABLE `member_loans`
  ADD PRIMARY KEY (`member_loan_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `loan_type_id` (`loan_type_id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`official_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`),
  ADD UNIQUE KEY `position_name` (`position_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `user_categories`
--
ALTER TABLE `user_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `unique_vote` (`election_id`,`position_id`,`candidate_id`,`voter_member_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `voter_member_id` (`voter_member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `candidate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `election_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loan_approvals`
--
ALTER TABLE `loan_approvals`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `loan_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan_type_details`
--
ALTER TABLE `loan_type_details`
  MODIFY `loan_type_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loan_type_signatories`
--
ALTER TABLE `loan_type_signatories`
  MODIFY `loan_type_signatory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `member_cbu`
--
ALTER TABLE `member_cbu`
  MODIFY `member_cbu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `member_loans`
--
ALTER TABLE `member_loans`
  MODIFY `member_loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_categories`
--
ALTER TABLE `user_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`election_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidates_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidates_ibfk_3` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_ibfk_1` FOREIGN KEY (`member_loan_id`) REFERENCES `member_loans` (`member_loan_id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_type_details`
--
ALTER TABLE `loan_type_details`
  ADD CONSTRAINT `loan_type_details_ibfk_1` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`loan_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_type_signatories`
--
ALTER TABLE `loan_type_signatories`
  ADD CONSTRAINT `loan_type_signatories_ibfk_1` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`loan_type_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_type_signatories_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`);

--
-- Constraints for table `member_cbu`
--
ALTER TABLE `member_cbu`
  ADD CONSTRAINT `member_cbu_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `member_loans`
--
ALTER TABLE `member_loans`
  ADD CONSTRAINT `member_loans_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `member_loans_ibfk_2` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`loan_type_id`);

--
-- Constraints for table `officials`
--
ALTER TABLE `officials`
  ADD CONSTRAINT `officials_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `officials_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`),
  ADD CONSTRAINT `officials_ibfk_3` FOREIGN KEY (`election_id`) REFERENCES `elections` (`election_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `user_categories` (`category_id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`election_id`),
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`),
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`candidate_id`),
  ADD CONSTRAINT `votes_ibfk_4` FOREIGN KEY (`voter_member_id`) REFERENCES `members` (`member_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
