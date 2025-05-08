-- Database Section
-- ________________ 
DROP SCHEMA IF EXISTS CaffeBoDB;

CREATE DATABASE CaffeBoDB;

USE CaffeBoDB;

-- Elimina l'utente esistente
DROP USER IF EXISTS 'db_user'@'localhost';

-- Crea un nuovo utente con una password sicura
CREATE USER 'db_user' @'localhost' IDENTIFIED BY '1234';

-- Concedi privilegi solo sul database specifico
GRANT ALL PRIVILEGES ON CaffeBoDB.* TO 'db_user' @'localhost';

-- Applica i cambiamenti
FLUSH PRIVILEGES;

CREATE TABLE `Roles` (
    `role_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `role_name` varchar(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `User` (
    `user_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `first_name` varchar(30) NOT NULL,
    `last_name` varchar(30) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `passwordHash` varchar(60) NOT NULL,
    `address` varchar(100) DEFAULT NULL,
    `phone_number` varchar(12) NOT NULL,
    `role` int(11) NOT NULL,
    FOREIGN KEY (`role`) REFERENCES `Roles` (`role_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Category` (
    `category_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `NAME` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Payment_Method` (
    `payment_method_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(100) NOT NULL,
    `description` varchar(255) DEFAULT NULL,
    `is_active` boolean DEFAULT TRUE,
    `icon` varchar(100) DEFAULT NULL,  -- Percorso ad un'icona/immagine rappresentativa
    `sort_order` int(11) DEFAULT 0     -- Per controllare l'ordine di visualizzazione
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Payment_Status` (
    `payment_status_id` int(11) NOT NULL PRIMARY KEY,
    `description` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Product` (
    `product_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `seller_id` int(11) NOT NULL PRIMARY KEY,
    `SKU` varchar(100) NOT NULL,
    `product_name` varchar(50) NOT NULL,
    `product_description` text NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `stock` int(11) NOT NULL,
    `category_id` int(11) NOT NULL,
    `image` varchar(255) DEFAULT NULL,
    `available` boolean DEFAULT 1,
    FOREIGN KEY (`category_id`) REFERENCES `Category` (`category_id`),
    FOREIGN KEY (`seller_id`) REFERENCES `User` (`user_id`),
    CHECK (`price` >= 0), -- Evita prezzi negativi
    CHECK (`stock` >= 0)  -- Evita stock negativi
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Cart` (
    `cart_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Cart_Detail` (
    `cart_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `quantity` INT(11) NOT NULL CHECK (`quantity` > 0), -- Evita quantità negative
    `price` DECIMAL(10,2) NOT NULL CHECK (`price` >= 0), -- Evita prezzi negativi
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`cart_id`, `product_id`), -- Garantisce che ogni prodotto sia univoco nel carrello
    FOREIGN KEY (`cart_id`) REFERENCES `Cart` (`cart_id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Order_State` (
    `order_state_id` int(11) NOT NULL PRIMARY KEY,
    `descrizione` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Shipment_Status` (
    `shipment_status_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `status` varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Shipping_Method` (
    `shipping_method_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` varchar(255) DEFAULT NULL,
    `price` decimal(3, 2) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Shipment` (
    `shipment_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `shipment_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `address` varchar(100) NOT NULL,
    `tracking_number` varchar(100) DEFAULT NULL,
    `shipping_method` int(11) NOT NULL,
    `status` int(11) NOT NULL,
    FOREIGN KEY (`status`) REFERENCES `Shipment_Status` (`shipment_status_id`),
    FOREIGN KEY (`shipping_method`) REFERENCES `Shipping_Method` (`shipping_method_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Order` (
    `order_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `total_price` decimal(10, 2) NOT NULL,
    `user_id` int(11) NOT NULL,
    `seller_id` int(11) DEFAULT NULL, -- Viene settato quando l'ordine viene accettato
    `order_state_id` int(11) NOT NULL,
    `shipment_id` int(11) DEFAULT NULL, -- Può essere NULL inizialmente
    FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`),
    FOREIGN KEY (`seller_id`) REFERENCES `User` (`user_id`), -- Assumendo che il venditore sia un utente
    FOREIGN KEY (`order_state_id`) REFERENCES `Order_State` (`order_state_id`),
    FOREIGN KEY (`shipment_id`) REFERENCES `Shipment` (`shipment_id`),
    CHECK (`total_price` >= 0) -- Evita prezzi negativi
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Payment` (
    `payment_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `order_id` int(11) NOT NULL,
    `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `payment_method_id` int(11) NOT NULL,
    `amount` decimal(10, 2) NOT NULL,
    `status` int(11) NOT NULL,
    `transaction_reference` varchar(255) DEFAULT NULL, -- Per numeri di riferimento
    FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`),
    FOREIGN KEY (`payment_method_id`) REFERENCES `Payment_Method` (`payment_method_id`),
    FOREIGN KEY (`status`) REFERENCES `Payment_Status` (`payment_status_id`),
    CHECK (`amount` >= 0) -- Evita importi negativi
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `Order_Detail` (
    `order_detail_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
    `price` decimal(10, 2) NOT NULL CHECK (`price` >= 0),
    FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


