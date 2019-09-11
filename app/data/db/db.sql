CREATE DATABASE IF NOT EXISTS `livemaster`
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

USE `livemaster`;

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id`   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30)      NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`    VARCHAR(30)      NOT NULL,
  `age`     INT UNSIGNED     NOT NULL,
  `city_id` INT(11) UNSIGNED NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
)
  ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


INSERT INTO `cities`
SET `name` = 'Москва';

INSERT INTO `cities`
SET `name` = 'Владивосток';

INSERT INTO `cities`
SET `name` = 'Смоленск';
