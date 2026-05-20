-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2026 at 08:55 AM
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
-- Database: `impc_db`
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
(5, 'delete_user', 'Member user analopez@sksu.edu.ph was deleted', 18, 1, '2026-04-03 11:44:57');

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
(5, 2, 1, 1, NULL, 'active', '2026-03-29 11:57:31');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'admin', 'System Administrator', '2026-03-29 03:01:35'),
(2, 'member', 'Regular User', '2026-03-29 03:01:35'),
(3, 'editor', NULL, '2026-03-29 03:02:14'),
(4, 'viewer', NULL, '2026-03-29 03:02:14');

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
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`election_id`, `election_year`, `election_name`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, '2024', 'IMPC Election 2024', '2024-03-01', '2024-03-05', 'inactive', '2026-03-29 04:48:08'),
(2, '2025', 'IMPC Election 2025', '2025-03-01', '2025-03-05', 'active', '2026-03-29 04:48:08');

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
  `cbu_percentage` decimal(5,2) DEFAULT 2.00,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`loan_type_id`, `loan_type_name`, `description`, `cbu_percentage`, `status`) VALUES
(1, 'STL', 'Short Term Loan', 2.00, 'active'),
(2, 'REGULAR', 'Regular Loan', 2.00, 'active'),
(3, 'COMMODITY', 'Commodity Loan', 2.00, 'active'),
(5, 'test', 'asd', 2.00, 'active');

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
(2, 1, 2, 2),
(3, 2, 1, 1),
(4, 2, 2, 2),
(5, 2, 3, 3),
(7, 3, 2, 3);

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
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `full_name`, `email`, `contact_no`, `address`, `status`, `created_at`) VALUES
(1, 'Juan Dela Cruz', 'juandelacruz@sksu.edu.ph', '09123456789', 'Tacurong City', 'active', '2026-03-29 04:29:09'),
(2, 'Maria Santos', 'mariasantos@sksu.edu.ph', '09123456780', 'Isulan, Sultan Kudarat', 'active', '2026-03-29 04:29:09'),
(3, 'Pedro Reyes', 'lenmarcatajay@sksu.edu.ph', '09123456781', 'Lutayan', 'active', '2026-03-29 04:29:09'),
(4, 'Ana Lopez', 'analopez@sksu.edu.ph', '09123456782', 'Esperanza', 'active', '2026-03-29 04:29:09'),
(5, 'Jose Ramos1', 'ltcatajay@sksu.edu.ph', '09123456783', 'Lebak', 'active', '2026-03-29 04:29:09');

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
  `loan_term_years` int(11) DEFAULT NULL CHECK (`loan_term_years` between 1 and 5),
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
  `loan_status` enum('Pending','Approved','Returned','Disapproved','Paid') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_loans`
--

INSERT INTO `member_loans` (`member_loan_id`, `member_id`, `loan_type_id`, `application_date`, `mode_of_payment`, `loan_term_years`, `loan_purpose`, `amount_applied`, `amount_granted`, `capital_build_up`, `service_fee`, `insurance_fee`, `notarial_fee`, `standing_balance`, `previous_nthp`, `amortization`, `total_deductions`, `net_proceeds`, `loan_status`, `remarks`, `created_at`) VALUES
(2, 2, 3, '2026-04-02', 'Salary', 1, 'asd', 1.00, 1.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'asd', '2026-04-02 07:17:04'),
(3, 2, 1, '2026-04-02', 'Salary', 1, 'asd', 1.00, 1.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', '', '2026-04-02 07:21:08'),
(5, 5, 3, '0000-00-00', 'Salary', 1, '1', 1.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, '2026-04-02 11:46:47'),
(6, 5, 2, '2026-04-02', 'Salary', 1, '1', 1.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, '2026-04-02 11:48:22');

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

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(100) NOT NULL,
  `ordinal_no` int(11) NOT NULL,
  `max_vote` int(11) DEFAULT 1,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`position_id`, `position_name`, `ordinal_no`, `max_vote`, `status`, `created_at`) VALUES
(1, 'President', 1, 1, 'active', '2026-03-29 07:05:37'),
(2, 'Vice President', 2, 1, 'active', '2026-03-29 07:05:37'),
(3, 'Secretary', 3, 1, 'active', '2026-03-29 07:05:37'),
(4, 'Treasurer', 4, 1, 'active', '2026-03-29 07:05:37'),
(5, 'Auditor', 5, 1, 'active', '2026-03-29 07:05:37');

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
(1, 'lenmarcatajay@sksu.edu.ph', '105027035548748984220', 'LENMAR CATAJAY', 'https://lh3.googleusercontent.com/a/ACg8ocIy59L00zCEQDsv-HbNsgWVzhllGnn_TIazH-tzh1A-EtNMXOj6=s96-c', 1, 'active', '2026-03-29 03:05:00', '2026-04-03 01:57:51'),
(14, 'ltcatajay@sksu.edu.ph', '109463789598670667469', 'Lenmar Catajay', 'https://lh3.googleusercontent.com/a/ACg8ocLQaN26XlmSbGBOy72Cm63CY0H0OaeSlk5RSObOw-1HLwOE_A=s96-c', 2, 'active', '2026-03-29 04:15:38', '2026-04-03 02:59:21'),
(15, 'mariasantos@sksu.edu.ph', NULL, NULL, NULL, 2, 'active', '2026-04-03 03:26:37', NULL);

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
(1, 2, 1, 5, 5, '2026-04-02 12:59:19'),
(2, 2, 2, 1, 5, '2026-04-02 12:59:19');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`election_id`);

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
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `unique_vote` (`election_id`,`position_id`,`voter_member_id`),
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
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `candidate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `election_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `member_loans`
--
ALTER TABLE `member_loans`
  MODIFY `member_loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

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
