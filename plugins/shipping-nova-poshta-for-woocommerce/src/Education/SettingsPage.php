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

namespace NovaPoshta\Education;

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

		add_filter( 'shipping_nova_poshta_for_woocommerce_admin_settings_page_tabs', [ $this, 'register_tabs' ] );
		add_action( 'shipping_nova_poshta_for_woocommerce_settings_page_shipping_cost_tab', [ $this, 'shipping_cost' ] );
	}

	/**
	 * Register new tabs.
	 *
	 * @param array $tabs List of registered tabs.
	 *
	 * @return array
	 */
	public function register_tabs( array $tabs ): array {

		$tabs['shipping-cost'] = sprintf(
			'%s<span class="shipping-nova-poshta-for-woocommerce-pro"></span>',
			esc_html__( 'Shipping Cost', 'shipping-nova-poshta-for-woocommerce' )
		);

		return $tabs;
	}

	/**
	 * View for shipping cost tab.
	 *
	 * @param string $tab_label Current tab label.
	 */
	public function shipping_cost( string $tab_label ) {

		$screenshots = [
			1 => esc_html__( 'Shipping Cost on the Settings Page', 'shipping-nova-poshta-for-woocommerce' ),
			2 => esc_html__( 'Shipping Cost on the Category Page', 'shipping-nova-poshta-for-woocommerce' ),
			3 => esc_html__( 'Shipping Cost on the Product Page', 'shipping-nova-poshta-for-woocommerce' ),
			4 => esc_html__( 'Shipping Cost on the Checkout Page 1', 'shipping-nova-poshta-for-woocommerce' ),
			5 => esc_html__( 'Shipping Cost on the Checkout Page 2', 'shipping-nova-poshta-for-woocommerce' ),
		];

		require NOVA_POSHTA_PATH . 'templates/education/admin/page-options/tab-shipping-cost.php';
	}
}
