<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\Metabox;

use IC\Plugin\CartLinkWooCommerce\Campaign\Campaign;
use IC\Plugin\CartLinkWooCommerce\Campaign\Metabox\Products\ProductsTable;
use IC\Plugin\CartLinkWooCommerce\Campaign\RegisterPostType;
use IC\Plugin\CartLinkWooCommerce\PluginData;
use WP_Post;

/**
 * Metabox "Products"
 */
class MetaboxProducts {

	public const NONCE_NAME   = 'ic_campaign_products_nonce';
	public const NONCE_ACTION = 'ic_campaign_products';

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

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 1 );
	}

	/**
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'campaign-products',
			__( 'Products', 'cart-link-for-woocommerce' ),
			[ $this, 'render_metabox' ],
			RegisterPostType::POST_TYPE,
			'normal',
			'low'
		);
	}

	/**
	 * @param WP_Post $post .
	 *
	 * @return void
	 */
	public function render_metabox( WP_Post $post ): void {
		$campaign = new Campaign( $post->ID );

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		echo wp_kses_post( wpautop( __( 'Choose the products which will be automatically added to the cart once the customer clicks the direct cart link.', 'cart-link-for-woocommerce' ) ) );

		$table = new ProductsTable();
		$table->set_items( $campaign->get_products() );
		$table->set_plugin_data( $this->plugin_data );
		$table->prepare_items();
		$table->display();
	}
}
