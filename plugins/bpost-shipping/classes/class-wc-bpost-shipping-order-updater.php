<?php

use Bpost\BpostApiClient\Bpost\Order\Box;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Api\Exception\WC_BPost_Shipping_Api_Exception_Poi_Not_Found;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Geo6_Search;

/**
 * Class WC_BPost_Shipping_Order_Updater adds bpost info to the order
 */
class WC_BPost_Shipping_Order_Updater {

	/** @var  array */
	private $data;
	/** @var  WC_Order */
	private $order;
	/** @var WC_BPost_Shipping_Api_Geo6_Search */
	private $api_geo6_search;

	/** @var WC_BPost_Shipping_Meta_Handler */
	private $meta_handler;

	/**
	 * @param WC_Order $order
	 * @param array $data
	 * @param WC_BPost_Shipping_Api_Geo6_Search $api_geo6_search
	 */
	public function __construct( WC_Order $order, array $data, WC_BPost_Shipping_Api_Geo6_Search $api_geo6_search ) {
		$this->order           = $order;
		$this->data            = $data;
		$this->api_geo6_search = $api_geo6_search;

		$this->init_meta_handler();
	}

	private function init_meta_handler() {
		$adapter            = new WC_BPost_Shipping_Adapter_Woocommerce();
		$this->meta_handler = new WC_BPost_Shipping_Meta_Handler(
			$adapter,
			new WC_BPost_Shipping_Meta_Type( $adapter ),
			$this->order->get_id()
		);
	}

	/**
	 * do the update
	 */
	public function update_order() {
		$shipping_item_id = $this->get_shipping_item_id();

		$this->update_shipping_method( $shipping_item_id );
		$this->update_meta();
	}

	/**
	 * @return int
	 */
	private function get_shipping_item_id() {
		$shipping_items      = $this->order->get_items( 'shipping' );
		$shipping_items_keys = array_keys( $shipping_items );

		return $shipping_items_keys[0];
	}

	/**
	 * @param $shipping_item_id
	 */
	private function update_shipping_method( $shipping_item_id ) {
		wc_update_order_item(
			$shipping_item_id,
			array(
				'order_item_name' => $this->get_delivery_method_translated(),
			)
		);
	}

	/**
	 * @return string return translated delivery method
	 */
	private function get_delivery_method_translated() {
		$delivery_method = new WC_BPost_Shipping_Delivery_Method( $this->get_data( 'bpost_delivery_method' ) );

		return bpost__( 'bpost -' ) . ' ' . bpost__( $delivery_method->get_delivery_method_as_string() );
	}

	/**
	 * @param string $item
	 *
	 * @return string
	 */
	private function get_data( $item ) {
		return $this->data[ $item ];
	}

	private function update_meta() {
		$delivery_method = new WC_BPost_Shipping_Delivery_Method( $this->get_data( 'bpost_delivery_method' ) );

		$meta_items = [
			WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_DATE   => $this->get_data( 'bpost_delivery_date' ),
			WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_METHOD => $delivery_method->get_delivery_method_as_string(),
			WC_BPost_Shipping_Meta_Handler::KEY_ORDER_REFERENCE => $this->get_data( 'bpost_order_reference' ),
			WC_BPost_Shipping_Meta_Handler::KEY_PHONE_NUMBER    => $this->get_data( 'bpost_phone' ),
			WC_BPost_Shipping_Meta_Handler::KEY_EMAIL           => $this->get_data( 'bpost_email' ),
			WC_BPost_Shipping_Meta_Handler::KEY_STATUS          => Box::BOX_STATUS_OPEN,

		];

		if ( $this->get_data( 'bpost_delivery_point_id' ) ) {
			$meta_items += [
				WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_POINT           => $delivery_method->get_delivery_point( $this->get_data( 'bpost_postal_location' ) ),
				WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_METHOD_POINT_ID => $this->get_data( 'bpost_delivery_point_id' ),
				WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_POINT_TYPE      => $this->get_bpost_point_type(),
			];
		}

		foreach ( $meta_items as $meta_item_key => $meta_item_value ) {
			$this->meta_handler->set_meta( $meta_item_key, $meta_item_value );
		}
	}

	public function update_bpost_point_type() {
		$this->meta_handler->set_meta(
			WC_BPost_Shipping_Meta_Handler::KEY_DELIVERY_POINT_TYPE,
			$this->get_bpost_point_type()
		);
	}

	public function get_bpost_point_type() {
		try {
			return $this->api_geo6_search->get_point_type( $this->get_data( 'bpost_delivery_point_id' ) );
		} catch ( WC_BPost_Shipping_Api_Exception_Poi_Not_Found $ex ) {
		}

		return 0;
	}
}
