
create database if not exists carddb;

use carddb;

CREATE TABLE `carddb`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `phone` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NULL,
  `createtime` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC));

