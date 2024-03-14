<?php
/**
 * Handles the routing for the autocomplete page section of the PeachPay admin panel
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

require_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-admin-section.php';
require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-extension.php';
require_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-onboarding-tour.php';

/**
 * Initializer for the PeachPay address autocomplete settings.
 */
class PeachPay_Address_Autocomplete {
	use PeachPay_Extension;

	/**
	 * Should the extension load?
	 */
	public static function should_load() {
		return true;
	}

	/**
	 * Is the integration enabled?
	 */
	public static function enabled() {
		return true;
	}

	/**
	 * .
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function includes( $enabled ) {
		if ( ! $enabled ) {
			return;
		}

		require_once PEACHPAY_ABSPATH . 'core/modules/address-autocomplete/hooks.php';
		require_once PEACHPAY_ABSPATH . 'core/modules/address-autocomplete/functions.php';
	}

	/**
	 * On plugins load.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	public function plugins_loaded( $enabled ) {
		require_once PEACHPAY_ABSPATH . 'core/modules/address-autocomplete/class-peachpay-address-autocomplete-settings.php';

		PeachPay_Admin_Section::create(
			'address_autocomplete',
			array(
				new PeachPay_Address_Autocomplete_Settings(),
			),
			array(),
			false,
			true
		);

		// migrate address autocommplete setting
		$need_to_migrate_setting = peachpay_get_settings_option( 'peachpay_express_checkout_window', 'address_autocomplete' );
		if ( $need_to_migrate_setting ) {
			PeachPay_Address_Autocomplete_Settings::update_setting( 'active_locations', 'default' );
			PeachPay_Address_Autocomplete_Settings::update_setting( 'enabled', 'yes' );

			peachpay_set_settings_option( 'peachpay_express_checkout_window', 'address_autocomplete', false );
		}

		if ( ! PeachPay_Capabilities::connected( 'woocommerce_premium' ) ) {
			PeachPay_Address_Autocomplete_Settings::update_setting( 'enabled', 'no' );
		}

		PeachPay_Onboarding_Tour::complete_section( 'address_autocomplete' );
	}
}
PeachPay_Address_Autocomplete::instance();
