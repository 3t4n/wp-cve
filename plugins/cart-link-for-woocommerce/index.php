<?php
/**
 * Plugin Name: Cart Link for WooCommerce
 * Description: Create, customize and save the direct links adding the predefined products setup to the cart. Share the cart links with your customers, make their carts be automatically filled with the right items and boost your sales in no time!
 * Plugin URI: https://wordpress.org/plugins/cart-link-for-woocommerce/
 * Version: 1.5.0
 * Author: Sebastian Pisula
 * Author URI: mailto:sebastian.pisula@gmail.com
 * Text Domain: cart-link-for-woocommerce
 * Domain Path: /lang/
 * Requires at least: 6.1
 * Tested up to: 6.4
 * WC requires at least: 8.0
 * WC tested up to: 8.3
 * Requires PHP: 7.2
 */

use IC\Plugin\CartLinkWooCommerce\Assets;
use IC\Plugin\CartLinkWooCommerce\AssetsChecker;
use IC\Plugin\CartLinkWooCommerce\Campaign;
use IC\Plugin\CartLinkWooCommerce\Notice;
use IC\Plugin\CartLinkWooCommerce\Order;
use IC\Plugin\CartLinkWooCommerce\PluginData;
use IC\Plugin\CartLinkWooCommerce\PluginLinks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include __DIR__ . '/vendor/autoload.php';

$plugin_data = new PluginData( __FILE__, 'Cart Link for WooCommerce', '1.5.0', 'cart-link-for-woocommerce', 2 );

( new class( $plugin_data ) {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	/**
	 * @param PluginData $plugin_data .
	 */
	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	public function hooks(): void {
		( new Notice\NoticeWooCommerceRequired( $this->plugin_data ) )->hooks();

		add_action( 'woocommerce_init', function () {
			// Plugin.
			( new PluginLinks( $this->plugin_data ) )->hooks();

			// Campaign.
			( new Campaign\CampaignActions\AddProductsAction\ModifyProductPrice() )->hooks();

			( new Campaign\CampaignList() )->hooks();
			( new Campaign\CampaignSaveActions() )->hooks();
			( new Campaign\CampaignSaveProducts() )->hooks();
			( new Campaign\ModifyPermalink() )->hooks();
			( new Campaign\RegisterPostType() )->hooks();
			( new Campaign\TriggerAction() )->hooks();

			( new Campaign\Metabox\MetaboxProducts( $this->plugin_data ) )->hooks();
			( new Campaign\Metabox\MetaboxActions( $this->plugin_data ) )->hooks();

			// Notice.
			( new Notice\NoticeAction() )->hooks();
			( new Notice\NoticeNoCampaigns( $this->plugin_data ) )->hooks();

			// Order.
			( new Order\DisplayOrderCampaign() )->hooks();
			( new Order\FilterOrderByCampaign( $this->plugin_data ) )->hooks();
			( new Order\OrderColumnInfo() )->hooks();
			( new Order\SaveOrderCampaign() )->hooks();

			// Assets.
			$assets_checker = new AssetsChecker();
			( new Assets( $this->plugin_data, $assets_checker ) )->hooks();
		} );

		add_action( 'before_woocommerce_init', static function () {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} );
	}
} )->hooks();
