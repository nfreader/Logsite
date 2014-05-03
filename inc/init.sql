-- Create syntax for TABLE 'ls_events'
CREATE TABLE `ls_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `who` int(11) DEFAULT NULL,
  `what` enum('BA','C','W','B') DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `data` varchar(256) DEFAULT NULL,
  `link` varchar(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ls_playermeta'
CREATE TABLE `ls_playermeta` (
  `key` varchar(32) DEFAULT NULL COMMENT 'IP Address, Email address',
  `value` varchar(256) DEFAULT NULL,
  `player` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ls_players'
CREATE TABLE `ls_players` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `status` enum('B','W','P','C') DEFAULT NULL COMMENT 'Banned, Warned, Permanent, Contacted',
  `expiration` timestamp NULL DEFAULT NULL,
  `lastupdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ls_reportcomments'
CREATE TABLE `ls_reportcomments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report` varchar(11) DEFAULT NULL COMMENT 'The report this is referencing',
  `comment` longtext NOT NULL,
  `guest` tinyint(1) NOT NULL DEFAULT '0',
  `userid` int(11) DEFAULT NULL,
  `guestid` varchar(32) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ls_reports'
CREATE TABLE `ls_reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `type` enum('C','W','B') NOT NULL DEFAULT 'C',
  `notes` longtext,
  `perma` tinyint(1) NOT NULL DEFAULT '0',
  `user` int(11) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `eventid` varchar(9) DEFAULT NULL,
  `appeal` tinyint(1) NOT NULL DEFAULT '0',
  `public` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ls_session'
CREATE TABLE `ls_session` (
  `session_id` varchar(256) NOT NULL DEFAULT '',
  `session_data` longtext NOT NULL,
  `session_lastaccesstime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ls_user'
CREATE TABLE `ls_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `salt` varchar(256) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `rank` enum('U','A') NOT NULL DEFAULT 'U' COMMENT 'User, Admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;