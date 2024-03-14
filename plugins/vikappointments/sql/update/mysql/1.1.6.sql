CREATE TABLE IF NOT EXISTS `#__vikappointments_special_restrictions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `maxapp` int(4) DEFAULT 0 COMMENT 'max number of appointments per interval (0: unlimited)',
  `interval` varchar(32) COMMENT 'the interval identifier',
  `mode` tinyint(1) DEFAULT 1 COMMENT 'applies to current date (1) or check-in date (2)',
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `all` tinyint(1) DEFAULT 0 COMMENT '1 to consider all the services',
  `usergroups` varchar(32) DEFAULT '' COMMENT 'a list of accepted user groups (comma separated)',
  `createdon` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__vikappointments_ser_restr_assoc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_restriction` int(10) unsigned NOT NULL,
  `id_service` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `#__vikappointments_service`
ADD COLUMN `minrestr` int(8) DEFAULT -1 NOT NULL AFTER `interval`;

ALTER TABLE `#__vikappointments_cust_mail`
ADD COLUMN `published` tinyint(1) DEFAULT 1 AFTER `file`;