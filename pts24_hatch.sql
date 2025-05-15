-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 01:23 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pts24_hatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `input_name` varchar(20) NOT NULL,
  `answer_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `input_name`, `answer_value`) VALUES
(1, 2, 'answer_one', '='),
(2, 3, 'answer_one', '\"number\"'),
(3, 3, 'answer_one', '\'number\''),
(4, 4, 'answer_one', 'length'),
(5, 6, 'answer_one', '+='),
(6, 5, 'answer_one', 'const');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `language_id` int(11) NOT NULL,
  `language_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`language_id`, `language_name`) VALUES
(1, 'JavaScript'),
(2, 'Python');

-- --------------------------------------------------------

--
-- Table structure for table `languages_topic`
--

CREATE TABLE `languages_topic` (
  `id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `languages_topic`
--

INSERT INTO `languages_topic` (`id`, `language_id`, `topic_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 5),
(5, 1, 4),
(6, 2, 1),
(7, 2, 4),
(8, 2, 2),
(9, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `languages_topic_id` int(11) NOT NULL,
  `question` varchar(500) DEFAULT NULL,
  `form_content` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `languages_topic_id`, `question`, `form_content`) VALUES
(2, 1, 'Write a correct syntax for assigning a value to a variable:', '<div>\r\n        <span>myNumber</span>\r\n        <input type=\"text\" name=\"answer_one\">\r\n        <span>9</span>\r\n    </div>'),
(3, 1, 'How would you assign a value to a variable, if it’s supposed to be the word <i>number</i>?', ' <div>\r\n        <span>myString = </span>\r\n        <input type=\"text\" name=\"answer_one\">\r\n    </div>'),
(4, 1, 'If you want to count the number of characters in a text, you would use the built-in property:', ' <div>\r\n        <p>let myText = \"Ihana!\"</p>\r\n<span>let amountOfCharacters = myText.</span>\r\n        <input type=\"text\" name=\"answer_one\">\r\n    </div>'),
(5, 1, 'If you don`t want to change the value of the variable <i>price</i> in your code, what keyword would you use to declare it?', ' <div>\r\n      <input type=\"text\" name=\"answer_one\">  <span> \r\n price = 100 </span>\r\n    </div>'),
(6, 1, 'If you want to increase your counter <i>index</i> by 1, you can write:', '<div>\r\n        <p>let index = 1</p>\r\n        <p>index = index + 1</p>\r\n        <p>or</p>\r\n        <span>index</span>\r\n        <input type=\"text\" name=\"answer_one\">\r\n        <span>1</span>\r\n    </div>');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `topic_id` int(11) NOT NULL,
  `topic_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`topic_id`, `topic_name`) VALUES
(1, 'Basics and syntax'),
(2, 'Objects and arrays'),
(3, 'DOM manipulation'),
(4, 'Functions'),
(5, 'Events and event handling'),
(6, 'Exception Handling');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question` (`question_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`language_id`);

--
-- Indexes for table `languages_topic`
--
ALTER TABLE `languages_topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `languages_topic_id` (`languages_topic_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `languages_topic`
--
ALTER TABLE `languages_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

--
-- Constraints for table `languages_topic`
--
ALTER TABLE `languages_topic`
  ADD CONSTRAINT `languages_topic_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`),
  ADD CONSTRAINT `languages_topic_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`languages_topic_id`) REFERENCES `languages_topic` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
