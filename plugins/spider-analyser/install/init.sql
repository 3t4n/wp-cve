
CREATE TABLE IF NOT EXISTS `wp_wb_spider` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `marker` varchar(64) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `skip` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `bot_type` varchar(32) DEFAULT NULL,
  `bot_url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `bot_type` (`bot_type`)
) ENGINE=InnoDB;

-- row split --

CREATE TABLE IF NOT EXISTS `wp_wb_spider_ip` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`,`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB;

-- row split --

CREATE TABLE IF NOT EXISTS `wp_wb_spider_log` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `spider` varchar(64) DEFAULT NULL,
  `visit_date` datetime NOT NULL,
  `code` varchar(32) DEFAULT NULL,
  `visit_ip` varchar(32) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  `url_md5` varchar(32) DEFAULT NULL,
  `url_type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spider` (`spider`),
  KEY `url_md5` (`url_md5`),
  KEY `url_type` (`url_type`)
) ENGINE=InnoDB;

-- row split --

CREATE TABLE IF NOT EXISTS `wp_wb_spider_post` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `url_in` int(11) NOT NULL DEFAULT '0',
  `url_out` int(11) NOT NULL DEFAULT '0',
  `url_md5` varchar(32) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `url_md5` (`url_md5`)
) ENGINE=InnoDB;

-- row split --

CREATE TABLE IF NOT EXISTS `wp_wb_spider_post_link` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `link_url_md5` varchar(32) NOT NULL,
  `link_post_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`link_url_md5`)
) ENGINE=InnoDB;

-- row split --

CREATE TABLE IF NOT EXISTS `wp_wb_spider_sum` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ymdh` int(10) UNSIGNED NOT NULL,
  `created` int(10) UNSIGNED NOT NULL,
  `spider` tinyint(3) UNSIGNED NOT NULL,
  `visit_times` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ymdh` (`ymdh`),
  KEY `spider` (`spider`)
) ENGINE=InnoDB;

-- row split --

CREATE TABLE IF NOT EXISTS `wp_wb_spider_visit` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `spider` tinyint(3) UNSIGNED NOT NULL,
  `ymdh` int(10) UNSIGNED NOT NULL,
  `created` int(10) UNSIGNED NOT NULL,
  `visit_times` int(10) UNSIGNED NOT NULL,
  `url_md5` varchar(32) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ymdh` (`ymdh`),
  KEY `spider` (`spider`),
  KEY `url_md5` (`url_md5`)
) ENGINE=InnoDB;




