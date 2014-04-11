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
  `regdate` date NOT NULL,
  `virtualuser` int(1) COLLATE utf8_unicode_ci NOT NULL default '0',
  timestamp timestamp,
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
  event varchar(255) NOT NULL,
  conditions varchar(255) NOT NULL,
  pub varchar(255) NOT NULL,
  rainedoff int(1) default 0,
  timestamp timestamp,
PRIMARY KEY (cragvisit_id)
);

INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, event, conditions, pub, rainedoff) VALUES ('1','1','2013/02/01','','Dry','The Moon','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, event, conditions, pub, rainedoff) VALUES ('2','1','2014/02/01','','Dry','The Knights Balls','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, event, conditions, pub, rainedoff) VALUES ('3','2','2014/02/07','','Moist','The Knights Balls','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, event, conditions, pub, rainedoff) VALUES ('4','3','2014/02/14','','Wet','The Knights Balls','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, event, conditions, pub, rainedoff) VALUES ('5','4','2014/02/21','','Dry','The Queens Norkers','0');
INSERT INTO cragvisit (cragvisit_id, cragdetail_id, date, event, conditions, pub, rainedoff) VALUES ('6','5','2014/02/28','','Dry','Millstone','0');

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

CREATE TABLE sunset (
  sunset_id int(11) NOT NULL AUTO_INCREMENT,
  date date,
  sunsettime varchar(10),
  timestamp timestamp,
PRIMARY KEY (sunset_id)
);

INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-01', '19:43');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-08', '19:55');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-15', '20:08');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-22', '20:20');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-29', '20:33');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-05-07', '20:47');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-05-13', '20:57');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-05-20', '21:09');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-05-28', '21:20');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-06-03', '21:27');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-06-10', '21:34');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-06-17', '21:38');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-06-24', '21:40');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-07-01', '21:38');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-07-08', '21:35');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-07-15', '21:28');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-07-22', '21:19');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-07-29', '21:08');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-08-06', '20:54');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-08-12', '20:42');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-08-19', '20:27');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-08-27', '20:09');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-09-02', '19:55');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-09-09', '19:38');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-09-16', '19:21');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-09-23', '19:04');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-09-30', '18:47');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-10-07', '18:30');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-10-14', '18:14');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-10-21', '17:59');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-08-05', '20:56');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-08-26', '20:11');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-05-27', '21:19');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-05-06', '20:45');

