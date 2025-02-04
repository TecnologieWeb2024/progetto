-- -----------------------------------------
-- -- riempio il db
-- inserisco i ruoli
INSERT INTO
    `Roles` (`role_id`, `role_name`)
VALUES
    (1, 'seller'),
    (2, 'customer');

INSERT INTO
    `Category` (`category_id`, `NAME`)
VALUES
    (1, 'Caffè in grani'),
    (2, 'Caffè macinato'),
    (3, 'Caffè Decaffeinato');


INSERT INTO
    `Order_state` (`order_state_id`, `descrizione`)
VALUES
    ('1', 'in elaborazione'),
    ('2', 'spedito'),
    ('3', 'in consegna'),
    ('4', 'consegnato'),
    ('5', 'cancellato'),
    ('6', 'disperso'),
    ('7', 'rimborsato');



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

INSERT INTO
    `Product` (
        `product_id`,
        `SKU`,
        `product_name`,
        `product_description`,
        `price`,
        `stock`,
        `category_id`,
        `image`
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
        'assets\\img\\products\\CAFF001.jpg'
    ),
    (
        2,
        'CAF002',
        'Caffè Robusta 250g',
        'Caffè robusta in grani, 250g',
        4.99,
        150,
        1,
        'assets\\img\\products\\CAFF001.jpg'
    ),
    (
        3,
        'CAF003',
        'Caffè Macinato 250g',
        'Caffè macinato, 250g',
        5.49,
        120,
        2,
        'assets\\img\\products\\CAFF001.jpg'
    ),
    (
        4,
        'CAF004',
        'Caffè Decaffeinato 250g',
        'Caffè decaffeinato in grani, 250g',
        6.49,
        80,
        3,
        'assets\\img\\products\\CAFF001.jpg'
    ),
    (
        5,
        'CAF005',
        'Caffè Decaffeinato Macinato 250g',
        'Caffè decaffeinato macinato, 250g',
        6.99,
        70,
        3,
        'assets\\img\\products\\CAFF001.jpg'
    );
    
INSERT INTO `order` (`order_id`, `total_price`, `user_id`, `order_state_id`) VALUES ('1', '26.95', '2', '1');
INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES ('1', '1', '1', '2', '5.99');
INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES ('2', '1', '2', '3', '4.99');
