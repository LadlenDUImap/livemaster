CREATE DATABASE `livemaster`;

CREATE TABLE `cities` (
  `id`   INT(11) NOT NULL AUTO_INCREMENT,
  `name` CHAR(40),
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  CHARACTER SET = UTF8;

CREATE TABLE `users` (
  `id`      INT(11) NOT NULL AUTO_INCREMENT,
  `city_id` INT(11) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`)
)
  ENGINE = InnoDB
  CHARACTER SET = UTF8;
