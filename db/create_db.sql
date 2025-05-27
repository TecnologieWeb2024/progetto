-- Database Section
-- ________________ 
DROP SCHEMA IF EXISTS CaffeBoDB;

CREATE DATABASE CaffeBoDB;

USE CaffeBoDB;

-- Elimina l'utente esistente
DROP USER IF EXISTS 'db_user'@'localhost';
-- Crea un nuovo utente con una password sicura
CREATE USER 'db_user'@'localhost' IDENTIFIED BY '1234';
-- Concedi privilegi solo sul database specifico
GRANT ALL PRIVILEGES ON CaffeBoDB.* TO 'db_user'@'localhost';
-- Applica i cambiamenti
FLUSH PRIVILEGES;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 13, 2025 at 08:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

--
-- Database: `CaffeBoDB`
--
-- --------------------------------------------------------
--
-- Table structure for table `Cart`
--
CREATE TABLE
    `Cart` (
        `cart_id` int (11) NOT NULL,
        `user_id` int (11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp()
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Cart_Detail`
--
CREATE TABLE
    `Cart_Detail` (
        `cart_id` int (11) NOT NULL,
        `product_id` int (11) NOT NULL,
        `quantity` int (11) NOT NULL
    );

-- --------------------------------------------------------
--
-- Table structure for table `Category`
--
CREATE TABLE
    `Category` (
        `category_id` int (11) NOT NULL,
        `NAME` varchar(100) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Order`
--
CREATE TABLE
    `Order` (
        `order_id` int (11) NOT NULL,
        `order_date` datetime DEFAULT current_timestamp(),
        `total_price` decimal(10, 2) NOT NULL,
        `user_id` int (11) NOT NULL,
        `order_status_id` int (11) NOT NULL,
        `shipment_id` int (11) DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Order_Detail`
--
CREATE TABLE
    `Order_Detail` (
        `order_detail_id` int (11) NOT NULL,
        `order_id` int (11) NOT NULL,
        `product_id` int (11) NOT NULL,
        `quantity` int (11) NOT NULL
    );

-- --------------------------------------------------------
--
-- Table structure for table `Order_Status`
--
CREATE TABLE
    `Order_Status` (
        `order_status_id` int (11) NOT NULL,
        `descrizione` varchar(100) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Payment`
--
CREATE TABLE
    `Payment` (
        `payment_id` int (11) NOT NULL,
        `order_id` int (11) NOT NULL,
        `payment_date` datetime DEFAULT current_timestamp(),
        `payment_method_id` int (11) NOT NULL,
        `amount` decimal(10, 2) NOT NULL,
        `status` int (11) NOT NULL,
        `transaction_reference` varchar(255) DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Payment_Method`
--
CREATE TABLE
    `Payment_Method` (
        `payment_method_id` int (11) NOT NULL,
        `name` varchar(100) NOT NULL,
        `description` varchar(255) DEFAULT NULL,
        `is_active` tinyint (1) DEFAULT 1,
        `icon` varchar(100) DEFAULT NULL,
        `sort_order` int (11) DEFAULT 0
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Payment_Status`
--
CREATE TABLE
    `Payment_Status` (
        `payment_status_id` int (11) NOT NULL,
        `description` varchar(100) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Product`
--
CREATE TABLE
    `Product` (
        `product_id` int (11) NOT NULL,
        `seller_id` int (11) NOT NULL,
        `SKU` varchar(100) NOT NULL,
        `product_name` varchar(50) NOT NULL,
        `product_description` text NOT NULL,
        `price` decimal(10, 2) NOT NULL,
        `stock` int (11) NOT NULL,
        `category_id` int (11) NOT NULL,
        `image` varchar(255) DEFAULT NULL,
        `available` tinyint (1) DEFAULT 1
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Roles`
--
CREATE TABLE
    `Roles` (
        `role_id` int (11) NOT NULL,
        `role_name` varchar(30) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Shipment`
--
CREATE TABLE
    `Shipment` (
        `shipment_id` int (11) NOT NULL,
        `shipment_date` datetime DEFAULT current_timestamp(),
        `address` varchar(100) NOT NULL,
        `tracking_number` varchar(100) DEFAULT NULL,
        `shipping_method` int (11) NOT NULL,
        `status` int (11) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Shipment_Status`
--
CREATE TABLE
    `Shipment_Status` (
        `shipment_status_id` int (11) NOT NULL,
        `status` varchar(50) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Shipping_Method`
--
CREATE TABLE
    `Shipping_Method` (
        `shipping_method_id` int (11) NOT NULL,
        `name` varchar(100) NOT NULL,
        `description` varchar(255) DEFAULT NULL,
        `icon` varchar(100) DEFAULT NULL,
        `price` decimal(3, 2) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `User`
--
CREATE TABLE
    `User` (
        `user_id` int (11) NOT NULL,
        `first_name` varchar(30) NOT NULL,
        `last_name` varchar(30) NOT NULL,
        `email` varchar(100) NOT NULL,
        `passwordHash` varchar(60) NOT NULL,
        `address` varchar(100) DEFAULT NULL,
        `phone_number` varchar(12) NOT NULL,
        `role` int (11) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


CREATE TABLE Notification (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);
--
-- Indexes for dumped tables
--
--
-- Indexes for table `Cart`
--
ALTER TABLE `Cart` ADD PRIMARY KEY (`cart_id`),
ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `Cart_Detail`
--
ALTER TABLE `Cart_Detail` ADD PRIMARY KEY (`cart_id`, `product_id`),
ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category` ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `Order`
--
ALTER TABLE `Order` ADD PRIMARY KEY (`order_id`),
ADD KEY `user_id` (`user_id`),
ADD KEY `order_status_id` (`order_status_id`),
ADD KEY `shipment_id` (`shipment_id`);

--
-- Indexes for table `Order_Detail`
--
ALTER TABLE `Order_Detail` ADD PRIMARY KEY (`order_detail_id`),
ADD KEY `order_id` (`order_id`),
ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `Order_Status`
--
ALTER TABLE `Order_Status` ADD PRIMARY KEY (`order_status_id`);

--
-- Indexes for table `Payment`
--
ALTER TABLE `Payment` ADD PRIMARY KEY (`payment_id`),
ADD KEY `order_id` (`order_id`),
ADD KEY `payment_method_id` (`payment_method_id`),
ADD KEY `status` (`status`);

--
-- Indexes for table `Payment_Method`
--
ALTER TABLE `Payment_Method` ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `Payment_Status`
--
ALTER TABLE `Payment_Status` ADD PRIMARY KEY (`payment_status_id`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product` ADD PRIMARY KEY (`product_id`),
ADD KEY `category_id` (`category_id`),
ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles` ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `Shipment`
--
ALTER TABLE `Shipment` ADD PRIMARY KEY (`shipment_id`),
ADD KEY `status` (`status`),
ADD KEY `shipping_method` (`shipping_method`);

--
-- Indexes for table `Shipment_Status`
--
ALTER TABLE `Shipment_Status` ADD PRIMARY KEY (`shipment_status_id`);

--
-- Indexes for table `Shipping_Method`
--
ALTER TABLE `Shipping_Method` ADD PRIMARY KEY (`shipping_method_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User` ADD PRIMARY KEY (`user_id`),
ADD UNIQUE KEY `email` (`email`),
ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `Cart`
--
ALTER TABLE `Cart` MODIFY `cart_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category` MODIFY `category_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Order`
--
ALTER TABLE `Order` MODIFY `order_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Order_Detail`
--
ALTER TABLE `Order_Detail` MODIFY `order_detail_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Order_Status`
--
ALTER TABLE `Order_Status` MODIFY `order_status_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Payment`
--
ALTER TABLE `Payment` MODIFY `payment_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Payment_Method`
--
ALTER TABLE `Payment_Method` MODIFY `payment_method_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Payment_Status`
--
ALTER TABLE `Payment_Status` MODIFY `payment_status_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product` MODIFY `product_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles` MODIFY `role_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Shipment`
--
ALTER TABLE `Shipment` MODIFY `shipment_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Shipment_Status`
--
ALTER TABLE `Shipment_Status` MODIFY `shipment_status_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Shipping_Method`
--
ALTER TABLE `Shipping_Method` MODIFY `shipping_method_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User` MODIFY `user_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `Cart`
--
ALTER TABLE `Cart` ADD CONSTRAINT `Cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Cart_Detail`
--
ALTER TABLE `Cart_Detail` ADD CONSTRAINT `Cart_Detail_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `Cart` (`cart_id`) ON DELETE CASCADE,
ADD CONSTRAINT `Cart_Detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `Order`
--
ALTER TABLE `Order` ADD CONSTRAINT `Order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`),
ADD CONSTRAINT `Order_ibfk_3` FOREIGN KEY (`order_status_id`) REFERENCES `Order_Status` (`order_status_id`),
ADD CONSTRAINT `Order_ibfk_4` FOREIGN KEY (`shipment_id`) REFERENCES `Shipment` (`shipment_id`);

--
-- Constraints for table `Order_Detail`
--
ALTER TABLE `Order_Detail` ADD CONSTRAINT `Order_Detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE,
ADD CONSTRAINT `Order_Detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`);

--
-- Constraints for table `Payment`
--
ALTER TABLE `Payment` ADD CONSTRAINT `Payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE,
ADD CONSTRAINT `Payment_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `Payment_Method` (`payment_method_id`),
ADD CONSTRAINT `Payment_ibfk_3` FOREIGN KEY (`status`) REFERENCES `Payment_Status` (`payment_status_id`);

--
-- Constraints for table `Product`
--
ALTER TABLE `Product` ADD CONSTRAINT `Product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Category` (`category_id`),
ADD CONSTRAINT `Product_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `User` (`user_id`);

--
-- Constraints for table `Shipment`
--
ALTER TABLE `Shipment` ADD CONSTRAINT `Shipment_ibfk_1` FOREIGN KEY (`status`) REFERENCES `Shipment_Status` (`shipment_status_id`),
ADD CONSTRAINT `Shipment_ibfk_2` FOREIGN KEY (`shipping_method`) REFERENCES `Shipping_Method` (`shipping_method_id`);

--
-- Constraints for table `User`
--
ALTER TABLE `User` ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`role`) REFERENCES `Roles` (`role_id`);

COMMIT;

-- Optimize seller-specific queries
ALTER TABLE `Product` ADD INDEX `idx_product_seller_stock` (`seller_id`, `stock`),
ADD INDEX `idx_product_category` (`category_id`, `available`);

-- Optimize order processing
ALTER TABLE `Order` ADD INDEX `idx_order_date_status` (`order_date`, `order_status_id`),
ADD INDEX `idx_order_user_status` (`user_id`, `order_status_id`);

-- Optimize order details joins
ALTER TABLE `Order_Detail` ADD INDEX `idx_detail_product_order` (`product_id`, `order_id`),
ADD INDEX `idx_detail_order_product` (`order_id`, `product_id`);

-- Optimize user role management
ALTER TABLE `User` ADD INDEX `idx_user_role` (`role`, `user_id`);

-- Optimize payment processing
ALTER TABLE `Payment` ADD INDEX `idx_payment_order_status` (`order_id`, `status`),
ADD INDEX `idx_payment_date_method` (`payment_date`, `payment_method_id`);

-- Optimize shipment tracking
ALTER TABLE `Shipment` ADD INDEX `idx_shipment_date_status` (`shipment_date`, `status`),
ADD INDEX `idx_shipment_tracking` (`tracking_number`);

-- Optimize cart operations
ALTER TABLE `Cart_Detail` ADD INDEX `idx_cart_product_quantity` (`product_id`, `quantity`);

-- Optimize product search
ALTER TABLE `Product` ADD INDEX `idx_product_price_stock` (`price`, `stock`),
ADD INDEX `idx_product_sku` (`SKU`),
ADD INDEX `idx_product_availability` (`available`, `category_id`);