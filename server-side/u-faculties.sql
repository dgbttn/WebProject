-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 18, 2019 lúc 08:11 PM
-- Phiên bản máy phục vụ: 10.1.38-MariaDB
-- Phiên bản PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `u-faculties-hoang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `role` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `address` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `phone_number` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `website` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `name`, `type`, `address`, `phone_number`, `website`) VALUES
(1, 'Bộ môn Công nghệ thông tin', 'Bộ môn', '304-E3', '0965708637', 'https://uet.vnu.edu.vn/'),
(3, 'Bá»™ mÃ´n Khoa há»c mÃ¡y tÃ­nh', 'Bá»™ mÃ´n', '204-E3', '1234', 'abc');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `research`
--

CREATE TABLE `research` (
  `research_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `research_staff`
--

CREATE TABLE `research_staff` (
  `research_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `full_name` varchar(45) NOT NULL,
  `vnu_email` varchar(45) NOT NULL,
  `staff_number` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  `other_email` varchar(45) DEFAULT NULL,
  `emai` varchar(45) DEFAULT NULL,
  `academic_title` varchar(45) DEFAULT NULL,
  `faculty_id` int(11) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `staff_type` varchar(45) DEFAULT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Chỉ mục cho bảng `research`
--
ALTER TABLE `research`
  ADD PRIMARY KEY (`research_id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Chỉ mục cho bảng `research_staff`
--
ALTER TABLE `research_staff`
  ADD PRIMARY KEY (`research_id`,`staff_id`),
  ADD KEY `staff_idx` (`staff_id`);

--
-- Chỉ mục cho bảng `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `staff_account_idx` (`account_id`),
  ADD KEY `staff_faculty_idx` (`faculty_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `research`
--
ALTER TABLE `research`
  MODIFY `research_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `research_staff`
--
ALTER TABLE `research_staff`
  ADD CONSTRAINT `research` FOREIGN KEY (`research_id`) REFERENCES `research` (`research_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `staff_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
