<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce Product Bundles Compatibility
class WC_Szamlazz_Product_Bundles_Compatibility {

	public static function init() {
		add_filter( 'wc_szamlazz_invoice_line_item', array( __CLASS__, 'add_product_bundle_info' ), 10, 4 );

		//Create settings
		add_filter( 'wc_szamlazz_settings_fields', array( __CLASS__, 'add_settings') );

	}
	public static function add_settings($settings) {
		$settings_custom = array(
			'section_compat_prodcut_bundles' => array(
				'title' => __( 'WooCommerce Product Bundles', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'Settings related to WooCommerce Product Bundles.', 'wc-szamlazz' ),
			),
			'compat_prodcut_bundles_hide_free_items' => array(
				'title'    => __( 'Hide free bundled items on the invoice', 'wc-szamlazz' ),
				'type'     => 'checkbox',
				'default' => 'yes',
				'desc_tip' => __( 'If checked, bundles items that are free will be hidden on the invoice.', 'wc-szamlazz' ),
			)
		);

		return array_merge($settings, $settings_custom);
	}

	public static function add_product_bundle_info( $tetel, $order_item, $order, $szamla ) {

		//Check if line item is a container
		if(is_object($order_item) && !$order_item->get_type('shop_order_refund') && wc_pb_is_bundle_container_order_item($order_item) && $tetel->bruttoErtek != 0) {

			//Get bundled items
			$bundled_items = wc_pb_get_bundled_order_items($order_item, $order);
			foreach ($bundled_items as $bundled_order_item) {
				$tetel->megjegyzes .= '• '.$bundled_order_item->get_quantity().'× '.$bundled_order_item->get_name()."\n";
			}

		}

		if(WC_Szamlazz()->get_option('compat_prodcut_bundles_hide_free_items', 'yes') == 'yes') {
			if(is_object($order_item) && method_exists($order_item, 'get_product')) {
				//Hide separate bundle items
				if(wc_pb_is_bundled_order_item($order_item, $order) && $tetel->bruttoErtek == 0) {
					$tetel = false;
				}

				//Hide main item if its free
				if(wc_pb_is_bundle_container_order_item($order_item) && $tetel->bruttoErtek == 0) {
					$tetel = false;
				}
			}
		}

		return $tetel;
	}

}

WC_Szamlazz_Product_Bundles_Compatibility::init();
