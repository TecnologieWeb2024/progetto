--
-- Dumping data for table `Roles` (independent table)
--
INSERT INTO
    `Roles` (`role_id`, `role_name`)
VALUES
    (1, 'seller'),
    (2, 'customer');

--
-- Dumping data for table `User` (depends on Roles)
--
INSERT INTO
    `User` (
        `user_id`,
        `first_name`,
        `last_name`,
        `email`,
        `passwordHash`,
        `address`,
        `phone_number`,
        `role`
    )
VALUES
    (
        1,
        'VenditoreEsempio',
        'CognomeVenditore',
        'venditore@example.com',
        '$2a$12$YdBzuFDfMhBHwgj5HG3doeibBe2AWlkfON69gGQq42Z9yZ9k/Jlrm',
        'Via Esempio 123, Cesena, FC',
        '3331231231',
        1
    ),
    (
        2,
        'ProvaNomeUno',
        'ProvaCognomeUno',
        'prova-1@test.com',
        '$2y$10$kZSfGM9OgyOfrBf8I0WdZutr7lcaXGSRX0ewQlvGs.tJEYIb6EepC',
        'Via Controesempio 123, Cesena, FC',
        '3331231231',
        2
    ),
    (
        3,
        'VenditoreDue',
        'CognomeVenditore',
        'venditore2@example.com',
        '$2a$12$YdBzuFDfMhBHwgj5HG3doeibBe2AWlkfON69gGQq42Z9yZ9k/Jlrm',
        'Via Esempio 456, Cesena, FC',
        '3331231232',
        1
    );

--
-- Dumping data for table `Category` (independent table)
--
INSERT INTO
    `Category` (`category_id`, `NAME`)
VALUES
    (1, 'Caffè in grani'),
    (2, 'Caffè macinato'),
    (3, 'Caffè Decaffeinato');

--
-- Dumping data for table `Product` (depends on User/seller and Category)
--
INSERT INTO
    `Product` (
        `product_id`,
        `seller_id`,
        `SKU`,
        `product_name`,
        `product_description`,
        `price`,
        `stock`,
        `category_id`,
        `image`,
        `available`
    )
VALUES
    (
        1,
        1,
        'CAF001',
        'Caffè Arabica 250g',
        'Caffè arabica in grani, 250g',
        5.99,
        100,
        1,
        'assets/img/products/CAF001.jpg',
        1
    ),
    (
        2,
        1,
        'CAF002',
        'Caffè Robusta 250g',
        'Caffè robusta in grani, 250g',
        4.99,
        150,
        1,
        'assets/img/products/CAF001.jpg',
        1
    ),
    (
        3,
        1,
        'CAF003',
        'Caffè Macinato 250g',
        'Caffè macinato, 250g',
        5.49,
        120,
        2,
        'assets/img/products/CAF001.jpg',
        1
    ),
    (
        4,
        1,
        'CAF004',
        'Caffè Decaffeinato 250g',
        'Caffè decaffeinato in grani, 250g',
        6.49,
        80,
        3,
        'assets/img/products/CAF001.jpg',
        1
    ),
    (
        5,
        1,
        'CAF005',
        'Caffè Decaf. Macinato 250g',
        'Caffè decaffeinato macinato, 250g',
        6.99,
        70,
        3,
        'assets/img/products/CAF001.jpg',
        1
    ),
    (
        6,
        3,
        'CAF006',
        'Caffè Specialty 250g',
        'Caffè specialty in grani, 250g',
        8.99,
        50,
        1,
        'assets/img/products/CAF001.jpg',
        1
    ),
    (
        7,
        3,
        'CAF007',
        'Espresso Blend 500g',
        'Miscela per espresso, 500g',
        12.49,
        30,
        2,
        'assets/img/products/CAF001.jpg',
        1
    );

--
-- Dumping data for table `Cart` (depends on User)
--
INSERT INTO
    `Cart` (`cart_id`, `user_id`, `created_at`)
VALUES
    (2, 2, '2025-05-08 14:29:20');

--
-- Dumping data for table `Cart_Detail` (depends on Cart and Product)
--
INSERT INTO
    `Cart_Detail` (
        `cart_id`,
        `product_id`,
        `quantity`
    )
VALUES
    (2, 1, 3);

--
-- Dumping data for table `Shipment_Status` (independent table)
--
INSERT INTO
    `Shipment_Status` (`shipment_status_id`, `status`)
VALUES
    (1, 'In preparazione'),
    (2, 'In transito'),
    (3, 'Consegnato');

--
-- Dumping data for table `Shipping_Method` (independent table)
--
INSERT INTO
    `Shipping_Method` (
        `shipping_method_id`,
        `name`,
        `description`,
        `icon`,
        `price`
    )
VALUES
    (
        1,
        'Spedizione Standard',
        '7-10 giorni',
        'assets/img/icons/shipping.png',
        2.99
    ),
    (
        2,
        'Express',
        '3-5 giorni',
        'assets/img/icons/express_shipping.png',
        5.99
    ),
    (
        3,
        'Ritiro in Negozio',
        'Ritiro diretto',
        'assets/img/icons/free_shipping.png',
        0.00
    );

--
-- Dumping data for table `Shipment` (depends on Shipping_Method and Shipment_Status)
--
INSERT INTO
    `Shipment` (
        `shipment_id`,
        `shipment_date`,
        `address`,
        `tracking_number`,
        `shipping_method`,
        `status`
    )
VALUES
    (
        1,
        '2025-05-08 16:29:20',
        'Via Controesempio 123, Cesena, FC',
        NULL,
        1,
        1
    ),
    (
        2,
        '2025-05-08 16:29:20',
        'Via Controesempio 123, Cesena, FC',
        NULL,
        1,
        2
    ),
    (
        3,
        '2025-05-08 16:29:20',
        'Via Controesempio 123, Cesena, FC',
        NULL,
        2,
        2
    ),
    (
        4,
        '2025-05-08 16:29:20',
        'Via Controesempio 123, Cesena, FC',
        NULL,
        1,
        3
    ),
    (
        5,
        '2025-05-08 16:29:20',
        'Via Esempio 456, Cesena, FC',
        NULL,
        2,
        1
    ),
    (
        6,
        '2025-05-08 16:29:20',
        'Via Controesempio 123, Cesena, FC',
        NULL,
        1,
        1
    );

--
-- Dumping data for table `Order_Status` (independent table)
--
INSERT INTO
    `Order_Status` (`order_status_id`, `descrizione`)
VALUES
    (1, 'Checkout'),
    (2, 'In attesa di pagamento'),
    (3, 'Pagato'),
    (4, 'In preparazione'),
    (5, 'Spedito'),
    (6, 'Consegnato'),
    (7, 'Cancellato'),
    (8, 'Rimborsato');

--
-- Dumping data for table `Order` (depends on User, Seller, Order_Status, Shipment)
--
INSERT INTO
    `Order` (
        `order_id`,
        `order_date`,
        `total_price`,
        `user_id`,
        `order_status_id`,
        `shipment_id`
    )
VALUES
    (1, '2025-01-08 16:29:20', 26.95, 2, 1, 1),
    (2, '2025-02-10 16:29:20', 26.95, 2, 2, 2),
    (3, '2025-03-12 16:29:20', 26.95, 2, 3, 3),
    (4, '2025-04-14 16:29:20', 26.95, 2, 4, 4),
    (5, '2025-05-16 16:29:20', 14.98, 2, 1, 5),
    (6, '2025-06-18 16:52:42', 14.98, 2, 1, 5),
    (7, '2025-02-24 11:15:27', 12.49, 2, 1, 5),
    (8, '2025-02-26 21:57:43', 26.95, 2, 2, 4),
    (9, '2024-01-09 11:49:05', 19.99, 2, 4, 6),
    (10, '2025-04-18 10:10:44', 14.98, 2, 7, 6),
    (11, '2025-01-31 16:02:03', 23.47, 2, 2, 1),
    (12, '2025-05-21 08:58:05', 23.47, 2, 4, 2),
    (13, '2024-08-15 04:57:20', 12.49, 2, 5, 1),
    (14, '2024-06-30 21:25:16', 19.99, 2, 2, 1),
    (15, '2024-11-27 06:56:52', 8.99, 2, 3, 5),
    (16, '2024-05-02 07:02:56', 19.99, 2, 2, 1),
    (17, '2024-03-21 11:13:57', 14.98, 2, 4, 2),
    (18, '2024-10-24 10:56:38', 8.99, 2, 1, 2),
    (19, '2025-01-05 08:26:06', 8.99, 2, 1, 2),
    (20, '2024-02-27 10:52:45', 14.98, 2, 1, 1),
    (21, '2024-10-06 22:46:27', 26.95, 2, 6, 5),
    (22, '2024-12-21 20:48:42', 19.99, 2, 6, 4),
    (23, '2025-06-07 19:38:28', 26.95, 2, 4, 3),
    (24, '2025-03-19 17:51:10', 19.99, 2, 7, 6),
    (25, '2024-11-09 08:31:47', 12.49, 2, 7, 5),
    (26, '2024-05-06 06:06:25', 8.99, 2, 5, 3),
    (27, '2025-05-09 23:56:17', 19.99, 2, 8, 3),
    (28, '2024-02-26 04:37:07', 8.99, 2, 8, 3),
    (29, '2025-02-20 04:41:21', 14.98, 2, 1, 1),
    (30, '2024-10-17 03:51:57', 12.49, 2, 4, 6),
    (31, '2024-06-23 12:07:27', 19.99, 2, 2, 5),
    (32, '2025-06-01 18:44:03', 23.47, 2, 6, 5),
    (33, '2024-01-11 06:51:59', 14.98, 2, 4, 5),
    (34, '2024-11-25 19:23:15', 23.47, 2, 8, 3),
    (35, '2024-07-10 05:08:54', 12.49, 2, 4, 5),
    (36, '2024-11-04 12:23:27', 19.99, 2, 1, 3),
    (37, '2024-04-20 21:42:07', 14.98, 2, 8, 3),
    (38, '2025-02-27 00:25:13', 12.49, 2, 4, 4),
    (39, '2025-02-16 17:15:22', 19.99, 2, 3, 4),
    (40, '2024-09-27 07:24:27', 26.95, 2, 7, 3),
    (41, '2025-04-29 03:04:56', 8.99, 2, 3, 2),
    (42, '2024-06-07 11:11:41', 8.99, 2, 7, 2),
    (43, '2024-12-03 10:54:40', 12.49, 2, 2, 1),
    (44, '2024-01-23 21:16:04', 26.95, 2, 1, 2),
    (45, '2024-07-02 22:13:09', 26.95, 2, 1, 3),
    (46, '2024-08-27 21:46:16', 8.99, 2, 4, 4);

--
-- Dumping data for table `Order_Detail` (depends on Order and Product)
--
INSERT INTO
    `Order_Detail` (
        `order_detail_id`,
        `order_id`,
        `product_id`,
        `quantity`
    )
VALUES
    (11, 1, 1, 2),
    (12, 1, 2, 3),
    (13, 2, 1, 2),
    (14, 2, 2, 3),
    (15, 3, 1, 2),
    (16, 3, 2, 3),
    (17, 4, 1, 2),
    (18, 4, 2, 3),
    (19, 5, 6, 1),
    (20, 5, 1, 1),
    (21, 6, 6, 1),
    (22, 6, 1, 1),
    (23, 7, 5, 1),
    (24, 8, 6, 1),
    (25, 9, 4, 1),
    (26, 9, 6, 2),
    (27, 9, 5, 2),
    (28, 10, 4, 1),
    (29, 11, 4, 3),
    (30, 11, 6, 2),
    (31, 12, 1, 2),
    (32, 13, 6, 2),
    (33, 13, 3, 1),
    (34, 13, 7, 3),
    (35, 14, 2, 2),
    (36, 14, 4, 2),
    (37, 15, 2, 2),
    (38, 16, 1, 3),
    (39, 16, 2, 2),
    (40, 17, 3, 3),
    (41, 17, 5, 2),
    (42, 17, 4, 3),
    (43, 18, 2, 3),
    (44, 18, 1, 1),
    (45, 19, 1, 3),
    (46, 19, 6, 2),
    (47, 20, 3, 1),
    (48, 20, 2, 3),
    (49, 21, 2, 2),
    (50, 21, 3, 3),
    (51, 21, 1, 1),
    (52, 22, 3, 3),
    (53, 23, 6, 1),
    (54, 23, 5, 1),
    (55, 24, 3, 1),
    (56, 25, 4, 2),
    (57, 25, 3, 1),
    (58, 26, 6, 1),
    (59, 26, 7, 2),
    (60, 27, 7, 2),
    (61, 27, 3, 1),
    (62, 27, 5, 1),
    (63, 28, 4, 1),
    (64, 28, 3, 3),
    (65, 28, 6, 1),
    (66, 29, 7, 2),
    (67, 29, 1, 1),
    (68, 30, 1, 3),
    (69, 31, 3, 2),
    (70, 32, 4, 3),
    (71, 32, 1, 2),
    (72, 33, 3, 3),
    (73, 33, 2, 1),
    (74, 33, 6, 2),
    (75, 34, 5, 2),
    (76, 34, 2, 3),
    (77, 35, 6, 3),
    (78, 36, 6, 1),
    (79, 36, 3, 3),
    (80, 37, 3, 1),
    (81, 37, 4, 2),
    (82, 38, 7, 2),
    (83, 38, 6, 3),
    (84, 38, 2, 2),
    (85, 39, 3, 1),
    (86, 39, 4, 1),
    (87, 39, 7, 2),
    (88, 40, 6, 1),
    (89, 40, 7, 2),
    (90, 41, 6, 2),
    (91, 41, 1, 1),
    (92, 41, 3, 1),
    (93, 42, 2, 3),
    (94, 43, 3, 2),
    (95, 43, 5, 1),
    (96, 43, 7, 2),
    (97, 44, 3, 2),
    (98, 44, 5, 2),
    (99, 44, 1, 2),
    (100, 45, 7, 1),
    (101, 45, 6, 2),
    (102, 45, 2, 1),
    (103, 46, 7, 3);

--
-- Dumping data for table `Payment_Method` (independent table)
--
INSERT INTO
    `Payment_Method` (
        `payment_method_id`,
        `name`,
        `description`,
        `is_active`,
        `icon`,
        `sort_order`
    )
VALUES
    (
        1,
        'Carta di Credito',
        'Pagamento con carta di credito/debito',
        1,
        'assets/img/icons/credit_card.png',
        10
    ),
    (
        2,
        'PayPal',
        'Pagamento tramite conto PayPal',
        1,
        'assets/img/icons/paypal.png',
        20
    ),
    (
        3,
        'Bonifico Bancario',
        'Pagamento con bonifico bancario',
        1,
        'assets/img/icons/bank_transfer.png',
        30
    ),
    (
        4,
        'Contrassegno',
        'Pagamento alla consegna',
        1,
        'assets/img/icons/contrassegno.png',
        40
    ),
    (
        5,
        'Crypto',
        'Pagamento con criptovalute',
        0,
        'assets/img/icons/bitcoin.png',
        50
    );

--
-- Dumping data for table `Payment_Status` (independent table)
--
INSERT INTO
    `Payment_Status` (`payment_status_id`, `description`)
VALUES
    (1, 'In attesa'),
    (2, 'Completato'),
    (3, 'Fallito'),
    (4, 'Rimborsato'),
    (5, 'Annullato'),
    (6, 'In elaborazione');

--
-- Dumping data for table `Payment` (depends on Order, Payment_Method, Payment_Status)
--
INSERT INTO
    `Payment` (
        `payment_id`,
        `order_id`,
        `payment_date`,
        `payment_method_id`,
        `amount`,
        `status`,
        `transaction_reference`
    )
VALUES
    (
        6,
        1,
        '2025-05-08 16:29:20',
        1,
        26.95,
        1,
        'TXN-2025050501'
    ),
    (
        7,
        2,
        '2025-05-08 16:29:20',
        2,
        26.95,
        1,
        'PP-2025050502'
    ),
    (
        8,
        3,
        '2025-05-08 16:29:20',
        1,
        26.95,
        2,
        'TXN-2025050503'
    ),
    (
        9,
        4,
        '2025-05-08 16:29:20',
        3,
        26.95,
        2,
        'BNK-2025050504'
    ),
    (
        10,
        5,
        '2025-05-08 16:29:20',
        2,
        14.98,
        2,
        'PP-2025050505'
    );


    
INSERT INTO notification (user_id, message, is_read, created_at) VALUES
(1, 'Benvenuto su CoffeeBo! Scopri i nostri prodotti e le offerte speciali.', FALSE, '2023-10-01 12:00:00'),
(1, 'Il tuo ordine #12345 è stato spedito e arriverà presto.', FALSE, '2023-10-02 14:30:00'),
(1, 'Hai ricevuto un nuovo messaggio dal supporto clienti.', TRUE, '2023-10-03 09:15:00'),
(1, 'Il tuo profilo è stato aggiornato con successo.', TRUE, '2023-10-04 11:45:00'),
(1, 'Non perdere le nostre offerte speciali! Visita il nostro sito per maggiori dettagli.', FALSE, '2023-10-05 16:20:00');