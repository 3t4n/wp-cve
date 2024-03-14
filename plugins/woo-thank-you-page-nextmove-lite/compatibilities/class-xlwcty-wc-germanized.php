<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Wc_Germanized {
	private static $ins = null;

	public function __construct() {
		if ( class_exists( 'WC_GZD_Checkout' ) ) {
			$this->remove_wc_germanized_checkout_hook();

			add_filter( 'woocommerce_order_formatted_billing_address', array( $this, 'xlwcty_format_billing_address_campatibility' ), 99, 2 );
			add_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'xlwcty_format_shipping_address_campatibility' ), 99, 2 );
			add_filter( 'xlwcty_customer_info_contact_name', array( $this, 'xlwcty_add_honorific_to_contact_name' ), 10, 2 );
			add_filter( 'woocommerce_get_order_address', array( $this, 'xlwcty_add_title_field_in_address' ), 10, 3 );
		}
	}

	public function remove_wc_germanized_checkout_hook() {
		$wc_gzd_checkout = new WC_GZD_Checkout();

		remove_filter( 'woocommerce_order_formatted_billing_address', array( $wc_gzd_checkout, 'set_formatted_billing_address' ), 0, 2 );
		remove_filter( 'woocommerce_order_formatted_shipping_address', array( $wc_gzd_checkout, 'set_formatted_shipping_address' ), 0, 2 );
	}

	public static function get_instance() {
		if ( self::$ins == null ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function xlwcty_format_billing_address_campatibility( $billing_address, $order ) {
		if ( isset( $billing_address['title'] ) ) {
			unset( $billing_address['title'] );
			$billing_address = array_filter( $billing_address );
		}

		return $billing_address;
	}

	public function xlwcty_format_shipping_address_campatibility( $shipping_address, $order ) {
		if ( isset( $shipping_address['title'] ) ) {
			unset( $shipping_address['title'] );
			$shipping_address = array_filter( $shipping_address );
		}

		return $shipping_address;
	}

	public function xlwcty_add_honorific_to_contact_name( $contact_name, $address_raw ) {
		if ( isset( $address_raw['title'] ) && ! empty( $address_raw['title'] ) ) {
			$contact_name = $address_raw['title'] . ' ' . $contact_name;
		}

		return $contact_name;
	}

	public function xlwcty_add_title_field_in_address( $fields, $type, $obj ) {

		if ( version_compare( WC_GERMANIZED_VERSION, '2.2.8', '>=' ) ) {
			if ( $type == 'billing' && wc_gzd_get_crud_data( $obj, 'billing_title' ) ) {
				$fields['title'] = wc_gzd_get_customer_title( wc_gzd_get_crud_data( $obj, 'billing_title' ) );
			} elseif ( $type == 'shipping' && wc_gzd_get_crud_data( $obj, 'shipping_title' ) ) {
				$fields['title'] = wc_gzd_get_customer_title( wc_gzd_get_crud_data( $obj, 'shipping_title' ) );
			}
		} else {
			$wc_gzd_checkout = new WC_GZD_Checkout();

			if ( $type == 'billing' && wc_gzd_get_crud_data( $obj, 'billing_title' ) ) {
				$fields['title'] = $wc_gzd_checkout->get_customer_title( wc_gzd_get_crud_data( $obj, 'billing_title' ) );
			} elseif ( $type == 'shipping' && wc_gzd_get_crud_data( $obj, 'shipping_title' ) ) {
				$fields['title'] = $wc_gzd_checkout->get_customer_title( wc_gzd_get_crud_data( $obj, 'shipping_title' ) );
			}
		}

		return $fields;
	}

}

add_action( 'plugins_loaded', function () {
	XLWCTY_Wc_Germanized::get_instance();
} );
