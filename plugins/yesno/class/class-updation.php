<?php
/**
 *	Plugin updation
 */

class YESNO_Updation {
	/**
	 *	Load
	 */
	public static function load() {
		add_action('plugins_loaded', array('YESNO_Updation', 'db_update') );
		add_action('init', array('YESNO_Updation', 'plugin_update') );
	}

	/** 
	 *	Plugin update
	 */
	public static function plugin_update() {
		global $wpdb, $yesno;

		if ( ! is_admin() ) {
			return;
		}
		$options = get_option( YESNO::PLUGIN_ID );
		if ( version_compare( $options['version']['plugin'], YESNO::PLUGIN_VERSION, '<') ) {
			$options['version']['plugin'] = YESNO::PLUGIN_VERSION;
			update_option( YESNO::PLUGIN_ID, $options );
		}
		return;
	}

	/** 
	 *	DB update
	 */
	public static function db_update() {
		global $wpdb, $yesno;

		if ( ! is_admin() ) {
			return;
		}
		$options = get_option( YESNO::PLUGIN_ID );
		if ( version_compare( $options['version']['db'], YESNO::DB_VERSION, '<') ) {
			$options['version']['db'] = YESNO::DB_VERSION;
			update_option( YESNO::PLUGIN_ID, $options );
		}
		return;
	}

}
?>
