CREATE DATABASE IF NOT EXISTS `livemaster`;

USE `livemaster`;

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id`   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` CHAR(40),
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  CHARACTER SET = UTF8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`        INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`      CHAR(40),
  `birthdate` DATETIME,
  `city_id`   INT(11) UNSIGNED NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
)
  ENGINE = InnoDB
  CHARACTER SET = UTF8;
