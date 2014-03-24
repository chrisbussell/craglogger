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
  `activation_code` varchar(100) NOT NULL default'0',
  `emailshow` int(1) NOT NULL default'0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
); 

CREATE TABLE cragdetail (
  cragdetail_id int(11) NOT NULL AUTO_INCREMENT,
  venue varchar(255) NOT NULL,
  area varchar(255) NOT NULL,
  rock char(64) NOT NULL,
  country char(64) NOT NULL,
  county char(64) NOT NULL,
  altitude char(64) NOT NULL,
  faces char(64) NOT NULL,
  web varchar(255) NOT NULL,
  lat FLOAT( 10, 6 ) NOT NULL,
  lng FLOAT( 10, 6 ) NOT NULL,
  timestamp timestamp,
PRIMARY KEY (cragdetail_id)
);

INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('1','High Tor','All','Limestone','England','Derbyshire', '100', 'W','http://www.ukc.com','53.369494','-1.6648134');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('2','Stanage','Popular','Grit','England','Derbyshire', '300', 'W','http://www.ukc.com','53.369494','-1.6648134');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('3','Froggatt','All','Grit','England','Derbyshire', '100', 'S','http://www.ukc.com','53.369494','-1.6648134');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('4','Stanage','High Neb','Grit','England','Derbyshire', '500', 'E','http://www.ukc.com','53.369494','-1.6648134');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('5','Roaches','All','Grit','England','Staffordshire', '100', 'W','http://www.ukc.com','53.369494','-1.6648134');

CREATE TABLE cragvisit (
  cragvisit_id int(11) NOT NULL AUTO_INCREMENT,
  cragdetail_id int(11) NOT NULL,
  date date NOT NULL,
  conditions varchar(255) NOT NULL,
  pub varchar(255) NOT NULL,
  rainedoff int(1) default 0,
  timestamp timestamp,
PRIMARY KEY (cragvisit_id)
);

INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, conditions, pub, rainedoff) VALUES ('1','1','2013/02/01','Dry','The Moon','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, conditions, pub, rainedoff) VALUES ('2','1','2014/02/01','Dry','The Knights Balls','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, conditions, pub, rainedoff) VALUES ('3','2','2014/02/07','Moist','The Knights Balls','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, conditions, pub, rainedoff) VALUES ('4','3','2014/02/14','Wet','The Knights Balls','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, conditions, pub, rainedoff) VALUES ('5','4','2014/02/21','Dry','The Knights Balls','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, conditions, pub, rainedoff) VALUES ('6','5','2014/02/28','Dry','Millstone','0');

CREATE TABLE attended (
  attended_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  cragvisit_id int(11) NOT NULL,
  timestamp timestamp,
PRIMARY KEY (attended_id)
);

CREATE TABLE cragreports (
  cragreport_id int(11) NOT NULL AUTO_INCREMENT,
  cragvisit_id int(11) NOT NULL,
  cragreport BLOB,
  timestamp timestamp,
PRIMARY KEY (cragreport_id),
UNIQUE KEY `cragvisit_id` (`cragvisit_id`)
);

INSERT INTO cragreports (cragreport_id, cragvisit_id, cragreport) VALUES ('1','1','A load of words about climbing rocks');
