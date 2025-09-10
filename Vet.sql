-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 28, 2024 at 01:09 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Vet`
--

-- --------------------------------------------------------

--
-- Table structure for table `Appointment`
--

CREATE TABLE `Appointment` (
  `AppointmentID` int NOT NULL,
  `PatientID` int DEFAULT NULL,
  `petName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `OwnerName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Contact` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `AppointmentDate` date DEFAULT NULL,
  `AppointmentTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Appointment`
--

INSERT INTO `Appointment` (`AppointmentID`, `PatientID`, `petName`, `OwnerName`, `Contact`, `AppointmentDate`, `AppointmentTime`) VALUES
(10, 22, 'P', 'V', '0987654323', '2024-11-28', '02:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `FollowUp`
--

CREATE TABLE `FollowUp` (
  `FollowUpID` int NOT NULL,
  `PatientID` int DEFAULT NULL,
  `Notes` text,
  `LastTreatmentDate` date DEFAULT NULL,
  `RecoveryStatus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `NextCheckUpDate` date DEFAULT NULL,
  `NextCheckUpTime` time DEFAULT NULL,
  `SpecialInstructions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `FollowUp`
--

INSERT INTO `FollowUp` (`FollowUpID`, `PatientID`, `Notes`, `LastTreatmentDate`, `RecoveryStatus`, `NextCheckUpDate`, `NextCheckUpTime`, `SpecialInstructions`) VALUES
(6, 22, 'Please arrive 30 minutes early.', '2024-11-20', 'Your furry friend is doing well after the check-up!', '2024-11-30', '13:00:00', 'Administer the prescribed medications twice daily with food. Ensure your dog has access to fresh water and monitor for any unusual behavior or side effects. Contact the clinic if you have concerns or need further assistance');

-- --------------------------------------------------------

--
-- Table structure for table `Patient`
--

CREATE TABLE `Patient` (
  `PatientID` int NOT NULL,
  `petName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Species` varchar(50) DEFAULT NULL,
  `Breed` varchar(50) DEFAULT NULL,
  `Age` int DEFAULT NULL,
  `VaccinationStatus` tinyint(1) DEFAULT NULL,
  `MedicalHistory` text,
  `UserID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Patient`
--

INSERT INTO `Patient` (`PatientID`, `petName`, `Species`, `Breed`, `Age`, `VaccinationStatus`, `MedicalHistory`, `UserID`) VALUES
(22, 'Peepo', 'Dog', 'Beagle', 10, 1, '', 7);

-- --------------------------------------------------------

--
-- Table structure for table `Reports`
--

CREATE TABLE `Reports` (
  `report_id` int NOT NULL,
  `UserID` int NOT NULL,
  `Email` varchar(500) NOT NULL,
  `title` varchar(255) NOT NULL,
  `Notes` text NOT NULL,
  `status` enum('Not Started','In Progress','Done') DEFAULT 'Not Started',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `TreatmentPlan`
--

CREATE TABLE `TreatmentPlan` (
  `TreatmentPlanID` int NOT NULL,
  `PatientID` int NOT NULL,
  `HealthStatus` text NOT NULL,
  `VaccinationRecords` text NOT NULL,
  `LastTreatmentDate` date NOT NULL,
  `NextCheckUpDate` date NOT NULL,
  `NextCheckUpTime` time NOT NULL,
  `SpecialInstructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `TreatmentPlan`
--

INSERT INTO `TreatmentPlan` (`TreatmentPlanID`, `PatientID`, `HealthStatus`, `VaccinationRecords`, `LastTreatmentDate`, `NextCheckUpDate`, `NextCheckUpTime`, `SpecialInstructions`) VALUES
(5, 22, 'Your furry friend is doing well.', 'Vaccination complete! Your pet is now protected against common illnesses. Please keep this record for future reference and follow-up schedules.', '2024-11-23', '2024-11-29', '16:30:00', 'Keep the wound clean and dry; avoid excessive activity for the next 7 days.');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `UserID` int NOT NULL,
  `UserName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Phone` varbinary(255) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varbinary(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`UserID`, `UserName`, `Name`, `Phone`, `Email`, `Password`) VALUES
(7, 'Noom', 'Noom Painty', 0x30393231333832383837, 'noom@gmail.com', 0x24327924313024536d386472323631746a756e4b437156565278587765784e3158564c36494d42443543706e66366a4a2f542f5555524b475557336d),
(8, 'Admin', 'Peepo Moodeng', 0x30393133323833323231, 'vet@admin.co.th', 0x243279243130245937766d5361304f4d63444965666547387973733275587a4e475a417a7a6539734168494e70616e4d5554715442456e63486c4553),
(9, 'Job', 'Jobbu Jubjub', 0x30393234383339343835, 'job@gmail.com', 0x243279243130244a356933654a50797943583543627751573274424d4f34755a2f5a2e4e4b794a4658326466474e43376b633647366356486a57434b);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD PRIMARY KEY (`AppointmentID`),
  ADD KEY `OwnerName` (`OwnerName`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `FollowUp`
--
ALTER TABLE `FollowUp`
  ADD PRIMARY KEY (`FollowUpID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `Patient`
--
ALTER TABLE `Patient`
  ADD PRIMARY KEY (`PatientID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `Reports`
--
ALTER TABLE `Reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `TreatmentPlan`
--
ALTER TABLE `TreatmentPlan`
  ADD PRIMARY KEY (`TreatmentPlanID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Appointment`
--
ALTER TABLE `Appointment`
  MODIFY `AppointmentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `FollowUp`
--
ALTER TABLE `FollowUp`
  MODIFY `FollowUpID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Patient`
--
ALTER TABLE `Patient`
  MODIFY `PatientID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `Reports`
--
ALTER TABLE `Reports`
  MODIFY `report_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `TreatmentPlan`
--
ALTER TABLE `TreatmentPlan`
  MODIFY `TreatmentPlanID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `Patient` (`PatientID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `FollowUp`
--
ALTER TABLE `FollowUp`
  ADD CONSTRAINT `followup_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `Patient` (`PatientID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Patient`
--
ALTER TABLE `Patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`) ON UPDATE CASCADE;

--
-- Constraints for table `Reports`
--
ALTER TABLE `Reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `TreatmentPlan`
--
ALTER TABLE `TreatmentPlan`
  ADD CONSTRAINT `treatmentplan_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `Patient` (`PatientID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
