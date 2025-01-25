-- Database Section
-- ________________ 
drop schema if exists CaffeBoDB;
create database CaffeBoDB;
use CaffeBoDB;
-- DROP USER IF EXISTS 'web_app';
CREATE USER IF NOT EXISTS 'web_app'@'%' IDENTIFIED BY '1234';
-- DROP USER IF EXISTS web_app;
GRANT ALL PRIVILEGES ON *.* TO 'web_app'@'%' WITH GRANT OPTION;


-- Tables Section
-- _____________ 

CREATE TABLE Shipment (
    shipment_id INTEGER PRIMARY KEY,
    shipment_date DATETIME NOT NULL,
    address VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    zip_code VARCHAR(10) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Customer (
    customer_id INTEGER PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    address VARCHAR(100) NOT NULL,
    phone_number VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Cart (
    cart_id INTEGER PRIMARY KEY,
    quantity INTEGER NOT NULL,
    customer_id INTEGER NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
) ENGINE=InnoDB;

CREATE TABLE Wishlist (
    wishlist_id INTEGER PRIMARY KEY,
    customer_id INTEGER NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
) ENGINE=InnoDB;

CREATE TABLE Category (
    category_id INTEGER PRIMARY KEY,
    name VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Product (
    product_id INTEGER PRIMARY KEY,
    SKU VARCHAR(100) NOT NULL,
    description VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INTEGER NOT NULL,
    category_id INTEGER NOT NULL,
    FOREIGN KEY (category_id) REFERENCES Category(category_id)
) ENGINE=InnoDB;


CREATE TABLE Payment (
    payment_id INTEGER PRIMARY KEY,
    payment_date DATETIME NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL
) ENGINE=InnoDB;


CREATE TABLE `Order` (
    order_id INTEGER PRIMARY KEY,
    order_date DATETIME NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    customer_id INTEGER NOT NULL,
    shipment_id INTEGER NOT NULL,
    payment_id INTEGER NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id),
    FOREIGN KEY (shipment_id) REFERENCES Shipment(shipment_id),
    FOREIGN KEY (payment_id) REFERENCES Payment(payment_id)
) ENGINE=InnoDB;

CREATE TABLE Order_Item (
    order_item_id INTEGER PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES `Order`(order_id),
    FOREIGN KEY (product_id) REFERENCES `Product`(product_id)
) ENGINE=InnoDB;
