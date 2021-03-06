ROP DATABASE IF EXISTS craglogger;

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

CREATE TABLE nickname (
  nickname_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11),
  nickname varchar(50),
  timestamp timestamp,
  PRIMARY KEY (nickname_id),
  UNIQUE KEY `user_id` (`user_id`)
  );

CREATE TABLE lastlogin (
  lastlogin_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11),
  date date,
  timestamp timestamp,
  PRIMARY KEY (lastlogin_id)
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

CREATE TABLE sunset (
  sunset_id int(11) NOT NULL AUTO_INCREMENT,
  date date,
  sunsettime varchar(10),
  timestamp timestamp,
PRIMARY KEY (sunset_id)
);

CREATE TABLE moonphase(
  moonphase_id int(11) NOT NULL AUTO_INCREMENT,
  date date,
  phase varchar(10),
  coverage varchar(4),
  timestamp timestamp,
PRIMARY KEY (moonphase_id)
);

CREATE TABLE endoftermreport(
  report_id int(11) NOT NULL AUTO_INCREMENT,
  year int(4) NOT NULL,
  report BLOB,
  timestamp timestamp,
PRIMARY KEY (report_id)
);

INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-04-01', 'waxing', '5.11');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-04-08', 'waxing', '63.62');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-04-15', 'full', '99.72');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-04-23', 'waning', '33.3');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-04-29', 'new', '0.36');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-05-07', 'waxing', '54.34');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-05-13', 'waxing', '98.79');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-05-20', 'waning', '58.43');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-05-28', 'new', '0.00');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-06-03', 'waxing', '26.06');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-06-10', 'waxing', '89.05');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-06-17', 'waning', '77.72');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-06-24', 'waning', '9.27');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-07-01', 'waxing', '13.45');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-07-08', 'waxing', '77.13');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-07-15', 'waning', '88.75');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-07-22', 'waning', '15.16');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-07-29', 'waxing', '7.32');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-08-06', 'waxing', '94.31');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-08-12', 'waning', '93.59');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-08-19', 'waning', '27.58');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-08-27', 'waxing', '4.5');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-09-02', 'waxing', '54.09');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-09-09', 'waning', '99.16');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-09-16', 'waning', '42.76');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-09-23', 'new', '0.18');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-09-30', 'waxing', '39.22');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-10-07', 'waxing', '99.44');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-10-14', 'waning', '59.47');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-10-21', 'waning', '4.37');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-08-26', 'waxing', '1.37');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-05-27', 'waning', '0.95');
INSERT INTO moonphase (date, phase, coverage) VALUES ('2014-05-06', 'waxing', '47.02');

INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-01', '19:43');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-08', '19:55');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-15', '20:08');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-22', '20:20');
INSERT INTO sunset (date, sunsettime) VALUES ('2014-04-23', '20:22');
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

select year(date) as 'year', count(*) as attempts, sum(if(rainedoff = 0, 1, 0)) as actual, sum(if(rainedoff = 1, 1, 0)) as rainedoff from cragvisit group by YEAR(date);

ALTER TABLE cragdetail add column crag varchar(255) NOT NULL AFTER area;


CREATE TABLE userconfig (
  userconfig_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11),
  admin int(1) default 0,
  approved int(1) default 0,
  emailshow int(1),
  usertype_id int(1),
  timestamp timestamp,
  PRIMARY KEY (userconfig_id),
  UNIQUE KEY `user_id` (`user_id`)
  );

INSERT INTO userconfig (user_id, admin, approved, emailshow, usertype_id) VALUES ('1')

CREATE TABLE usertype (
  usertype_id int(11) NOT NULL AUTO_INCREMENT,
  handle varchar(20),
  PRIMARY KEY (usertype_id)
  );

INSERT INTO usertype (handle) VALUES ('FULL');
INSERT INTO usertype (handle) VALUES ('VIRTUAL USER');

SELECT u.user_id, username, n.nickname, password, salt, firstname, surname,username,email,admin,approved,emailshow, virtualuser FROM users u LEFT JOIN nickname n ON u.user_id = n.user_id

SELECT u.user_id , username, n.nickname, password, salt, firstname, surname, email, uc.admin, uc.approved, uc.emailshow, uc.usertype_id FROM users u INNER JOIN userconfig uc ON u.user_id = uc.user_id LEFT JOIN nickname n ON u.user_id = n.user_id


INSERT INTO sunset (date,sunsettime) VALUES ('2015/03/31','19:40');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/04/07','19:53');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/04/08','19:55');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/04/14','20:06');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/04/21','20:18');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/04/28','20:31');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/05/05','20:43');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/05/06','20:45');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/05/12','20:55');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/05/19','21:06');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/05/26','21:17');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/05/27','21:18');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/06/02','21:25');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/06/09','21:32');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/06/16','21:37');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/06/23','21:39');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/06/30','21:38');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/07/07','21:35');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/07/14','21:28');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/07/21','21:20');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/07/28','21:09');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/08/04','20:57');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/08/11','20:44');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/08/18','20:29');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/08/25','20:13');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/09/01','19:57');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/09/02','19:55');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/09/08','19:40');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/09/15','19:23');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/09/22','19:06');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/09/29','18:49');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/10/06','18:32');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/10/13','18:16');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/10/20','18:00');
INSERT INTO sunset (date,sunsettime) VALUES ('2015/10/27','16:45');

ALTER TABLE users DROP column username;

CREATE TABLE britishsummertime (
  summertime_id int(11) NOT NULL AUTO_INCREMENT,
  startdate date,
  enddate date,
  timestamp timestamp,
  PRIMARY KEY (summertime_id)
  );

INSERT INTO britishsummertime (startdate,enddate) VALUES ('2015/03/29','2015/10/25');
INSERT INTO britishsummertime (startdate,enddate) VALUES ('2016/03/27','2016/10/30');
INSERT INTO britishsummertime (startdate,enddate) VALUES ('2017/03/26','2017/10/29');
INSERT INTO britishsummertime (startdate,enddate) VALUES ('2018/03/25','2018/10/28');
INSERT INTO britishsummertime (startdate,enddate) VALUES ('2019/03/31','2019/10/27');
