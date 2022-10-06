-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Жов 06 2022 р., 11:57
-- Версія сервера: 10.4.24-MariaDB
-- Версія PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `soft_wars_api`
--

-- --------------------------------------------------------

--
-- Структура таблиці `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `is_personal` tinyint(1) NOT NULL DEFAULT 0,
  `is_working` tinyint(1) NOT NULL DEFAULT 0,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп даних таблиці `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `is_urgent`, `is_personal`, `is_working`, `is_done`, `completed_at`, `user_id`) VALUES
(1, 'A new task 1', 'Desc task 1', 0, 0, 0, 0, NULL, 1),
(2, 'A new task 2', 'Desc task 2', 0, 0, 0, 0, NULL, 1),
(3, 'A new task 3', 'Desc task 3', 0, 0, 0, 0, NULL, 1),
(4, 'A new task 4', 'Desc task 4', 0, 1, 0, 0, NULL, 1),
(5, 'A new task 5', 'Desc task 5', 0, 1, 0, 0, NULL, 1),
(6, 'A new task 6', 'Desc task 6', 0, 0, 0, 0, '2022-10-21 22:00:01', 1),
(7, 'A new task 7', 'Desc task 7', 0, 0, 0, 0, '2022-10-21 00:00:00', 1),
(8, 'A new task 8 edited again', 'Desc task 8 edited 2', 0, 0, 0, 0, '2022-11-21 17:14:07', 1),
(11, 'A new task 9 by editor', 'Desc task 9 by editor', 0, 0, 0, 0, '2022-10-21 17:14:07', 3),
(13, 'A new task 10 by guest', 'Desc task 10 by guest', 0, 0, 0, 0, '2022-10-22 22:14:07', 4);

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `api_key` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `api_key`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$g1aicESFO8Tn2JGxXFVLc.ow/x/gjH2Umg0g1DYHFyc.19K0u/yZu', '575f02ca24292f87224c8cd19e13106c'),
(3, 'Editor', 'editor@gmail.com', '$2y$10$J6S0UsfBxfDMgRBp1tDoIOpJTy9m9klnw4cLU6kgsorcrBVpKsbYG', '278fb9174cb24ff5283b21dc47b84556'),
(4, 'Guest', 'guest@ukr.net', '$2y$10$Db.nqneD.CH25E8E7YSKI.bOU/gpXtz6YFB8dtDtR9pB58abOhxhS', '05911c3f071981b39bfedd1b3fe33fd7');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `api_key` (`api_key`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
