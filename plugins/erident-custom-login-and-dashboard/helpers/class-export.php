<?php
/**
 * Setup export functions.
 *
 * @package Custom_Login_Dashboard
 */

namespace CustomLoginDashboard\Helpers;

/**
 * Setup widget export functions.
 */
class Export {
	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {}

	/**
	 * Run importer.
	 */
	public function export() {

		$settings = get_option( 'plugin_erident_settings', [] );

		ignore_user_abort( true );

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=erident-settings-export-' . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		echo json_encode( $settings );
		exit;

	}
}
