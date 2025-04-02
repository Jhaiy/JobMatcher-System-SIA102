-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 01:23 AM
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
-- Database: `test_jobmatcher`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicantprofiles`
--

CREATE TABLE `applicantprofiles` (
  `ApplicantProfileID` int(11) NOT NULL,
  `ApplicantPic` varchar(255) DEFAULT NULL,
  `ApplicantExperience` text DEFAULT NULL,
  `ApplicantRating` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicantprofiles`
--

INSERT INTO `applicantprofiles` (`ApplicantProfileID`, `ApplicantPic`, `ApplicantExperience`, `ApplicantRating`) VALUES
(10, 'DSC_0290.JPG', NULL, NULL),
(11, NULL, NULL, NULL),
(12, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `ApplicantID` int(11) NOT NULL,
  `ApplicantFName` varchar(50) NOT NULL,
  `ApplicantLName` varchar(50) NOT NULL,
  `ApplicantSex` varchar(255) DEFAULT NULL,
  `ApplicantBday` varchar(255) DEFAULT NULL,
  `ApplicantEmail` varchar(255) NOT NULL,
  `ApplicantContact` varchar(255) DEFAULT NULL,
  `ApplicantPass` varchar(255) NOT NULL,
  `ApplicantDateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `ApplicantAccountStatus` enum('Active','Inactive') DEFAULT 'Active',
  `ApplicantBlockLot` varchar(50) DEFAULT NULL,
  `ApplicantStreet` varchar(255) DEFAULT NULL,
  `ApplicantBarangay` varchar(100) DEFAULT NULL,
  `ApplicantCity` varchar(100) NOT NULL,
  `ApplicantProvince` varchar(100) DEFAULT NULL,
  `ApplicantProfileID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`ApplicantID`, `ApplicantFName`, `ApplicantLName`, `ApplicantSex`, `ApplicantBday`, `ApplicantEmail`, `ApplicantContact`, `ApplicantPass`, `ApplicantDateCreated`, `ApplicantAccountStatus`, `ApplicantBlockLot`, `ApplicantStreet`, `ApplicantBarangay`, `ApplicantCity`, `ApplicantProvince`, `ApplicantProfileID`) VALUES
(10, 'WUwvRThCM1lZTG5BQWpXTzRWU3E4UT09', 'QXF1ay9td1FLZFRUNzhSOFlUNGdhZz09', 'ejJETm01K29GakJTSXlTVFVSQm9pdz09', 'WVord0o1bjkvcXlnUG0vT2NoWU11UT09', 'dVFJempDSDBlb1M2TWxIV3FGSjYxeXpTb0VIVkUyd3hjb2pBSGRhTUZXND0=', 'QlUrRFpLMnNBTDM0NjRHYzVsQVVRQT09', 'MldLQ28yTHdqL0MrTEZOVzV0eFB5UT09', '2025-03-27 15:27:37', 'Active', 'VkQ0bE9mN1Nya0RYeHJBUjNudE5iQT09', 'UThacGJJbmdRSDdzeC84RFhRMU0vUT09', 'THkvV3BtSWZiN1NjOHN0bE9sZWRCQT09', 'cFBxY3J4T3NlT3NyMENiNzhVSVFMUT09', 'dzhwb2hHWlNqL1FKU214WDJXY1BPUT09', 10),
(11, 'aDdRdVVWZEF6dUJwQ214clU2R1JOdz09', 'RHpqeW5iNXVRU0NDajltejRObHo3UT09', NULL, NULL, 'b29DeWsvWlVHQkFUeWZyRy83MVIxRTdhcTJZRWVyQU1EdzVvVmViS01GYz0=', NULL, 'MldLQ28yTHdqL0MrTEZOVzV0eFB5UT09', '2025-03-27 15:28:41', 'Active', NULL, NULL, NULL, 'cFBxY3J4T3NlT3NyMENiNzhVSVFMUT09', 'dzhwb2hHWlNqL1FKU214WDJXY1BPUT09', 11),
(12, 'aDdRdVVWZEF6dUJwQ214clU2R1JOdz09', 'RHpqeW5iNXVRU0NDajltejRObHo3UT09', NULL, NULL, 'azhRKzlMZldXdWNCUGtXbmtvSDRWUT09', NULL, 'MldLQ28yTHdqL0MrTEZOVzV0eFB5UT09', '2025-04-02 15:53:33', 'Active', NULL, NULL, NULL, 'VVhDQm15UTAvcVBUY1pOdnFoMnh3Zz09', 'em1IZkJGdjBQRWlzR2dTblE4TWcvUT09', 12);

-- --------------------------------------------------------

--
-- Table structure for table `applicantskills`
--

CREATE TABLE `applicantskills` (
  `ApplicantSkillID` int(11) NOT NULL,
  `ApplicantID` int(11) NOT NULL,
  `SkillID` int(11) NOT NULL,
  `SkillLevel` enum('Beginner','Intermediate','Advanced') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicantskills`
--

INSERT INTO `applicantskills` (`ApplicantSkillID`, `ApplicantID`, `SkillID`, `SkillLevel`) VALUES
(44, 10, 1, NULL),
(45, 10, 6, NULL),
(46, 10, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `ApplicationID` int(11) NOT NULL,
  `ApplicantID` int(11) NOT NULL,
  `JobListingID` int(11) NOT NULL,
  `ApplicationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ApplicationStatus` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `CoverLetterPath` varchar(255) DEFAULT NULL,
  `InterviewSched` datetime DEFAULT NULL,
  `ApplicationRating` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `CompanyID` int(11) NOT NULL,
  `CompanyName` varchar(255) NOT NULL,
  `CompanyEmail` varchar(255) NOT NULL,
  `CompanyContact` varchar(20) NOT NULL,
  `CompanyPass` varchar(255) NOT NULL,
  `CompanyDateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `CompanyAccountStatus` enum('Active','Inactive') DEFAULT 'Active',
  `CompanyBlockLot` varchar(50) DEFAULT NULL,
  `CompanyStreet` varchar(255) DEFAULT NULL,
  `CompanyBarangay` varchar(100) DEFAULT NULL,
  `CompanyCity` varchar(100) DEFAULT NULL,
  `CompanyProvince` varchar(100) DEFAULT NULL,
  `CompanyPostalCode` varchar(10) DEFAULT NULL,
  `CompanyDetailsID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`CompanyID`, `CompanyName`, `CompanyEmail`, `CompanyContact`, `CompanyPass`, `CompanyDateCreated`, `CompanyAccountStatus`, `CompanyBlockLot`, `CompanyStreet`, `CompanyBarangay`, `CompanyCity`, `CompanyProvince`, `CompanyPostalCode`, `CompanyDetailsID`) VALUES
(4, 'VE5xV0NHejFXeEt2SHJ6OXN0bTQ5Zz09', 'd21taVNaSEVhaHhGQzdaZDgydUd0aUpjSVBBeUpRWmJWYk54UnFYTkdLVT0=', 'aTlveEt5M0RNMlRRODU5', 'MldLQ28yTHdqL0MrTEZOVzV0eFB5UT09', '2025-03-27 16:45:42', 'Active', 'VU5SejJRVWMvY0trL3ltcUxlVzh2QT09', 'dlN1SVZuaG9DRkc5Smg2dE1TeXdWUT09', 'QkZlQytyS0N6NXV0cnQ3Kzl2bzRwQT09', 'RFRuVkVKUHowMCtuUkF5aDhyYXpzdz09', 'cFBxY3J4T3NlT3NyMENiNzhVSVFMUT09', 'dzhwb2hHWl', 4),
(6, 'c3d4VFduRW9ZNWw1d2pXQjFTK0g3QT09', 'VFZQMzBuVkFuNmhJVjNlSkJJM1NFZz09', 'dUMzdE9iYUZ1RVFzamZB', 'MldLQ28yTHdqL0MrTEZOVzV0eFB5UT09', '2025-03-28 15:16:29', 'Active', 'VkQ0bE9mN1Nya0RYeHJBUjNudE5iQT09', 'c3d4VFduRW9ZNWw1d2pXQjFTK0g3QT09', 'cUNmSnQybnZZVEowYnJCSnBDYmI0Zz09', 'NjYyZGdEY0k2MmVCS1MwRUtPVE5JUT09', 'cFBxY3J4T3NlT3NyMENiNzhVSVFMUT09', 'em1IZkJGdj', 6);

-- --------------------------------------------------------

--
-- Table structure for table `companydetails`
--

CREATE TABLE `companydetails` (
  `CompanyDetailsID` int(11) NOT NULL,
  `CompanyDescription` text DEFAULT NULL,
  `CompanyRating` decimal(3,2) DEFAULT NULL,
  `CompanyLogo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companydetails`
--

INSERT INTO `companydetails` (`CompanyDetailsID`, `CompanyDescription`, `CompanyRating`, `CompanyLogo`) VALUES
(4, NULL, NULL, NULL),
(6, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobcategories`
--

CREATE TABLE `jobcategories` (
  `JobCategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `CategoryDescription` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobcategories`
--

INSERT INTO `jobcategories` (`JobCategoryID`, `CategoryName`, `CategoryDescription`) VALUES
(1, 'Software Development & Engineering', 'These professionals design, develop, test, and maintain software applications. They work with programming languages and development frameworks to create applications for various platforms.'),
(2, 'Data Science & Analytics', 'These professionals analyze and interpret large datasets to drive business decisions and develop machine learning models.'),
(3, 'Cybersecurity & Network Administration', 'Professionals in this field protect computer systems and networks from cyber threats.'),
(4, 'IT Support & System Administration', 'This category includes professionals responsible for maintaining IT infrastructure and providing technical support.'),
(5, 'Web Development & UI/UX Design', 'These professionals focus on designing and building websites and web applications.'),
(6, 'Artificial Intelligence & Robotics', 'This category focuses on AI-driven solutions and robotics development.'),
(7, 'Database Administration & Cloud Computing', 'These professionals manage data storage and cloud-based systems.'),
(8, 'IT Project Management', 'This category focuses on managing IT projects and teams.'),
(9, 'Embedded Systems & Hardware Engineering', 'These professionals work on hardware development and embedded software.'),
(10, 'Computer Science Research & Academia', 'This field is for those interested in advancing knowledge and teaching in the field of computing.');

-- --------------------------------------------------------

--
-- Table structure for table `joblistings`
--

CREATE TABLE `joblistings` (
  `JobListingID` int(11) NOT NULL,
  `CompanyID` int(11) NOT NULL,
  `JobTitle` varchar(255) NOT NULL,
  `JobDescription` text NOT NULL,
  `SalaryRange` varchar(50) DEFAULT NULL,
  `JobType` enum('Full-time','Part-time','Remote','Contract') NOT NULL,
  `PostDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ExpiryDate` date NOT NULL,
  `JobStatus` enum('Open','Closed') DEFAULT 'Open',
  `JobTag` varchar(255) DEFAULT NULL,
  `JobBlockLot` varchar(50) DEFAULT NULL,
  `JobStreet` varchar(255) DEFAULT NULL,
  `JobBarangay` varchar(100) DEFAULT NULL,
  `JobCity` varchar(100) DEFAULT NULL,
  `JobProvince` varchar(100) DEFAULT NULL,
  `JobPostalCode` varchar(10) DEFAULT NULL,
  `JobCategoryID` int(11) NOT NULL,
  `JobRoleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobrequirements`
--

CREATE TABLE `jobrequirements` (
  `JobRequirementID` int(11) NOT NULL,
  `JobListingID` int(11) NOT NULL,
  `SkillID` int(11) NOT NULL,
  `RequiredSkillLevel` enum('Beginner','Intermediate','Advanced') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobroles`
--

CREATE TABLE `jobroles` (
  `JobRoleID` int(11) NOT NULL,
  `RoleName` varchar(255) NOT NULL,
  `RoleDescription` text DEFAULT NULL,
  `RoleCategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobroles`
--

INSERT INTO `jobroles` (`JobRoleID`, `RoleName`, `RoleDescription`, `RoleCategoryID`) VALUES
(1, 'Software Developer', 'Designs and builds applications for different platforms.', 1),
(2, 'Full-Stack Developer', 'Works on both front-end and back-end development.', 1),
(3, 'Front-End Developer', 'Specializes in building the visual and interactive parts of websites and applications.', 1),
(4, 'Back-End Developer', 'Manages server-side logic, databases, and API integrations.', 1),
(5, 'Mobile App Developer', 'Develops mobile applications for iOS and Android.', 1),
(6, 'Game Developer', 'Creates video games using game engines like Unity or Unreal Engine.', 1),
(7, 'Data Scientist', 'Uses machine learning and analytics to derive insights from data.', 2),
(8, 'Data Analyst', 'Examines data to identify trends and make business decisions.', 2),
(9, 'Machine Learning Engineer', 'Develops and deploys machine learning models.', 2),
(10, 'AI Engineer', 'Designs artificial intelligence solutions and models.', 2),
(11, 'Business Intelligence Analyst', 'Uses data visualization tools to create reports and insights.', 2),
(12, 'Cybersecurity Analyst', 'Monitors threats, detects vulnerabilities, and mitigates risks.', 3),
(13, 'Ethical Hacker (Penetration Tester)', 'Tests systems for security weaknesses by simulating cyberattacks.', 3),
(14, 'Network Administrator', 'Manages and maintains computer networks.', 3),
(15, 'Security Engineer', 'Designs and implements security measures for IT infrastructure.', 3),
(16, 'IT Security Consultant', 'Advises organizations on cybersecurity best practices.', 3),
(17, 'IT Support Specialist', 'Assists users with hardware and software issues.', 4),
(18, 'System Administrator', 'Maintains servers and IT infrastructure.', 4),
(19, 'Cloud Engineer', 'Manages cloud-based solutions on platforms like AWS, Azure, and Google Cloud.', 4),
(20, 'DevOps Engineer', 'Automates and optimizes software development and deployment processes.', 4),
(21, 'IT Technician', 'Installs and maintains computer hardware and networks.', 4),
(22, 'Web Developer', 'Designs and develops websites and web applications.', 5),
(23, 'UI/UX Designer', 'Creates user-friendly and visually appealing interfaces.', 5),
(24, 'Front-End Engineer', 'Develops interactive and responsive web interfaces.', 5),
(25, 'Interaction Designer', 'Specializes in designing digital experiences for users.', 5),
(26, 'AI Researcher', 'Conducts research in artificial intelligence and machine learning.', 6),
(27, 'Robotics Engineer', 'Develops and builds robotic systems for automation.', 6),
(28, 'NLP Engineer', 'Works on natural language processing applications like chatbots.', 6),
(29, 'Computer Vision Engineer', 'Develops image and video recognition technologies.', 6),
(30, 'Database Administrator', 'Manages and optimizes database systems.', 7),
(31, 'Cloud Solutions Architect', 'Designs and implements cloud infrastructure.', 7),
(32, 'Cloud Engineer', 'Deploys and maintains cloud computing environments.', 7),
(33, 'Big Data Engineer', 'Builds systems for processing large-scale data.', 7),
(34, 'IT Project Manager', 'Oversees planning, execution, and delivery of IT projects.', 8),
(35, 'Scrum Master', 'Facilitates Agile development teams.', 8),
(36, 'Agile Coach', 'Guides teams in adopting Agile methodologies.', 8),
(37, 'Embedded Systems Engineer', 'Develops software for hardware and embedded devices.', 9),
(38, 'IoT Developer', 'Creates Internet of Things (IoT) applications.', 9),
(39, 'Hardware Engineer', 'Designs and develops computer hardware components.', 9),
(40, 'Computer Science Lecturer', 'Teaches computer science courses at universities.', 10),
(41, 'Research Scientist', 'Conducts research in AI, cybersecurity, and other computing fields.', 10),
(42, 'Computational Scientist', 'Uses computing techniques for scientific and engineering applications.', 10);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `barangay` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resumes`
--

CREATE TABLE `resumes` (
  `ResumeID` int(11) NOT NULL,
  `ApplicantID` int(11) NOT NULL,
  `ResumeTitle` varchar(255) NOT NULL,
  `ResumeFilePath` varchar(255) NOT NULL,
  `ResumeDateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `SkillID` int(11) NOT NULL,
  `SkillName` varchar(255) NOT NULL,
  `SkillDescription` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`SkillID`, `SkillName`, `SkillDescription`) VALUES
(1, 'Programming', 'The ability to write, debug, and optimize code using various programming languages. Essential for software engineers, game developers, and system programmers.'),
(2, 'Software Development', 'The ability to write, debug, and optimize code using various programming languages. Essential for software engineers, game developers, and system programmers.'),
(3, 'Data Science', 'The expertise to analyze large datasets, build machine learning models, and apply statistical techniques to make data-driven decisions. Important for data scientists and AI engineers.'),
(4, 'Machine Learning', 'The expertise to analyze large datasets, build machine learning models, and apply statistical techniques to make data-driven decisions. Important for data scientists and AI engineers.'),
(5, 'Cybersecurity', 'The knowledge of securing computer systems, networks, and data from cyber threats. Involves penetration testing, encryption, and compliance with security protocols.'),
(6, 'Network Administration', 'The knowledge of securing computer systems, networks, and data from cyber threats. Involves penetration testing, encryption, and compliance with security protocols.'),
(7, 'Web Development', 'The ability to design and develop interactive websites and web applications. Includes frontend development, backend programming, and user experience (UX) design principles.'),
(8, 'UI/UX Design', 'The ability to design and develop interactive websites and web applications. Includes frontend development, backend programming, and user experience (UX) design principles.'),
(9, 'Cloud Computing', 'The skills to deploy, manage, and optimize applications on cloud platforms such as AWS, Azure, or Google Cloud. DevOps focuses on automation, continuous integration, and containerization.'),
(10, 'DevOps', 'The skills to deploy, manage, and optimize applications on cloud platforms such as AWS, Azure, or Google Cloud. DevOps focuses on automation, continuous integration, and containerization.'),
(11, 'Database ', 'The knowledge of relational and NoSQL databases, API development, and server-side programming to store and manage data efficiently. Essential for backend developers and database administrators.'),
(12, 'Backend Development', 'The knowledge of relational and NoSQL databases, API development, and server-side programming to store and manage data efficiently. Essential for backend developers and database administrators.'),
(13, 'Embedded Systems', 'The ability to program and integrate hardware with software, such as microcontrollers, sensors, and IoT devices. Used in robotics, industrial automation, and smart devices.'),
(14, 'IoT', 'The ability to program and integrate hardware with software, such as microcontrollers, sensors, and IoT devices. Used in robotics, industrial automation, and smart devices.'),
(15, 'IT Project Management', 'The expertise in managing software projects using methodologies like Agile and Scrum. Involves task prioritization, risk assessment, and effective communication within development teams.'),
(16, 'Artificial Intelligence', 'The development of intelligent systems that can learn, recognize patterns, and make decisions. Includes AI-powered chatbots, computer vision applications, and autonomous robots.'),
(17, 'Robotics', 'The development of intelligent systems that can learn, recognize patterns, and make decisions. Includes AI-powered chatbots, computer vision applications, and autonomous robots.'),
(18, 'Computer Science Research', 'The ability to analyze algorithms, contribute to new technologies, and publish research in computing fields. Often involves mathematical computing, data structures, and technical writing.'),
(19, 'Academia', 'The ability to analyze algorithms, contribute to new technologies, and publish research in computing fields. Often involves mathematical computing, data structures, and technical writing.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicantprofiles`
--
ALTER TABLE `applicantprofiles`
  ADD PRIMARY KEY (`ApplicantProfileID`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`ApplicantID`),
  ADD UNIQUE KEY `ApplicantEmail` (`ApplicantEmail`),
  ADD KEY `ApplicantProfileID` (`ApplicantProfileID`);

--
-- Indexes for table `applicantskills`
--
ALTER TABLE `applicantskills`
  ADD PRIMARY KEY (`ApplicantSkillID`),
  ADD KEY `ApplicantID` (`ApplicantID`),
  ADD KEY `SkillID` (`SkillID`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`ApplicationID`),
  ADD KEY `ApplicantID` (`ApplicantID`),
  ADD KEY `JobListingID` (`JobListingID`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`CompanyID`),
  ADD UNIQUE KEY `CompanyEmail` (`CompanyEmail`),
  ADD KEY `CompanyDetailsID` (`CompanyDetailsID`);

--
-- Indexes for table `companydetails`
--
ALTER TABLE `companydetails`
  ADD PRIMARY KEY (`CompanyDetailsID`);

--
-- Indexes for table `jobcategories`
--
ALTER TABLE `jobcategories`
  ADD PRIMARY KEY (`JobCategoryID`),
  ADD UNIQUE KEY `CategoryName` (`CategoryName`);

--
-- Indexes for table `joblistings`
--
ALTER TABLE `joblistings`
  ADD PRIMARY KEY (`JobListingID`),
  ADD KEY `CompanyID` (`CompanyID`),
  ADD KEY `JobCategoryID` (`JobCategoryID`),
  ADD KEY `JobRoleID` (`JobRoleID`);

--
-- Indexes for table `jobrequirements`
--
ALTER TABLE `jobrequirements`
  ADD PRIMARY KEY (`JobRequirementID`),
  ADD KEY `JobListingID` (`JobListingID`),
  ADD KEY `SkillID` (`SkillID`);

--
-- Indexes for table `jobroles`
--
ALTER TABLE `jobroles`
  ADD PRIMARY KEY (`JobRoleID`),
  ADD KEY `RoleCategoryID` (`RoleCategoryID`);

--
-- Indexes for table `resumes`
--
ALTER TABLE `resumes`
  ADD PRIMARY KEY (`ResumeID`),
  ADD KEY `ApplicantID` (`ApplicantID`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`SkillID`),
  ADD UNIQUE KEY `SkillName` (`SkillName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicantprofiles`
--
ALTER TABLE `applicantprofiles`
  MODIFY `ApplicantProfileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `applicantskills`
--
ALTER TABLE `applicantskills`
  MODIFY `ApplicantSkillID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `ApplicationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companydetails`
--
ALTER TABLE `companydetails`
  MODIFY `CompanyDetailsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobcategories`
--
ALTER TABLE `jobcategories`
  MODIFY `JobCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `joblistings`
--
ALTER TABLE `joblistings`
  MODIFY `JobListingID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobrequirements`
--
ALTER TABLE `jobrequirements`
  MODIFY `JobRequirementID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobroles`
--
ALTER TABLE `jobroles`
  MODIFY `JobRoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `resumes`
--
ALTER TABLE `resumes`
  MODIFY `ResumeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `SkillID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_ibfk_1` FOREIGN KEY (`ApplicantProfileID`) REFERENCES `applicantprofiles` (`ApplicantProfileID`),
  ADD CONSTRAINT `fk_applicants_applicantprofiles` FOREIGN KEY (`ApplicantProfileID`) REFERENCES `applicantprofiles` (`ApplicantProfileID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `applicantskills`
--
ALTER TABLE `applicantskills`
  ADD CONSTRAINT `applicantskills_ibfk_1` FOREIGN KEY (`ApplicantID`) REFERENCES `applicants` (`ApplicantID`),
  ADD CONSTRAINT `applicantskills_ibfk_2` FOREIGN KEY (`SkillID`) REFERENCES `skills` (`SkillID`);

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`ApplicantID`) REFERENCES `applicants` (`ApplicantID`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`JobListingID`) REFERENCES `joblistings` (`JobListingID`);

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`CompanyDetailsID`) REFERENCES `companydetails` (`CompanyDetailsID`);

--
-- Constraints for table `joblistings`
--
ALTER TABLE `joblistings`
  ADD CONSTRAINT `joblistings_ibfk_1` FOREIGN KEY (`CompanyID`) REFERENCES `company` (`CompanyID`),
  ADD CONSTRAINT `joblistings_ibfk_2` FOREIGN KEY (`JobCategoryID`) REFERENCES `jobcategories` (`JobCategoryID`),
  ADD CONSTRAINT `joblistings_ibfk_3` FOREIGN KEY (`JobRoleID`) REFERENCES `jobroles` (`JobRoleID`);

--
-- Constraints for table `jobrequirements`
--
ALTER TABLE `jobrequirements`
  ADD CONSTRAINT `jobrequirements_ibfk_1` FOREIGN KEY (`JobListingID`) REFERENCES `joblistings` (`JobListingID`),
  ADD CONSTRAINT `jobrequirements_ibfk_2` FOREIGN KEY (`SkillID`) REFERENCES `skills` (`SkillID`);

--
-- Constraints for table `jobroles`
--
ALTER TABLE `jobroles`
  ADD CONSTRAINT `jobroles_ibfk_1` FOREIGN KEY (`RoleCategoryID`) REFERENCES `jobcategories` (`JobCategoryID`);

--
-- Constraints for table `resumes`
--
ALTER TABLE `resumes`
  ADD CONSTRAINT `resumes_ibfk_1` FOREIGN KEY (`ApplicantID`) REFERENCES `applicants` (`ApplicantID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
