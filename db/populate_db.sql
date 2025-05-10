--

-- Dumping data for table `Roles` (independent table)
--

INSERT INTO `Roles`(`role_id`, `role_name`)
VALUES(1, 'seller'),(2, 'customer');

--
-- Dumping data for table `User` (depends on Roles)
--

INSERT INTO `User`(
    `user_id`,
    `first_name`,
    `last_name`,
    `email`,
    `passwordHash`,
    `address`,
    `phone_number`,
    `role`
)
VALUES(
    1,
    'VenditoreEsempio',
    'CognomeVenditore',
    'venditore@example.com',
    '$2a$12$YdBzuFDfMhBHwgj5HG3doeibBe2AWlkfON69gGQq42Z9yZ9k/Jlrm',
    'Via Esempio 123, Cesena, FC',
    '3331231231',
    1
),(
    2,
    'ProvaNomeUno',
    'ProvaCognomeUno',
    'prova-1@test.com',
    '$2y$10$kZSfGM9OgyOfrBf8I0WdZutr7lcaXGSRX0ewQlvGs.tJEYIb6EepC',
    'Via Controesempio 123, Cesena, FC',
    '3331231231',
    2
),(
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

INSERT INTO `Category`(`category_id`, `NAME`)
VALUES(1, 'Caffè in grani'),(2, 'Caffè macinato'),(3, 'Caffè Decaffeinato');

--
-- Dumping data for table `Product` (depends on User/seller and Category)
--

INSERT INTO `Product`(
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
VALUES(
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
),(
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
),(
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
),(
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
),(
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
),(
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
),(
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

INSERT INTO `Cart`(
    `cart_id`,
    `user_id`,
    `created_at`,
    `updated_at`
)
VALUES(
    2,
    2,
    '2025-05-08 14:29:20',
    '2025-05-08 14:29:20'
);

--
-- Dumping data for table `Cart_Detail` (depends on Cart and Product)
--

INSERT INTO `Cart_Detail`(
    `cart_id`,
    `product_id`,
    `quantity`,
    `price`,
    `created_at`,
    `updated_at`
)
VALUES(
    2,
    1,
    3,
    5.99,
    '2025-05-08 15:08:59',
    '2025-05-08 15:09:03'
);

--
-- Dumping data for table `Shipment_Status` (independent table)
--

INSERT INTO `Shipment_Status`(`shipment_status_id`, `status`)
VALUES(1, 'In preparazione'),(2, 'In transito'),(3, 'Consegnato');

--
-- Dumping data for table `Shipping_Method` (independent table)
--

INSERT INTO `Shipping_Method`(
    `shipping_method_id`,
    `name`,
    `description`,
    `price`
)
VALUES(
    1,
    'Spedizione Standard',
    '7-10 giorni',
    2.99
),(
    2,
    'Express',
    '3-5 giorni',
    5.99
),(
    3,
    'Ritiro in Negozio',
    'Ritiro diretto',
    0.00
);

--
-- Dumping data for table `Shipment` (depends on Shipping_Method and Shipment_Status)
--

INSERT INTO `Shipment`(
    `shipment_id`,
    `shipment_date`,
    `address`,
    `tracking_number`,
    `shipping_method`,
    `status`
)
VALUES(
    1,
    '2025-05-08 16:29:20',
    'Via Controesempio 123, Cesena, FC',
    NULL,
    1,
    1
),(
    2,
    '2025-05-08 16:29:20',
    'Via Controesempio 123, Cesena, FC',
    NULL,
    1,
    2
),(
    3,
    '2025-05-08 16:29:20',
    'Via Controesempio 123, Cesena, FC',
    NULL,
    2,
    2
),(
    4,
    '2025-05-08 16:29:20',
    'Via Controesempio 123, Cesena, FC',
    NULL,
    1,
    3
),(
    5,
    '2025-05-08 16:29:20',
    'Via Esempio 456, Cesena, FC',
    NULL,
    2,
    1
),(
    6,
    '2025-05-08 16:29:20',
    'Via Controesempio 123, Cesena, FC',
    NULL,
    1,
    1
);

--
-- Dumping data for table `Order_State` (independent table)
--

INSERT INTO `Order_State`(`order_state_id`, `descrizione`)
VALUES(1, 'Checkout'),(2, 'In attesa di pagamento'),(3, 'Pagato'),(4, 'In preparazione'),(5, 'Spedito'),(6, 'Consegnato'),(7, 'Cancellato'),(8, 'Rimborsato');

--
-- Dumping data for table `Order` (depends on User, Seller, Order_State, Shipment)
--

INSERT INTO `Order`(
    `order_id`,
    `order_date`,
    `total_price`,
    `user_id`,
    `seller_id`,
    `order_state_id`,
    `shipment_id`
)
VALUES(
    1,
    '2025-05-08 16:29:20',
    26.95,
    2,
    1,
    1,
    1
),(
    2,
    '2025-05-08 16:29:20',
    26.95,
    2,
    1,
    2,
    2
),(
    3,
    '2025-05-08 16:29:20',
    26.95,
    2,
    1,
    3,
    3
),(
    4,
    '2025-05-08 16:29:20',
    26.95,
    2,
    1,
    4,
    4
),(
    5,
    '2025-05-08 16:29:20',
    14.98,
    2,
    3,
    1,
    5
),(
    6,
    '2025-05-08 16:52:42',
    14.98,
    2,
    3,
    1,
    5
);

--
-- Dumping data for table `Order_Detail` (depends on Order and Product)
--

INSERT INTO `Order_Detail`(
    `order_detail_id`,
    `order_id`,
    `product_id`,
    `quantity`,
    `price`
)
VALUES(11, 1, 1, 2, 5.99),(12, 1, 2, 3, 4.99),(13, 2, 1, 2, 5.99),(14, 2, 2, 3, 4.99),(15, 3, 1, 2, 5.99),(16, 3, 2, 3, 4.99),(17, 4, 1, 2, 5.99),(18, 4, 2, 3, 4.99),(19, 5, 6, 1, 8.99),(20, 5, 1, 1, 5.99),(21, 6, 6, 1, 8.99),(22, 6, 1, 1, 5.99);

--
-- Dumping data for table `Payment_Method` (independent table)
--

INSERT INTO `Payment_Method`(
    `payment_method_id`,
    `name`,
    `description`,
    `is_active`,
    `icon`,
    `sort_order`
)
VALUES(
    1,
    'Carta di Credito',
    'Pagamento con carta di credito/debito',
    1,
    'assets/img/icons/credit_card.png',
    10
),(
    2,
    'PayPal',
    'Pagamento tramite conto PayPal',
    1,
    'assets/img/icons/paypal.png',
    20
),(
    3,
    'Bonifico Bancario',
    'Pagamento con bonifico bancario',
    1,
    'assets/img/icons/bank_transfer.png',
    30
),(
    4,
    'Contrassegno',
    'Pagamento alla consegna',
    1,
    'assets/img/icons/contrassegno.png',
    40
),(
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

INSERT INTO `Payment_Status`(`payment_status_id`, `description`)
VALUES(1, 'In attesa'),(2, 'Completato'),(3, 'Fallito'),(4, 'Rimborsato'),(5, 'Annullato'),(6, 'In elaborazione');

--
-- Dumping data for table `Payment` (depends on Order, Payment_Method, Payment_Status)
--

INSERT INTO `Payment`(
    `payment_id`,
    `order_id`,
    `payment_date`,
    `payment_method_id`,
    `amount`,
    `status`,
    `transaction_reference`
)
VALUES(
    6,
    1,
    '2025-05-08 16:29:20',
    1,
    26.95,
    1,
    'TXN-2025050501'
),(
    7,
    2,
    '2025-05-08 16:29:20',
    2,
    26.95,
    1,
    'PP-2025050502'
),(
    8,
    3,
    '2025-05-08 16:29:20',
    1,
    26.95,
    2,
    'TXN-2025050503'
),(
    9,
    4,
    '2025-05-08 16:29:20',
    3,
    26.95,
    2,
    'BNK-2025050504'
),(
    10,
    5,
    '2025-05-08 16:29:20',
    2,
    14.98,
    2,
    'PP-2025050505'
);