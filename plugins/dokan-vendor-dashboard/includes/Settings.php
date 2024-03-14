<?php

namespace WeDevs\DokanVendorDashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Settings related to the plugin.
 *
 * @since 1.0.0
 */
class Settings {

	/**
	 * Add settings to enable interactive vendor dashboard ui.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public static function add_setting_to_enable_vendor_dashboard( $settings ) {
		$settings['vendor_dashboard_ui'] = [
			'name'    => 'vendor_dashboard_ui',
			'label'   => __( 'Use full page vendor dashboard ui', 'dokan-vendor-dashboard' ),
			'desc'    => __( 'Enable vendor dashboard full page interactive user interface', 'dokan-vendor-dashboard' ),
			'type'    => 'switcher',
			'default' => 'on',
		];

		return $settings;
	}

	/**
	 * Is switched to new dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @todo we can manage this from options table and a toggling button.
	 *
	 * @return boolean
	 */
	public static function is_switched_new_dashboard() {
		return 'on' === dokan_get_option( 'vendor_dashboard_ui', 'dokan_general', 'on' );
	}
}
