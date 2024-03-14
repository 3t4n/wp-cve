CREATE TABLE IF NOT EXISTS `{WPDB_PREFIX}tblight_payment_plg_cash` (
	`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`order_id` int(1) UNSIGNED DEFAULT NULL,
	`order_number` char(64) DEFAULT NULL,
	`paymentmethod_id` mediumint(1) UNSIGNED DEFAULT NULL,
	`payment_name` varchar(5000) DEFAULT NULL,
	`payment_order_total` decimal(15,5) NOT NULL DEFAULT 0.00000,
	`payment_currency` char(3) DEFAULT NULL,
	`cost_per_transaction` decimal(10,2) DEFAULT NULL,
	`cost_percent_total` decimal(10,2) DEFAULT NULL,
	`created_on` datetime NOT NULL,
	`created_by` int(11) NOT NULL DEFAULT 0,
	`modified_on` datetime NOT NULL,
	`modified_by` int(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Payment Cash Table';