<?php
/**
 * Settings trait
 *
 * Provides shared logic for navigation tab settings
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

defined( 'ABSPATH' ) || exit();

/**
 * WC_Revolut_Settings_Trait trait.
 */
trait WC_Revolut_Settings_Trait {


	/**
	 * Tab title
	 *
	 * @var string
	 */
	protected $tab_title;

	/**
	 * API settings
	 *
	 * @var WC_Revolut_Settings_API
	 */
	protected $api_settings;

	/**
	 * Add setting tab to admin configuration.
	 *
	 * @param array $tabs setting tabs.
	 */
	public function admin_nav_tab( $tabs ) {
		$tabs[ $this->id ] = $this->tab_title;
		return $tabs;
	}

	/**
	 * Display navigation for settings page
	 */
	public function output_settings_nav() {
		if ( $this->check_is_get_data_submitted( 'page' ) && $this->check_is_get_data_submitted( 'section' ) ) {
			$is_revolut_section = 'wc-settings' === $this->get_request_data( 'page' ) && in_array( $this->get_request_data( 'section' ), WC_REVOLUT_GATEWAYS, true ) || $this->get_request_data( 'section' ) === 'revolut_advanced_settings';
			if ( $is_revolut_section ) {
				include REVOLUT_PATH . 'templates/html-settings-nav.php';
			}
		}
	}

	/**
	 * Check API mode
	 */
	public function is_sandbox() {
		return 'sandbox' === $this->get_option( 'mode' );
	}
}
