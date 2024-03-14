<?php

/**
 * Plugin main class.
 *
 * @package InvoicesWooCommerce
 */

namespace WPDesk\WPDeskFRFree;

use FRFreeVendor\WPDesk\Dashboard\DashboardWidget;
use FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Integration;
use FRFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FRFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use FRFreeVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use FRFreeVendor\WPDesk_Plugin_Info;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
class Plugin extends AbstractPlugin implements HookableCollection {

	use HookableParent;

	private $start_here_url;
	/**
	 * @var string
	 */
	private $upgrade_url;

	/**
	 * @param WPDesk_Plugin_Info $plugin_info Plugin data.
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
		parent::__construct( $this->plugin_info );

		$this->start_here_url = admin_url( 'admin.php?page=wc-settings&tab=flexible_refunds&section=support' );
		$this->settings_url   = admin_url( 'admin.php?page=wc-settings&tab=flexible_refunds' );
		$this->docs_url       = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/elastyczne-zwroty-i-reklamacje-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-refund-docs&utm_content=plugin-list' : 'https://wpdesk.net/docs/flexible-refund-and-cancel-order-for-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-refund-docs&utm_content=plugin-list';
		$this->support_url    = get_locale() === 'pl_PL' ? 'https://wordpress.org/support/plugin/flexible-refund-and-return-order-for-woocommerce/' : 'https://wordpress.org/support/plugin/flexible-refund-and-return-order-for-woocommerce/';
		$this->upgrade_url    = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/elastyczne-zwroty-i-reklamacje-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-refund-pro&utm_content=plugin-list' : 'https://wpdesk.net/products/flexible-refund-and-return-order-for-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-refund-pro&utm_content=plugin-list';
	}

	public function links_filter( $links ) {
		$links_array  = parent::links_filter( $links );
		$start_link = '<a href="' . $this->start_here_url . '" style="font-weight: bold;color: #007050">' . esc_html__( 'Start here', 'flexible-refund-and-return-order-for-woocommerce' ) . '</a>';
		array_splice( $links_array, 0, 0, $start_link );

		$upgrade_link = '<a href="' . $this->upgrade_url . '" style="font-weight: bold;color: #FF9743">' . esc_html__( 'Upgrade to PRO &rarr;', 'flexible-refund-and-return-order-for-woocommerce' ) . '</a>';
		array_splice( $links_array, 3, 0, $upgrade_link );


		return $links_array;
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();
		$this->add_hookable( new Integration() );
		$this->add_hookable( new DeactivateFree() );
		$this->add_hookable( new Tracker\DeactivationTracker( $this->plugin_info ) );
		$this->hooks_on_hookable_objects();
		( new DashboardWidget() )->hooks();
	}
}
