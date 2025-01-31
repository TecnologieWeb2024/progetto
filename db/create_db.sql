-- Database Section
-- ________________ 
DROP SCHEMA IF EXISTS CaffeBoDB;

CREATE DATABASE CaffeBoDB;

USE CaffeBoDB;

-- Elimina l'utente esistente
DROP
    USER IF EXISTS 'db_user'@'localhost';
-- Crea un nuovo utente con una password sicura
CREATE USER 'db_user'@'localhost' 
    IDENTIFIED BY '1234';
-- Concedi privilegi solo sul database specifico
GRANT ALL PRIVILEGES 
    ON CaffeBoDB.* TO 'db_user'@'localhost';
-- Applica i cambiamenti
FLUSH PRIVILEGES;

-- Tables Section
-- _____________ 
--
-- Table structure for table `Cart`
--
CREATE TABLE
    `Cart` (
        `cart_id` int (11) NOT NULL,
        `user_id` int (11) NOT NULL,
        `product_id` int (11) NOT NULL,
        `quantity` int (11) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

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
        `order_date` datetime NOT NULL,
        `total_price` decimal(10, 2) NOT NULL,
        `user_id` int (11) NOT NULL,
        `shipment_id` int (11) NOT NULL,
        `payment_id` int (11) NOT NULL
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
        `quantity` int (11) NOT NULL,
        `price` decimal(10, 2) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Payment`
--
CREATE TABLE
    `Payment` (
        `payment_id` int (11) NOT NULL,
        `payment_date` datetime NOT NULL,
        `payment_method` varchar(100) NOT NULL,
        `amount` decimal(10, 2) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `Product`
--
CREATE TABLE
    `Product` (
        `product_id` int (11) NOT NULL,
        `SKU` varchar(100) NOT NULL,
        `product_name` varchar(50) NOT NULL,
        `product_description` text NOT NULL,
        `price` decimal(10, 2) NOT NULL,
        `stock` int (11) NOT NULL,
        `category_id` int (11) NOT NULL,
        `image` varchar(255) DEFAULT NULL
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
        `shipment_date` datetime NOT NULL,
        `address` varchar(100) NOT NULL,
        `STATUS` varchar(50) NOT NULL
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

-- --------------------------------------------------------
--
-- Table structure for table `Wishlist`
--
CREATE TABLE
    `Wishlist` (
        `wishlist_id` int (11) NOT NULL,
        `user_id` int (11) NOT NULL,
        `product_id` int (11) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Indexes for dumped tables
--
--
-- Indexes for table `Cart`
--
ALTER TABLE `Cart` ADD PRIMARY KEY (`cart_id`),
ADD UNIQUE KEY `user_id` (`user_id`, `product_id`),
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
ADD KEY `shipment_id` (`shipment_id`),
ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `Order_Detail`
--
ALTER TABLE `Order_Detail` ADD PRIMARY KEY (`order_detail_id`),
ADD KEY `order_id` (`order_id`),
ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `Payment`
--
ALTER TABLE `Payment` ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product` ADD PRIMARY KEY (`product_id`),
ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles` ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `Shipment`
--
ALTER TABLE `Shipment` ADD PRIMARY KEY (`shipment_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User` ADD PRIMARY KEY (`user_id`),
ADD UNIQUE KEY `email` (`email`),
ADD KEY `role` (`role`);

--
-- Indexes for table `Wishlist`
--
ALTER TABLE `Wishlist` ADD PRIMARY KEY (`wishlist_id`),
ADD UNIQUE KEY `user_id` (`user_id`, `product_id`),
ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `Cart`
--
ALTER TABLE `Cart` MODIFY `cart_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles` MODIFY `role_id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT for table `Shipment`
--
ALTER TABLE `Shipment` MODIFY `shipment_id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User` MODIFY `user_id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 6;

--
-- AUTO_INCREMENT for table `Wishlist`
--
ALTER TABLE `Wishlist` MODIFY `wishlist_id` int (11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `Cart`
--
ALTER TABLE `Cart` ADD CONSTRAINT `Cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
ADD CONSTRAINT `Cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `Order`
--
ALTER TABLE `Order` ADD CONSTRAINT `Order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`),
ADD CONSTRAINT `Order_ibfk_2` FOREIGN KEY (`shipment_id`) REFERENCES `Shipment` (`shipment_id`),
ADD CONSTRAINT `Order_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `Payment` (`payment_id`);

--
-- Constraints for table `Order_Detail`
--
ALTER TABLE `Order_Detail` ADD CONSTRAINT `Order_Detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`),
ADD CONSTRAINT `Order_Detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`);

--
-- Constraints for table `Product`
--
ALTER TABLE `Product` ADD CONSTRAINT `Product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Category` (`category_id`);

--
-- Constraints for table `User`
--
ALTER TABLE `User` ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`role`) REFERENCES `Roles` (`role_id`);

--
-- Constraints for table `Wishlist`
--
ALTER TABLE `Wishlist` ADD CONSTRAINT `Wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
ADD CONSTRAINT `Wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE;

COMMIT;