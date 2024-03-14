<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */
class GeotMaxmindDeactivator {

	/**
	 * Remove cron
	 * @since    1.0.0
	 */
	public static function deactivate() {

		wp_clear_scheduled_hook( 'geot_maxmind_cron' );

		do_action( 'geotmax/deactivated' );
	}

}
