<?php
// Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

/**
 * Uninstall this plugin
 */
class EPHD_Uninstall {

	public function __construct() {

        delete_option( 'ephd_error_log' );

		if ( get_transient( '_ephd_delete_all_hd_data' ) ) {
			$this->uninstall_hd();
		}
    }

	/**
	 * Removes ALL plugin data for Help Dialog
	 * only when the relevant option is active
	 */
	private function uninstall_hd() {

		global $wpdb;

		delete_option( 'ephd_version' );
		delete_option( 'ephd_version_first' );
		delete_option( 'ephd_global_config' );  // defined in EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME
		delete_option( 'ephd_notification_rules_config' );  // EPHD_Config_DB::EPHD_NOTIFICATION_RULES_CONFIG_NAME
		delete_option( 'ephd_show_upgrade_message' );
		delete_option( 'ephd_analytics_purge_date' );
		delete_transient( '_ephd_plugin_activated' );
		delete_transient( '_ephd_delete_all_hd_data' );

		// Remove all database tables
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'ephd_submissions' );  // defined in EPHD_Submissions_DB
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'ephd_search' );  // defined in EPHD_Search_DB
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'ephd_analytics' );  // defined in EPHD_Analytics_DB
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'ephd_faqs' );  // defined in EPHD_FAQs_DB
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . 'ephd_widgets' );  // defined in EPHD_Widgets_DB
	}
}

new EPHD_Uninstall();