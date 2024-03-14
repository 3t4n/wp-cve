CREATE TABLE IF NOT EXISTS `{WPDB_PREFIX}tblight_currencies` (
	`currency_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`currency_name` varchar(64) DEFAULT NULL,
	`currency_code` char(3) DEFAULT NULL,
	PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';