<?php

class TBLight_Installer {

	/**
	 * Run the installer
	 *
	 * @return void
	 */
	public function run() {
		 $this->add_version();
		$this->create_tables();
	}

	/**
	 * Add version in DB
	 *
	 * @return void
	 */
	public function add_version() {
		 $installed = get_option( 'tblight_installed_at' );

		if ( ! $installed ) {
			update_option( 'tblight_installed_at', time() );
		}

		update_option( 'tblight_plugin_version', TBLIGHT_PLUGIN_VERSION );
	}

	/**
	 * Create necessary database tables
	 *
	 * @return void
	 */
	public function create_tables() {
		global $wpdb;

		$installed_db_ver = (int) get_option( 'tblight_db_version' );

		if ( $installed_db_ver == 0 ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/cars.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/configs.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/countries.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/currencies.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/orders.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/order_car.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/paymentmethods.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/payment_plg_cash.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/payment_plg_paypal.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			// now lets import data
			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/configs_data.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/countries_data.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . 'admin/sql/install/currencies_data.sql' );
			$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
			dbDelta( $sql );

			add_option( 'tblight_db_version', TBLIGHT_DB_VERSION );
		} else // update
		{
			if ( $installed_db_ver != TBLIGHT_DB_VERSION ) {
				$installed_db_ver++;

				for ( $folder_counter = $installed_db_ver; $folder_counter <= TBLIGHT_DB_VERSION; $folder_counter++ ) {
					$update_sql_dir = TBLIGHT_PLUGIN_PATH . "admin/sql/updates/$folder_counter";

					if ( is_dir( $update_sql_dir ) ) {
						if ( $dh = opendir( $update_sql_dir ) ) {
							while ( ( $file = readdir( $dh ) ) !== false ) {
								if ( $file != '.' && $file != '..' ) {
									$sql = file_get_contents( TBLIGHT_PLUGIN_PATH . "admin/sql/updates/$folder_counter/$file" );
									$sql = str_replace( '{WPDB_PREFIX}', $wpdb->prefix, $sql );
									dbDelta( $sql );
								}
							}
							closedir( $dh );
						}
					}
				}

				update_option( 'tblight_db_version', TBLIGHT_DB_VERSION );
			}
		}
	}
}
