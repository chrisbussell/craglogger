DROP DATABASE IF EXISTS craglogger;

CREATE DATABASE craglogger;

USE craglogger;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin` int(1) COLLATE utf8_unicode_ci NOT NULL default '0',
  `approved` int(1) COLLATE utf8_unicode_ci NOT NULL default '0',
  `activation_code` varchar(10) NOT NULL default'0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
); 

CREATE TABLE craglist (
  crag_id int(11) NOT NULL AUTO_INCREMENT,
  venue varchar(255) NOT NULL,
  area varchar(255) NOT NULL,
  web varchar(255) NOT NULL,
  conditions varchar(255) NOT NULL,
  date date NOT NULL,
  rock char(64) NOT NULL,
  rainedoff int(1) NOT NULL,
  pub varchar(255) NOT NULL,
PRIMARY KEY (crag_id)
);

INSERT INTO craglist (crag_id, venue, area, web, conditions, date, rock, rainedoff, pub)VALUES ('','High Tor', 'All', 'http://www.ukclimbing.com', '','2014/02/01','Limestone','0','Millstone');
INSERT INTO craglist (crag_id, venue, area, web, conditions, date, rock, rainedoff, pub)VALUES ('','Burbage', 'Knights Move', 'http://www.ukclimbing.com', '','2014/3/11','Grit','0','Queens Arms');
INSERT INTO craglist (crag_id, venue, area, web, conditions, date, rock, rainedoff, pub)VALUES ('','Stanage', 'All', 'http://www.ukclimbing.com', '','2014/03/01','Grit','1','Millstone');

CREATE TABLE attended (
  attended_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  crag_id int(11) NOT NULL,
  timestamp timestamp,
PRIMARY KEY (attended_id)
);


