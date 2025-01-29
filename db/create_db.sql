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

-- Tables Section
-- _____________ 
CREATE TABLE
    Shipment (
        shipment_id INTEGER PRIMARY KEY,
        shipment_date DATETIME NOT NULL,
        address VARCHAR(100) NOT NULL,
        status VARCHAR(50) NOT NULL
    ) ENGINE = InnoDB;

CREATE TABLE
    Roles (
        role_id INTEGER PRIMARY KEY AUTO_INCREMENT,
        first_name VARCHAR(30) NOT NULL
    ) ENGINE = InnoDB;

CREATE TABLE
    `User` (
        user_id INTEGER PRIMARY KEY AUTO_INCREMENT,
        first_name VARCHAR(30) NOT NULL,
        last_name VARCHAR(30) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        passwordHash VARCHAR(255) NOT NULL,
        address VARCHAR(100), -- Si potrebbe rimuovere il campo, basta che l'utente lo inserisca al momento dell'ordine
        phone_number VARCHAR(12) NOT NULL,
        role INTEGER NOT NULL,
        FOREIGN KEY (role) REFERENCES Roles (role_id)
    ) ENGINE = InnoDB;

CREATE TABLE
    Category (
        category_id INTEGER PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    ) ENGINE = InnoDB;

CREATE TABLE
    Product (
        product_id INTEGER PRIMARY KEY,
        SKU VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        stock INTEGER NOT NULL,
        category_id INTEGER NOT NULL,
        FOREIGN KEY (category_id) REFERENCES Category (category_id)
    ) ENGINE = InnoDB;
    
CREATE TABLE
    Wishlist (
        wishlist_id INTEGER PRIMARY KEY AUTO_INCREMENT,
        user_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        FOREIGN KEY (user_id) REFERENCES `User` (user_id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES Product (product_id) ON DELETE CASCADE,
        UNIQUE (user_id, product_id) -- Ensures a user cannot add the same product multiple times
    ) ENGINE = InnoDB;

CREATE TABLE
    Payment (
        payment_id INTEGER PRIMARY KEY,
        payment_date DATETIME NOT NULL,
        payment_method VARCHAR(100) NOT NULL,
        amount DECIMAL(10, 2) NOT NULL
    ) ENGINE = InnoDB;

CREATE TABLE
    `Order` (
        order_id INTEGER PRIMARY KEY,
        order_date DATETIME NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        user_id INTEGER NOT NULL,
        shipment_id INTEGER NOT NULL,
        payment_id INTEGER NOT NULL,
        FOREIGN KEY (user_id) REFERENCES `User` (user_id),
        FOREIGN KEY (shipment_id) REFERENCES Shipment (shipment_id),
        FOREIGN KEY (payment_id) REFERENCES Payment (payment_id)
    ) ENGINE = InnoDB;

CREATE TABLE
    Order_Item (
        order_item_id INTEGER PRIMARY KEY,
        order_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES `Order` (order_id),
        FOREIGN KEY (product_id) REFERENCES `Product` (product_id)
    ) ENGINE = InnoDB;
    
CREATE TABLE
    Cart (
        cart_id INTEGER PRIMARY KEY AUTO_INCREMENT,
        user_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL,
        FOREIGN KEY (user_id) REFERENCES `User` (user_id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES Product (product_id) ON DELETE CASCADE,
        UNIQUE (user_id, product_id) -- Ensures a user cannot have duplicate entries for the same product
    ) ENGINE = InnoDB;