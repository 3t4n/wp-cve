CREATE TABLE IF NOT EXISTS `{WPDB_PREFIX}tblight_countries` (
    `country_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
    `country_name` char(64) DEFAULT NULL,
    `country_3_code` char(3) DEFAULT NULL,
    `country_2_code` char(2) DEFAULT NULL,
    `calling_code` varchar(50) NOT NULL,
    `currency_code` char(3) DEFAULT NULL,
    `published` tinyint(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Country records';