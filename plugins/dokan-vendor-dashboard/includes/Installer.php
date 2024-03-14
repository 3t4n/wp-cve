<?php

namespace WeDevs\DokanVendorDashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Scripts and styles class for the plugin.
 *
 * @since 1.0.0
 */
class Installer {

	/**
	 * Runs the installer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run() {
		$this->add_version_info();
	}

	/**
	 * Adds version information.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_version_info() {
		update_option( 'dokan_vendor_dashboard_version', DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION );
	}
}
