<?php
/**
 * @package talentlms-wordpress
 */
namespace TalentlmsIntegration;

use TalentlmsIntegration\Services\PluginService;

class Database implements PluginService
{

    public function register(): void
    {
        global $wpdb;

        define("TLMS_COURSES_TABLE", $wpdb->prefix . "talentlms_courses");
        define("TLMS_CATEGORIES_TABLE", $wpdb->prefix . "talentlms_categories");
        define("TLMS_PRODUCTS_TABLE", $wpdb->prefix . "talentlms_products");
        define("TLMS_PRODUCTS_CATEGORIES_TABLE", $wpdb->prefix . "talentlms_products_categories");
        define("WP_POSTS_TABLE", $wpdb->prefix . "posts");
    }

    public static function tlms_createDB(): void
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE " .TLMS_COURSES_TABLE. " (
		id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
		name varchar(150) NOT NULL,
		course_code varchar(20),
		category_id smallint(5) unsigned,
		description text,
		price varchar(150),
		status enum('active','inactive','archived') NOT NULL DEFAULT 'active',
		creation_date int(10) unsigned DEFAULT NULL,
        last_update_on int(10) unsigned DEFAULT NULL,
        hide_catalog tinyint(4) DEFAULT '0',
		shared tinyint(1) unsigned DEFAULT '0',
		shared_url varchar(255) DEFAULT NULL, 
		avatar varchar(255) DEFAULT NULL,
        big_avatar varchar(255) DEFAULT NULL,
		certification varchar(150),
		certification_duration varchar(150),
		PRIMARY KEY  (id)
		) $charset_collate;";

        $sql2 = "CREATE TABLE " . TLMS_CATEGORIES_TABLE . " (
		id mediumint(9) unsigned NOT NULL,
		name varchar(150) NOT NULL,
		price float DEFAULT '0',
		parent_id smallint(5) unsigned DEFAULT NULL,
		PRIMARY KEY  (id)
		) $charset_collate;";

        $sql3 = "CREATE TABLE ".TLMS_PRODUCTS_TABLE." (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			product_id mediumint(9) NOT NULL,
			course_id mediumint (9) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

        $sql4 = "CREATE TABLE " . TLMS_PRODUCTS_CATEGORIES_TABLE . " (
			id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
			tlms_categories_ID mediumint (9),
			woo_categories_ID mediumint (9),
			PRIMARY KEY  (id)
		) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta(array($sql1,$sql2,$sql3,$sql4));
    }
}
