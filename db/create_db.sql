-- Database Section
-- ________________ 
DROP SCHEMA IF EXISTS CaffeBoDB;

CREATE DATABASE CaffeBoDB;

USE CaffeBoDB;

-- Elimina l'utente esistente
DROP USER IF EXISTS 'db_user' @'localhost';

-- Crea un nuovo utente con una password sicura
CREATE USER 'db_user' @'localhost' IDENTIFIED BY '1234';

-- Concedi privilegi solo sul database specifico
GRANT ALL PRIVILEGES ON CaffeBoDB.* TO 'db_user' @'localhost';

-- Applica i cambiamenti
FLUSH PRIVILEGES;

CREATE TABLE `Category` (
    `category_id` int (11) NOT NULL PRIMARY KEY,
    `NAME` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Payment` (
    `payment_id` int (11) NOT NULL PRIMARY KEY,
    `payment_date` datetime NOT NULL,
    `payment_method` varchar(100) NOT NULL,
    `amount` decimal(10, 2) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Product` (
    `product_id` int (11) NOT NULL PRIMARY KEY,
    `SKU` varchar(100) NOT NULL,
    `product_name` varchar(50) NOT NULL,
    `product_description` text NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `stock` int (11) NOT NULL,
    `category_id` int (11) NOT NULL,
    `image` varchar(255) DEFAULT NULL,
    FOREIGN KEY (`category_id`) REFERENCES `Category` (`category_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Roles` (
    `role_id` int (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `role_name` varchar(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `User` (
    `user_id` int (11) NOT NULL PRIMARY KEY,
    `first_name` varchar(30) NOT NULL,
    `last_name` varchar(30) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `passwordHash` varchar(60) NOT NULL,
    `address` varchar(100) DEFAULT NULL,
    `phone_number` varchar(12) NOT NULL,
    `role` int (11) NOT NULL,
    FOREIGN KEY (`role`) REFERENCES `Roles` (`role_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Cart` (
    `cart_id` int (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` int (11) NOT NULL UNIQUE,
    `product_id` int (11) NOT NULL,
    `quantity` int (11) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;



CREATE TABLE `Order_state` (
    `order_state_id` int (11) NOT NULL PRIMARY KEY,
    `descrizione` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Order` (
    `order_id` int (11) NOT NULL PRIMARY KEY,
    `order_date` datetime DEFAULT (CURRENT_DATE),
    `total_price` decimal(10, 2) NOT NULL,
    `user_id` int (11) NOT NULL,
    `order_state_id` int (11) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`),
    FOREIGN KEY (`order_state_id`) REFERENCES `Order_state` (`order_state_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Order_Detail` (
    `order_detail_id` int (11) NOT NULL PRIMARY KEY,
    `order_id` int (11) NOT NULL,
    `product_id` int (11) NOT NULL,
    `quantity` int (11) NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Shipment` (
    `shipment_id` int (11) NOT NULL PRIMARY KEY,
    `shipment_date` datetime NOT NULL,
    `address` varchar(100) NOT NULL,
    `order_id` int (11) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Wishlist` (
    `wishlist_id` int (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` int (11) NOT NULL,
    `product_id` int (11) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

COMMIT;
