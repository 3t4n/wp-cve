CREATE TABLE IF NOT EXISTS `{WPDB_PREFIX}tblight_paymentmethods` (
	`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`alias` varchar(255) NOT NULL,
	`text` text NOT NULL,
	`payment_element` char(50) NOT NULL DEFAULT '',
	`payment_params` TEXT NOT NULL,
	`created` datetime NOT NULL,
	`created_by` int(10) UNSIGNED NOT NULL,
	`modified` datetime NOT NULL,
	`modified_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`state` tinyint(4) NOT NULL,
	`ordering` int(10) UNSIGNED NOT NULL,
	`language` char(7) NOT NULL DEFAULT '*' COMMENT 'The language code for the method',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;