USE 'yeticave';

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Доски и лыжи', 'boards'),
(2, 'Крепления', 'attachment'),
(3, 'Ботинки', 'boots'),
(4, 'Одежда', 'clothing'),
(5, 'Инструменты', 'tools'),
(6, 'Разное', 'other');

INSERT INTO `users` (`id`, `email`, `password`, `name`, `avatar_url`, `contacts`, `date_add`) VALUES
(1, 'one@mail.ru', '12345', 'ivanov', 'avatar1.jpg', 'г. Новосибирск, ул. Ленина, 1', '2018-11-16 21:26:26'),
(2, 'two@mail.ru', '54321', 'prtrov', 'avatar2.jpg', 'г.Москва, ул. Чкалова, 2', '2018-11-26 21:28:46'),
(3, 'third@mail.ru', '121212', 'sidorov', 'avatar3.png', 'г. Яровое, ул. 40-лет Октабря, 10', '2018-11-21 23:23:49');

INSERT INTO `lots` (`id`, `date_add`, `title`, `description`, `image_url`, `start_price`, `date_end`, `bit_step`, `owner_id`, `winner_id`, `category_id`) VALUES
(1, '2018-11-26 22:32:07', '2014 Rossignol District Snowboard', 'Крутой сноуборд. Отечественный.', 'img/lot-1.jpg', 10999, '2018-12-26 22:32:15', 10, 1, NULL, 1),
(2, '2018-11-21 22:47:08', 'DC Ply Mens 2016/2017 Snowboard', 'Современный сноуборд.', 'img/lot-2.jpg', 159999, '2018-12-26 22:27:16', 150, 2, NULL, 2),
(3, '2018-11-22 22:47:08', 'Крепления Union Contact Pro 2015 года размер L/XL', 'В отличном состоянии.', 'img/lot-3.jpg', 8000, '2018-12-26 22:27:16', 20, 1, NULL, 3),
(4, '2018-11-23 22:47:08', 'Ботинки для сноуборда DC Mutiny Charocal', 'Редкие.', 'img/lot-4.jpg', 10999, '2018-11-24 22:27:16', 25, 1, 2, 4),
(5, '2018-11-24 22:47:08', 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка замшевая.', 'img/lot-5.jpg', 7500, '2018-11-26 22:27:16', 50, 2, 1, 5),
(6, '2018-11-24 22:47:08', 'Маска Oakley Canopy', 'Хит сезона.', 'img/lot-6.jpg', 5400, '2018-12-26 22:27:16', 20, 1, NULL, 6),
(7, '2018-11-28 22:47:08', 'Ботинки Henry Snow', 'Спецзаказ.', 'img/lot-4.jpg', 49999, '2018-11-28 23:16:42', 40, 1, NULL, 3);

INSERT INTO `bids` (`id`, `start_date`, `user_id`, `lot_id`, `amount`) VALUES
(1, '2018-11-26 23:08:56', 1, 1, 1000),
(2, '2018-11-26 23:08:56', 2, 2, 1000),
(3, '2018-11-26 23:17:10', 2, 5, 2000),
(4, '2018-11-27 09:47:03', 3, 1, 5555),
(5, '2018-11-27 09:47:52', 3, 1, 12000);

#получить все категории;
SELECT NAME FROM categories;

#получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT l.title,l.start_price,l.image_url,c.name
FROM lots l
JOIN categories c ON c.id = l.category_id
WHERE l.winner_id IS NULL
ORDER BY l.date_add;

#показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT l.title,c.name FROM lots l
JOIN categories c ON c.id = l.category_id WHERE l.id=3

#обновить название лота по его идентификатору;
UPDATE `lots` SET `title`='New title' WHERE  `id`=1;

#получить список самых свежих ставок для лота по его идентификатору;
SELECT * FROM bids
WHERE lot_id = 1
ORDER BY bids.start_date