
create database if not exists carddb;

use carddb;

CREATE TABLE `carddb`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `phone` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NULL,
  `createtime` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC));


CREATE TABLE `carddb`.`cards` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `num` BIGINT NOT NULL,
  `cvv` INT NOT NULL,
  `valid` VARCHAR(45) NOT NULL,
  `added_timestamp` TIMESTAMP NULL DEFAULT NOW(),
  `userid` INT NOT NULL,
  `isdefault` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`));


CREATE TABLE `carddb`.`trans` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `recid` INT NOT NULL,
  `sendid` INT NOT NULL,
  `sendcardid` INT NOT NULL,
  `reccardid` INT NOT NULL,
  `amount` INT NOT NULL,
  `transtime` TIMESTAMP NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`));

