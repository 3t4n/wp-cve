<?php
namespace WP_Reactions\Lite;

class Activation {

	public function __construct() {
		global $wpdb;
		// if no wpj options then create defaults
		if ( ! get_option( WPRA_LITE_OPTIONS ) ) {
			update_option( WPRA_LITE_OPTIONS, json_encode(Config::$default_options));
		}

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS " . Config::$tbl_reacted_users . " ( 
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                bind_id varchar(100) NOT NULL,
                react_id varchar(30) NOT NULL,
                reacted_date DATETIME NOT NULL,
                source varchar(50) NOT NULL,
                emoji_id SMALLINT NOT NULL,
                user_id BIGINT(20) NOT NULL,
                sgc_id BIGINT(20) NOT NULL,
                PRIMARY KEY (id)
        ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	static function start() {
		return new self();
	}
}
