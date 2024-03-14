<?php
/**
 * Activation date.
 *
 * @package WPDesk\FlexibleShippingUps
 */

namespace WPDesk\FlexibleShippingUps;


use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can save first plugin activation date.
 */
class ActivationDate implements Hookable {

	const UPS_PLUGIN_FILE = 'flexible-shipping-ups/flexible-shipping-ups.php';
	const PAST_DATE       = '2019-05-10 01:00';

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'maybe_set_plugin_activation_date' ) );
	}

	/**
	 * Maybe set plugin activation date.
	 */
	public function maybe_set_plugin_activation_date(): void {
		$option_name     = 'plugin_activation_' . self::UPS_PLUGIN_FILE;
		$activation_date = get_option( $option_name, '' );
		if ( '' === $activation_date ) {
			if ( $this->ups_settings_exists() ) {
				$activation_date = self::PAST_DATE;
			} else {
				$activation_date = current_time( 'mysql' );
			}
			update_option( $option_name, $activation_date );
		}
	}

	/**
	 * UPS settings already exists?
	 *
	 * @return bool
	 */
	private function ups_settings_exists() {
		return '' !== get_option( 'woocommerce_flexible_shipping_ups_settings', '' );
	}

}
