<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 *
 * @package    lwscache
 * @subpackage lwscache/includes
 *
 * @author     LWS
 */

/**
 * Class LWSCache_Activator
 */
class LWSCache_Activator {

	/**
	 * Create log directory. Add capability of LWSCache.
	 * Schedule event to check log file size daily.
	 *
	 * @since    1.0.0
	 *
	 * @global LWSCache_Admin $lws_cache_admin
	 */
	public static function activate() {

		global $lws_cache_admin;

		$path = $lws_cache_admin->functional_asset_path();

		if ( ! is_dir( $path ) ) {
			mkdir( $path );
		}

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$role = get_role( 'administrator' );

		if ( empty( $role ) ) {

			update_site_option(
				'rt_wp_lws_cache_init_check',
				__( 'Sorry, you need to be an administrator to use LWSCache', 'lwscache' )
			);

			return;

		}

		$role->add_cap( 'LWSCache | Config' );
		$role->add_cap( 'LWSCache | Purge cache' );

		wp_schedule_event( time(), 'daily', 'rt_wp_lws_cache_check_log_file_size_daily' );

	}

}
