-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.7.20 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных fedor
CREATE DATABASE IF NOT EXISTS `fedor` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `fedor`;

-- Дамп структуры для таблица fedor.Districts
CREATE TABLE IF NOT EXISTS `Districts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы fedor.Districts: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `Districts` DISABLE KEYS */;
INSERT INTO `Districts` (`ID`, `name`, `active`) VALUES
	(1, 'Красногвардейский', 1);
/*!40000 ALTER TABLE `Districts` ENABLE KEYS */;

-- Дамп структуры для таблица fedor.Logs
CREATE TABLE IF NOT EXISTS `Logs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `manager_id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `manager_id` (`manager_id`),
  CONSTRAINT `FK3` FOREIGN KEY (`manager_id`) REFERENCES `Managers` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы fedor.Logs: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `Logs` DISABLE KEYS */;
INSERT INTO `Logs` (`ID`, `manager_id`, `type`, `date`) VALUES
	(1, 1, 'Авторизовался', '2019-05-27 14:44:13'),
	(2, 1, 'Добавил заявку №1', '2019-05-27 14:45:13'),
	(3, 1, 'Добавил заявку №2', '2019-05-27 14:46:47'),
	(4, 1, 'Вышел', '2019-05-27 14:46:56'),
	(5, 0, 'Добавил заявку №7', '2019-05-27 14:54:11');
/*!40000 ALTER TABLE `Logs` ENABLE KEYS */;

-- Дамп структуры для таблица fedor.Managers
CREATE TABLE IF NOT EXISTS `Managers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT '0',
  `password` varchar(50) DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы fedor.Managers: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `Managers` DISABLE KEYS */;
INSERT INTO `Managers` (`ID`, `username`, `password`, `active`) VALUES
	(0, 'admin', '21232F297A57A5A743894A0E4A801FC3', 1),
	(1, 'test', '098f6bcd4621d373cade4e832627b4f6', 1);
/*!40000 ALTER TABLE `Managers` ENABLE KEYS */;

-- Дамп структуры для таблица fedor.Statements
CREATE TABLE IF NOT EXISTS `Statements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `manager_id` int(11) NOT NULL,
  `FIO` varchar(50) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `workpoint` varchar(50) DEFAULT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `children` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `manager_id` (`manager_id`),
  CONSTRAINT `FK2` FOREIGN KEY (`manager_id`) REFERENCES `Managers` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы fedor.Statements: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `Statements` DISABLE KEYS */;
INSERT INTO `Statements` (`ID`, `manager_id`, `FIO`, `gender`, `birthday`, `workpoint`, `citizenship`, `children`, `date`, `active`) VALUES
	(1, 1, 'Кочкин Семен Петрович', 'male', '1997-02-13', 'Газпром', 'Армения', NULL, '2019-05-27 14:45:13', 0),
	(2, 1, 'Афонасьев Киррил Вадимович', 'male', '2019-05-28', 'ИТМО', 'Россия', '[{"ФИО":"Акулова Марина Кирриловна","Пол":"Женский","День рождения":"2019-05-01","Место работы":"ИТМО"}]', '2019-05-27 14:46:47', 1),
	(7, 0, 'Толстый Вадим Васильевич', 'male', '1993-01-20', 'Политех', 'Казахстан', NULL, '2019-05-27 14:54:11', 1);
/*!40000 ALTER TABLE `Statements` ENABLE KEYS */;

-- Дамп структуры для таблица fedor.Statuses
CREATE TABLE IF NOT EXISTS `Statuses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `statement_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `statusinfo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `statement_id` (`statement_id`),
  CONSTRAINT `FK_Statuses_Statements` FOREIGN KEY (`statement_id`) REFERENCES `Statements` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы fedor.Statuses: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `Statuses` DISABLE KEYS */;
INSERT INTO `Statuses` (`ID`, `statement_id`, `name`, `statusinfo`) VALUES
	(1, 1, 'st-1', '{"Статус":"Подал заявление об участии в Государственной программе","Номер":"135645","Дата":"2019-05-28"}'),
	(2, 2, 'st-1', '{"Статус":"Подал заявление об участии в Государственной программе","Номер":"164256","Дата":"2019-05-29"}'),
	(3, 7, 'st-2', '{"Статус":"Направлен запрос в МВК района","Район":"Красногвардейский"}');
/*!40000 ALTER TABLE `Statuses` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
