-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.51b-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema sqd17_oo
--

CREATE DATABASE IF NOT EXISTS sqd17_oo;
USE sqd17_oo;

--
-- Temporary table structure for view `sqd_view_contentpage_names`
--
DROP TABLE IF EXISTS `sqd_view_contentpage_names`;
DROP VIEW IF EXISTS `sqd_view_contentpage_names`;
CREATE TABLE `sqd_view_contentpage_names` (
  `page_id` int(11) unsigned,
  `page_alias` varchar(40),
  `template_priority` bit(1),
  `name_id` int(11) unsigned,
  `name` varchar(40),
  `visibility` bit(1),
  `template_id` int(11) unsigned,
  `template` text
);

--
-- Temporary table structure for view `sqd_view_contentpages`
--
DROP TABLE IF EXISTS `sqd_view_contentpages`;
DROP VIEW IF EXISTS `sqd_view_contentpages`;
CREATE TABLE `sqd_view_contentpages` (
  `page_id` int(11) unsigned,
  `page_alias` varchar(40),
  `template_priority` bit(1),
  `template_id` int(11) unsigned,
  `template` text
);

--
-- Temporary table structure for view `sqd_view_contents`
--
DROP TABLE IF EXISTS `sqd_view_contents`;
DROP VIEW IF EXISTS `sqd_view_contents`;
CREATE TABLE `sqd_view_contents` (
  `content_id` int(11) unsigned,
  `content_alias` varchar(10),
  `content_type` tinyint(4) unsigned,
  `template` text,
  `local_path` varchar(50)
);

--
-- Temporary table structure for view `sqd_view_forms`
--
DROP TABLE IF EXISTS `sqd_view_forms`;
DROP VIEW IF EXISTS `sqd_view_forms`;
CREATE TABLE `sqd_view_forms` (
  `form_id` int(11) unsigned,
  `form_name` varchar(40),
  `form_method` bit(1),
  `form_action` varchar(70),
  `form_type` tinyint(4) unsigned,
  `form_submit_class` varchar(50),
  `template` text
);

--
-- Temporary table structure for view `sqd_view_tables`
--
DROP TABLE IF EXISTS `sqd_view_tables`;
DROP VIEW IF EXISTS `sqd_view_tables`;
CREATE TABLE `sqd_view_tables` (
  `table_id` int(11) unsigned,
  `table_alias` varchar(40),
  `table_type` tinyint(4) unsigned,
  `table_query` text,
  `template` text
);

--
-- Definition of table `sqd_config`
--

DROP TABLE IF EXISTS `sqd_config`;
CREATE TABLE `sqd_config` (
  `site` varchar(10) NOT NULL,
  `theme` int(11) unsigned default NULL,
  PRIMARY KEY  (`site`),
  KEY `theme` (`theme`),
  CONSTRAINT `sqd_config_fk` FOREIGN KEY (`theme`) REFERENCES `sqd_themes` (`theme_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_config`
--

/*!40000 ALTER TABLE `sqd_config` DISABLE KEYS */;
INSERT INTO `sqd_config` (`site`,`theme`) VALUES 
 ('default',1),
 ('admin',2);
/*!40000 ALTER TABLE `sqd_config` ENABLE KEYS */;


--
-- Definition of table `sqd_content_local_paths`
--

DROP TABLE IF EXISTS `sqd_content_local_paths`;
CREATE TABLE `sqd_content_local_paths` (
  `content_id` int(11) unsigned NOT NULL,
  `local_path` varchar(50) NOT NULL,
  PRIMARY KEY  (`content_id`),
  CONSTRAINT `content_local_paths_fk` FOREIGN KEY (`content_id`) REFERENCES `sqd_contents` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_content_local_paths`
--

/*!40000 ALTER TABLE `sqd_content_local_paths` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqd_content_local_paths` ENABLE KEYS */;


--
-- Definition of table `sqd_content_templates`
--

DROP TABLE IF EXISTS `sqd_content_templates`;
CREATE TABLE `sqd_content_templates` (
  `content_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`content_id`,`template_id`),
  KEY `content_id` (`content_id`),
  KEY `template_id` (`template_id`),
  CONSTRAINT `content_templates_fk` FOREIGN KEY (`content_id`) REFERENCES `sqd_contents` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `content_templates_fk1` FOREIGN KEY (`template_id`) REFERENCES `sqd_templates` (`template_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_content_templates`
--

/*!40000 ALTER TABLE `sqd_content_templates` DISABLE KEYS */;
INSERT INTO `sqd_content_templates` (`content_id`,`template_id`) VALUES 
 (1,1),
 (2,2);
/*!40000 ALTER TABLE `sqd_content_templates` ENABLE KEYS */;


--
-- Definition of table `sqd_contentpage_name_templates`
--

DROP TABLE IF EXISTS `sqd_contentpage_name_templates`;
CREATE TABLE `sqd_contentpage_name_templates` (
  `page_id` int(11) unsigned NOT NULL,
  `name_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`page_id`,`name_id`),
  KEY `page_id` (`page_id`),
  KEY `name_id` (`name_id`),
  KEY `template_id` (`template_id`),
  CONSTRAINT `sqd_contentpage_name_templates_fk` FOREIGN KEY (`page_id`) REFERENCES `sqd_contentpages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sqd_contentpage_name_templates_fk1` FOREIGN KEY (`name_id`) REFERENCES `sqd_contentpage_names` (`name_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sqd_contentpage_name_templates_fk2` FOREIGN KEY (`template_id`) REFERENCES `sqd_templates` (`template_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_contentpage_name_templates`
--

/*!40000 ALTER TABLE `sqd_contentpage_name_templates` DISABLE KEYS */;
INSERT INTO `sqd_contentpage_name_templates` (`page_id`,`name_id`,`template_id`) VALUES 
 (2,1,5),
 (2,2,6),
 (2,4,7),
 (2,5,8),
 (2,12,10),
 (5,6,10),
 (2,8,12),
 (2,10,17),
 (2,11,18),
 (2,7,19),
 (2,9,20);
/*!40000 ALTER TABLE `sqd_contentpage_name_templates` ENABLE KEYS */;


--
-- Definition of table `sqd_contentpage_names`
--

DROP TABLE IF EXISTS `sqd_contentpage_names`;
CREATE TABLE `sqd_contentpage_names` (
  `name_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`name_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_contentpage_names`
--

/*!40000 ALTER TABLE `sqd_contentpage_names` DISABLE KEYS */;
INSERT INTO `sqd_contentpage_names` (`name_id`,`name`) VALUES 
 (1,'admin-default'),
 (2,'admin-modules'),
 (4,'contentpage-default'),
 (5,'contentpage-add-new'),
 (6,'user-page'),
 (7,'javascript-back'),
 (8,'contentpage-save-success'),
 (9,'contentpage-save-error-message'),
 (10,'content-default'),
 (11,'table-default'),
 (12,'form-default');
/*!40000 ALTER TABLE `sqd_contentpage_names` ENABLE KEYS */;


--
-- Definition of table `sqd_contentpage_templates`
--

DROP TABLE IF EXISTS `sqd_contentpage_templates`;
CREATE TABLE `sqd_contentpage_templates` (
  `page_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`page_id`),
  KEY `template_id` (`template_id`),
  CONSTRAINT `sqd_contentpage_templates_fk` FOREIGN KEY (`page_id`) REFERENCES `sqd_contentpages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sqd_contentpage_templates_fk1` FOREIGN KEY (`template_id`) REFERENCES `sqd_templates` (`template_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_contentpage_templates`
--

/*!40000 ALTER TABLE `sqd_contentpage_templates` DISABLE KEYS */;
INSERT INTO `sqd_contentpage_templates` (`page_id`,`template_id`) VALUES 
 (1,3),
 (2,4),
 (8,18);
/*!40000 ALTER TABLE `sqd_contentpage_templates` ENABLE KEYS */;


--
-- Definition of table `sqd_contentpage_visibility`
--

DROP TABLE IF EXISTS `sqd_contentpage_visibility`;
CREATE TABLE `sqd_contentpage_visibility` (
  `page_id` int(11) unsigned NOT NULL,
  `name_id` int(11) unsigned NOT NULL,
  `visibility` bit(1) NOT NULL default '',
  PRIMARY KEY  (`page_id`,`name_id`),
  KEY `page_id` (`page_id`),
  KEY `name_id` (`name_id`),
  CONSTRAINT `sqd_contentpage_visibility_fk` FOREIGN KEY (`page_id`) REFERENCES `sqd_contentpages` (`page_id`) ON UPDATE CASCADE,
  CONSTRAINT `sqd_contentpage_visibility_fk1` FOREIGN KEY (`name_id`) REFERENCES `sqd_contentpage_names` (`name_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_contentpage_visibility`
--

/*!40000 ALTER TABLE `sqd_contentpage_visibility` DISABLE KEYS */;
INSERT INTO `sqd_contentpage_visibility` (`page_id`,`name_id`,`visibility`) VALUES 
 (2,1,0x01),
 (2,2,0x01),
 (2,4,0x01),
 (2,5,0x01),
 (2,7,0x01),
 (2,8,0x01),
 (2,9,0x01),
 (2,10,0x01),
 (2,11,0x01),
 (2,12,0x01),
 (5,6,0x01);
/*!40000 ALTER TABLE `sqd_contentpage_visibility` ENABLE KEYS */;


--
-- Definition of table `sqd_contentpages`
--

DROP TABLE IF EXISTS `sqd_contentpages`;
CREATE TABLE `sqd_contentpages` (
  `page_id` int(11) unsigned NOT NULL auto_increment,
  `page_alias` varchar(40) NOT NULL default '',
  `template_priority` bit(1) NOT NULL default '',
  PRIMARY KEY  (`page_id`),
  UNIQUE KEY `page_alias` (`page_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_contentpages`
--

/*!40000 ALTER TABLE `sqd_contentpages` DISABLE KEYS */;
INSERT INTO `sqd_contentpages` (`page_id`,`page_alias`,`template_priority`) VALUES 
 (1,'home',0x01),
 (2,'admin',0x01),
 (5,'user',0x01),
 (8,'8',0x01);
/*!40000 ALTER TABLE `sqd_contentpages` ENABLE KEYS */;


--
-- Definition of table `sqd_contents`
--

DROP TABLE IF EXISTS `sqd_contents`;
CREATE TABLE `sqd_contents` (
  `content_id` int(11) unsigned NOT NULL auto_increment,
  `content_alias` varchar(10) NOT NULL,
  `content_type` tinyint(4) unsigned NOT NULL default '12',
  PRIMARY KEY  (`content_id`),
  UNIQUE KEY `content_alias` (`content_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_contents`
--

/*!40000 ALTER TABLE `sqd_contents` DISABLE KEYS */;
INSERT INTO `sqd_contents` (`content_id`,`content_alias`,`content_type`) VALUES 
 (1,'menu',12),
 (2,'url_prefix',12);
/*!40000 ALTER TABLE `sqd_contents` ENABLE KEYS */;


--
-- Definition of table `sqd_form_field_configs`
--

DROP TABLE IF EXISTS `sqd_form_field_configs`;
CREATE TABLE `sqd_form_field_configs` (
  `field_id` int(11) unsigned NOT NULL auto_increment,
  `field_alias` varchar(20) NOT NULL,
  `field_name` varchar(20) NOT NULL,
  `field_text` varchar(100) NOT NULL,
  `field_default_value` varchar(40) default NULL,
  `field_input_type` tinyint(4) unsigned NOT NULL default '10',
  PRIMARY KEY  (`field_id`),
  UNIQUE KEY `field_alias` (`field_alias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_form_field_configs`
--

/*!40000 ALTER TABLE `sqd_form_field_configs` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqd_form_field_configs` ENABLE KEYS */;


--
-- Definition of table `sqd_form_field_css_classes`
--

DROP TABLE IF EXISTS `sqd_form_field_css_classes`;
CREATE TABLE `sqd_form_field_css_classes` (
  `field_id` int(11) unsigned NOT NULL,
  `css_class` varchar(30) NOT NULL,
  PRIMARY KEY  (`field_id`),
  CONSTRAINT `sqd_form_field_css_classes_fk` FOREIGN KEY (`field_id`) REFERENCES `sqd_form_field_configs` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_form_field_css_classes`
--

/*!40000 ALTER TABLE `sqd_form_field_css_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqd_form_field_css_classes` ENABLE KEYS */;


--
-- Definition of table `sqd_form_fields`
--

DROP TABLE IF EXISTS `sqd_form_fields`;
CREATE TABLE `sqd_form_fields` (
  `form_id` int(11) unsigned NOT NULL,
  `field_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`form_id`,`field_id`),
  KEY `form_id` (`form_id`),
  KEY `field_id` (`field_id`),
  CONSTRAINT `sqd_form_fields_fk` FOREIGN KEY (`form_id`) REFERENCES `sqd_forms` (`form_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sqd_form_fields_fk1` FOREIGN KEY (`field_id`) REFERENCES `sqd_form_field_configs` (`field_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_form_fields`
--

/*!40000 ALTER TABLE `sqd_form_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `sqd_form_fields` ENABLE KEYS */;


--
-- Definition of table `sqd_form_templates`
--

DROP TABLE IF EXISTS `sqd_form_templates`;
CREATE TABLE `sqd_form_templates` (
  `form_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`form_id`),
  KEY `template_id` (`template_id`),
  CONSTRAINT `sqd_form_templates_fk` FOREIGN KEY (`form_id`) REFERENCES `sqd_forms` (`form_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sqd_form_templates_fk1` FOREIGN KEY (`template_id`) REFERENCES `sqd_templates` (`template_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_form_templates`
--

/*!40000 ALTER TABLE `sqd_form_templates` DISABLE KEYS */;
INSERT INTO `sqd_form_templates` (`form_id`,`template_id`) VALUES 
 (2,8),
 (1,16);
/*!40000 ALTER TABLE `sqd_form_templates` ENABLE KEYS */;


--
-- Definition of table `sqd_forms`
--

DROP TABLE IF EXISTS `sqd_forms`;
CREATE TABLE `sqd_forms` (
  `form_id` int(11) unsigned NOT NULL auto_increment,
  `form_name` varchar(40) NOT NULL default '',
  `form_method` bit(1) NOT NULL default '',
  `form_action` varchar(70) default NULL,
  `form_type` tinyint(4) unsigned NOT NULL default '10',
  `form_submit_class` varchar(50) default NULL,
  PRIMARY KEY  (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_forms`
--

/*!40000 ALTER TABLE `sqd_forms` DISABLE KEYS */;
INSERT INTO `sqd_forms` (`form_id`,`form_name`,`form_method`,`form_action`,`form_type`,`form_submit_class`) VALUES 
 (1,'contentpage-add-new',0x01,NULL,10,'contentpage_add_new'),
 (2,'contentpage-add-new-named',0x01,NULL,10,'contentpage_add_new');
/*!40000 ALTER TABLE `sqd_forms` ENABLE KEYS */;


--
-- Definition of table `sqd_modules`
--

DROP TABLE IF EXISTS `sqd_modules`;
CREATE TABLE `sqd_modules` (
  `module_id` int(11) unsigned NOT NULL auto_increment,
  `module_name` varchar(20) NOT NULL,
  `enabled` bit(1) NOT NULL default '',
  PRIMARY KEY  (`module_id`),
  UNIQUE KEY `module_name` (`module_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_modules`
--

/*!40000 ALTER TABLE `sqd_modules` DISABLE KEYS */;
INSERT INTO `sqd_modules` (`module_id`,`module_name`,`enabled`) VALUES 
 (1,'Content',0x01),
 (2,'ContentPage',0x01),
 (3,'Admin',0x01),
 (4,'Table',0x01),
 (5,'Form',0x01);
/*!40000 ALTER TABLE `sqd_modules` ENABLE KEYS */;


--
-- Definition of table `sqd_table_templates`
--

DROP TABLE IF EXISTS `sqd_table_templates`;
CREATE TABLE `sqd_table_templates` (
  `table_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`table_id`),
  KEY `template_id` (`template_id`),
  CONSTRAINT `sqd_table_templates_fk` FOREIGN KEY (`table_id`) REFERENCES `sqd_tables` (`table_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sqd_table_templates_fk1` FOREIGN KEY (`template_id`) REFERENCES `sqd_templates` (`template_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_table_templates`
--

/*!40000 ALTER TABLE `sqd_table_templates` DISABLE KEYS */;
INSERT INTO `sqd_table_templates` (`table_id`,`template_id`) VALUES 
 (1,11),
 (2,14),
 (3,15),
 (4,15),
 (5,15),
 (6,15),
 (7,15);
/*!40000 ALTER TABLE `sqd_table_templates` ENABLE KEYS */;


--
-- Definition of table `sqd_tables`
--

DROP TABLE IF EXISTS `sqd_tables`;
CREATE TABLE `sqd_tables` (
  `table_id` int(11) unsigned NOT NULL auto_increment,
  `table_alias` varchar(40) NOT NULL default '',
  `table_type` tinyint(4) unsigned NOT NULL default '10',
  `table_query` text NOT NULL,
  PRIMARY KEY  (`table_id`),
  UNIQUE KEY `table_alias` (`table_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_tables`
--

/*!40000 ALTER TABLE `sqd_tables` DISABLE KEYS */;
INSERT INTO `sqd_tables` (`table_id`,`table_alias`,`table_type`,`table_query`) VALUES 
 (1,'admin',10,'SELECT m.module_id, m.module_name, CAST(m.enabled as unsigned integer) AS enabled FROM sqd_modules m;'),
 (2,'admin-modules',15,'SELECT module_name FROM sqd_modules;'),
 (3,'contentpage_table_pages',10,'SELECT p.`page_id`, p.`page_alias`, \r\n	CASE p.`template_priority` WHEN 1 THEN \'local-file\'\r\n    	WHEN 0 THEN \'database\' END AS template_priority\r\nFROM `sqd_view_contentpages` p;'),
 (4,'contentpage_table_namedpages',10,'SELECT CONCAT(n.`page_id`, \':\', n.`name_id`) AS name_id,\r\n	n.`page_alias`, n.`name`, \r\n    CASE 1 WHEN 1 THEN \'true\' ELSE \'false\' END AS visibility,\r\n	CASE n.`template_priority` WHEN 1 THEN \'local-file\'\r\n    	WHEN 0 THEN \'database\' END AS template_priority\r\nFROM `sqd_view_contentpage_names` n;'),
 (5,'content-table',10,'SELECT c.`content_id`, c.`content_alias`, c.`content_type`, c.`local_path` FROM `sqd_view_contents` c;'),
 (6,'table-table',10,'SELECT t.`table_id`, t.`table_alias`, t.`table_type`, t.`table_query` FROM `sqd_view_tables` t;'),
 (7,'form-table',10,'SELECT f.`form_id`, f.`form_name`, \r\n	CASE f.`form_method`\r\n    	WHEN 1 THEN \'POST\'\r\n        ELSE \'GET\' END AS form_method, \r\n    f.`form_type`, f.`form_submit_class`, f.`form_action` \r\nFROM `sqd_view_forms` f;');
/*!40000 ALTER TABLE `sqd_tables` ENABLE KEYS */;


--
-- Definition of table `sqd_templates`
--

DROP TABLE IF EXISTS `sqd_templates`;
CREATE TABLE `sqd_templates` (
  `template_id` int(11) unsigned NOT NULL auto_increment,
  `template_alias` varchar(40) NOT NULL default 'template',
  `system_template` bit(1) NOT NULL default '\0',
  `template` text NOT NULL,
  PRIMARY KEY  (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_templates`
--

/*!40000 ALTER TABLE `sqd_templates` DISABLE KEYS */;
INSERT INTO `sqd_templates` (`template_id`,`template_alias`,`system_template`,`template`) VALUES 
 (1,'1',0x00,'<p><strong><br />\r\n1. What is initrd?</strong><br />\r\nInitial Ram Disk or initrd is a compressed file system which is uncompressed and loaded to the memory by the boot loader. When the kernel is booting it treats this initial file system as the root file system and executes the first program /linuxrc. (Note: for Debian kernels the default is /init) This program then mounts the actual file system and passes the control to it and ideally un-mounting the initrd and freeing memory. </p>\r\n<p>Initial ram disk is a way of resolving the chicken and egg dilemma for the kernel. Imagine you have the actual root file system in a device which requires the kernel to use additional modules, but in order to load these modules the kernel has to access the file system. Thus the initrd could contain all the required modules and mount the actual file system and then pass over the control. </p>\r\n<p><strong>2. Why create an initrd?</strong><br />\r\nAt a glance all the distributions come with an initrd (if they need one) and most of the time provides tools to upgrade it. So why go through all the trouble of making a custom initrd? Well, the answer is simple. You can\'t get the stock initrd to do what you want in the way you want, especially if you are building a system of your own. And that?s when you want to tweak the stock initrd or create your own.</p>\r\n<p><strong>3. Modifying the Debian initrd</strong><br />\r\nAs a start, we\'ll try modifying a stock initrd. I chose Debian since I?m familiar with it but you could use your own. Obviously the steps won?t be the same, but once you get the basics you?ll be able to continue on your own. Try looking into the troubleshooting section if you face any difficulties. </p>\r\n<p><em><u>3.1 What you need</u></em><br />\r\n\r\nAll you need is a Linux System to do your work and a stock initrd.<br />\r\nI chose the initrd from the Debian Etch business-card installation disk since it is simple and small (not the smallest) in size. You can always use your system initrd but remember not to modify the original and always keep a copy of the original. </p>\r\n<p><em><u>3.2 Extracting the initrd</u></em><br />\r\nDownload the <a href=\"http://www.debian.org/CD/netinst/#businesscard-stable\" target=\"_blank\">business card</a> iso image from <a href=\"http://www.debian.org/\" target=\"_blank\">Debian</a>  and mount it.<br />\r\n<strong><br />\r\n<code><br />\r\n# Create the folder to mount<br />\r\n\r\nmkdir ?p /mnt/isoimage/<br />\r\n</code></strong><br />\r\n<strong><code><br />\r\n# Mount the iso image<br />\r\nmount -o loop -t iso9660 debian-40r2-i386-businesscard.iso /mnt/isoimage/<br />\r\n</code><br />\r\n</strong></p>\r\n<p>It is a good idea to have a clean working directory so you know what you are doing at the moment and it is easier to manage. I setup my working directory as <strong>$HOME/WorkBench</strong></p>\r\n<p>Now let?s copy the initrd from Debian iso image. But first we need to find where the initrd image is. You could find the initrd image in /boot in a Linux System so hoping that the iso image has the same structure we?ll look in the mounted image structure.<br />\r\n<code><br />\r\n$ ls ?l /mnt/isoimage/<br />\r\n\r\n</code></p>\r\n<p>Unfortunately the boot folder is not there, but we could find a folder named ?isolinux?. Since we find this folder we could conclude the iso image uses the <a href=\"http://syslinux.zytor.com/iso.php\" target=\"_blank\">ISOLINUX</a> boot system. </p>\r\n<p>So we look inside isolinux folder,<br />\r\n<code><br />\r\n$ ls ?l /mnt/isoimage/isolinux<br />\r\n</code></p>\r\n<p>Now we have the <strong>isolinux.cfg</strong> file which is used to store ISOLINUX configuration.<br />\r\n\r\n<strong><code><br />\r\n$ less /mnt/isoimage/isolinux/isolinux.cfg<br />\r\n</code></strong></p>\r\n<p>Try to find something like,<br />\r\n<strong><br />\r\n<code><br />\r\nLABEL	install<br />\r\nkernel 	/install.386/vmlinuz<br />\r\nappend	vga=normal initrd=/install.386/initrd.gz ?<br />\r\n</code><br />\r\n</strong></p>\r\n<p>There we are, the initrd image is in install.386 folder. Copy it to your work bench.</p>\r\n\r\n<p><strong><code><br />\r\n$ cp /mnt/isoimage/isolinux/install.386/initrd.gz $HOME/WorkBench<br />\r\n$ cd $HOME/WorkBench<br />\r\n</code><br />\r\n'),
 (2,'url-prefix',0x01,'/squad17.com/');
INSERT INTO `sqd_templates` (`template_id`,`template_alias`,`system_template`,`template`) VALUES 
 (3,'3',0x00,'{menu}'),
 (4,'admin-template',0x01,'<div class=\"admin-module\">\r\n{Admin:admin}\r\n</div>'),
 (5,'5',0x00,'<a href=\"{url_prefix}admin/modules\">Modules</a>'),
 (6,'6',0x00,'Default Modules Page <br /> Wil be listing all the configurable modules...'),
 (7,'contentpage-default',0x01,'<dl class=\"admin-dlist\">\r\n<dt><b>Content Pages</b> - <a href=\"{url_prefix}admin/modules/ContentPage/?config=add&page=page\">Add new</a></dt>\r\n<dd>\r\n<table class=\"admin-contentpage\">\r\n<tr><th>Content Page ID</th><th>Page URL (Alias)</th><th>Template Priority</th></tr>\r\n{Table:contentpage_table_pages}\r\n</table>\r\n</dd>\r\n<dt><b>Named Content Pages</b> - <a href=\"{url_prefix}admin/modules/ContentPage/?config=add&page=named\">Add new</a></dt>\r\n<dd>\r\n<table class=\"admin-contentpage\">\r\n<tr><th>Named ID</th><th>Page URL (Alias)</th><th>Content Page Name</th><th>Visibility</th><th>Template Priority</th></tr>\r\n{Table:contentpage_table_namedpages}\r\n</table>\r\n</dd>\r\n<dt>Navigate to <a href=\"{url_prefix}admin/modules\">Modules</a></dt>\r\n</dl>'),
 (8,'contentpage-add-new-named',0x01,'<form name=\"add-new\" method=\"post\" action=\"{url_prefix}admin/modules/ContentPage/?config=add&page=named&action=save\">\r\n<input type=\"hidden\" name=\"save\" value=\"true\" />\r\n<table class=\"configpage-add-new\">\r\n<tr><td>Page URL (Alias):</td></tr>\r\n<tr><td><input type=\"text\" name=\"alias\" class=\"input-text\" /></td></tr>\r\n<tr><td>Page name:</td></tr>\r\n<tr><td><input type=\"text\" name=\"page-name\" class=\"input-text\" /></td></tr>\r\n<tr><td>Page Template:</td></tr>\r\n<tr><td><textarea name=\"template\" rows=\"10\" cols=\"50\"></textarea></td></tr>\r\n<tr><td><input type=\"submit\" name=\"submit\" value=\"Save\" class=\"input-submit\" /></td></tr>\r\n</table>\r\n</form>');
INSERT INTO `sqd_templates` (`template_id`,`template_alias`,`system_template`,`template`) VALUES 
 (10,'form-default',0x01,'<dl class=\"admin-dlist\">\r\n<dt><b>Forms</b> - <a href=\"{url_prefix}admin/modules/Form/?config=add\">Add new</a></dt>\r\n<dd>\r\n<table class=\"admin-contentpage\">\r\n<tr><th>Form ID</th><th>Form Name</th><th>Form Method</th><th>Form Type</th><th>Form Submit Class</th><th>Form Action</th></tr>\r\n{Table:form-table}\r\n</table>\r\n</dd>\r\n<dt>Navigate to <a href=\"{url_prefix}admin/modules\">Modules</a></dt>\r\n</dl>'),
 (11,'11',0x00,'<table>\r\n[row]\r\n<tr>\r\n[cell]<td>{cell}</td>[/cell]\r\n</tr>\r\n[/row]\r\n</table>'),
 (12,'contentpage-save-success',0x01,'<table>\r\n<tr><td>Content Page saved successfully</td></tr>\r\n<tr><td><a href=\"{url_prefix}admin/modules/ContentPage/\">Back</a> to Content Page Administration</td></tr>\r\n</table>'),
 (13,'13',0x00,'[row]\r\n<ul>\r\n[cell] \r\n<li> {cell} \r\n</li>\r\n[/cell]\r\n</ul>\r\n\r\n[/row]'),
 (14,'14',0x00,'<ul>\r\n[row]<li>[cell]<a href=\"{url_prefix}admin/modules/{cell}\">{cell}</a>[/cell]</li>[/row]\r\n</ul>'),
 (15,'contentpage-tables',0x01,'[row]<tr>[cell]<td>{cell}</td>[/cell]</tr>[/row]');
INSERT INTO `sqd_templates` (`template_id`,`template_alias`,`system_template`,`template`) VALUES 
 (16,'contentpage-add-new',0x01,'<form name=\"add-new\" method=\"post\" action=\"{url_prefix}admin/modules/ContentPage/?config=add&page=page&action=save\">\r\n<input type=\"hidden\" name=\"save\" value=\"true\" />\r\n<input type=\"hidden\" name=\"page-name\" value=\"\" />\r\n<table class=\"configpage-add-new\">\r\n<tr><td>Page URL (Alias):</td></tr>\r\n<tr><td><input type=\"text\" name=\"alias\" class=\"input-text\" /></td></tr>\r\n<tr><td>Page Template:</td></tr>\r\n<tr><td><textarea name=\"template\" rows=\"10\" cols=\"50\"></textarea></td></tr>\r\n<tr><td><input type=\"submit\" name=\"submit\" value=\"Save\" class=\"input-submit\" /></td></tr>\r\n</table>\r\n</form>'),
 (17,'content-default',0x01,'<dl class=\"admin-dlist\">\r\n<dt><b>Contents</b> - <a href=\"{url_prefix}admin/modules/Content/?config=add\">Add new</a></dt>\r\n<dd>\r\n<table class=\"admin-contentpage\">\r\n<tr><th>Content ID</th><th>Content Alias</th><th>Content Type</th><th>Local Template Path</th></tr>\r\n{Table:content-table}\r\n</table>\r\n</dd>\r\n<dt>Navigate to <a href=\"{url_prefix}admin/modules\">Modules</a></dt>\r\n</dl>'),
 (18,'table-default',0x01,'<dl class=\"admin-dlist\">\r\n<dt><b>Tables</b> - <a href=\"{url_prefix}admin/modules/Table/?config=add\">Add new</a></dt>\r\n<dd>\r\n<table class=\"admin-contentpage\">\r\n<tr><th>Table ID</th><th>Table Alias</th><th>Table Type</th><th>Table Query</th></tr>\r\n{Table:table-table}\r\n</table>\r\n</dd>\r\n<dt>Navigate to <a href=\"{url_prefix}admin/modules\">Modules</a></dt>\r\n</dl>');
INSERT INTO `sqd_templates` (`template_id`,`template_alias`,`system_template`,`template`) VALUES 
 (19,'javascript-back',0x01,'<a href=\"javascript:history.go(-1);\">&lt;&lt; back</a>'),
 (20,'admin-error',0x01,'<table class=\"admin-error\">\r\n	<tr><td>{error-message}</td></tr>\r\n</table>\r\n<table class=\"admin-message\">\r\n	<tr><td>Navigate to <a href=\"{url_prefix}admin/modules/ContentPage\">Content Page</a></td></tr>\r\n</table>');
/*!40000 ALTER TABLE `sqd_templates` ENABLE KEYS */;


--
-- Definition of table `sqd_themes`
--

DROP TABLE IF EXISTS `sqd_themes`;
CREATE TABLE `sqd_themes` (
  `theme_id` int(11) unsigned NOT NULL auto_increment,
  `theme_name` varchar(20) NOT NULL,
  PRIMARY KEY  (`theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sqd_themes`
--

/*!40000 ALTER TABLE `sqd_themes` DISABLE KEYS */;
INSERT INTO `sqd_themes` (`theme_id`,`theme_name`) VALUES 
 (1,'default'),
 (2,'admin');
/*!40000 ALTER TABLE `sqd_themes` ENABLE KEYS */;


--
-- Definition of procedure `sqd_addNamedContentPage`
--

DROP PROCEDURE IF EXISTS `sqd_addNamedContentPage`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`sqdAdmin`@`%` PROCEDURE `sqd_addNamedContentPage`(IN _alias VARCHAR(40), IN _name VARCHAR(40), IN _template TEXT, OUT _RETURNCODE TINYINT)
BEGIN



DECLARE intTMP INT DEFAULT 0;

DECLARE intPageID INT DEFAULT 0;
DECLARE intNameID INT DEFAULT 0;
DECLARE intTPLID INT DEFAULT 0;

DECLARE EXIT HANDLER FOR NOT FOUND ROLLBACK;
DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
DECLARE EXIT HANDLER FOR SQLWARNING ROLLBACK;

SET `_RETURNCODE` = 1;

SET intTMP = (SELECT c.`page_id` FROM `sqd_contentpages` c 
				WHERE c.`page_alias` = `_alias`);

IF intTMP > 0 THEN
	SET `_RETURNCODE` = 10;
ELSE
	SET intTMP = (SELECT n.`name_id` FROM `sqd_contentpage_names` n 
    				WHERE n.`name` = `_name`);
    IF intTMP > 0 THEN
    	SET `_RETURNCODE` = 20;
    ELSE
    	START TRANSACTION;
        
    	IF NOT `_alias` = '' THEN
        	INSERT INTO `sqd_contentpages`(`page_alias`) VALUES(`_alias`);
            SET intPageID = LAST_INSERT_ID();
        ELSE
        	INSERT INTO `sqd_contentpages`(`page_alias`) 
            	VALUES(TIMESTAMP(CURDATE(), CURTIME()));
                
            SET intPageID = LAST_INSERT_ID();
            
            UPDATE `sqd_contentpages` 
            	SET `sqd_contentpages`.`page_alias` = intPageID
                WHERE `sqd_contentpages`.`page_id` = intPageID;
        END IF;
        
        IF NOT `_name` = '' THEN
        	INSERT INTO `sqd_contentpage_names`(`name`) VALUES(`_name`);
            SET intNameID = LAST_INSERT_ID();
        END IF;
        
        INSERT INTO `sqd_templates`(`template`) VALUES(`_template`);
        SET intTPLID = LAST_INSERT_ID();
        
        IF intNameID > 0 THEN
        	INSERT INTO `sqd_contentpage_visibility`(`page_id`, `name_id`)
            	VALUES (intPageID, intNameID);
            INSERT INTO `sqd_contentpage_name_templates`(`page_id`, `name_id`, `template_id`) 
            	VALUES(intPageID, intNameID, intTPLID);
        ELSE
        	INSERT INTO `sqd_contentpage_templates`(`page_id`, `template_id`)
            	VALUES(intPageID, intTPLID);
        END IF;
        
        SET `_RETURNCODE` = 0;
        
        COMMIT;
    END IF;
END IF;

END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of procedure `sqd_amendNamedContentPage`
--

DROP PROCEDURE IF EXISTS `sqd_amendNamedContentPage`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`sqdAdmin`@`%` PROCEDURE `sqd_amendNamedContentPage`(IN _alias VARCHAR(40), IN _name VARCHAR(40), IN _template TEXT, OUT _RETURNCODE TINYINT)
BEGIN



DECLARE intTMP INT DEFAULT 0;

DECLARE intPageID INT DEFAULT 0;
DECLARE intNameID INT DEFAULT 0;
DECLARE intTPLID INT DEFAULT 0;

DECLARE EXIT HANDLER FOR NOT FOUND ROLLBACK;
DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
DECLARE EXIT HANDLER FOR SQLWARNING ROLLBACK;

SET `_RETURNCODE` = 1;

SET intTMP = (SELECT c.`page_id` FROM `sqd_contentpages` c 
				WHERE c.`page_alias` = `_alias`);

IF intTMP > 0 THEN
	SET intPageID = intTMP;
END IF;

SET intTMP = (SELECT n.`name_id` FROM `sqd_contentpage_names` n 
                WHERE n.`name` = `_name`);
IF intTMP > 0 THEN
    SET `_RETURNCODE` = 20;
ELSE
    START TRANSACTION;
        
    IF NOT `_alias` = '' THEN
    	IF NOT intPageID > 0 THEN
        	INSERT INTO `sqd_contentpages`(`page_alias`) VALUES(`_alias`);
        	SET intPageID = LAST_INSERT_ID();
        END IF;
    ELSE
        INSERT INTO `sqd_contentpages`(`page_alias`) 
            VALUES(TIMESTAMP(CURDATE(), CURTIME()));
                
        SET intPageID = LAST_INSERT_ID();
            
        UPDATE `sqd_contentpages` 
            SET `sqd_contentpages`.`page_alias` = intPageID
            WHERE `sqd_contentpages`.`page_id` = intPageID;
    END IF;
        
    IF NOT `_name` = '' THEN
        INSERT INTO `sqd_contentpage_names`(`name`) VALUES(`_name`);
        SET intNameID = LAST_INSERT_ID();
    END IF;
        
    INSERT INTO `sqd_templates`(`template`) VALUES(`_template`);
    SET intTPLID = LAST_INSERT_ID();
        
    IF intNameID > 0 THEN
        INSERT INTO `sqd_contentpage_visibility`(`page_id`, `name_id`)
            VALUES (intPageID, intNameID);
        INSERT INTO `sqd_contentpage_name_templates`(`page_id`, `name_id`, `template_id`) 
            VALUES(intPageID, intNameID, intTPLID);
    ELSE
        INSERT INTO `sqd_contentpage_templates`(`page_id`, `template_id`)
            VALUES(intPageID, intTPLID);
    END IF;
        
    SET `_RETURNCODE` = 0;
        
    COMMIT;
END IF;


END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of procedure `sqd_getContent`
--

DROP PROCEDURE IF EXISTS `sqd_getContent`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`sqdAdmin`@`%` PROCEDURE `sqd_getContent`(IN alias VARCHAR(10))
BEGIN
  DECLARE done BOOLEAN DEFAULT FALSE;
  DECLARE txtResult TEXT;
  DECLARE intLocation TINYINT;
  
  DECLARE c_id INT;
  DECLARE c_type TINYINT;
  DECLARE cur_CType CURSOR FOR
	SELECT 
    	c.`content_id`, c.`content_type`
	FROM 
    	`sqd_contents` c 
    WHERE c.`content_alias` = alias;
  
  DECLARE  CONTINUE HANDLER FOR SQLSTATE '02000' SET done = TRUE;
  OPEN cur_CType;
  cur_loop: LOOP
  	FETCH cur_CType INTO c_id, c_type;
    IF done THEN LEAVE cur_loop; END IF;
    CASE (c_type MOD 10)
    WHEN 1 THEN
          SET txtResult = (SELECT p.`local_path` 
                  FROM `sqd_content_local_paths` p
                  WHERE p.`content_id` = c_id);
          SET intLocation = c_type;
    WHEN 2 THEN
          SET txtResult = (SELECT t.`template`
                  FROM `sqd_content_templates` c INNER JOIN `sqd_templates` t
                      ON c.`template_id` = t.`template_id`
                  WHERE c.`content_id` = c_id);
          SET intLocation = c_type;
    END CASE;
  END LOOP cur_loop;
  CLOSE cur_CType;

  SELECT intLocation AS content_location, txtResult AS content;
  
END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of procedure `sqd_getContentPageTemplate`
--

DROP PROCEDURE IF EXISTS `sqd_getContentPageTemplate`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`sqdAdmin`@`%` PROCEDURE `sqd_getContentPageTemplate`(IN page_alias VARCHAR(40), IN name VARCHAR(40))
BEGIN
  DECLARE done BOOLEAN DEFAULT FALSE;

  DECLARE p_id INT;
  DECLARE n_id INT;
  DECLARE vis TINYINT DEFAULT 1;
  DECLARE tpl_prio TINYINT DEFAULT 0;
  DECLARE intTPL INT;
  
  DECLARE cur_visibility CURSOR FOR
	SELECT
    	c.`page_id`, n.`name_id`, v.`visibility`
	FROM 
    	`sqd_contentpages` c, `sqd_contentpage_names` n,
        `sqd_contentpage_visibility` v 
  	WHERE
    	c.`page_alias` = page_alias AND
      	n.`name` = `name` AND
      	v.`page_id` = c.`page_id` AND v.`name_id` = n.`name_id`;

  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = TRUE;

  OPEN cur_visibility;
  	FETCH cur_visibility INTO p_id, n_id, vis;
    IF NOT done THEN
    	SET tpl_prio = (SELECT c.`template_priority`
        				FROM `sqd_contentpages` c
        				WHERE c.`page_id` = p_id);
        SET intTPL = (SELECT t.`template_id`
        				FROM `sqd_contentpage_name_templates` nt INNER JOIN
                        	`sqd_templates` t ON
                            nt.`template_id` = t.`template_id`
                        WHERE nt.`page_id` = p_id AND nt.`name_id` = n_id);
  ELSE
    	SET tpl_prio = (SELECT c.`template_priority`
        				FROM `sqd_contentpages` c
        				WHERE c.`page_alias` = page_alias);
        SET intTPL = (SELECT t.`template_id`
        				FROM `sqd_contentpage_templates` ct INNER JOIN
                        	`sqd_contentpages` c ON 
                            ct.`page_id` = c.`page_id` INNER JOIN
                        	`sqd_templates` t ON
                            ct.`template_id` = t.`template_id`
                        WHERE c.`page_alias` = page_alias);  
    END IF;
  CLOSE cur_visibility;

SELECT tpl_prio AS template_priority, vis AS content_visibility,
    intTPL AS template_id;

END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of procedure `sqd_getSiteTheme`
--

DROP PROCEDURE IF EXISTS `sqd_getSiteTheme`;

DELIMITER $$

/*!50003 SET @TEMP_SQL_MODE=@@SQL_MODE, SQL_MODE='' */ $$
CREATE DEFINER=`mysql_fake_admin`@`%` PROCEDURE `sqd_getSiteTheme`(IN site VARCHAR(10))
BEGIN
SELECT
	t.`theme_name`
FROM `sqd_config` c INNER JOIN `sqd_themes` t ON
	c.`theme` = t.`theme_id`
WHERE c.`site` = site;
END $$
/*!50003 SET SESSION SQL_MODE=@TEMP_SQL_MODE */  $$

DELIMITER ;

--
-- Definition of view `sqd_view_contentpage_names`
--

DROP TABLE IF EXISTS `sqd_view_contentpage_names`;
DROP VIEW IF EXISTS `sqd_view_contentpage_names`;
CREATE ALGORITHM=UNDEFINED DEFINER=`sqdAdmin`@`%` SQL SECURITY DEFINER VIEW `sqd_view_contentpage_names` AS select distinct `c`.`page_id` AS `page_id`,`c`.`page_alias` AS `page_alias`,`c`.`template_priority` AS `template_priority`,`n`.`name_id` AS `name_id`,`n`.`name` AS `name`,`v`.`visibility` AS `visibility`,`t`.`template_id` AS `template_id`,`t`.`template` AS `template` from ((((`sqd_contentpages` `c` join `sqd_contentpage_name_templates` `nt` on((`c`.`page_id` = `nt`.`page_id`))) join `sqd_contentpage_names` `n` on((`n`.`name_id` = `nt`.`name_id`))) join `sqd_templates` `t` on((`t`.`template_id` = `nt`.`template_id`))) join `sqd_contentpage_visibility` `v` on((`c`.`page_id` = `v`.`page_id`)));

--
-- Definition of view `sqd_view_contentpages`
--

DROP TABLE IF EXISTS `sqd_view_contentpages`;
DROP VIEW IF EXISTS `sqd_view_contentpages`;
CREATE ALGORITHM=UNDEFINED DEFINER=`sqdAdmin`@`%` SQL SECURITY DEFINER VIEW `sqd_view_contentpages` AS select `c`.`page_id` AS `page_id`,`c`.`page_alias` AS `page_alias`,`c`.`template_priority` AS `template_priority`,`t`.`template_id` AS `template_id`,`t`.`template` AS `template` from ((`sqd_contentpages` `c` join `sqd_contentpage_templates` `ct` on((`c`.`page_id` = `ct`.`page_id`))) join `sqd_templates` `t` on((`t`.`template_id` = `ct`.`template_id`)));

--
-- Definition of view `sqd_view_contents`
--

DROP TABLE IF EXISTS `sqd_view_contents`;
DROP VIEW IF EXISTS `sqd_view_contents`;
CREATE ALGORITHM=UNDEFINED DEFINER=`sqdAdmin`@`%` SQL SECURITY DEFINER VIEW `sqd_view_contents` AS select `c`.`content_id` AS `content_id`,`c`.`content_alias` AS `content_alias`,`c`.`content_type` AS `content_type`,`t`.`template` AS `template`,`p`.`local_path` AS `local_path` from (((`sqd_contents` `c` join `sqd_content_templates` `ct` on((`c`.`content_id` = `ct`.`content_id`))) join `sqd_templates` `t` on((`ct`.`template_id` = `t`.`template_id`))) left join `sqd_content_local_paths` `p` on((`c`.`content_id` = `p`.`content_id`)));

--
-- Definition of view `sqd_view_forms`
--

DROP TABLE IF EXISTS `sqd_view_forms`;
DROP VIEW IF EXISTS `sqd_view_forms`;
CREATE ALGORITHM=UNDEFINED DEFINER=`sqdAdmin`@`%` SQL SECURITY DEFINER VIEW `sqd_view_forms` AS select `f`.`form_id` AS `form_id`,`f`.`form_name` AS `form_name`,`f`.`form_method` AS `form_method`,`f`.`form_action` AS `form_action`,`f`.`form_type` AS `form_type`,`f`.`form_submit_class` AS `form_submit_class`,`t`.`template` AS `template` from ((`sqd_forms` `f` join `sqd_form_templates` `Ft` on((`f`.`form_id` = `ft`.`form_id`))) join `sqd_templates` `t` on((`ft`.`template_id` = `t`.`template_id`)));

--
-- Definition of view `sqd_view_tables`
--

DROP TABLE IF EXISTS `sqd_view_tables`;
DROP VIEW IF EXISTS `sqd_view_tables`;
CREATE ALGORITHM=UNDEFINED DEFINER=`sqdAdmin`@`%` SQL SECURITY DEFINER VIEW `sqd_view_tables` AS select `t`.`table_id` AS `table_id`,`t`.`table_alias` AS `table_alias`,`t`.`table_type` AS `table_type`,`t`.`table_query` AS `table_query`,`te`.`template` AS `template` from ((`sqd_tables` `t` join `sqd_table_templates` `tt` on((`t`.`table_id` = `tt`.`table_id`))) join `sqd_templates` `te` on((`tt`.`template_id` = `te`.`template_id`)));



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
