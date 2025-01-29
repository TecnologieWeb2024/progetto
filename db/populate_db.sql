-- -----------------------------------------
-- -- riempio il db
-- inserisco i ruoli
INSERT INTO
    `Roles` (`role_id`, `first_name`)
VALUES
    ('1', 'seller');

INSERT INTO
    `Roles` (`role_id`, `first_name`)
VALUES
    ('2', 'buyer');

-- inserisco gli utenti
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
        '1',
        'seller',
        'seller',
        'seller@gmail.com',
        'seller',
        'Via Seller 01',
        '123',
        '1'
    );

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
        '2',
        'buyer',
        'buyer',
        'buyer@gmail.com',
        'buyer',
        'Via Buyer 02',
        '456',
        '2'
    );