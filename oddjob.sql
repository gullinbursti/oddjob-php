#
# Encoding: Unicode (UTF-8)
#


DROP TABLE IF EXISTS `tblEducationTypes`;
DROP TABLE IF EXISTS `tblJobs`;
DROP TABLE IF EXISTS `tblJobTags`;
DROP TABLE IF EXISTS `tblJobTypes`;
DROP TABLE IF EXISTS `tblLocales`;
DROP TABLE IF EXISTS `tblPosters`;
DROP TABLE IF EXISTS `tblUsers`;


CREATE TABLE `tblEducationTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `added` datetime DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `tblJobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(128) DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `info` varchar(128) DEFAULT NULL,
  `poster_id` int(128) DEFAULT '0',
  `icon_url` varchar(128) DEFAULT NULL,
  `points` int(128) DEFAULT '0',
  `required` int(128) DEFAULT '0',
  `location_id` int(128) DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


CREATE TABLE `tblJobTags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `added` datetime DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `tblJobTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `info` varchar(128) DEFAULT '',
  `added` datetime DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `tblLocales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `longitude` float NOT NULL DEFAULT '0',
  `latitude` float NOT NULL DEFAULT '0',
  `added` datetime DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `tblPosters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `info` varchar(128) DEFAULT NULL,
  `added` datetime DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tblUsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` varchar(128) DEFAULT NULL,
  `fName` varchar(128) DEFAULT NULL,
  `lName` varchar(128) DEFAULT NULL,
  `age` int(128) DEFAULT '0',
  `sex` char(1) DEFAULT NULL,
  `hometown` varchar(128) DEFAULT NULL,
  `edu_id` varchar(128) DEFAULT NULL,
  `location_id` int(128) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;




SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `tblEducationTypes` WRITE;
INSERT INTO `tblEducationTypes` (`id`, `name`, `added`, `modified`) VALUES (1, 'High School', '2011-09-16 12:37:08', '2011-09-16 12:37:08'), (2, 'College', '2011-09-16 12:37:40', '2011-09-16 12:37:40');
UNLOCK TABLES;


LOCK TABLES `tblJobs` WRITE;
INSERT INTO `tblJobs` (`id`, `type_id`, `title`, `info`, `poster_id`, `icon_url`, `points`, `required`, `location_id`, `added`, `expires`, `modified`) VALUES (1, 1, 'Wash my little Smart Car!', 'I need someone to come wash my Smart Car today!', 0, NULL, 25, 0, 0, '2011-09-16 13:15:59', '2011-09-26 16:15:59', '2011-09-16 16:15:59'), (2, 2, 'Catering Help Wanted!', 'Need multiple people to help cater a wedding.', 0, NULL, 0, 0, 0, '2011-09-16 13:16:44', '2011-09-22 16:16:44', '2011-09-16 16:16:44'), (3, 1, 'Dog Walker WANTED!', 'I need someone to walk my two Jack Russells. One is named peter the other is named jackson.', 0, NULL, 0, 0, 0, '2011-09-16 13:17:25', '2011-09-24 16:17:25', '2011-09-16 16:17:25'), (4, 3, 'Hauling Junk', '5 strong individuals are needed to move my belongings.', 0, NULL, 0, 0, 0, '2011-09-16 13:18:01', '2011-09-28 16:18:01', '2011-09-16 16:18:01'), (5, 1, 'Clean my house!', 'I need someone to come over and clean my dirty house.', 0, NULL, 0, 0, 0, '2011-09-16 13:18:28', '2011-09-24 16:18:28', '2011-09-16 16:18:28'), (6, 2, 'Paint my house!', 'Can you paint my house? I need a good painter now!', 0, NULL, 0, 0, 0, '2011-09-16 13:19:14', '2011-09-18 16:19:14', '2011-09-16 16:19:14'), (7, 2, 'Live band needed at a local bar', 'Live Band needed at local Moes bar and grill. Must play rock music.', 0, NULL, 0, 0, 0, '2011-09-16 13:19:56', '2011-09-20 16:19:56', '2011-09-16 16:19:56'), (8, 3, 'Local Model Wanted!', 'I need a model to pose for me. I am not creepy.', 0, NULL, 0, 0, 0, '2011-09-16 13:20:19', '2011-09-26 16:20:19', '2011-09-16 16:20:19'), (9, 3, 'Want to be an extra!', 'Be an extra in a real Hollywood movie! Do you want to hang out on set all day drinking.', 0, NULL, 0, 0, 0, '2011-09-16 13:20:32', '2011-10-06 16:20:32', '2011-09-16 16:20:32'), (10, 2, 'BE THE SIGN GUY!', 'Ya know, that guy on the side of the road spinning the sign like he is a rock star!', 0, NULL, 0, 0, 0, '2011-09-16 13:20:58', '2011-10-16 16:20:58', '2011-09-16 16:20:58'), (11, 4, 'Share Zynga games with your friends!', 'Share any Zynga game with 30 friends and get cash!', 0, NULL, 0, 0, 0, '2011-09-16 13:21:19', '2011-11-16 16:21:19', '2011-09-16 16:21:19');
UNLOCK TABLES;


LOCK TABLES `tblJobTags` WRITE;
INSERT INTO `tblJobTags` (`id`, `name`, `added`, `modified`) VALUES (1, 'Tag I', '0000-00-00 00:00:00', '2011-09-17 10:35:41');
UNLOCK TABLES;


LOCK TABLES `tblJobTypes` WRITE;
INSERT INTO `tblJobTypes` (`id`, `name`, `info`, `added`, `modified`) VALUES (1, 'Work for Hire', NULL, '2011-09-16 16:26:19', '2011-09-17 10:36:07'), (2, 'Job Type II', NULL, '2011-09-16 16:27:12', '2011-09-17 10:36:09'), (3, 'Job Type III', NULL, '2011-09-16 16:28:27', '2011-09-17 10:36:12'), (4, 'Job Type IV', NULL, '2011-09-16 16:29:27', '2011-09-17 10:36:14');
UNLOCK TABLES;


LOCK TABLES `tblLocales` WRITE;
INSERT INTO `tblLocales` (`id`, `name`, `longitude`, `latitude`, `added`, `modified`) VALUES (1, 'toof', 0, 0, NULL, '2011-09-16 16:25:06');
UNLOCK TABLES;


LOCK TABLES `tblPosters` WRITE;
UNLOCK TABLES;


LOCK TABLES `tblUsers` WRITE;
INSERT INTO `tblUsers` (`id`, `fbid`, `fName`, `lName`, `age`, `sex`, `hometown`, `edu_id`, `location_id`) VALUES (1, '660042243', 'Matthew', 'Holcombe', 30, 'M', 'Islamorada, FL', '2', 0);
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = 1;


