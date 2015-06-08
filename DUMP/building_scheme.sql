-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 14 2015 г., 10:51
-- Версия сервера: 5.7.2-m12
-- Версия PHP: 5.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `building_scheme`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cs_admins`
--

CREATE TABLE IF NOT EXISTS `cs_admins` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL DEFAULT '',
  `date_set` int(32) NOT NULL DEFAULT '0',
  `password` varchar(32) NOT NULL DEFAULT '',
  `access_class` int(5) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `contacts` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `cs_admins`
--

INSERT INTO `cs_admins` (`id`, `login`, `date_set`, `password`, `access_class`, `name`, `ip`, `contacts`) VALUES
(24, 'admin', 1426038880, 'b43216291522faa1e335f70b0f4d13c6', 19, 'Администратор', '', ''),
(1, 'dev', 1382264421, 'b43216291522faa1e335f70b0f4d13c6', 2, 'Dev', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `cs_admin_classes`
--

CREATE TABLE IF NOT EXISTS `cs_admin_classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `allowed` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Дамп данных таблицы `cs_admin_classes`
--

INSERT INTO `cs_admin_classes` (`id`, `name`, `allowed`) VALUES
(19, 'Максимальная', 'a:6:{s:9:"buildings";i:2;s:8:"settings";i:2;s:6:"admins";i:2;s:5:"tools";i:2;s:5:"menus";i:2;s:5:"pages";i:2;}');

-- --------------------------------------------------------

--
-- Структура таблицы `cs_attach_files`
--

CREATE TABLE IF NOT EXISTS `cs_attach_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `for_id` int(11) DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `date_add` int(11) NOT NULL DEFAULT '0',
  `file` varchar(255) NOT NULL DEFAULT '',
  `size` int(30) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT '',
  `content_type` varchar(50) NOT NULL,
  `sort` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `for_id` (`for_id`,`content_type`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cs_attach_fotos`
--

CREATE TABLE IF NOT EXISTS `cs_attach_fotos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `for_id` int(11) NOT NULL DEFAULT '0',
  `picture` varchar(250) NOT NULL DEFAULT '',
  `sort` int(10) NOT NULL,
  `width` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `height` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `content_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `for_id` (`for_id`,`content_type`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Дамп данных таблицы `cs_attach_fotos`
--

INSERT INTO `cs_attach_fotos` (`id`, `for_id`, `picture`, `sort`, `width`, `height`, `name`, `content_type`) VALUES
(2, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182339.png', 0, 0, 0, '', 'buildings'),
(3, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182340.png', 0, 0, 0, '', 'buildings'),
(4, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182341.png', 0, 0, 0, '', 'buildings'),
(5, 14, 'examplepano.jpg', 0, 0, 0, '', 'buildings'),
(6, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182342.png', 0, 0, 0, '', 'buildings'),
(10, 14, '1048823176-3.jpg', 0, 0, 0, '', 'buildings'),
(9, 14, '1048823176-4.jpg', 0, 0, 0, '', 'buildings'),
(11, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182343.png', 0, 0, 0, '', 'buildings'),
(16, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182344.png', 0, 0, 0, '', 'buildings'),
(13, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182345.png', 0, 0, 0, '', 'buildings'),
(14, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182346.png', 0, 0, 0, '', 'buildings'),
(19, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182347.png', 0, 0, 0, '', 'buildings'),
(20, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182348.png', 0, 0, 0, '', 'buildings'),
(21, 14, '1048823176-5.jpg', 0, 0, 0, '', 'buildings'),
(22, 14, '1048823176-6.jpg', 0, 0, 0, '', 'buildings'),
(23, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182349.png', 0, 0, 0, '', 'buildings'),
(24, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182350.png', 0, 0, 0, '', 'buildings'),
(25, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182351.png', 0, 0, 0, '', 'buildings'),
(29, 14, '1048823176-7.jpg', 0, 0, 0, '', 'buildings'),
(33, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182353.png', 0, 0, 0, '', 'buildings'),
(31, 14, 'profilephoto.jpg', 0, 0, 0, '', 'buildings'),
(42, 14, 'examplepano.jpg', 0, 0, 0, '', 'buildings'),
(43, 0, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182337.png', 0, 0, 0, '', 'buildings'),
(36, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182354.png', 0, 0, 0, '', 'buildings'),
(37, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182355.png', 0, 0, 0, '', 'buildings'),
(38, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182356.png', 0, 0, 0, '', 'buildings'),
(39, 14, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182357.png', 0, 0, 0, '', 'buildings'),
(44, 0, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182338.png', 0, 0, 0, '', 'buildings'),
(45, 0, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182339.png', 0, 0, 0, '', 'buildings'),
(46, 24, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182340.png', 0, 0, 0, '', 'buildings'),
(48, 24, 'viktoriya-sedova---mozilla-firefox-2015-03-13-182342.png', 0, 0, 0, '', 'buildings'),
(55, 25, 'examplepano-3.jpg', 0, 0, 0, '', 'buildings'),
(50, 25, 'examplepano-2.jpg', 0, 0, 0, '', 'buildings'),
(53, 25, '1048823176.jpg', 0, 0, 0, '', 'buildings'),
(56, 0, 'unixar_tabl_2.png', 0, 0, 0, '', 'buildings'),
(57, 28, 'examplepano-4.jpg', 0, 0, 0, '', 'buildings'),
(58, 28, 'examplepano-5.jpg', 0, 0, 0, '', 'buildings'),
(59, 25, 'unixar_tabl_2-2.png', 0, 0, 0, '', 'buildings'),
(60, 25, 'examplepano-6.jpg', 0, 0, 0, '', 'buildings'),
(61, 0, 'examplepano.jpg', 0, 0, 0, '', 'buildings'),
(62, 30, 'examplepano-2.jpg', 0, 0, 0, '', 'buildings'),
(63, 30, 'examplepano-3.jpg', 0, 0, 0, '', 'buildings'),
(64, 30, 'jivotnit_positiv_11_1_bender777post.jpg', 0, 0, 0, '', 'buildings'),
(72, 36, 'examplepano-5.jpg', 0, 0, 0, '', 'buildings'),
(66, 30, 'jivotnit_positiv_11_95_bender777post.JPG', 0, 0, 0, '', 'buildings'),
(67, 30, 'jivotnit_positiv_11_47_bender777post.jpg', 0, 0, 0, '', 'buildings'),
(68, 30, 'jivotnit_positiv_11_7_bender777post.jpg', 0, 0, 0, '', 'buildings'),
(69, 30, 'examplepano-4.jpg', 0, 0, 0, '', 'buildings'),
(70, 30, '1048823176.jpg', 0, 0, 0, '', 'buildings'),
(71, 30, 'virt_client.png', 0, 0, 0, '', 'buildings'),
(73, 36, 'unixar_tabl_2.png', 0, 0, 0, '', 'buildings'),
(74, 36, 'virt_admin.png', 0, 0, 0, '', 'buildings');

-- --------------------------------------------------------

--
-- Структура таблицы `cs_buildings`
--

CREATE TABLE IF NOT EXISTS `cs_buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date_add` int(11) NOT NULL,
  `num_floors` int(11) NOT NULL,
  `num_points` int(11) NOT NULL,
  `floors` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Дамп данных таблицы `cs_buildings`
--

INSERT INTO `cs_buildings` (`id`, `title`, `date_add`, `num_floors`, `num_points`, `floors`) VALUES
(37, 'Первое здание', 1425848400, 4, 8, 'a:4:{i:1;a:4:{s:5:"title";s:10:"Этаж 1";s:16:"floor_scheme_img";i:71;s:6:"points";a:4:{i:0;a:4:{s:5:"title";s:16:"Комната 1";s:11:"description";s:1276:"Высшая арифметика концентрирует интеграл Дирихле. В общем, неопределенный интеграл отображает положительный скачок функции. Бином Ньютона, следовательно, неограничен сверху.&lt;br&gt;&lt;br&gt;Ортогональный определитель, в первом приближении, осмысленно порождает двойной интеграл. Окрестность точки отрицательна. Арифметическая прогрессия изоморфна. Используя таблицу интегралов элементарных функций, получим: интеграл Пуассона в принципе определяет многомерный неопределенный интеграл. Приступая к доказательству следует безапелляционно заявить, что целое число синхронизирует анормальный предел последовательности. Прямоугольная матрица в принципе синхронизирует криволинейный интеграл.";s:4:"area";s:81:"0.011666666666666667|0.007541478129713424|0.30083333333333334|0.48717948717948717";s:17:"palennum_tour_img";i:61;}i:1;a:4:{s:5:"title";s:16:"Комната 2";s:11:"description";s:933:"Ортогональный определитель, в первом приближении, осмысленно порождает двойной интеграл. Окрестность точки отрицательна. Арифметическая прогрессия изоморфна. Используя таблицу интегралов элементарных функций, получим: интеграл Пуассона в принципе определяет многомерный неопределенный интеграл. Приступая к доказательству следует безапелляционно заявить, что целое число синхронизирует анормальный предел последовательности. Прямоугольная матрица в принципе синхронизирует криволинейный интеграл.";s:4:"area";s:65:"0.0025|0.6108202443280978|0.44166666666666665|0.38917975567190227";s:17:"palennum_tour_img";i:62;}i:2;a:4:{s:5:"title";s:16:"Комната 3";s:11:"description";s:0:"";s:4:"area";s:43:"0.4008333333333333|0.019197207678883072|0|0";s:17:"palennum_tour_img";i:63;}i:3;a:4:{s:5:"title";s:16:"Комната 4";s:11:"description";s:1276:"Высшая арифметика концентрирует интеграл Дирихле. В общем, неопределенный интеграл отображает положительный скачок функции. Бином Ньютона, следовательно, неограничен сверху.&lt;br&gt;&lt;br&gt;Ортогональный определитель, в первом приближении, осмысленно порождает двойной интеграл. Окрестность точки отрицательна. Арифметическая прогрессия изоморфна. Используя таблицу интегралов элементарных функций, получим: интеграл Пуассона в принципе определяет многомерный неопределенный интеграл. Приступая к доказательству следует безапелляционно заявить, что целое число синхронизирует анормальный предел последовательности. Прямоугольная матрица в принципе синхронизирует криволинейный интеграл.";s:4:"area";s:77:"0.5891666666666666|0.13787085514834205|0.31916666666666665|0.7102966841186736";s:17:"palennum_tour_img";i:64;}}s:14:"selected_point";i:3;}i:2;a:4:{s:5:"title";s:27:"Любое название";s:16:"floor_scheme_img";i:66;s:6:"points";a:3:{i:0;a:4:{s:5:"title";s:12:"Туалет";s:11:"description";s:0:"";s:4:"area";s:75:"0.7266666666666667|0.45403111739745405|0.2733333333333333|0.545968882602546";s:17:"palennum_tour_img";i:67;}i:1;a:4:{s:5:"title";s:16:"Комната 1";s:11:"description";s:0:"";s:4:"area";s:78:"0.035833333333333335|0.04384724186704385|0.2866666666666667|0.4695898161244696";s:17:"palennum_tour_img";i:68;}i:2;a:4:{s:5:"title";s:10:"Кухня";s:11:"description";s:0:"";s:4:"area";s:63:"0.3016666666666667|0.2065063649222065|0.4425|0.5841584158415841";s:17:"palennum_tour_img";i:0;}}s:14:"selected_point";i:0;}i:3;a:4:{s:5:"title";s:10:"Этаж 3";s:16:"floor_scheme_img";i:70;s:6:"points";a:1:{i:0;a:4:{s:5:"title";s:16:"комната 1";s:11:"description";s:1276:"Высшая арифметика концентрирует интеграл Дирихле. В общем, неопределенный интеграл отображает положительный скачок функции. Бином Ньютона, следовательно, неограничен сверху.&lt;br&gt;&lt;br&gt;Ортогональный определитель, в первом приближении, осмысленно порождает двойной интеграл. Окрестность точки отрицательна. Арифметическая прогрессия изоморфна. Используя таблицу интегралов элементарных функций, получим: интеграл Пуассона в принципе определяет многомерный неопределенный интеграл. Приступая к доказательству следует безапелляционно заявить, что целое число синхронизирует анормальный предел последовательности. Прямоугольная матрица в принципе синхронизирует криволинейный интеграл.";s:4:"area";s:64:"0.43333333333333335|0.023333333333333334|0.3233333333333333|0.63";s:17:"palennum_tour_img";i:69;}}s:14:"selected_point";i:0;}i:4;a:4:{s:5:"title";s:21:"Пустой этаж";s:16:"floor_scheme_img";i:0;s:6:"points";a:0:{}s:14:"selected_point";i:0;}}'),
(36, 'здание', 1426280400, 2, 3, 'a:2:{i:1;a:4:{s:5:"title";s:37:"Всего лишь один этаж";s:16:"floor_scheme_img";i:73;s:6:"points";a:1:{i:0;a:4:{s:5:"title";s:32:"Точка без области";s:11:"description";s:0:"";s:4:"area";s:0:"";s:17:"palennum_tour_img";i:72;}}s:14:"selected_point";i:0;}i:2;a:4:{s:5:"title";s:30:"Этаж без панорам";s:16:"floor_scheme_img";i:74;s:6:"points";a:2:{i:0;a:4:{s:5:"title";s:16:"Комната 1";s:11:"description";s:0:"";s:4:"area";s:64:"0.35|0.27314814814814814|0.20583333333333334|0.10648148148148148";s:17:"palennum_tour_img";i:0;}i:1;a:4:{s:5:"title";s:16:"Комната 2";s:11:"description";s:0:"";s:4:"area";s:76:"0.6691666666666667|0.25308641975308643|0.2891666666666667|0.4645061728395062";s:17:"palennum_tour_img";i:0;}}s:14:"selected_point";i:1;}}');

-- --------------------------------------------------------

--
-- Структура таблицы `cs_menus`
--

CREATE TABLE IF NOT EXISTS `cs_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `cs_menus`
--

INSERT INTO `cs_menus` (`id`, `name`, `sort`) VALUES
(1, 'dsfsd', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `cs_menu_items`
--

CREATE TABLE IF NOT EXISTS `cs_menu_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `parent` int(10) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `menu_id` int(5) NOT NULL,
  `title2` varchar(255) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`,`menu_id`),
  KEY `parent` (`parent`,`enabled`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cs_modules`
--

CREATE TABLE IF NOT EXISTS `cs_modules` (
  `id` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_attach` tinyint(1) NOT NULL,
  `sort` int(3) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `is_attach` (`is_attach`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cs_modules`
--

INSERT INTO `cs_modules` (`id`, `name`, `is_attach`, `sort`) VALUES
('buildings', 'Здания', 0, 1),
('settings', 'Настройки', 0, 4),
('admins', 'Администраторы', 0, 5),
('tools', 'Инструменты', 0, 6),
('menus', 'Меню', 0, 8),
('pages', 'Страницы', 0, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `cs_pages`
--

CREATE TABLE IF NOT EXISTS `cs_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `nomenu` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `nohead` tinyint(1) NOT NULL DEFAULT '0',
  `catalog` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(10) NOT NULL,
  `full_link` varchar(255) NOT NULL,
  `title_first` varchar(255) NOT NULL,
  `topage` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `nesting` tinyint(1) NOT NULL DEFAULT '1',
  `meta_title` varchar(255) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `module` varchar(100) NOT NULL DEFAULT '',
  `template` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog` (`catalog`,`parent`,`full_link`),
  KEY `enabled` (`enabled`),
  KEY `topage` (`topage`),
  KEY `nesting` (`nesting`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cs_revisions`
--

CREATE TABLE IF NOT EXISTS `cs_revisions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `for_id` int(11) NOT NULL,
  `content_type` varchar(50) NOT NULL,
  `date_add` int(32) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `for_id` (`for_id`,`content_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cs_router`
--

CREATE TABLE IF NOT EXISTS `cs_router` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `record_rule` varchar(255) NOT NULL,
  `record_vars` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cs_settings`
--

CREATE TABLE IF NOT EXISTS `cs_settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `cs_settings`
--

INSERT INTO `cs_settings` (`setting_id`, `name`, `value`) VALUES
(1, 'site_title', ''),
(2, 'company_name', ''),
(3, 'meta_title', ''),
(4, 'meta_description', ''),
(5, 'meta_keywords', ''),
(6, 'counters_code', ''),
(7, 'site_email', ''),
(8, 'site_phone2', ''),
(9, 'site_email2', ''),
(10, 'skype', ''),
(11, 'office_hours', ''),
(12, 'limit_num', '15'),
(13, 'limit_admin_num', '15'),
(14, 'admin_num_links', '5'),
(15, 'num_links', '5'),
(16, 'antivirus_enabled', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `cs_try_login_ip`
--

CREATE TABLE IF NOT EXISTS `cs_try_login_ip` (
  `ip` varchar(32) NOT NULL,
  `nums` tinyint(1) NOT NULL,
  `last_date` int(32) NOT NULL,
  UNIQUE KEY `ip` (`ip`),
  KEY `nums` (`nums`,`last_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
