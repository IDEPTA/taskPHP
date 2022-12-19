-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 16 2022 г., 13:13
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Test`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`%` PROCEDURE `addComents` (IN `pic` INT)   BEGIN
SET @c = (SELECT COUNT(`user`) FROM `comments` WHERE `picture` = pic);
UPDATE `Picture` SET `comments` = @c WHERE `Picture`.`id` = pic;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `picture` int NOT NULL,
  `user` int NOT NULL,
  `Text` text NOT NULL,
  `date` datetime NOT NULL,
  `edited` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Триггеры `comments`
--
DELIMITER $$
CREATE TRIGGER `addComents` AFTER INSERT ON `comments` FOR EACH ROW CALL `addComents`(new.picture)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delHistoey` AFTER DELETE ON `comments` FOR EACH ROW DELETE FROM `historyEdit` WHERE `user` = OLD.user and `comment` = old.id and `picture` = old.picture
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `dellComments` AFTER DELETE ON `comments` FOR EACH ROW CALL `addComents`(old.picture)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `history` AFTER UPDATE ON `comments` FOR EACH ROW INSERT INTO historyEdit VALUES (NULL,OLD.id,New.user,NEW.picture,OLD.Text,CURTIME())
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `historyEdit`
--

CREATE TABLE `historyEdit` (
  `id` int NOT NULL,
  `comment` int NOT NULL,
  `user` int NOT NULL,
  `picture` int NOT NULL,
  `Text` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Picture`
--

CREATE TABLE `Picture` (
  `id` int NOT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user` int NOT NULL,
  `time` datetime NOT NULL,
  `comments` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `Login` tinytext NOT NULL,
  `Password` tinytext NOT NULL,
  `name` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `picture` (`picture`),
  ADD KEY `user` (`user`);

--
-- Индексы таблицы `historyEdit`
--
ALTER TABLE `historyEdit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`,`picture`),
  ADD KEY `picture` (`picture`),
  ADD KEY `comment` (`comment`);

--
-- Индексы таблицы `Picture`
--
ALTER TABLE `Picture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `picture` (`picture`),
  ADD KEY `user` (`user`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT для таблицы `historyEdit`
--
ALTER TABLE `historyEdit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `Picture`
--
ALTER TABLE `Picture`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `Picture` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `historyEdit`
--
ALTER TABLE `historyEdit`
  ADD CONSTRAINT `historyedit_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `Picture` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `historyedit_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `Picture`
--
ALTER TABLE `Picture`
  ADD CONSTRAINT `picture_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
