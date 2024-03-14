<?php
/**
 * Admin page manage options
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Education\ManageOrders;

use NovaPoshta\Main;

/**
 * Class ManageOrders
 *
 * @package NovaPoshta\Pro\ManageOrders;
 */
class ManageOrdersPage {

	/**
	 * Screen ID.
	 */
	const SCREEN_ID = Main::PLUGIN_SLUG . '-manage-orders';

	/**
	 * Add hooks.
	 */
	public function hooks() {

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles() {

		if ( ! $this->is_current_page() ) {
			return;
		}

		wp_enqueue_style(
			Main::PLUGIN_SLUG,
			NOVA_POSHTA_URL . 'assets/build/css/admin/admin.css',
			[],
			Main::VERSION
		);
	}

	/**
	 * Check if is it current page.
	 *
	 * @return bool
	 */
	private function is_current_page(): bool {

		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( empty( $_GET['page'] ) ) {
			return false;
		}

		return Main::PLUGIN_SLUG . '-manage-orders' === sanitize_key( $_GET['page'] );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Register menu
	 */
	public function add_menu() {

		add_submenu_page(
			Main::PLUGIN_SLUG,
			esc_html__( 'Manage orders', 'shipping-nova-poshta-for-woocommerce' ),
			esc_html__( 'Manage orders', 'shipping-nova-poshta-for-woocommerce' ),
			'manage_options',
			self::SCREEN_ID,
			[
				$this,
				'view',
			]
		);
	}

	/**
	 * Show page
	 */
	public function view() {

		require NOVA_POSHTA_PATH . 'templates/education/admin/manage-orders.php';
	}
}
