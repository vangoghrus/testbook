-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 12 2019 г., 06:00
-- Версия сервера: 5.7.12
-- Версия PHP: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `book_imysite`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE `author` (
  `id` int(4) UNSIGNED NOT NULL,
  `author_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `author`
--

INSERT DELAYED INTO `author` (`id`, `author_name`) VALUES
(1, 'Автор 1'),
(2, 'Автор 2'),
(3, 'Автор 3'),
(4, 'Автор 4');

-- --------------------------------------------------------

--
-- Структура таблицы `book`
--

CREATE TABLE `book` (
  `id` int(4) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `book`
--

INSERT DELAYED INTO `book` (`id`, `title`) VALUES
(1, 'Книга 1'),
(2, 'Книга 2'),
(3, 'Книга все авторы'),
(5, 'Книга без автора');

-- --------------------------------------------------------

--
-- Структура таблицы `list_book`
--

CREATE TABLE `list_book` (
  `id` int(5) UNSIGNED NOT NULL,
  `id_book` int(4) UNSIGNED NOT NULL,
  `id_author` int(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `list_book`
--

INSERT DELAYED INTO `list_book` (`id`, `id_book`, `id_author`) VALUES
(1, 1, 1),
(2, 1, 2),
(8, 3, 1),
(9, 3, 2),
(10, 3, 3),
(11, 3, 4),
(14, 2, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `list_book`
--
ALTER TABLE `list_book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_id_book` (`id_book`),
  ADD KEY `index_id_author` (`id_author`),
  ADD KEY `id_author` (`id_author`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `book`
--
ALTER TABLE `book`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `list_book`
--
ALTER TABLE `list_book`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `list_book`
--
ALTER TABLE `list_book`
  ADD CONSTRAINT `list_book_ibfk_1` FOREIGN KEY (`id_book`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `list_book_ibfk_2` FOREIGN KEY (`id_author`) REFERENCES `author` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
