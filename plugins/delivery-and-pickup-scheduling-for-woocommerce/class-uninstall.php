<?php
/**
 * Fired by freemius when the plugin is uninstalled.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 * @package DPS
 */

/**
 * Uninstall class.
 *
 * @since 1.0.0
 */
class DPS_Uninstall {

	/**
	 * Remove plugin settings.
	 *
	 * @since    1.0.0
	 */
	public static function remove_plugin_settings() {

		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		/**
		 * If the free version and PRO version exist then don't delete the settings.
		 * This ensures that users do not accidentally delete their settings when installing PRO plugin.
		 */
		$plugins = get_plugins();
		if ( array_key_exists( 'delivery-and-pickup-scheduling-for-woocommerce/delivery-and-pickup-scheduling.php', $plugins ) && array_key_exists( 'delivery-and-pickup-scheduling-for-woocommerce-pro/delivery-and-pickup-scheduling.php', $plugins ) ) {
			return;
		}

		$should_delete_settings = get_option( 'lpac_dps' )['misc__delete_settings_uninstall'] ?? '';
		if ( empty( $should_delete_settings ) ) {
			return;
		}

		$option_keys = array(
			'lpac_dps',
			'lpac_dps_first_install_date',
			'lpac_dps_installed_at_version',
			'lpac_dps_maxed_delivery_dates',
			'lpac_dps_maxed_pickup_dates',
		);

		foreach ( $option_keys as $key ) {
			delete_option( $key );
		}
	}
}
