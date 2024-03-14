<?php

namespace IC\Plugin\CartLinkWooCommerce\Order;

use IC\Plugin\CartLinkWooCommerce\Campaign\RegisterPostType;
use IC\Plugin\CartLinkWooCommerce\PluginData;
use WP_Query;

class FilterOrderByCampaign {
	public const POST_TYPE         = 'shop_order';
	public const FILTER_FIELD_NAME = 'campaign_id';

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
		add_action( 'pre_get_posts', [ $this, 'filter_orders' ] );

		add_action( 'restrict_manage_posts', [ $this, 'add_filter' ] );
	}

	/**
	 * @param WP_Query $q .
	 *
	 * @return void
	 */
	public function filter_orders( WP_Query $q ): void {
		if ( ! is_admin() || ! $q->is_main_query() ) {
			return;
		}

		if ( $q->get( 'post_type' ) !== self::POST_TYPE ) {
			return;
		}

		if ( empty( $_GET[ self::FILTER_FIELD_NAME ] ?? '' ) ) {
			return;
		}

		$meta_query = $q->get( 'meta_query', [] );

		if ( ! is_array( $meta_query ) ) {
			$meta_query = [];
		}

		$meta_query[] = [
			'key'   => SaveOrderCampaign::META_CAMPAIGN_ID,
			'value' => (int) $_GET[ self::FILTER_FIELD_NAME ],
		];

		$q->set( 'meta_query', $meta_query );
	}

	/**
	 * @param string $post_type .
	 *
	 * @return void
	 */
	public function add_filter( string $post_type ): void {
		if ( self::POST_TYPE !== $post_type ) {
			return;
		}

		$campaigns = get_posts(
			[
				'post_type' => RegisterPostType::POST_TYPE,
				'nopaging'  => true,
				'orderby'   => 'post_title',
				'order'     => 'asc',
				'fields'    => 'ids',
			]
		);

		if ( ! $campaigns ) {
			return;
		}

		$current = sanitize_text_field( $_GET[ self::FILTER_FIELD_NAME ] ?? '' );

		include $this->plugin_data->get_plugin_absolute_path( 'views/html-filter-campaign-field.php' );
	}
}
