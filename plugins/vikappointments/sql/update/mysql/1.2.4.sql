--
-- Table structure for table `#__vikappointments_media`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(256) NOT NULL,
  `alt` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `caption` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Table structure for table `#__vikappointments_lang_media`
--

CREATE TABLE IF NOT EXISTS `#__vikappointments_lang_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alt` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `caption` varchar(2048) DEFAULT NULL,
  `image` varchar(256) NOT NULL,
  `tag` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Alter for table `#__vikappointments_reservation`
--

ALTER TABLE `#__vikappointments_reservation`
ADD COLUMN `modifiedon` datetime DEFAULT NULL AFTER `createdby`;

--
-- Alter for table `#__vikappointments_custfields`
--

ALTER TABLE `#__vikappointments_custfields`
ADD COLUMN `readonly` tinyint(1) unsigned DEFAULT 0 COMMENT 'editable only once' AFTER `repeat`;
