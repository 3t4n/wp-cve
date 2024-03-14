CREATE TABLE IF NOT EXISTS `WP_PREFIXfirebox_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visitorid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` int(11) NOT NULL,
  `box` bigint(20) unsigned NOT NULL,
  `page` text NOT NULL,
  `country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `visitorid` (`visitorid`),
  KEY `box` (`box`),
  KEY `sessionid` (`sessionid`),
  KEY `date` (`date`),
  KEY `box_date` (`box`, `date`),
  KEY `device_date` (`device`, `date`),
  KEY `country_date` (`country`, `date`),
  KEY `idx_box_id` (`box`, `id`)
) WP_COLLATE;
-----
CREATE TABLE IF NOT EXISTS `WP_PREFIXfirebox_logs_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` bigint(20) unsigned NOT NULL,
  `event` varchar(50) NOT NULL DEFAULT 'open',
  `event_source` varchar(200) NOT NULL,
  `event_label` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `log_id` (`log_id`),
  KEY `date` (`date`)
) WP_COLLATE;
-----
CREATE TABLE IF NOT EXISTS `WP_PREFIXfirebox_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` varchar(64) NOT NULL,
  `visitor_id` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) WP_COLLATE;
-----
CREATE TABLE IF NOT EXISTS `WP_PREFIXfirebox_submission_meta` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `submission_id` int(10) NOT NULL,
  `meta_type` varchar(100) NOT NULL,
  `meta_key` varchar(100) NOT NULL,
  `meta_value` mediumtext NOT NULL,
  `params` mediumtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_meta_key` (`meta_key`)
) WP_COLLATE;