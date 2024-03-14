<?php
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Assets\WC_BPost_Shipping_Assets_Management;
use WC_BPost_Shipping\Controller\WC_BPost_Shipping_Controller_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_BPost_Shipping_Admin_Order_Data_Controller displays data for admin order edit details page
 * urls like: wp-admin/post.php?post=XXX&action=edit
 */
class WC_BPost_Shipping_Admin_Order_Data_Controller extends WC_BPost_Shipping_Controller_Base {
	/** @var WC_BPost_Shipping_Meta_Handler */
	private $meta_handler;
	/** @var WC_BPost_Shipping_Assets_Management */
	private $assets_management;
	/** @var WC_Order */
	private $order;


	/**
	 * WC_BPost_Shipping_Admin_Order_Data_Controller constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Assets_Management $assets_management
	 * @param WC_BPost_Shipping_Meta_Handler $meta_handler
	 * @param WC_Order $order
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Assets_Management $assets_management,
		WC_BPost_Shipping_Meta_Handler $meta_handler,
		WC_Order $order
	) {
		parent::__construct( $adapter );
		$this->meta_handler      = $meta_handler;
		$this->assets_management = $assets_management;
		$this->order             = $order;
	}

	public function load_template() {
		$bpost_meta = $this->meta_handler->get_translated_bpost_meta();

		if ( ! $bpost_meta ) {
			return;
		}

		$js_data = array(
			'url'      => $this->get_generate_url( array( $this->order->get_id() ) ),
			'order_id' => $this->order->get_id(),
		);

		$this->assets_management->admin_order_edit_page( $js_data );

		$this->get_template(
			'admin-order-data.php',
			array(
				'bpost_meta' => $bpost_meta,
			)
		);

	}

	/**
	 * @param int[] $post_ids
	 *
	 * @return string
	 */
	private function get_generate_url( array $post_ids ) {
		$query = $this->adapter->build_query(
			array(
				'wp_once'  => $this->adapter->wp_create_nonce(),
				'post_ids' => $post_ids,
			)
		);

		$api_url = WC()->api_request_url( 'bpost-refresh-status?' . $query );
		if ( substr( $api_url, - 1 ) === '/' ) {
			return substr( $api_url, 0, - 1 );
		}

		return $api_url;
	}
}
