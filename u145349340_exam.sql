-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 19, 2026 at 04:17 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u145349340_exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'Shri', '$2y$10$OvYcYEysjkyJPXikBSHfPejzhcPGmSi/wK.JY7SaI7YH2HHp3U3iW');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `q_id` int(11) NOT NULL,
  `sub_id` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `ans_correct` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`q_id`, `sub_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `ans_correct`) VALUES
(1, 1, 'Full Form Of PHP?', 'Hypertext Language', 'Hypertext Markup Language', 'Hypertext Preprocessor Scripting Language', 'Scripting Language', 'C'),
(2, 1, 'Father Of PHP Language?', 'Denish Retchie', 'Lasmos Lardof', 'Lardof', 'Denish', 'B'),
(3, 1, 'Old PHP Name Is?', 'Personal Home Page', 'Markup Language', 'Personal Web Page', 'Server Side Scripting Language', 'A'),
(4, 2, 'What is the main difference between echo and print in PHP?', 'print is faster than echo.', 'echo can take multiple parameters, while print cannot.', 'echo returns a value of 1, while print returns 0.', ' There is no difference. ', 'B'),
(5, 2, 'What will be the output of the following PHP code?\r\n\r\n<?php\r\n$a = \"5\";\r\n$b = 5;\r\nvar_dump($a === $b);\r\n?>', 'bool(true)', 'bool(false)', 'error', 'int 1', 'B'),
(6, 2, 'Which of the following is the correct way to declare a variable in PHP?', 'var name = \"John\";', '$name = \"John\";', 'name = \"John\";', 'string $name = \"John\"; ', 'B'),
(7, 3, 'What Is Java', 'Programm', 'Language', 'Exam', 'Test', 'B'),
(8, 3, 'Who invented Java Programming?', 'Guido van Rossum', 'James Gosling', 'Dennis Ritchie', 'Bjarne Stroustrup', 'B'),
(10, 4, 'Who developed C++?', 'Dennis Ritchie', 'Bjarne Stroustrup', 'James Gosling', 'Guido van Rossum', 'B'),
(11, 4, 'Which of the following is the correct file extension of C++?', '.c', '.cpp', '.cp', '.cxx', 'B'),
(12, 4, 'Which symbol is used for comments in C++ (single line)?', '#', '//', '/* */', '--', 'B'),
(13, 4, 'Which of the following is a valid C++ variable name?', 'int', '1value', 'value_1', 'value-1', 'C'),
(14, 4, 'What is the correct syntax to include iostream library?', 'include<iostream>', '#include<iostream>', '#include iostream', 'include iostream', 'B'),
(15, 4, 'Which operator is used for scope resolution?', ':', '::', '->', '.', 'B'),
(16, 4, 'Which of the following is not a C++ data type?', 'int', 'float', 'real', 'char', 'C'),
(17, 4, 'What is the default return type of main() in C++?', 'void', 'int', 'float', 'char', 'B'),
(18, 4, 'Which keyword is used to define a constant?', 'const', 'constant', 'define', 'fixed', 'A'),
(19, 4, 'What does ‘cout’ do in C++?', 'Takes input', 'Prints output', 'Declares variable', 'Defines function', 'B'),
(20, 4, 'Which operator is used to access members of a class using object?', '->', '.', '::', '&', 'B'),
(21, 4, 'Which concept allows multiple functions with same name?', 'Inheritance', 'Polymorphism', 'Encapsulation', 'Abstraction', 'B'),
(22, 4, 'Which function is the entry point of a C++ program?', 'start()', 'main()', 'init()', 'run()', 'B'),
(23, 4, 'What is used to allocate memory dynamically?', 'malloc()', 'alloc()', 'new', 'create', 'C'),
(24, 4, 'Which loop executes at least once?', 'for', 'while', 'do-while', 'none', 'C'),
(25, 4, 'Which keyword is used for inheritance?', 'extends', 'inherits', ':', 'implements', 'C'),
(26, 4, 'What is the size of int (generally)?', '2 bytes', '4 bytes', '8 bytes', 'Depends on compiler', 'D'),
(27, 4, 'Which of the following is a constructor?', 'A function with return type', 'A function with same name as class', 'A static function', 'A private function', 'B'),
(28, 4, 'Which operator is used to allocate memory for objects?', 'delete', 'new', 'free', 'malloc', 'B'),
(29, 4, 'Which of the following supports OOP in C++?', 'Classes', 'Functions', 'Variables', 'Arrays', 'A'),
(30, 5, 'Who developed JavaScript?', 'Dennis Ritchie', 'Brendan Eich', 'James Gosling', 'Guido van Rossum', 'B'),
(31, 5, 'Which company developed JavaScript?', 'Microsoft', 'Netscape', 'Google', 'IBM', 'B'),
(32, 5, 'Which symbol is used for single-line comments in JavaScript?', '#', '//', '<!-- -->', '**', 'B'),
(33, 5, 'Which keyword is used to declare a variable in JavaScript?', 'int', 'var', 'declare', 'dim', 'B'),
(34, 5, 'Which of the following is not a JavaScript data type?', 'Number', 'String', 'Boolean', 'Float', 'D'),
(35, 5, 'What is the correct way to write a JavaScript array?', 'var arr = (1,2,3)', 'var arr = [1,2,3]', 'var arr = {1,2,3}', 'var arr = <1,2,3>', 'B'),
(36, 5, 'Which operator is used for strict equality?', '==', '=', '===', '!=', 'C'),
(37, 5, 'What will typeof null return?', 'null', 'object', 'undefined', 'number', 'B'),
(38, 5, 'Which function is used to print something in console?', 'print()', 'console.log()', 'echo()', 'log()', 'B'),
(39, 5, 'Which keyword is used to define a constant?', 'const', 'constant', 'let', 'final', 'A'),
(40, 5, 'Which method is used to add an element at the end of an array?', 'push()', 'pop()', 'shift()', 'unshift()', 'A'),
(41, 5, 'Which method removes the last element of an array?', 'push()', 'pop()', 'shift()', 'slice()', 'B'),
(42, 5, 'What is the default value of an uninitialized variable?', 'null', '0', 'undefined', 'false', 'C'),
(43, 5, 'Which keyword is used for function declaration?', 'function', 'func', 'def', 'method', 'A'),
(44, 5, 'Which symbol is used for template literals?', '\' \'', '\" \"', '` `', '( )', 'C'),
(45, 5, 'Which operator is used to assign a value?', '==', '=', '===', ':=', 'B'),
(46, 5, 'Which event occurs when user clicks on HTML element?', 'onchange', 'onmouseclick', 'onmouseover', 'onclick', 'D'),
(47, 5, 'Which loop is guaranteed to execute at least once?', 'for', 'while', 'do...while', 'foreach', 'C'),
(48, 5, 'Which object is used for JSON handling?', 'JSON', 'Object', 'Data', 'Parse', 'A'),
(49, 5, 'Which method converts JSON to JavaScript object?', 'JSON.parse()', 'JSON.stringify()', 'JSON.convert()', 'JSON.toObject()', 'A'),
(50, 6, 'What does CSS stand for?', 'Creative Style Sheets', 'Cascading Style Sheets', 'Computer Style Sheets', 'Colorful Style Sheets', 'B'),
(51, 6, 'Which HTML tag is used to define an internal CSS?', '<css>', '<script>', '<style>', '<link>', 'C'),
(52, 6, 'Which property is used to change text color?', 'font-color', 'color', 'text-color', 'background-color', 'B'),
(53, 6, 'Which property controls the text size?', 'font-style', 'text-size', 'font-size', 'text-style', 'C'),
(54, 6, 'How do you select an element with id \"demo\"?', '.demo', '#demo', '*demo', 'demo', 'B'),
(55, 6, 'How do you select elements with class \"test\"?', '#test', '.test', 'test', '*test', 'B'),
(56, 6, 'Which property is used for background color?', 'bgcolor', 'background-color', 'color', 'background-style', 'B'),
(57, 6, 'Which CSS property controls spacing between elements?', 'margin', 'padding', 'spacing', 'border', 'A'),
(58, 6, 'Which property is used to make text bold?', 'font-weight', 'text-style', 'font-style', 'text-weight', 'A'),
(59, 6, 'Which property is used to align text?', 'text-align', 'align', 'font-align', 'vertical-align', 'A'),
(60, 6, 'What does CSS stand for?', 'Creative Style Sheets', 'Cascading Style Sheets', 'Computer Style Sheets', 'Colorful Style Sheets', 'B'),
(61, 6, 'Which HTML tag is used to define an internal CSS?', '<css>', '<script>', '<style>', '<link>', 'C'),
(62, 6, 'Which property is used to change text color?', 'font-color', 'color', 'text-color', 'background-color', 'B'),
(63, 6, 'Which property controls the text size?', 'font-style', 'text-size', 'font-size', 'text-style', 'C'),
(64, 6, 'How do you select an element with id \"demo\"?', '.demo', '#demo', '*demo', 'demo', 'B'),
(65, 6, 'How do you select elements with class \"test\"?', '#test', '.test', 'test', '*test', 'B'),
(66, 6, 'Which property is used for background color?', 'bgcolor', 'background-color', 'color', 'background-style', 'B'),
(67, 6, 'Which CSS property controls spacing between elements?', 'margin', 'padding', 'spacing', 'border', 'A'),
(68, 6, 'Which property is used to make text bold?', 'font-weight', 'text-style', 'font-style', 'text-weight', 'A'),
(69, 6, 'Which property is used to align text?', 'text-align', 'align', 'font-align', 'vertical-align', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `res_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `total_score` int(11) NOT NULL,
  `date_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`res_id`, `user_id`, `sub_id`, `total_score`, `date_time`) VALUES
(1, 1, 1, 2, '2026-02-13 06:10:38'),
(2, 1, 1, 1, '2026-02-13 06:12:11'),
(3, 3, 2, 0, '2026-02-13 07:21:33'),
(4, 2, 2, 2, '2026-02-13 07:30:46'),
(5, 5, 3, 0, '2026-03-07 12:57:10'),
(6, 11, 4, 10, '2026-03-18 13:35:15'),
(7, 12, 4, 6, '2026-03-18 13:41:05'),
(8, 13, 4, 7, '2026-03-25 08:51:34'),
(9, 14, 6, 3, '2026-04-13 10:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `sub_id` int(11) NOT NULL,
  `sub_name` varchar(100) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `exam_duration` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`sub_id`, `sub_name`, `start_time`, `end_time`, `exam_duration`) VALUES
(2, 'PHP MySQL', '2026-02-13 12:40:00', '2026-02-14 12:40:00', 10),
(3, 'Java', '2026-03-07 18:20:00', '2026-03-10 21:00:00', 10),
(4, 'C++', '2026-03-18 18:41:00', '2026-03-28 18:41:00', 30),
(5, 'JavaScript', '2026-04-13 18:01:00', '2026-04-20 18:01:00', 40),
(6, 'css', '2026-04-13 15:35:00', '2026-04-15 15:35:00', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `father_name` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `created_at`, `father_name`, `mother_name`, `dob`, `profile_pic`) VALUES
(1, 'Shrikant Kumar', 'Shri@gmail.com', '$2y$10$cFR6xCCljajZT2NkPAd.tecEwNO/uKwrPgPEoXdUSlOjmCD38D1nC', '2026-02-13 05:49:57', 'Virendra Chauhan', 'Vibha Devi', '2004-02-17', 'user_1_1770964231.jpg'),
(2, 'Abhijit ', 'abhijitsingh11100@gmail.com', '$2y$10$OwfFZ7IskqFdVnIj7MG8MuOkmarQTqpZy6Fs6LSw.JnFBYK8LDH7i', '2026-02-13 06:56:24', NULL, NULL, NULL, 'default.png'),
(3, 'Arv', 'abc@gmail.com', '$2y$10$.NFRJB2fR/.iL9U1aoPGfeynEuoQUkcaT/jSXUFhGObPadByn1qPW', '2026-02-13 06:56:38', NULL, NULL, NULL, 'default.png'),
(4, 'Rishav Raj', 'rishav@gmail.com', '$2y$10$0DgDJTKWm1cxVbIduy8luuhVMPRLi8bhVBWRzb9hJE.KQk2LTLJfe', '2026-02-13 06:57:51', NULL, NULL, NULL, 'default.png'),
(5, 'Neha', 'neha@gmail.com', '$2y$10$V359JoE5oUcJ69ohbrsHrOWrtCvMFKAougY.0BzxvZZrAP3Nm1EYm', '2026-03-07 12:56:05', '', '', '0000-00-00', 'user_5_1773380135.png'),
(6, 'Shrikant', 'Shrikant@gmail.com', '$2y$10$.FXnqOLqToidB1O0pQOdfO3374CmdaiGp/MvB5kDIbfV2M.8Kw/PO', '2026-03-18 13:27:56', NULL, NULL, NULL, 'default.png'),
(11, 'Hema', 'hemant@gmail.com', '$2y$10$dVUZKEmScoAFT75wYT2FZO2dhHYYteWMadHVsaFKsCwv7UAz4DV26', '2026-03-18 13:31:06', NULL, NULL, NULL, 'default.png'),
(12, 'Hemma', 'hemma@gmail.com', '$2y$10$kn8e1bviAxijc4WlYb07iuJt2yJr/qK26q2voO5CSJ/.XgWJn7m6C', '2026-03-18 13:39:34', NULL, NULL, NULL, 'default.png'),
(13, 'Shrik Kumar', 'shrik@gmail.com', '$2y$10$Sji4pRrZ8urDMMaVczJqa.DI2P.QQ01WBUY9qEVyNkJzYioY.X2Mm', '2026-03-25 08:25:05', NULL, NULL, NULL, 'default.png'),
(14, 'Shrikan Kumar', 'Pankajdh@gmail.com', '$2y$10$sL.CieVP.GBGfIf0qwEmbOGAmln6pUX.oSvIY9tOhQUVa5M/7gluW', '2026-04-13 10:03:53', NULL, NULL, NULL, 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`q_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`res_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`sub_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
