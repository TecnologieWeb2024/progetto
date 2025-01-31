-- -----------------------------------------
-- -- riempio il db
-- inserisco i ruoli
INSERT INTO
    `Roles` (`role_id`, `first_name`)
VALUES
    ('1', 'seller'),
    ('2', 'buyer');

-- inserisco un venditore
INSERT INTO
    `User` (
        first_name,
        last_name,
        email,
        passwordHash,
        address,
        phone_number,
        role
    )
VALUES
    (
        'VenditoreEsempio',
        'CognomeVenditore',
        'venditore@example.com',
        '$2a$12$YdBzuFDfMhBHwgj5HG3doeibBe2AWlkfON69gGQq42Z9yZ9k/Jlrm', -- password: Venditore-1234
        'Via Esempio 123, Cesena, FC',
        '3331231231',
        1
    );

-- Aggiungi Categorie
INSERT INTO
    Category (category_id, name)
VALUES
    (1, 'Caffè in grani'),
    (2, 'Caffè macinato'),
    (3, 'Caffè Decaffeinato');

-- Aggiungi Prodotti (5 prodotti, 3 diversi tipi di caffè)
INSERT INTO
    Product (
        product_id,
        SKU,
        product_name,
        product_description,
        price,
        stock,
        category_id
    )
VALUES
    (
        1,
        'CAF001',
        'Caffè Arabica 250g',
        'Caffè arabica in grani, 250g',
        5.99,
        100,
        1
    ), -- Categoria: Caffè in grani
    (
        2,
        'CAF002',
        'Caffè Robusta 250g',
        'Caffè robusta in grani, 250g',
        4.99,
        150,
        1
    ), -- Categoria: Caffè in grani
    (
        3,
        'CAF003',
        'Caffè Macinato 250g',
        'Caffè macinato, 250g',
        5.49,
        120,
        2
    ), -- Categoria: Caffè macinato
    (
        4,
        'CAF004',
        'Caffè Decaffeinato 250g',
        'Caffè decaffeinato in grani, 250g',
        6.49,
        80,
        3
    ), -- Categoria: Caffè Decaffeinato
    (
        5,
        'CAF005',
        'Caffè Decaffeinato Macinato 250g',
        'Caffè decaffeinato macinato, 250g',
        6.99,
        70,
        3
    );

-- Categoria: Caffè Decaffeinato
-- Gli utenti devono essere registrati dal sito.