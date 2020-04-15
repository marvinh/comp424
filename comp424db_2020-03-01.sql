# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.26)
# Database: comp424db
# Generation Time: 2020-03-02 01:20:37 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table login_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_log`;

CREATE TABLE `login_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `success` int(11) NOT NULL DEFAULT '0',
  `fail` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `login_log` WRITE;
/*!40000 ALTER TABLE `login_log` DISABLE KEYS */;

INSERT INTO `login_log` (`id`, `user`, `attempts`, `success`, `fail`)
VALUES
	(4,21,19,9,10);

/*!40000 ALTER TABLE `login_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table questions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions`;

CREATE TABLE `questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;

INSERT INTO `questions` (`id`, `question`)
VALUES
	(1,'What was the name of your first pet?'),
	(2,'What model was your first car?'),
	(3,'What is your favorite place to eat?');

/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_question_answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_question_answer`;

CREATE TABLE `user_question_answer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `question_id` int(11) unsigned NOT NULL,
  `answer` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_question_answer` WRITE;
/*!40000 ALTER TABLE `user_question_answer` DISABLE KEYS */;

INSERT INTO `user_question_answer` (`id`, `user`, `question_id`, `answer`)
VALUES
	(14,21,1,'$2y$10$qwld7Po3MjLSANHWjqBKDukqlYR0DhnCHiMvbeg0yTpXNjsaQ5Xoq'),
	(15,21,2,'$2y$10$bz0iLBS9ZtGC1RKLjyzF7OUWKNL6qxkdAqpuEh65NqzuTy9LMU4di'),
	(16,21,3,'$2y$10$16gt0wCELbxSb5cvFbfGO.AY.0e7gLNGx8CujU.lAdKslE4a6xj9K');

/*!40000 ALTER TABLE `user_question_answer` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `pass` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `birth_date` varchar(255) NOT NULL DEFAULT '',
  `token` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `pass`, `email`, `birth_date`, `token`, `verified`)
VALUES
	(21,'Marvin','Harootoonyan','marvin','$2y$10$m2ZumMuaXWjNy1I/5JIOXu2W.yZjjEJgd4fqXzyHFssYMUz1NpGPC','MarvinHarootoonyan@gmail.com','1992-01-01','4359c98cc4eefcff06349e505f944f2d',1);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
