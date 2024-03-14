<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\Metabox;

use IC\Plugin\CartLinkWooCommerce\Campaign\Campaign;
use IC\Plugin\CartLinkWooCommerce\Campaign\RegisterPostType;
use IC\Plugin\CartLinkWooCommerce\PluginData;
use WP_Post;

/**
 * Metabox "Actions".
 */
class MetaboxActions {

	public const NONCE_NAME   = 'ic_campaign_actions_nonce';
	public const NONCE_ACTION = 'ic_campaign_actions';

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
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 2 );
	}

	/**
	 * @return void
	 */
	public function add_meta_boxes(): void {
		add_meta_box(
			'campaign-settings',
			__( 'Actions', 'cart-link-for-woocommerce' ),
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
		$pages    = $this->get_pages();

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		include $this->plugin_data->get_plugin_absolute_path( 'views/html-metabox-actions.php' );
	}

	/**
	 * @return array<int, string>
	 */
	private function get_pages(): array {
		return wp_list_pluck( get_pages(), 'post_title', 'ID' );
	}
}
