<?php
/**
 * Admin area code
 *
 * @package  Faire/Admin
 */

namespace Faire\Wc\Admin;

use Faire\Wc\Admin\Order\Order;
use Faire\Wc\Admin\Product\Simple as Product_Simple;
use Faire\Wc\Admin\Product\Variation as Product_Variation;
use Faire\Wc\Api\Order_Api;
use Faire\Wc\Wpml\WPML_Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin Main class.
 */
class Main {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Adds a new integration to WooCommerce.
		add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );

		// Adds a settings link to the plugin in the plugins admin list.
		add_filter(
			'plugin_action_links_' . plugin_basename( FAIRE_WC_PLUGIN_FILE ),
			array( $this, 'add_plugin_settings_link' )
		);

		// Add product simple and product variation UI customizations.
		new Product_Simple();
		new Product_Variation();
		// Inits orders backend functionality.
		new Order( new Order_Api() );
		// Enqueue admin assets.
		new Assets();
		// Adds WPML Integration.
		if ( $this->is_wmpl_activated() ) {
			new WPML_Product();
		}
	}

	/**
	 * Adds a new integration to WooCommerce.
	 *
	 * @param array<string> $integrations List of WooCommerce integrations.
	 *
	 * @return array<string> Updated list of WooCommerce integrations.
	 */
	public function add_integration( array $integrations ): array {
		$integrations[] = __NAMESPACE__ . '\Wc_Integration_Faire';
		return $integrations;
	}

	/**
	 * Adds a settings link to the plugin in the plugins admin list.
	 *
	 * @param array $links Links for the plugin entry.
	 *
	 * @return array Updated links for the plugin entry.
	 */
	public function add_plugin_settings_link( array $links ): array {
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=wc-settings&tab=integration&section=faire_wc_integration' ),
			__( 'Settings' )
		);
		return $links;
	}

	/**
	 * Check if plugins WPML and WPML for WC are active
	 *
	 * @return bool Return true if WPML and WPML for WC are active
	 */
	private function is_wmpl_activated(): bool {
		return is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) && is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' );
	}

}
