-- Database Section
-- ________________ 
DROP SCHEMA IF EXISTS CaffeBoDB;

CREATE DATABASE CaffeBoDB;

USE CaffeBoDB;

-- Elimina l'utente esistente
DROP USER IF EXISTS 'db_user' @ 'localhost';
-- Crea un nuovo utente con una password sicura
CREATE USER 'db_user' @'localhost' IDENTIFIED BY '1234';
-- Concedi privilegi solo sul database specifico
GRANT ALL PRIVILEGES ON CaffeBoDB.* TO 'db_user' @'localhost';
-- Applica i cambiamenti
FLUSH PRIVILEGES;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 09, 2025 at 03:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CaffeBoDB`
--
-- --------------------------------------------------------
--
-- Table structure for table `Roles`
-- (No dependencies)
--
CREATE TABLE
    `Roles` (
        `role_id` int (11) NOT NULL AUTO_INCREMENT,
        `role_name` varchar(30) NOT NULL,
        PRIMARY KEY (`role_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `User`
-- (Depends on Roles)
--
CREATE TABLE
    `User` (
        `user_id` int (11) NOT NULL AUTO_INCREMENT,
        `first_name` varchar(30) NOT NULL,
        `last_name` varchar(30) NOT NULL,
        `email` varchar(100) NOT NULL,
        `passwordHash` varchar(60) NOT NULL,
        `address` varchar(100) DEFAULT NULL,
        `phone_number` varchar(12) NOT NULL,
        `role` int (11) NOT NULL,
        PRIMARY KEY (`user_id`),
        UNIQUE KEY `email` (`email`),
        KEY `role` (`role`),
        CONSTRAINT `User_ibfk_1` FOREIGN KEY (`role`) REFERENCES `Roles` (`role_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Category`
-- (No dependencies)
--
CREATE TABLE
    `Category` (
        `category_id` int (11) NOT NULL AUTO_INCREMENT,
        `NAME` varchar(100) NOT NULL,
        PRIMARY KEY (`category_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Product`
-- (Depends on Category and User)
--
CREATE TABLE
    `Product` (
        `product_id` int (11) NOT NULL AUTO_INCREMENT,
        `seller_id` int (11) NOT NULL,
        `SKU` varchar(100) NOT NULL,
        `product_name` varchar(50) NOT NULL,
        `product_description` text NOT NULL,
        `price` decimal(10, 2) NOT NULL,
        `stock` int (11) NOT NULL,
        `category_id` int (11) NOT NULL,
        `image` varchar(255) DEFAULT NULL,
        `available` tinyint (1) DEFAULT 1,
        PRIMARY KEY (`product_id`),
        KEY `category_id` (`category_id`),
        KEY `seller_id` (`seller_id`),
        CONSTRAINT `Product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Category` (`category_id`),
        CONSTRAINT `Product_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `User` (`user_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Cart`
-- (Depends on User)
--
CREATE TABLE
    `Cart` (
        `cart_id` int (11) NOT NULL AUTO_INCREMENT,
        `user_id` int (11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`cart_id`),
        UNIQUE KEY `user_id` (`user_id`),
        CONSTRAINT `Cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Cart_Detail`
-- (Depends on Cart and Product)
--
CREATE TABLE
    `Cart_Detail` (
        `cart_id` int (11) NOT NULL,
        `product_id` int (11) NOT NULL,
        `quantity` int (11) NOT NULL,
        `price` decimal(10, 2) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`cart_id`, `product_id`),
        KEY `product_id` (`product_id`),
        CONSTRAINT `Cart_Detail_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `Cart` (`cart_id`) ON DELETE CASCADE,
        CONSTRAINT `Cart_Detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE,
        CONSTRAINT `check_quantity` CHECK (`quantity` > 0),
        CONSTRAINT `check_price` CHECK (`price` >= 0)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Shipping_Method`
-- (No dependencies)
--
CREATE TABLE
    `Shipping_Method` (
        `shipping_method_id` int (11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `description` varchar(255) DEFAULT NULL,
        `icon` varchar(100) DEFAULT NULL,
        `price` decimal(3, 2) NOT NULL,
        PRIMARY KEY (`shipping_method_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Shipment_Status`
-- (No dependencies)
--
CREATE TABLE
    `Shipment_Status` (
        `shipment_status_id` int (11) NOT NULL AUTO_INCREMENT,
        `status` varchar(50) NOT NULL,
        PRIMARY KEY (`shipment_status_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Shipment`
-- (Depends on Shipment_Status and Shipping_Method)
--
CREATE TABLE
    `Shipment` (
        `shipment_id` int (11) NOT NULL AUTO_INCREMENT,
        `shipment_date` datetime DEFAULT current_timestamp(),
        `address` varchar(100) NOT NULL,
        `tracking_number` varchar(100) DEFAULT NULL,
        `shipping_method` int (11) NOT NULL,
        `status` int (11) NOT NULL,
        PRIMARY KEY (`shipment_id`),
        KEY `status` (`status`),
        KEY `shipping_method` (`shipping_method`),
        CONSTRAINT `Shipment_ibfk_1` FOREIGN KEY (`status`) REFERENCES `Shipment_Status` (`shipment_status_id`),
        CONSTRAINT `Shipment_ibfk_2` FOREIGN KEY (`shipping_method`) REFERENCES `Shipping_Method` (`shipping_method_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Order_State`
-- (No dependencies)
--
CREATE TABLE
    `Order_State` (
        `order_state_id` int (11) NOT NULL AUTO_INCREMENT,
        `descrizione` varchar(100) NOT NULL,
        PRIMARY KEY (`order_state_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Order`
-- (Depends on User, Shipment, Order_State)
--
CREATE TABLE
    `Order` (
        `order_id` int (11) NOT NULL AUTO_INCREMENT,
        `order_date` datetime DEFAULT current_timestamp(),
        `total_price` decimal(10, 2) NOT NULL,
        `user_id` int (11) NOT NULL,
        `seller_id` int (11) DEFAULT NULL,
        `order_state_id` int (11) NOT NULL,
        `shipment_id` int (11) DEFAULT NULL,
        PRIMARY KEY (`order_id`),
        KEY `user_id` (`user_id`),
        KEY `seller_id` (`seller_id`),
        KEY `order_state_id` (`order_state_id`),
        KEY `shipment_id` (`shipment_id`),
        CONSTRAINT `Order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`),
        CONSTRAINT `Order_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `User` (`user_id`),
        CONSTRAINT `Order_ibfk_3` FOREIGN KEY (`order_state_id`) REFERENCES `Order_State` (`order_state_id`),
        CONSTRAINT `Order_ibfk_4` FOREIGN KEY (`shipment_id`) REFERENCES `Shipment` (`shipment_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Order_Detail`
-- (Depends on Order and Product)
--
CREATE TABLE
    `Order_Detail` (
        `order_detail_id` int (11) NOT NULL AUTO_INCREMENT,
        `order_id` int (11) NOT NULL,
        `product_id` int (11) NOT NULL,
        `quantity` int (11) NOT NULL,
        `price` decimal(10, 2) NOT NULL,
        PRIMARY KEY (`order_detail_id`),
        KEY `order_id` (`order_id`),
        KEY `product_id` (`product_id`),
        CONSTRAINT `Order_Detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE,
        CONSTRAINT `Order_Detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`),
        CONSTRAINT `check_quantity_od` CHECK (`quantity` > 0),
        CONSTRAINT `check_price_od` CHECK (`price` >= 0)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Payment_Method`
-- (No dependencies)
--
CREATE TABLE
    `Payment_Method` (
        `payment_method_id` int (11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `description` varchar(255) DEFAULT NULL,
        `is_active` tinyint (1) DEFAULT 1,
        `icon` varchar(100) DEFAULT NULL,
        `sort_order` int (11) DEFAULT 0,
        PRIMARY KEY (`payment_method_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Payment_Status`
-- (No dependencies)
--
CREATE TABLE
    `Payment_Status` (
        `payment_status_id` int (11) NOT NULL AUTO_INCREMENT,
        `description` varchar(100) NOT NULL,
        PRIMARY KEY (`payment_status_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Payment`
-- (Depends on Order, Payment_Method, Payment_Status)
--
CREATE TABLE
    `Payment` (
        `payment_id` int (11) NOT NULL AUTO_INCREMENT,
        `order_id` int (11) NOT NULL,
        `payment_date` datetime DEFAULT current_timestamp(),
        `payment_method_id` int (11) NOT NULL,
        `amount` decimal(10, 2) NOT NULL,
        `status` int (11) NOT NULL,
        `transaction_reference` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`payment_id`),
        KEY `order_id` (`order_id`),
        KEY `payment_method_id` (`payment_method_id`),
        KEY `status` (`status`),
        CONSTRAINT `Payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`),
        CONSTRAINT `Payment_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `Payment_Method` (`payment_method_id`),
        CONSTRAINT `Payment_ibfk_3` FOREIGN KEY (`status`) REFERENCES `Payment_Status` (`payment_status_id`)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;