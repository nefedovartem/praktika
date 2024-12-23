-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Ноя 18 2024 г., 04:21
-- Версия сервера: 10.6.18-MariaDB-0ubuntu0.22.04.1
-- Версия PHP: 8.1.2-1ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `project_Nefedov`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
--

CREATE TABLE `admin` (
  `id` int(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `developer`
--

CREATE TABLE `developer` (
  `id` int(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `equipment_images`
--

CREATE TABLE `equipment_images` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `equipment_images`
--

INSERT INTO `equipment_images` (`id`, `equipment_id`, `image_url`) VALUES
(4, 12, 'https://патриотам.рф/wp-content/uploads/2014/04/t34-1024x682.jpg'),
(5, 14, 'https://overclockers.ru/st/r/800/-/legacy/blog/428149/522164_O.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `equipment_specs`
--

CREATE TABLE `equipment_specs` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `equipment_specs`
--

INSERT INTO `equipment_specs` (`id`, `equipment_id`, `spec_name`, `spec_value`) VALUES
(9, 12, 'Тип', 'Средний танк'),
(10, 14, 'Тип', 'Дальний бомбардировщик');

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL,
  `status` enum('new','resolved','rejected') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `feedback`
--

INSERT INTO `feedback` (`id`, `text`, `status`, `created_at`) VALUES
(1, 'Ñ‡Ñ‚Ð¾ ÑÑ‚Ð¾', 'resolved', '2024-11-13 09:29:19'),
(2, 'Ð¢ÑƒÑ‚ Ð²ÑÑ‘ Ð½ÐµÐ¿Ñ€Ð°Ð²Ð¸Ð»ÑŒÐ½Ð¾', 'rejected', '2024-11-14 05:04:45'),
(3, 'фигня какая то', 'rejected', '2024-11-18 03:52:01');

-- --------------------------------------------------------

--
-- Структура таблицы `military_equipment`
--

CREATE TABLE `military_equipment` (
  `id` int(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(2555) NOT NULL,
  `category` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `military_equipment`
--

INSERT INTO `military_equipment` (`id`, `name`, `description`, `category`, `country`) VALUES
(12, 'Т-34', 'Танк Т-34 — легендарный советский средний танк, который стал одним из самых известных символов Второй мировой войны. Его разработка началась в конце 1930-х годов, и он быстро завоевал репутацию благодаря своему уникальному сочетанию огневой мощи, маневренности и защите. Т-34 был важным элементом советских армий, активно использовавшимся на всех фронтах.  Проектирование танка отличалось инновационным подходом к дизайну, включая наклонную броню, что значительно повышало его защитные характеристики. Т-34 был не только эффективным на поле боя, но и относительно простым в производстве, что позволяло быстро наладить его массовое производство в условиях войны.  Танк сделал значительный вклад в победу над фашистскими силами, и его боевые качества оказали влияние на дальнейшее развитие танкостроения. Т-34 стал символом мужества и стойкости, олицетворяя дух советских войск и их решимость в борьбе за свободу. Этот танк оставил глубокий след в военной истории, и его наследие продолжает жить в современных танках и военной', 'Танки', 'Россия'),
(14, 'СУ-34', 'Самолёт Су-34 — российский дальний бомбардировщик и штурмовик, разработанный для выполнения задач стратегической воздушной поддержки и нанесения ударов по наземным целям. Он сочетает в себе высокую маневренность и боевую эффективность, что позволяет ему успешно выполнять сложные тактические задачи на поле боя.  Су-34 отличается современным дизайном с характерной кабиной, обеспечивающей пилотам хорошую видимость, а также возможностью работы в условиях сильного противодействия. Самолёт предназначен для проведения как автономных, так и совместных операций, и может использовать широкий спектр вооружения, включая высокоточные ракеты и бомбы.  Су-34 активно использовался в различных конфликтах, демонстрируя высокую степень живучести и эффективность. Его конструкция включает элементы современного оборудования и систем управления, что делает его подходящим для выполнения различных миссий, включая разведку и поддержку наземных войск. Этот самолёт стал важной частью российской авиации и продолжает служить в условиях современных боевых действий.', 'Самолеты', 'Россия');

-- --------------------------------------------------------

--
-- Структура таблицы `moderator`
--

CREATE TABLE `moderator` (
  `id` int(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `reader`
--

CREATE TABLE `reader` (
  `id` int(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `role` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role`, `username`, `password`) VALUES
(1, 'admin', 'vlad', '$2y$10$uLiRm.F10hgVZE6PPSbwrerhv2GtAM2tXEc7ogk6O0dpMhJbEZrDS'),
(14, 'developer', 'sashok', '$2y$10$T.xzsyKb/Lk72cDK/VuLBeia1Ks6JR/tU3RcMPocndffC6YylrMui'),
(15, 'moderator', 'nikitos', '$2y$10$lKaDnF8inm74p13VyB2i7eJP9r/NYc48TiT6tCOhh2zKmVppNJK0K'),
(16, 'reader', 'valerka', '$2y$10$IKAlwn3DZTqtBSccAYfo6ui13HzzR.6lHiAvkVDoTmmWpasrtHvuy'),
(17, 'reader', 'test', '$2y$10$vDIRrtaKvNrz.VoQ9yu9IOYSUJeKKO13qAwPdaP8sNoArBTA9e79m'),
(18, 'reader', '6', '$2y$10$iPNvotsh5QNGlx3laBMpe.lD7XOdpGdfyFhKbkMP8GUQI3UKq3AY6'),
(19, 'admin', 'adik', '$2y$10$xx7DYnJOaKtzudypamyPv.uNpcJyJi2yD5EI6TZsiSW0Z.o/HAaHu'),
(20, 'admin', 'adik', '$2y$10$CExlGA2gadsWbzDwuJiD6.97.YeJjI0AT1UzEB8i8I4.9jcO2pmwa'),
(21, 'developer', 'dev', '$2y$10$quqkPDVvb32UVk2djTgKP.yHJ6vixUMVKXNMi8st9AJCi3xV5ZaOK'),
(24, 'reader', 'artem', '$2y$10$mxy9FDM3cEIU4LoQvRUz5Oa0QppGnbDB5DlxIM8IDQ3tPuK5XAx6e'),
(25, 'moderator', 'moder', '$2y$10$m1lS7q4TKAAzPNRrjBZsDeqzS8AhQP1ost/G1gwP5KLg7rVRwP5yO'),
(26, 'moderator', 'dima', '$2y$10$T9dzFro1CHV874g7x8LiDuXfuPAak2/SnZAaO6/eWZPuYu5dOBoXq');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `developer`
--
ALTER TABLE `developer`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `equipment_images`
--
ALTER TABLE `equipment_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Индексы таблицы `equipment_specs`
--
ALTER TABLE `equipment_specs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`equipment_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `military_equipment`
--
ALTER TABLE `military_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `moderator`
--
ALTER TABLE `moderator`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reader`
--
ALTER TABLE `reader`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `equipment_images`
--
ALTER TABLE `equipment_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `equipment_specs`
--
ALTER TABLE `equipment_specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `military_equipment`
--
ALTER TABLE `military_equipment`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `equipment_images`
--
ALTER TABLE `equipment_images`
  ADD CONSTRAINT `equipment_images_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `military_equipment` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `equipment_specs`
--
ALTER TABLE `equipment_specs`
  ADD CONSTRAINT `equipment_specs_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `military_equipment` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `military_equipment` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
