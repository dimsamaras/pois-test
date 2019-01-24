CREATE USER IF NOT EXISTS 'user'@'%';
CREATE DATABASE IF NOT EXISTS test;

GRANT ALL ON *.* TO 'user'@'%' IDENTIFIED BY 'secret';

FLUSH PRIVILEGES;

CREATE TABLE IF NOT EXISTS `test`.`users` ( 
`id` int(11) nOT NULL AUTO_INCREMENT,
`fistname` varchar(256) NOT NULL,
`lastname` varchar(256) NOT NULL,
`email` varchar(256) NOT NULL,
`password` varchar(256) NOT NULL,
`created` datetime NOT NULL,
`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `test`.`pois` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `category` varchar(256) NOT NULL,
  `latitude` DECIMAL(10, 8) NOT NULL, 
  `longitude` DECIMAL(11, 8) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `test`.`categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `category` varchar(256) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `pois` (`id`, `name`, `category`, `latitude`, `longitude`, `created`, `modified`) VALUES
(1, 'Thessaloniki', '1', 40.736851, 22.920227, '2019-01-02 10:10:12', '2019-01-02 10:10:12'),
(2, 'Athens', '1', 37.983810, 23.727539, '2019-01-01 10:10:12', '2019-01-01 10:10:12'),
(3, 'Larisa', '1', 39.643452, 22.413208, '2019-01-03 10:10:12', '2019-01-03 10:10:12'),
(7, 'Thessaloniki', '1', 40.736851, 22.920227, '2019-01-02 10:10:12', '2019-01-02 10:10:12'),
(16, 'Raches', '2', 38.8693654, 22.7557458, '2019-01-10 10:10:12', '2019-01-01 10:12:26'),
(34, 'Zagkliveri', '2', 40.5718221, 23.285064, '2019-01-10 10:10:12', '2019-01-10 10:10:12'),
(60, 'Los Angeles', '3', 34.0201613, -118.6919148, '2019-01-24 10:10:12', '2019-01-24 10:10:12');

INSERT INTO `categories` (`id`, `name`,`created`, `modified`) VALUES
(1, ' greek city',  '2019-01-02 05:12:26', '2019-01-02 05:12:26'),
(2, ' greek village', '2019-01-01 06:12:26', '2019-01-01 06:12:26'),
(3, 'abroad city','2019-01-07 10:10:12', '2019-01-07 10:10:12'),
(3, 'abroad village','2019-01-09 10:10:12', '2019-01-09 10:10:12')

