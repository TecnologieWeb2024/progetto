-- Inserimento dei dati iniziali
-- Inserimento dei ruoli
INSERT INTO
    `Roles` (`role_id`, `role_name`)
VALUES
    (1, 'seller'),
    (2, 'customer');

-- Inserimento delle categorie
INSERT INTO
    `Category` (`category_id`, `NAME`)
VALUES
    (1, 'Caffè in grani'),
    (2, 'Caffè macinato'),
    (3, 'Caffè Decaffeinato');

-- Inserimento degli stati dell'ordine
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

-- Inserimento degli stati di pagamento
INSERT INTO
    `Payment_Status` (`payment_status_id`, `description`)
VALUES
    (1, 'In attesa'),
    (2, 'Completato'),
    (3, 'Fallito'),
    (4, 'Rimborsato'),
    (5, 'Annullato'),
    (6, 'In elaborazione');

-- processing
-- Inserimento dei metodi di pagamento
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

-- Inserimento degli utenti
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
        '$2a$12$YdBzuFDfMhBHwgj5HG3doeibBe2AWlkfON69gGQq42Z9yZ9k/Jlrm', -- password: Venditore-1234
        'Via Esempio 123, Cesena, FC',
        '3331231231',
        1
    ),
    (
        2,
        'ProvaNomeUno',
        'ProvaCognomeUno',
        'prova-1@test.com',
        '$2y$10$kZSfGM9OgyOfrBf8I0WdZutr7lcaXGSRX0ewQlvGs.tJEYIb6EepC', -- password: Prova-1234
        'Via Controesempio 123, Cesena, FC',
        '3331231231',
        2
    );

-- Inserimento dei prodotti
INSERT INTO
    `Product` (
        `product_id`,
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
        'CAF001',
        'Caffè Arabica 250g',
        'Caffè arabica in grani, 250g',
        5.99,
        100,
        1,
        'assets/img/products/CAFF001.jpg',
        1
    ),
    (
        2,
        'CAF002',
        'Caffè Robusta 250g',
        'Caffè robusta in grani, 250g',
        4.99,
        150,
        1,
        'assets/img/products/CAFF001.jpg',
        1
    ),
    (
        3,
        'CAF003',
        'Caffè Macinato 250g',
        'Caffè macinato, 250g',
        5.49,
        120,
        2,
        'assets/img/products/CAFF001.jpg',
        0
    ),
    (
        4,
        'CAF004',
        'Caffè Decaffeinato 250g',
        'Caffè decaffeinato in grani, 250g',
        6.49,
        80,
        3,
        'assets/img/products/CAFF001.jpg',
        0
    ),
    (
        5,
        'CAF005',
        'Caffè Decaffeinato Macinato 250g',
        'Caffè decaffeinato macinato, 250g',
        6.99,
        70,
        3,
        'assets/img/products/CAFF001.jpg',
        1
    );

-- Creazione dei carrelli per gli utenti
INSERT INTO
    `Cart` (`user_id`)
VALUES
    (2);

-- Carrello per l'utente customer
INSERT INTO
    `Shipment_Status` (`shipment_status_id`, `status`)
VALUES
    (1, 'In preparazione'),
    (2, 'In transito'),
    (3, 'Consegnato');

INSERT INTO
    `Shipping_Method` (
        `shipping_method_id`,
        `name`,
        `description`,
        `price`
    )
VALUES
    (
        1,
        'Spedizione Standard',
        'Spedizione standard con corriere espresso (7-10 giorni lavorativi)',
        2.99
    ),
    (
        2,
        'Express',
        'Spedizione veloce con corriere espresso (3-5 giorni lavorativi)',
        5.99
    ),
    (
        3,
        'Ritiro in Negozio',
        'Ritiro diretto presso il nostro negozio',
        0.00
    );

-- Inserimento delle spedizioni
INSERT INTO
    `Shipment` (
        `shipment_id`,
        `address`,
        `shipping_method`,
        `status`
    )
VALUES
    (
        1,
        'Via Controesempio 123, Cesena, FC',
        1,
        1
    ),
    (
        2,
        'Via Controesempio 123, Cesena, FC',
        1,
        2
    ),
    (
        3,
        'Via Controesempio 123, Cesena, FC',
        2,
        2
    ),
    (
        4,
        'Via Controesempio 123, Cesena, FC',
        1,
        3
    );

-- Inserimento degli ordini
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
    (4, 26.95, 2, 1, 4, 4);

-- Inserimento dei dettagli degli ordini
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
    (4, 2, 3, 4.99);

-- Inserimento dei pagamenti
INSERT INTO
    `Payment` (
        `order_id`,
        `payment_method_id`,
        `amount`,
        `status`,
        `transaction_reference`
    )
VALUES
    (1, 1, 26.95, 1, 'TXN-2025050501'), -- Carta di Credito, In attesa
    (2, 2, 26.95, 1, 'PP-2025050502'), -- PayPal, In attesa
    (3, 1, 26.95, 2, 'TXN-2025050503'), -- Carta di Credito, Completato
    (4, 3, 26.95, 2, 'BNK-2025050504');

-- Bonifico Bancario, Completato