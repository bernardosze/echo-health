# echo-health

*** General instructions to setup the environment *** 

1 - Create User table (Ready to MySql)
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema php
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema php
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `php` DEFAULT CHARACTER SET utf8 ;
USE `php` ;

-- -----------------------------------------------------
-- Table `php`.`USER`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `php`.`USER` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `EMAIL` VARCHAR(50) NOT NULL,
  `FIRST_NAME` VARCHAR(45) NOT NULL,
  `LAST_NAME` VARCHAR(45) NOT NULL,
  `PASSWORD` VARCHAR(255) NOT NULL,
  `BIRTHDAY` DATE NOT NULL,
  `LAST_LOGIN` DATETIME NULL,
  `LAST_LOGIN_ATTEMPT` DATETIME NULL,
  `LOGIN_ATTEMPT` INT NULL,
  `BLOCKED` VARCHAR(1) NOT NULL,
  `RECORD_CREATION` DATETIME NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `EMAIL_UNIQUE` (`EMAIL` ASC))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

2 - If necessary, edit database/database.php file to setup a database connection. However, as
all we gonna work in a centralized repository, the best approach is to set the environment equally for all members. Future TODO: TO use a external config file.

3 - Install Apache http server and deploy the app


*** Infrastructure features already implemented and tested ***
1 - Route management, not allowing direct access to .php files through urls
2 - Authentication service
3 - Signup user service
4 - Password encription using sha 128 bits
5 - Blocking user after 3 unsuccessful attempts
6 - Database access through centralized spot, using PDO.
7 - Friendly 404 error message
8 - Session management
9 - Availability of public routes (not requiring authentication)
10 - Boostrap
11 - Bootstrap date-picker library

Todo: Define through next group meetings:
1 - Default template engine, whether we will gona use one or not;
2 - Front-end form validation library;

*** Main project folder structure ***
1 - controllers -> Stores all route controllers. It can be organized by subfolders
2 - database -> Contains the main configuration to get access to the Database
3 - Models -> Place to strore all app Model classes
4 - Routes -> Stores the Routes and RouteManager classes
5 - Static -> Css, js and imgs go here
6 - util -> Utilitary classes for the project
7 - views -> Stores all html files