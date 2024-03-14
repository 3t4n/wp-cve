<?php
/**
 *	Plugin updation
 */

class ATTMGR_Updation {
	/**
	 *	Load
	 */
	public static function load() {
		add_action( 'plugins_loaded', array( 'ATTMGR_Updation', 'db_update' ) );
		add_action( 'plugins_loaded', array( 'ATTMGR_Updation', 'plugin_update' ) );
	}

	/** 
	 *	Plugin update
	 */
	public static function plugin_update() {
		global $wpdb, $attmgr;

		if ( ! is_admin() ) {
			return;
		}
		$installed_version = get_option( ATTMGR::PLUGIN_ID.'_version' );
		$new_version = $installed_version;

		if ( $installed_version['plugin'] < ATTMGR::PLUGIN_VERSION ) {
			// Add option 'Date/Time format' (ver 0.4.5 -> 0.5.0)
			if($installed_version['plugin'] < '0.5.0') {
				$options_key = ATTMGR::PLUGIN_ID;
				$old_option = get_option( $options_key );
				$new_option = ATTMGR::default_option();
				$update_keys = array(
					'format_year_month',
					'format_month_day',
					'format_time',
					'format_time_editor'
				);
				foreach( $new_option['general'] as $key => $value ) {
					if ( in_array( $key, $update_keys ) ) {
						$old_option['general'][$key] = $value;
					}
				}
				update_option( $options_key, $old_option );
			}
			$new_version['plugin'] = ATTMGR::PLUGIN_VERSION;
			update_option( ATTMGR::PLUGIN_ID.'_version', $new_version );
		}
		return;
	}

	/** 
	 *	DB update
	 */
	public static function db_update() {
		global $wpdb, $attmgr;

		if ( ! is_admin() ) {
			return;
		}
		$installed_version = get_option( ATTMGR::PLUGIN_ID.'_version' );
		$new_version = $installed_version;

		if ( empty( $installed_version['db'] ) || $installed_version['db'] < ATTMGR::DB_VERSION ) {
			$new_version['db'] = ATTMGR::DB_VERSION;
			update_option( ATTMGR::PLUGIN_ID.'_version', $new_version );
		}
		return;
	}

}
?>
