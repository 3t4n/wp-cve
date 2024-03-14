CREATE TABLE IF NOT EXISTS `{WPDB_PREFIX}tblight_configs` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `alias` varchar(100) NOT NULL,
    `text` text NOT NULL,
    `external` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Defines if a config section is external or not',
    `external_url` varchar(255) NOT NULL,
    `created` datetime NOT NULL,
    `created_by` int(10) UNSIGNED NOT NULL,
    `modified` datetime NOT NULL,
    `modified_by` int(10) UNSIGNED NOT NULL,
    `state` tinyint(3) NOT NULL DEFAULT 1,
    `ordering` int(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;