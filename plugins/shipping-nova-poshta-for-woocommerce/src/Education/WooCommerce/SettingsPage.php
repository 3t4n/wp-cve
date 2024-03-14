<?php
/**
 * Education on settings page
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Education\WooCommerce;

use NovaPoshta\Main;

/**
 * Class SettingsPage
 *
 * @package NovaPoshta\Education
 */
class SettingsPage {

	/**
	 * Hooks.
	 */
	public function hooks() {

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_style' ] );
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_style() {

		if ( ! $this->is_settings_page() ) {
			return;
		}

		wp_enqueue_style(
			Main::PLUGIN_SLUG . '-woocommerce-admin',
			NOVA_POSHTA_URL . 'assets/build/css/admin/education.css',
			[],
			Main::VERSION
		);
	}

	/**
	 * Determinate settings page.
	 *
	 * @return bool
	 */
	private function is_settings_page(): bool {

		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['page'] ) ) {
			return false;
		}

		$page = sanitize_key( $_GET['page'] );

		if ( Main::PLUGIN_SLUG . '-manage-orders' === $page ) {
			return true;
		}

		if ( empty( $_GET['tab'] ) ) {
			return false;
		}

		$tab = sanitize_key( $_GET['tab'] );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( 'wc-settings' === $page && in_array( $tab, [ 'shipping', 'payment' ], true ) ) {
			return true;
		}

		return Main::PLUGIN_SLUG === $page && 'shipping-cost' === $tab;
	}
}
