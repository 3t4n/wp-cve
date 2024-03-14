<?php

namespace QuadLayers\QLWAPP;

use QuadLayers\QLWAPP\Models\Box as Models_Box;
use QuadLayers\QLWAPP\Models\WooCommerce as Models_WooCommerce;
use QuadLayers\QLWAPP\Models\Display as Models_Display;
use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Services\Entity_Visibility;

class WooCommerce {

	protected static $instance;

	private function __construct() {
		add_action( 'wp', array( $this, 'woocommerce_init' ) );
	}

	public function woocommerce_init() {
		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_model = Models_WooCommerce::instance();
			$woocommerce       = $woocommerce_model->get();

			$position          = (string) $woocommerce['position'];
			$position_priority = (int) $woocommerce['position_priority'];

			// Add Product Button.
			if ( is_product() && 'none' !== $position ) {
				add_action( $position, array( $this, 'product_button' ), $position_priority );
			}
		}
	}

	public function product_button( $product ) {
		global $qlwapp;

		$obj = get_queried_object();

		$product = wc_get_product( $obj->ID );

		if ( is_file( $file = apply_filters( 'qlwapp_box_template', QLWAPP_PLUGIN_DIR . 'templates/box.php' ) ) ) {

			$models_box         = Models_Box::instance();
			$models_contacts    = Models_Contacts::instance();
			$models_woocommerce = Models_WooCommerce::instance();
			$models_display     = Models_Display::instance();
			$entity_visibility  = Entity_Visibility::instance();

			$contacts = $models_contacts->get_contacts_reorder();
			$display  = $models_display->get();
			$button   = $models_woocommerce->get();
			$box      = $models_box->get();
			include_once $file;
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
