<?php
namespace WC_BPost_Shipping\Label;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

/**
 * Class WC_BPost_Shipping_Label_Url_Generator build an url with a nonce && post_ids
 * @package WC_BPost_Shipping\Label
 */
class WC_BPost_Shipping_Label_Url_Generator {
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;
	/** @var \WooCommerce */
	private $woocommerce;

	/**
	 * WC_BPost_Shipping_Label_Url_Generator constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param \WooCommerce $woocommerce
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter, \WooCommerce $woocommerce ) {
		$this->adapter     = $adapter;
		$this->woocommerce = $woocommerce;
	}


	/**
	 * @param int[] $post_ids
	 *
	 * @return string
	 */
	public function get_generate_url( array $post_ids ) {
		$query = $this->adapter->build_query(
			array(
				'wp_once'  => $this->adapter->wp_create_nonce(),
				'post_ids' => $post_ids,
			)
		);

		$api_url = $this->woocommerce->api_request_url( 'bpost-label?' . $query );
		if ( substr( $api_url, - 1 ) === '/' ) {
			return substr( $api_url, 0, - 1 );
		}

		return $api_url;
	}

}
