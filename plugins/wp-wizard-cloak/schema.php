<?php
/**
 * Plugin database schema
 * WARNING: 
 * 	dbDelta() doesn't like empty lines in schema string, so don't put them there;
 *  WPDB doesn't like NULL values so better not to have them in the tables;
 */

/**
 * The database character collate.
 * @var string
 * @global string
 * @name $charset_collate
 */
$charset_collate = '';

// Declare these as global in case schema.php is included from a function.
global $wpdb, $plugin_queries;

if ( ! empty($wpdb->charset))
	$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
if ( ! empty($wpdb->collate))
	$charset_collate .= " COLLATE $wpdb->collate";
	
$table_prefix = PMLC_Plugin::getInstance()->getTablePrefix();

$plugin_queries = <<<SCHEMA
CREATE TABLE {$table_prefix}links (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(200) NOT NULL DEFAULT '',
	slug VARCHAR(200) NOT NULL DEFAULT '',
	preset VARCHAR(200) NOT NULL DEFAULT '',
	redirect_type ENUM('301','302','307','REFERER_MASK','META_REFRESH','FRAME','JAVASCRIPT') NOT NULL DEFAULT '301',
	destination_type ENUM('ONE_SET','BY_COUNTRY','BY_RULE') NOT NULL DEFAULT 'ONE_SET',
	expire_on DATE NOT NULL DEFAULT '0000-00-00',
	forward_url_params TINYINT(1) NOT NULL DEFAULT 1,
	no_global_tracking_code TINYINT(1) NOT NULL DEFAULT 0,
	header_tracking_code TEXT,
	footer_tracking_code TEXT,
	created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_on TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	is_trashed TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY  (id),
	KEY slug (slug),
	KEY name (name)
) $charset_collate;
CREATE TABLE {$table_prefix}automatches (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	link_id BIGINT(20) UNSIGNED NOT NULL,
	url VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY  (id),
	KEY link_id (link_id)
) $charset_collate;
CREATE TABLE {$table_prefix}rules (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	link_id BIGINT(20) UNSIGNED NOT NULL,
	type ENUM('ONE_SET','BY_COUNTRY','BY_RULE','EXPIRED','REFERER_MASK') NOT NULL DEFAULT 'ONE_SET',
	rule VARCHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY  (id),
	KEY link_id (link_id)
) $charset_collate;
CREATE TABLE {$table_prefix}destinations (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	rule_id BIGINT(20) UNSIGNED NOT NULL,
	url VARCHAR(255) NOT NULL DEFAULT '',
	weight FLOAT UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY  (id),
	KEY rule_id (rule_id)
) $charset_collate;
CREATE TABLE {$table_prefix}geoipcountry (
	begin_ip VARCHAR(15) NOT NULL DEFAULT '0.0.0.0',
	end_ip VARCHAR(15) NOT NULL DEFAULT '0.0.0.0',
	begin_num INT(10) UNSIGNED NOT NULL DEFAULT 0,
	end_num INT(10) UNSIGNED NOT NULL DEFAULT 0,
	country CHAR(2) NOT NULL DEFAULT '',
	name VARCHAR(50) NOT NULL DEFAULT '',
	PRIMARY KEY  (begin_num,end_num),
	KEY end_num (end_num)
) $charset_collate;
CREATE TABLE {$table_prefix}stats (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	link_id BIGINT(20) UNSIGNED NOT NULL,
	sub_id VARCHAR(32) NOT NULL DEFAULT '',
	registered_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	rule_type ENUM('ONE_SET','BY_COUNTRY','BY_RULE','EXPIRED','REFERER_MASK') NOT NULL DEFAULT 'ONE_SET',
	destination_url  VARCHAR(255) NOT NULL DEFAULT '',
	ip VARCHAR(15) NOT NULL DEFAULT '0.0.0.0',
	ip_num INT(10) UNSIGNED NOT NULL DEFAULT 0,
	country CHAR(2) NOT NULL DEFAULT '',
	host VARCHAR(200) NOT NULL DEFAULT '',
	user_agent TEXT,
	accept_language VARCHAR(50) NOT NULL DEFAULT '',
	referer VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY  (id),
	KEY registered_on (registered_on),
	KEY ip_num (ip_num)
) $charset_collate;
CREATE TABLE {$table_prefix}keywords (
	id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	keywords VARCHAR(255) NOT NULL DEFAULT '',
	replace_limit INT UNSIGNED NOT NULL DEFAULT 0,
	url VARCHAR(255) NOT NULL DEFAULT '',
	match_case TINYINT(1) NOT NULL DEFAULT 0,
	post_id_param VARCHAR(100) NOT NULL DEFAULT '',
	rel_nofollow TINYINT(1) NOT NULL DEFAULT 0,
	target_blank TINYINT(1) NOT NULL DEFAULT 0,
	is_trashed TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY  (id),
	KEY keywords (keywords)
) $charset_collate;
SCHEMA;
