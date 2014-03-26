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
  `expiry` date NOT NULL,
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

INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('1','High Tor','','Limestone','England','Derbyshire', '150', 'W','http://www.ukclimbing.com/logbook/crag.php?id=119','53.126446','-1.5576134');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('2','Stanage','Popular','Gritstone','England','Derbyshire', '450', 'SW','http://www.ukclimbing.com/logbook/crag.php?id=104','53.345128','-1.6319593');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('3','Froggatt Edge','','Gritstone','England','Derbyshire', '300', 'W','http://http://www.ukclimbing.com/logbook/crag.php?id=22','53.284896','-1.6294768');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('4','Stanage','High Neb','Gritstone','England','Derbyshire', '500', 'SW','http://www.ukclimbing.com/logbook/crag.php?id=100','53.363181','-1.6573495');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('5','Roaches','Skyline','Gritstone','England','Staffordshire', '470', 'SW','http://www.ukclimbing.com/logbook/crag.php?id=798','53.165919','-1.9969681');
INSERT INTO cragdetail (cragdetail_id, venue, area, rock, country, county, altitude, faces, web, lat, lng) VALUES ('6','Castle Naze','','Gritstone','England','Derbyshire', '400', 'W','http://www.ukclimbing.com/logbook/crag.php?id=137','53.301626','-1.9234341');

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
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, conditions, pub, rainedoff) VALUES ('5','4','2014/02/21','Dry','The Queens Norkers','0');
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

