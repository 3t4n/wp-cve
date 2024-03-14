<?php
/**
 * Contains LWSCache_Deactivator class.
 *
 * @package    lwscache
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0
 *
 * @package    lwscache
 * @subpackage lwscache/includes
 *
 * @author     LWS
 */
class LWSCache_Deactivator {

	/**
	 * Schedule event to check log file size daily. Remove LWSCache capability.
	 *
	 * @since    2.0.0
	 */
	public static function deactivate() {

		wp_clear_scheduled_hook( 'rt_wp_lws_cache_check_log_file_size_daily' );

		$role = get_role( 'administrator' );
		$role->remove_cap( 'LWSCache | Config' );
		$role->remove_cap( 'LWSCache | Purge cache' );

	}

}
