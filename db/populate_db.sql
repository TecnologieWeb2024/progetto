-- Inserimento dei dati iniziali (inserts unificati)
-- Ruoli
INSERT INTO
    `Roles` (`role_id`, `role_name`)
VALUES
    (1, 'seller'),
    (2, 'customer');

-- Categorie
INSERT INTO
    `Category` (`category_id`, `NAME`)
VALUES
    (1, 'Caffè in grani'),
    (2, 'Caffè macinato'),
    (3, 'Caffè Decaffeinato');

-- Stati ordine
INSERT INTO
    `Order_State` (`order_state_id`, `descrizione`)
VALUES
    (1, 'Checkout'),
    (2, 'In attesa di pagamento'),
    (3, 'Pagato'),
    (4, 'In preparazione'),
    (5, 'Spedito'),
    (6, 'Consegnato'),
    (7, 'Cancellato'),
    (8, 'Rimborsato');

-- Stati pagamento
INSERT INTO
    `Payment_Status` (`payment_status_id`, `description`)
VALUES
    (1, 'In attesa'),
    (2, 'Completato'),
    (3, 'Fallito'),
    (4, 'Rimborsato'),
    (5, 'Annullato'),
    (6, 'In elaborazione');

-- Metodi di pagamento
INSERT INTO
    `Payment_Method` (
        `payment_method_id`,
        `name`,
        `description`,
        `is_active`,
        `sort_order`
    )
VALUES
    (
        1,
        'Carta di Credito',
        'Pagamento con carta di credito/debito',
        TRUE,
        10
    ),
    (
        2,
        'PayPal',
        'Pagamento tramite conto PayPal',
        TRUE,
        20
    ),
    (
        3,
        'Bonifico Bancario',
        'Pagamento con bonifico bancario',
        TRUE,
        30
    ),
    (
        4,
        'Contrassegno',
        'Pagamento alla consegna',
        TRUE,
        40
    ),
    (
        5,
        'Crypto',
        'Pagamento con criptovalute',
        FALSE,
        50
    );

-- Utenti
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

-- Prodotti
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
        0
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
        0
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

-- Carrelli
INSERT INTO
    `Cart` (`user_id`)
VALUES
    (2);

-- Stati spedizione
INSERT INTO
    `Shipment_Status` (`shipment_status_id`, `status`)
VALUES
    (1, 'In preparazione'),
    (2, 'In transito'),
    (3, 'Consegnato');

-- Metodi di spedizione
INSERT INTO
    `Shipping_Method` (
        `shipping_method_id`,
        `name`,
        `description`,
        `price`
    )
VALUES
    (1, 'Spedizione Standard', '7-10 giorni', 2.99),
    (2, 'Express', '3-5 giorni', 5.99),
    (3, 'Ritiro in Negozio', 'Ritiro diretto', 0.00);

-- Spedizioni
INSERT INTO
    `Shipment` (
        `shipment_id`,
        `address`,
        `shipping_method`,
        `status`
    )
VALUES
    (1, 'Via Controesempio 123, Cesena, FC', 1, 1),
    (2, 'Via Controesempio 123, Cesena, FC', 1, 2),
    (3, 'Via Controesempio 123, Cesena, FC', 2, 2),
    (4, 'Via Controesempio 123, Cesena, FC', 1, 3),
    (5, 'Via Esempio 456, Cesena, FC', 2, 1),
    (6, 'Via Controesempio 123, Cesena, FC', 1, 1);

-- Ordini
INSERT INTO
    `Order` (
        `order_id`,
        `total_price`,
        `user_id`,
        `seller_id`,
        `order_state_id`,
        `shipment_id`
    )
VALUES
    (1, 26.95, 2, 1, 1, 1),
    (2, 26.95, 2, 1, 2, 2),
    (3, 26.95, 2, 1, 3, 3),
    (4, 26.95, 2, 1, 4, 4),
    (5, 14.98, 2, 3, 1, 5);

-- Dettagli ordine
INSERT INTO
    `Order_Detail` (`order_id`, `product_id`, `quantity`, `price`)
VALUES
    (1, 1, 2, 5.99),
    (1, 2, 3, 4.99),
    (2, 1, 2, 5.99),
    (2, 2, 3, 4.99),
    (3, 1, 2, 5.99),
    (3, 2, 3, 4.99),
    (4, 1, 2, 5.99),
    (4, 2, 3, 4.99),
    (5, 6, 1, 8.99),
    (5, 1, 1, 5.99);

-- Pagamenti
INSERT INTO
    `Payment` (
        `order_id`,
        `payment_method_id`,
        `amount`,
        `status`,
        `transaction_reference`
    )
VALUES
    (1, 1, 26.95, 1, 'TXN-2025050501'),
    (2, 2, 26.95, 1, 'PP-2025050502'),
    (3, 1, 26.95, 2, 'TXN-2025050503'),
    (4, 3, 26.95, 2, 'BNK-2025050504'),
    (5, 2, 14.98, 2, 'PP-2025050505');
    (6, 2, 14.98, 2, 'PP-2025050505');