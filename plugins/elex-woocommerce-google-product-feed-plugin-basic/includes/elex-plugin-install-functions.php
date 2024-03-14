<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Gpf_Plugin_Install {

	public function __construct() {
		$this->elex_gpf_create_tables();
	}

	public function elex_gpf_create_tables() {
		global $wpdb;
		$search_query = 'SHOW TABLES LIKE %s';
		$charset_collate = $wpdb->get_charset_collate();
		$like = '%' . $wpdb->prefix . 'gpf_feeds%';
		if ( ! $wpdb->get_results( $wpdb->prepare( 'SHOW TABLES LIKE %s', $like ), ARRAY_N ) ) {
			$table_name = $wpdb->prefix . 'gpf_feeds';
			$sql_feeds = "CREATE TABLE $table_name ( `meta_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,`feed_id` BIGINT(20) UNSIGNED NOT NULL, `feed_meta_key` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL , `feed_meta_content` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`meta_id`) )$charset_collate;";
			// dbDelta($sql_feeds);
			$wpdb->query( ( $wpdb->prepare( '%1s', $sql_feeds ) ? stripslashes( $wpdb->prepare( '%1s', $sql_feeds ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A );
		}
	}
}
new Elex_Gpf_Plugin_Install();
