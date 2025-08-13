-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 06:11 PM
-- Server version: 10.4.32-MariaDB
--PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `online_voting`
-- Table structure for table `user`

CREATE TABLE `user` (
  `I.D` int(100) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Mobile` bigint(10) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `Photo` varchar(255) NOT NULL,
  `Role` enum('voter','candidate','admin') NOT NULL,
  `Status` int(1) NOT NULL,
  `Votes` int(100) NOT NULL,
  `serial_number` int(11) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `user` (`I.D`, `Name`, `Mobile`, `Password`, `Address`, `Photo`, `Role`, `Status`, `Votes`, `serial_number`, `profile_image`) VALUES
(1, 'Sam', 1212121212, '$2y$10$/pCpATjLBFzuXeZCwRvw2.I8GW53doYCLPJaFWl1Tb1', 'banepa', '', 'voter', 0, 0, NULL, '1.jpg');


ALTER TABLE `user`
  ADD PRIMARY KEY (`I.D`);


ALTER TABLE `user`
  MODIFY `I.D` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

