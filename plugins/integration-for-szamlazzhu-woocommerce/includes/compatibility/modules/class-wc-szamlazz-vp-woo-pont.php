<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce Currency Switcher by WooBeWoo Compatibility
class WC_Szamlazz_VP_Woo_Pont_Compatibility {

	public static function init() {

		//Append invoices to tracking page
		add_filter( 'vp_woo_pont_tracking_page_variables', array( __CLASS__, 'add_invoices_to_tracking_page'), 10, 2);

	}

	public static function add_invoices_to_tracking_page($args, $order) {
		$order_id = $order->get_id();
		$document_types = WC_Szamlazz_Helpers::get_document_types();
		foreach ($document_types as $document_type => $document_label) {
			if(WC_Szamlazz()->is_invoice_generated($order_id, $document_type)) {
				$link = WC_Szamlazz()->generate_download_link($order, $document_type);
				$args['invoices'][$document_type] = array(
					'url' => $link,
					'name' => $order->get_meta('_wc_szamlazz_'.$document_type),
					'label' => $document_label
				);
			}
		}
		return $args;
	}

}

WC_Szamlazz_VP_Woo_Pont_Compatibility::init();
