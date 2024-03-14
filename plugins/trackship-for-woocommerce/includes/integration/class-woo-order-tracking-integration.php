<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_Order_Tracking_TS4WC {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();
	}
	
	/**
	 * Get the class instance
	 *
	 * @return WOO_Order_Tracking_TS4WC
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/*
	* init from parent mail class
	*/
	public function init() {
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
	}

	public function on_plugins_loaded () {
		// View Order Page.
		add_action( 'woocommerce_email_before_order_table', array( trackship_for_woocommerce()->front, 'wc_shipment_tracking_email_display' ), 0, 4 );
	}

	public function woo_orders_tracking_items( $order_id ) {
		$order = wc_get_order( $order_id );
		$tracking_number = [];
		$tracking_items = [];
		
		$_wot_tracking_number = $order->get_meta( '_wot_tracking_number', true );
		if ( $_wot_tracking_number ) {
			return $this->single_tracking_items( $_wot_tracking_number, $tracking_items, $order, $order_id );
		}

		$i = 0;
		foreach ( $order->get_items() as $item_id => $item_value ) {
			$item_data = wc_get_order_item_meta( $item_id, '_vi_wot_order_item_tracking_data', true );
			$item_data_qty = wc_get_order_item_meta( $item_id, '_vi_wot_order_item_tracking_data_by_quantity', true );
			$item_data = json_decode( $item_data );
			$item_data_qty = json_decode( $item_data_qty );

			$item_qty = $item_value->get_quantity();

			if ( $item_data_qty && $item_data ) {
				$item_data_last = [];
				$item_data_last[] = $item_data[array_key_last( $item_data )];
				$tracking_data = array_merge( $item_data_last, $item_data_qty );
				$qty = 1;
				foreach ( $tracking_data as $key => $value ) {
					$product_array = [];
					$product_data = (object) array (
						'product'	=> $item_value->get_product()->get_id(),
						'item_id'	=> $item_id,
						'qty'		=> $qty,
					);
					$search_id = array_search( $value->tracking_number, $tracking_number );
					if ( is_int( $search_id ) ) {
						$search_item_id = array_search( $item_id, array_column( $tracking_items[$search_id]['products_list'], 'item_id' ) );
						if ( is_int( $search_item_id ) ) {
							$tracking_items[$search_id]['products_list'][$search_item_id]->qty = ++$qty;
						} else {
							$tracking_items[$search_id]['products_list'][] = $product_data;
						}
					} else {
						array_push( $product_array, $product_data );
						$tracking_number[$i] = $value->tracking_number;
						$tracking_items[$i] = array(
							'formatted_tracking_provider'	=> $value->carrier_name,
							'tracking_provider'				=> $value->carrier_slug,
							'tracking_number'				=> $value->tracking_number,
							'formatted_tracking_link'		=> $value->carrier_url,
							'tracking_id'					=> '',
							'date_shipped'					=> $value->time,
							'tracking_page_link'				=> trackship_for_woocommerce()->actions->get_tracking_page_link( $order_id, $value->tracking_number ),
							'products_list'					=> $product_array,
						);
						$i++;
					}
				}
			} elseif ( $item_data ) {
				$tracking_data = $item_data[array_key_last( $item_data )];

				$product_array = [];
				$product_data = (object) array (
					'product'	=> $item_value->get_product()->get_id(),
					'item_id'	=> $item_id,
					'qty'		=> $item_qty,
				);
				
				$search_id = array_search( $tracking_data->tracking_number, $tracking_number );
				if ( is_int( $search_id ) ) {
					$tracking_items[$search_id]['products_list'][] = $product_data;
				} else {
					array_push( $product_array, $product_data );
					$tracking_number[$i] = $tracking_data->tracking_number;
					$tracking_items[$i] = array(
						'formatted_tracking_provider'	=> $tracking_data->carrier_name,
						'tracking_provider'				=> $tracking_data->carrier_slug,
						'tracking_number'				=> $tracking_data->tracking_number,
						'formatted_tracking_link'		=> $tracking_data->carrier_url,
						'tracking_id'					=> '',
						'date_shipped'					=> $tracking_data->time,
						'tracking_page_link'			=> trackship_for_woocommerce()->actions->get_tracking_page_link( $order_id, $value->tracking_number ),
						'products_list'					=> $product_array,
					);
					$i++;
				}
			}
		}
		// echo '<pre>';print_r($tracking_items);echo '</pre>';
		return $tracking_items;
	}

	public function single_tracking_items( $_wot_tracking_number, $tracking_items, $order, $order_id ) {
		$tracking_provider = $order->get_meta( '_wot_tracking_carrier', true );
		$tracking_items[0] = array(
			'formatted_tracking_provider'	=> trackship_for_woocommerce()->actions->get_provider_name( $tracking_provider ),
			'tracking_provider'				=> $tracking_provider,
			'formatted_tracking_link'		=> '',
			'tracking_number'				=> $_wot_tracking_number,
			'tracking_id'					=> '',
			'tracking_page_link'			=> trackship_for_woocommerce()->actions->get_tracking_page_link( $order_id, $value->tracking_number ),
			'date_shipped'					=> time(),
		);
		return $tracking_items;
	}
}
