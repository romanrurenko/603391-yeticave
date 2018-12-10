CREATE DATABASE IF NOT EXISTS `yeticave`
default character set utf8
default collate utf8_general_ci;

USE `yeticave`;

CREATE TABLE IF NOT EXISTS `bids` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL,
  `lot_id` int(11) unsigned NOT NULL,
  `amount` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  `style_name` char(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
  ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `lots` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` char(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(100) NOT NULL,
  `start_price` int(11) unsigned NOT NULL,
  `date_end` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bid_step` int(11) unsigned NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `winner_id` int(11) unsigned DEFAULT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `all_text` (`title`, `description`)
  ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` char(128) NOT NULL,
  `password` char(64) NOT NULL,
  `name` char(128) NOT NULL,
  `avatar_url` char(128) DEFAULT NULL,
  `contacts` varchar(512) NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`name`)
  ) ENGINE=InnoDB;