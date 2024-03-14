<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */
class GeotMaxmindActivator {

	/**
	 * On plugin activation check php version and download database
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( version_compare( PHP_VERSION, '5.6' ) < 0 ) {

			deactivate_plugins( GEOT_PLUGIN_FILE );
			wp_die(
				'<p>' . __( 'Hey, we\'ve noticed that you\'re running an outdated version of PHP. PHP is the programming language that WordPress and this plugin are built on. The version that is currently used for your site is no longer supported. Newer versions of PHP are both faster and more secure. In fact, your version of PHP no longer receives security updates.' ) . '</p>' .
				'<p>' . __( 'Geo Maxmind requires at least PHP 5.6 and you are running PHP ' ) . PHP_VERSION . '</p>'
			);
		}

		self::maybe_register_cron();
		GeotMaxmind::maybe_download_maxmind();

		do_action( 'geotmax/activated' );
	}


	/**
	 * Register Cron
	 * @return
	 */
	protected static function maybe_register_cron() {

		if ( ! wp_next_scheduled( 'geot_maxmind_cron' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'geot_every_month', 'geot_maxmind_cron' );
		}
	}

}
