-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 04, 2025 at 07:08 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u735253013_servispace`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `full_name`, `profile_image`, `last_login`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'admin', 'admin@example.com', '$2a$12$Kcrrkh39q5AXi.H5i2wywubKN3yqSlrcKD4lGS.dm8gdUgwtkYkSm', 'System Administrator', NULL, '2025-04-02 13:00:20', 1, '2025-03-06 03:34:52', '2025-04-02 13:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) UNSIGNED NOT NULL,
  `organization_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `organization_id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(3, 2, 'Navjot Singh', 'test@gmail.com', '6478974258', '19 grand river Ave', '2025-03-06 21:38:38', '2025-03-06 21:38:38'),
(4, 2, 'Leo Jiang', 'test@gmail.com', '+16476785735', '54 BOWMAN ST, HAMILTON, ON, L8S 2T3', '2025-03-06 19:46:01', '2025-03-06 19:46:01'),
(5, 2, 'Ali Bashash', '', '+15199444300', '3936 WYANDOTTE ST E UNIT 602\r\nWINDSOR, ON, N8Y 4V1', '2025-03-22 13:39:52', '2025-03-22 13:39:52');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `support_email` varchar(100) DEFAULT NULL,
  `support_phone` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `contact_name`, `contact_email`, `contact_phone`, `address`, `support_email`, `support_phone`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Lenovo Canada', 'Mike Deo', 'mikeDirector@lenovo.com', '6587458954', ' 55 Idema Rd, Markham, ON L3R 1B1', 'TechnicalSupport@lenovo.com', '+1 800-565-3344', 'Phone Support Hours\r\nMon-Fri: 8am-9pm EST\r\n\r\nSat-Sun: 11am-8pm EST\r\n1-855-253-6686\r\nSelect option #3', 1, '2025-03-06 06:34:32', '2025-03-06 06:34:32'),
(3, 'Canada Post', 'Laura Dindo', 'laura@canada-post.com', '6478874258', '82 Dalhousie St, Brantford, ON N3T 0A0', 'L3support@canada-post.com', '2278874258', 'CANADA POST CUSTOMER SERVICE\r\nPO BOX 90022\r\n2701 RIVERSIDE DRIVE\r\nOTTAWA ON K1V 1J8', 1, '2025-03-06 06:46:33', '2025-03-06 06:46:33');

-- --------------------------------------------------------

--
-- Table structure for table `organization_documents`
--

CREATE TABLE `organization_documents` (
  `id` int(11) UNSIGNED NOT NULL,
  `organization_id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_documents`
--

INSERT INTO `organization_documents` (`id`, `organization_id`, `file_name`, `file_path`, `file_type`, `file_size`, `description`, `uploaded_by`, `created_at`) VALUES
(3, 2, 'WWTS Call Management.pdf', 'uploads/documents/1741242980_dcb77104c6d3ee22f140.pdf', 'application/pdf', 531136, 'Lenovo WWTS Documentation manual', 4, '2025-03-06 06:36:20'),
(4, 2, 'Lenovo Work Order.pdf', 'uploads/documents/1741243017_d0cd5c15aa87257dce80.pdf', 'application/pdf', 175967, 'Lenovo Work order', 4, '2025-03-06 06:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `technicians`
--

CREATE TABLE `technicians` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `tech_id` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `password_reset_token` varchar(100) DEFAULT NULL,
  `password_reset_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `technicians`
--

INSERT INTO `technicians` (`id`, `name`, `email`, `phone`, `tech_id`, `photo`, `is_active`, `created_at`, `updated_at`, `password`, `remember_token`, `last_login_at`, `password_reset_token`, `password_reset_expires_at`) VALUES
(2, 'Navjot Singh', 'navjot.singh@easydesksolution.com', '6478974258', 'INC0030-11', '', 1, '2025-03-06 06:37:59', '2025-03-09 19:13:05', '$2y$10$VWMh.PDZqwN8ijtT88OyLOOcjfl61z6Y//tQtzgMVyphpiFSS6pA6', NULL, '2025-03-09 19:13:05', NULL, NULL),
(3, 'Nikhil ShivKumar', 'nikhil@easydesksolution.com', '6478974258', 'INC0030', '', 1, '2025-03-06 20:27:26', '2025-03-06 20:27:26', '$2y$10$XZ8HGgpF4PU31B/wEtdH5eC5h7WKHEUfrO2HmsBL.kVMeYyVBlCqm', NULL, NULL, NULL, NULL),
(4, 'Hani Kumari', 'hanikumari9831@gmail.com', '', 'INC0030-00', '', 1, '2025-03-17 23:38:30', '2025-04-02 13:00:08', '$2y$10$RsGlYqm1WYVr4MJPsrGABOXsV4Xo8sJzWFXvygkZyoDzixbwLuHkq', NULL, '2025-04-02 13:00:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `organization_id` int(11) UNSIGNED NOT NULL,
  `technician_id` int(11) UNSIGNED DEFAULT NULL,
  `status` enum('open','assigned','onsite','scheduled','in_progress','partially_completed','resolved','closed','cancelled') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `appointment_notes` text DEFAULT NULL,
  `estimated_duration` int(11) DEFAULT NULL,
  `service_started_at` timestamp NULL DEFAULT NULL,
  `service_completed_at` timestamp NULL DEFAULT NULL,
  `completion_code` varchar(50) DEFAULT NULL,
  `customer_signature` longtext DEFAULT NULL,
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `title`, `description`, `organization_id`, `technician_id`, `status`, `priority`, `created_by`, `created_at`, `updated_at`, `resolved_at`, `closed_at`, `appointment_date`, `appointment_time`, `appointment_notes`, `estimated_duration`, `service_started_at`, `service_completed_at`, `completion_code`, `customer_signature`, `customer_id`, `assigned_at`) VALUES
(2, 'New WWTS LENOAMCA PREM-TK Incident: 377952 / WZ03031669 - Dundas, ON', 'PTG Incident#: 377952\r\nCustomer Ref#: WZ03031669\r\nLenovo Ref#: 4017088725\r\nModel#: 21KV001CUS\r\nSerial#: PW0DZLYY\r\n\r\nSite Address:\r\nCentral Technology Services Corp\r\n26 Renata Ct\r\nEnter Address\r\nDundas, ON, L9H 6X2\r\n\r\nSite Contact:\r\nBryce Anger\r\n+19057493900\r\n\r\nPart ETA: 335291887511\r\n\r\nPart#: 5B21P78831 - BDPLANAR WINUl9185HLMCCDISyAdTyABEI\r\nParts to Be Returned:335291887636\r\n\r\nScope of Work:\r\nVerification Description-- no power\r\n', 2, 2, 'in_progress', 'high', 4, '2025-03-06 06:39:15', '2025-03-07 02:06:07', NULL, NULL, '2025-03-22', '09:00:00', 'test', 60, '2025-03-07 02:06:07', NULL, NULL, NULL, NULL, NULL),
(3, 'New Canada Post Incident: 378039 / 8795442 - ANCASTER, ON', 'It is necessary that all files and documents attached to this email be sent to technician and reviewed before going onsite. Any additional time onsite due to tech not being prepared is not billable.\r\n\r\nPTG Incident#: 378039\r\nPulse#: 8795442\r\nStore/RC# 438812\r\n\r\nRequired Tech ETA: ASAP\r\n\r\nMust be completed by: 3/7 EOD\r\n\r\nSite Address:\r\nWC #438812 MEADOWLANDS PO\r\nMEADOWLANDS PO\r\n27 LEGEND CT\r\nANCASTER, ON, L9K 1J0\r\n\r\nSite Contact:\r\n905-648-6747\r\n\r\nSite Hrs :\r\nMon - Fri: 9:00 - 17:00 EST, Weekends off\r\n\r\nPart #: R1-3PZ35A\r\nPart ETA: onsite \r\n\r\nScope of Work: REP FOR LASER PRINTER+CBL\r\n\r\nAdditional Scope:\r\nIf replacing a HP 2055 – the printer will appear to install but you still have to wait for it to fully load drivers, once set up in windows power off and disconnect the power cord from the printer then reconnect then try test print, the 2055 often freezes up when installing.\r\n\r\nIf replacing with a HP 40040 – Make sure the printer USB is enabled, from the home screen on the printer navigate to settings icon, select settings then general and Enable Device USB then select Enable.\r\n\r\nWhen installing or configuring the Report Printer some additional steps to set up the printer to print money orders. The setting in the tech account are not saving over to the CPC user account.\r\nThe solution once the settings are correct under the tech login then reboot and request that the clerk at the site log in with the CPC user account. The tech will need to call support so they can remote in and change the double sided printing to NO and make sure its set to default from the clerks account.', 3, 3, 'assigned', 'high', 4, '2025-03-06 06:47:39', '2025-03-06 20:31:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'New WWTS LENOAMCA Idea-TK Incident: 377649 / WZ02270781 - HAMILTON, ON', 'PTG Incident#: 377649\r\nCustomer Ref#: WZ02270781\r\nLenovo Ref#: 4017033913\r\nModel#: 82UG0000US\r\nSerial#: PF3XF5J9\r\n\r\nSite Address:\r\nLeo Jiang\r\n54 BOWMAN ST\r\nEnter Address\r\nHAMILTON, ON, L8S 2T3\r\n\r\nSite Contact:\r\nLeo Jiang\r\n+16476785735\r\n\r\nPart ETA: 335286277892\r\n\r\nPart#: 5SS0Z46561 - SSD SAM PM9A1 512G 2280 PCIe G4P x4 - 335286278015\r\n5B21J12073 - BDPLANAR MBL82UGWINR76800HRX6600S4G - 335286278189\r\n\r\nScope of Work:\r\nDescription NO BOOT\r\n', 2, 2, 'assigned', 'medium', 4, '2025-03-06 19:46:01', '2025-04-02 13:28:33', NULL, NULL, '2025-03-07', '09:00:00', 'note for customer', 60, NULL, NULL, NULL, NULL, NULL, '2025-03-06 19:46:01'),
(14, 'New WWTS LENOAMCA Premier Incident: 352403 / WY03220927 - WINDSOR, ON', 'PTG Incident#: 352403\r\nCustomer Ref#: WY03220927\r\nLenovo Ref#: 4012421923\r\nModel#: 20VE006UUS\r\nSerial#: MP26GL63\r\nSite Address:\r\nAli Bashash\r\n3936 WYANDOTTE ST E UNIT 602\r\n\r\nWINDSOR, ON, N8Y 4V1\r\n\r\nSite Contact:\r\nAli Bashash\r\n+15199444300\r\n\r\nPart ETA: 334622517897 Delivered\r\n\r\nPart#:\r\n5D10V82421 - DISPLAY BOE 15.6 FHD IPS AG\r\n5T10S33177 - TAPE Removable Tape C 20VG R&L\r\n5CB1B34808 - COVER LCD Cover C 20VG MG 3.2t\r\n\r\nParts to Be Returned: Yes\r\n5D10V82421 - DISPLAY BOE 15.6 FHD IPS AG / 334622518093\r\n\r\n•Tech must confirm waybill sent for all Lenovo calls matches waybill listed in the call. If it does not or the waybill is missing the tech must email wwts.service@peopletogo.com requesting the proper waybill or a new waybill be sent. If the incorrect waybill is used the tech/company is responsible and will be billed for the part.\r\n•For Lenovo calls multiple parts cannot be returned in one box under one waybill. If they are the part not associated with the waybill used will be billed to the tech. We have no way to prove the part was returned.\r\n•All parts must be returned within 3 business days from the completion of the call. The parts are the responsibility of the tech. If they cannot be tracked or the tech cannot show proof, they were dropped off then the tech is responsible for the cost of the missing parts.\r\n\r\n\r\nScope of Work:\r\nProblem Verification Description lcd damage\r\n\r\nTech Direction replace lcd and back cover\r\n\r\nPart(s) for onsite service--lcd and back cover\r\n\r\nPart Numbers\r\n\r\nPrev ious Failed Repair-- no\r\n\r\nParts Sent Directly to Customer-- no\r\n\r\nCustomer Educated on BitLocker-- no\r\n\r\nRepeat Repair for Same Issue--n\r\n\r\nRAID Configured--no\r\n\r\nPlease find link to shared drive below if you require any additional documentation or USB creation instructions\r\n• https://peopletogo.sharepoint.com/:f:/g/EsCwuVRDP-hPgODjtLva3IsBzlCkgyUCupEjV6HNL64NCw\r\n', 2, 4, 'resolved', 'medium', 4, '2025-03-22 13:39:52', '2025-04-02 13:32:56', NULL, NULL, '2025-03-24', '10:00:00', '', 60, '2025-04-02 13:32:35', '2025-04-02 13:32:56', 'COMPLETED', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAADICAYAAAAePETBAAAAAXNSR0IArs4c6QAAArdJREFUeF7t1cEJADAMw8B2/6Fd6BT3UCYQEiZ3207HGLgFYVp8kIJYPQqC9ShIQTQDGE8/pCCYAQynhRQEM4DhtJCCYAYwnBZSEMwAhtNCCoIZwHBaSEEwAxhOCykIZgDDaSEFwQxgOC2kIJgBDKeFFAQzgOG0kIJgBjCcFlIQzACG00IKghnAcFpIQTADGE4LKQhmAMNpIQXBDGA4LaQgmAEMp4UUBDOA4bSQgmAGMJwWUhDMAIbTQgqCGcBwWkhBMAMYTgspCGYAw2khBcEMYDgtpCCYAQynhRQEM4DhtJCCYAYwnBZSEMwAhtNCCoIZwHBaSEEwAxhOCykIZgDDaSEFwQxgOC2kIJgBDKeFFAQzgOG0kIJgBjCcFlIQzACG00IKghnAcFpIQTADGE4LKQhmAMNpIQXBDGA4LaQgmAEMp4UUBDOA4bSQgmAGMJwWUhDMAIbTQgqCGcBwWkhBMAMYTgspCGYAw2khBcEMYDgtpCCYAQynhRQEM4DhtJCCYAYwnBZSEMwAhtNCCoIZwHBaSEEwAxhOCykIZgDDaSEFwQxgOC2kIJgBDKeFFAQzgOG0kIJgBjCcFlIQzACG00IKghnAcFpIQTADGE4LKQhmAMNpIQXBDGA4LaQgmAEMp4UUBDOA4bSQgmAGMJwWUhDMAIbTQgqCGcBwWkhBMAMYTgspCGYAw2khBcEMYDgtpCCYAQynhRQEM4DhtJCCYAYwnBZSEMwAhtNCCoIZwHBaSEEwAxhOCykIZgDDaSEFwQxgOC2kIJgBDKeFFAQzgOG0kIJgBjCcFlIQzACG00IKghnAcFpIQTADGE4LKQhmAMNpIQXBDGA4LaQgmAEMp4UUBDOA4bSQgmAGMJwWUhDMAIbTQgqCGcBwWkhBMAMYTgspCGYAw2khBcEMYDgtpCCYAQznAUm0HdZM4nTYAAAAAElFTkSuQmCC', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_attachments`
--

CREATE TABLE `ticket_attachments` (
  `id` int(11) UNSIGNED NOT NULL,
  `ticket_id` int(11) UNSIGNED NOT NULL,
  `update_id` int(11) UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_by` int(11) UNSIGNED NOT NULL,
  `uploaded_by_type` enum('admin','technician','customer') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_attachments`
--

INSERT INTO `ticket_attachments` (`id`, `ticket_id`, `update_id`, `file_name`, `file_path`, `file_type`, `file_size`, `uploaded_by`, `uploaded_by_type`, `created_at`) VALUES
(1, 2, NULL, 'Lenovo Work Order.pdf', 'uploads/tickets/1741243155_ebfc77b63d2937a7a011.pdf', 'application/pdf', 175967, 4, 'admin', '2025-03-06 06:39:15'),
(2, 2, 4, 'structure.png', 'uploads/tickets/1741243339_359c00f2f1921b76c58e.png', 'image/png', 38758, 4, 'admin', '2025-03-06 06:42:19'),
(3, 3, NULL, 'CAF - CPC English new workorder.pdf', 'uploads/tickets/1741243659_014aa8827d9dd4741b69.pdf', 'application/pdf', 178873, 4, 'admin', '2025-03-06 06:47:39');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_parts`
--

CREATE TABLE `ticket_parts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ticket_id` int(11) UNSIGNED NOT NULL,
  `part_number` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` enum('unused','installed','defective','wrong_part','returned') NOT NULL DEFAULT 'unused',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_parts`
--

INSERT INTO `ticket_parts` (`id`, `ticket_id`, `part_number`, `description`, `quantity`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(5, 13, '5SS0Z46561 ', 'SSD SAM PM9A1 512G 2280 PCIe G4P x4 - 335286278015', 1, 'installed', 'Installed successfully', '2025-03-06 19:46:01', '2025-03-06 19:48:51'),
(6, 13, '5B21J12073 ', 'BDPLANAR MBL82UGWINR76800HRX6600S4G - 335286278189', 1, 'installed', 'installed and no return', '2025-03-06 19:46:01', '2025-03-06 19:49:14'),
(7, 14, '5D10V82421', 'DISPLAY BOE 15.6 FHD IPS AG', 1, 'installed', '', '2025-03-22 13:39:52', '2025-04-02 13:32:56'),
(8, 14, '5T10S33177', 'TAPE Removable Tape C 20VG R&L', 1, 'installed', '', '2025-03-22 13:39:52', '2025-04-02 13:32:56'),
(9, 14, '5CB1B34808', 'COVER LCD Cover C 20VG MG 3.2t', 1, 'installed', '', '2025-03-22 13:39:52', '2025-04-02 13:32:56');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_updates`
--

CREATE TABLE `ticket_updates` (
  `id` int(11) UNSIGNED NOT NULL,
  `ticket_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_type` enum('admin','technician','customer') NOT NULL,
  `comment` text NOT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_updates`
--

INSERT INTO `ticket_updates` (`id`, `ticket_id`, `user_id`, `user_type`, `comment`, `is_private`, `created_at`) VALUES
(2, 2, 4, 'admin', 'Ticket created', 1, '2025-03-06 06:39:15'),
(3, 2, 4, 'admin', 'Tech contacted the customer and Customer wants delay', 0, '2025-03-06 06:40:21'),
(4, 2, 4, 'admin', 'Tech find on site that parts are delay and not arrive on site, attaching call proof logs', 0, '2025-03-06 06:42:19'),
(5, 3, 4, 'admin', 'Ticket created', 1, '2025-03-06 06:47:39'),
(8, 3, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-03-06 20:30:53'),
(9, 3, 4, 'admin', 'Ticket assigned to Nikhil ShivKumar', 1, '2025-03-06 20:31:00'),
(24, 2, 2, 'technician', 'Service started', 0, '2025-03-07 00:05:53'),
(25, 2, 2, 'technician', 'Service completed with code: PARTS_DEFECTIVE\n\nsystem board branded, tested by user', 0, '2025-03-07 00:07:18'),
(26, 2, 2, 'technician', 'Customer contact', 0, '2025-03-07 00:07:51'),
(27, 2, 2, 'technician', 'test', 0, '2025-03-06 19:09:12'),
(28, 2, 2, 'technician', 'thising tje ticml', 0, '2025-03-06 19:09:31'),
(29, 13, 4, 'admin', 'Ticket created', 1, '2025-03-06 19:46:01'),
(30, 13, 4, 'admin', 'Ticket assigned to Navjot Singh', 1, '2025-03-06 19:46:01'),
(31, 13, 2, 'technician', 'Updated part 5SS0Z46561  status to Installed\n\nNotes: Installed successfully', 0, '2025-03-06 19:48:51'),
(32, 13, 2, 'technician', 'Updated part 5B21J12073  status to Installed\n\nNotes: installed and no return', 0, '2025-03-06 19:49:14'),
(33, 2, 2, 'technician', 'Appointment scheduled for March 15, 2025 at 9:00 AM\n\nNotes: test', 0, '2025-03-07 00:52:33'),
(34, 2, 2, 'technician', 'Appointment scheduled for March 5, 2025 at 9:00 AM\n\nNotes: test', 0, '2025-03-07 00:52:46'),
(35, 2, 2, 'technician', 'Appointment scheduled for March 8, 2025 at 9:00 AM\n\nNotes: test', 0, '2025-03-07 00:56:57'),
(36, 2, 2, 'technician', 'Appointment scheduled for March 14, 2025 at 9:00 AM\n\nNotes: test', 0, '2025-03-07 00:57:27'),
(37, 13, 2, 'technician', 'Appointment scheduled for March 10, 2025 at 9:00 AM\n\nNotes: note for customer', 0, '2025-03-07 01:02:52'),
(38, 13, 2, 'technician', 'Appointment scheduled for March 12, 2025 at 9:00 AM\n\nNotes: note for customer', 0, '2025-03-07 01:03:35'),
(39, 13, 2, 'technician', 'Appointment scheduled for March 7, 2025 at 9:00 AM\n\nNotes: note for customer', 0, '2025-03-07 01:04:59'),
(40, 13, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-03-07 01:27:50'),
(41, 13, 4, 'admin', 'Ticket assigned to Navjot Singh', 1, '2025-03-07 01:27:56'),
(42, 2, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-03-07 01:28:28'),
(43, 2, 4, 'admin', 'Ticket assigned to Navjot Singh', 1, '2025-03-07 01:28:50'),
(44, 2, 2, 'technician', 'Service started:\nstart working', 0, '2025-03-07 01:43:08'),
(45, 2, 2, 'technician', 'Appointment scheduled for March 22, 2025 at 9:00 AM\n\nNotes: test', 0, '2025-03-07 02:05:53'),
(46, 2, 2, 'technician', 'Service started', 0, '2025-03-07 02:06:07'),
(47, 14, 4, 'admin', 'Ticket created', 1, '2025-03-22 13:39:52'),
(48, 14, 4, 'admin', 'Ticket assigned to Hani Kumari', 1, '2025-03-22 13:45:14'),
(49, 14, 4, 'technician', 'Appointment scheduled for March 23, 2025 at 10:00 AM', 0, '2025-03-22 13:46:54'),
(50, 14, 4, 'technician', 'Appointment scheduled for March 24, 2025 at 10:00 AM', 0, '2025-03-22 13:48:45'),
(51, 14, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-03-22 13:49:10'),
(52, 14, 4, 'admin', 'Ticket assigned to Hani Kumari', 1, '2025-03-22 13:49:47'),
(53, 14, 4, 'technician', 'Service started', 0, '2025-03-22 13:50:34'),
(54, 14, 4, 'technician', 'Service completed with code: WRONG_PARTS\n\nUnsuccessfully completed as customer received a wrong part.', 0, '2025-03-22 13:54:16'),
(55, 14, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-03-22 13:59:02'),
(56, 14, 4, 'admin', 'Ticket assigned to Hani Kumari', 1, '2025-03-22 14:00:06'),
(57, 14, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-03-22 14:00:39'),
(58, 14, 4, 'admin', 'Ticket assigned to Hani Kumari', 1, '2025-03-22 14:01:10'),
(59, 14, 4, 'technician', 'Updated part 5D10V82421 status to Installed', 0, '2025-03-22 14:01:39'),
(60, 14, 4, 'technician', 'Updated part 5T10S33177 status to Installed', 0, '2025-03-22 14:01:46'),
(61, 14, 4, 'technician', 'Updated part 5CB1B34808 status to Installed', 0, '2025-03-22 14:01:53'),
(62, 14, 4, 'technician', 'Service started', 0, '2025-03-22 14:02:18'),
(63, 14, 4, 'technician', 'Service completed with code: COMPLETED\n\nSuccessfully replace the Display', 0, '2025-03-22 14:03:30'),
(64, 14, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-03-22 14:07:05'),
(65, 14, 4, 'admin', 'Ticket assigned to Hani Kumari', 1, '2025-03-22 14:07:22'),
(66, 14, 4, 'technician', 'Service started', 0, '2025-04-02 13:06:09'),
(67, 14, 4, 'technician', 'Service completed with code: COMPLETED\n\nSuccessfully Complete', 0, '2025-04-02 13:07:33'),
(68, 14, 4, 'admin', 'Ticket unassigned and returned to open status', 1, '2025-04-02 13:30:39'),
(69, 14, 4, 'admin', 'Ticket assigned to Hani Kumari', 1, '2025-04-02 13:30:58'),
(70, 14, 4, 'technician', 'Service started', 0, '2025-04-02 13:32:35'),
(71, 14, 4, 'technician', 'Service completed with code: COMPLETED\n\ntest', 0, '2025-04-02 13:32:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_documents`
--
ALTER TABLE `organization_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_id` (`organization_id`),
  ADD KEY `technician_id` (`technician_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `update_id` (`update_id`);

--
-- Indexes for table `ticket_parts`
--
ALTER TABLE `ticket_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `ticket_updates`
--
ALTER TABLE `ticket_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organization_documents`
--
ALTER TABLE `organization_documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `technicians`
--
ALTER TABLE `technicians`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ticket_parts`
--
ALTER TABLE `ticket_parts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ticket_updates`
--
ALTER TABLE `ticket_updates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `organization_documents`
--
ALTER TABLE `organization_documents`
  ADD CONSTRAINT `organization_documents_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`technician_id`) REFERENCES `technicians` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD CONSTRAINT `ticket_attachments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_attachments_ibfk_2` FOREIGN KEY (`update_id`) REFERENCES `ticket_updates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_parts`
--
ALTER TABLE `ticket_parts`
  ADD CONSTRAINT `ticket_parts_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_updates`
--
ALTER TABLE `ticket_updates`
  ADD CONSTRAINT `ticket_updates_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
