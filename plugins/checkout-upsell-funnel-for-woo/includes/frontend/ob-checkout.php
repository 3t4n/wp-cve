<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Ob_Checkout {
	protected $settings, $table;

	public function __construct() {
		$this->settings = new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		if ( ! $this->settings->enable( 'ob_' ) ) {
			return;
		}
		$this->table = 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table';
		//save order bump data
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'viwcuf_ob_woocommerce_checkout_create_order_line_item' ), 10, 4 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'viwcuf_ob_woocommerce_checkout_update_order_meta' ), 10, 2 );
	}

	public function viwcuf_ob_woocommerce_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
		if ( ! empty( $values['viwcuf_ob_product'] ) ) {
			$arg = $values['viwcuf_ob_product'];
			$item->add_meta_data( '_vi_wcuf_ob_info', $values['viwcuf_ob_product'] );
			$arg['product_id']   = $values['product_id'] ?? '';
			$arg['variation_id'] = $values['variation_id'] ?? '';
			$added               = WC()->session->get( 'viwcuf_ob_added_products', array() );
			$added[]             = $arg;
			WC()->session->set( 'viwcuf_ob_added_products', $added );
		}
	}

	public function viwcuf_ob_woocommerce_checkout_update_order_meta( $order_id, $data ) {
		$added_product = WC()->session->get( 'viwcuf_ob_added_products', '' );
		if ( ! empty( $added_product ) ) {
			$ob_info = json_encode( $added_product );
			if ( $this->table::get_row_by_order_id( $order_id ) ) {
				$this->table::update_by_order_id( $order_id, array( 'ob_info' => $ob_info ) );
			} else {
				$this->table::insert( $order_id, $data['billing_email'] ?? '', date( 'Y-m-d H:i:s' ), get_current_user_id(), '', $ob_info );
			}
		}
		WC()->session->__unset( 'viwcuf_ob_added_products' );
	}
}