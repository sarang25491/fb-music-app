-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2009 at 11:08 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `music`
--

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(3) NOT NULL auto_increment,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `fb_page_id` bigint(64) NOT NULL,
  `owner` bigint(64) NOT NULL,
  `name` text NOT NULL,
  `show` int(1) NOT NULL,
  `verification_status` int(1) NOT NULL,
  `comment_box` int(1) NOT NULL,
  PRIMARY KEY  (`fb_page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE IF NOT EXISTS `system` (
  `var` varchar(32) NOT NULL,
  `data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userdb_plays`
--

CREATE TABLE IF NOT EXISTS `userdb_plays` (
  `owner` bigint(64) NOT NULL default '0',
  `player` bigint(64) NOT NULL default '0',
  `id` varchar(16) NOT NULL default '',
  `type` varchar(24) NOT NULL default '',
  `title` text character set utf8 NOT NULL,
  `artist` text character set utf8 NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `flag` int(1) NOT NULL default '0',
  `hide` int(1) NOT NULL,
  KEY `owner` (`owner`),
  KEY `player` (`player`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userdb_stats`
--

CREATE TABLE IF NOT EXISTS `userdb_stats` (
  `user` bigint(64) NOT NULL default '0',
  `todays_count` int(24) NOT NULL default '0',
  `all_time_count` int(24) NOT NULL default '0',
  `todays_activity` int(24) NOT NULL default '0',
  `all_time_activity` int(24) NOT NULL default '0',
  PRIMARY KEY  (`user`),
  KEY `todays_count` (`todays_count`),
  KEY `all_time_count` (`all_time_count`),
  KEY `todays_activity` (`todays_activity`),
  KEY `all_time_activity` (`all_time_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userdb_temporary`
--

CREATE TABLE IF NOT EXISTS `userdb_temporary` (
  `user` bigint(64) NOT NULL default '0',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `title` varchar(255) character set utf8 NOT NULL,
  `artist` varchar(255) character set utf8 NOT NULL,
  `playtime` int(11) NOT NULL,
  `crc32` varchar(100) NOT NULL default '',
  `sha1` varchar(100) NOT NULL default '',
  `md5` varchar(100) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userdb_tos`
--

CREATE TABLE IF NOT EXISTS `userdb_tos` (
  `user` bigint(64) NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userdb_transactions`
--

CREATE TABLE IF NOT EXISTS `userdb_transactions` (
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `payee` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY  (`payee`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userdb_uploads`
--

CREATE TABLE IF NOT EXISTS `userdb_uploads` (
  `id` int(16) NOT NULL auto_increment,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `user` bigint(64) NOT NULL default '0',
  `title` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `artist` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `playtime` int(8) NOT NULL,
  `crc32` varchar(255) NOT NULL default '',
  `sha1` varchar(255) NOT NULL default '',
  `md5` varchar(255) NOT NULL default '',
  `type` varchar(10) NOT NULL,
  `link` text NOT NULL,
  `buy_link` text NOT NULL,
  `count` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=519763 ;

-- --------------------------------------------------------

--
-- Table structure for table `userdb_users`
--

CREATE TABLE IF NOT EXISTS `userdb_users` (
  `user` bigint(64) NOT NULL default '0',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `page` int(1) NOT NULL,
  `credit` int(3) NOT NULL,
  `override` int(3) NOT NULL,
  `pro` int(1) NOT NULL default '0',
  `comment_box` int(1) NOT NULL,
  `pp_email` varchar(255) NOT NULL,
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
