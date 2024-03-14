CREATE TABLE IF NOT EXISTS `{WPDB_PREFIX}tblight_order_car_rel` (
	`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`order_id` int(10) NOT NULL,
	`vehicle_id` int(10) NOT NULL,
	`journey_type` enum('outbound','return') NOT NULL DEFAULT 'outbound',
	`booking_time_start` int(11) NOT NULL COMMENT 'seconds',
	`booking_time_end` int(11) NOT NULL COMMENT 'seconds',
	`created_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;