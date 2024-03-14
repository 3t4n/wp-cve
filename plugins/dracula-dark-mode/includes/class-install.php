<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 */
class Dracula_Install {

	/**
	 * Plugin activation stuffs
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		self::create_default_data();
		self::create_tables();
	}


	public static function deactivate() {
	}

	public static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$tables = [

			// Buttons table
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dracula_toggles(
	            id bigint(20) NOT NULL AUTO_INCREMENT,
				config longtext NULL,
				title text NULL,
				created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				updated_at TIMESTAMP NULL,
				PRIMARY KEY  (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",


			// Analytics table
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dracula_analytics(
			    id bigint(20) NOT NULL AUTO_INCREMENT,
			    unique_id VARCHAR(32) NOT NULL UNIQUE,
			    user_key VARCHAR(32) NOT NULL UNIQUE,
			    activation bigint(20) NOT NULL DEFAULT 0,
			    deactivation bigint(20) NOT NULL DEFAULT 0,
			    view bigint(20) NOT NULL DEFAULT 0,
			    dark_view bigint(20) NOT NULL DEFAULT 0,
			    visitor bigint(20) NOT NULL DEFAULT 0,
			    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			    PRIMARY KEY (id),
			    UNIQUE KEY (unique_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			// Create Feedbacks table
			"CREATE TABLE `{$wpdb->prefix}dracula_feedbacks` (
			    `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
			    `message` TEXT,                        
			    `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
			    PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"

		];

		foreach ( $tables as $table ) {
			dbDelta( $table );
		}

	}

	/**
	 * Create plugin settings default data
	 *
	 * @since 1.0.0
	 */
	private static function create_default_data() {

		$version      = get_option( 'dracula_version', '0' );
		$install_time = get_option( 'dracula_install_time', '' );

		if ( empty( $version ) ) {
			update_option( 'dracula_version', DRACULA_VERSION );
		}

		if ( empty( $install_time ) ) {
			update_option( 'dracula_install_time', time() );
		}

		set_transient( 'dracula_rating_notice_interval', 'off', 10 * DAY_IN_SECONDS );


	}

}