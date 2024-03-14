<?php

namespace WC_BPost_Shipping\Assets;

/**
 * Class WC_BPost_Shipping_Assets_Resources handle resources to load and their management
 * @package WC_BPost_Shipping\Assets
 */
class WC_BPost_Shipping_Assets_Resources {

	/**
	 * load scripts for checkout page
	 *
	 * @param string[] $after_checkout
	 */
	public function get_checkout_page( $after_checkout ) {
		$this->enqueue_bpost_shm_js();
		add_thickbox();
		wp_register_script(
			'bpost-shm-checkout',
			BPOST_PLUGIN_URL . 'public/js/checkout.min.js',
			array(
				'thickbox',
				'wc-checkout',
			),
			BPOST_PLUGIN_VERSION
		);
		wp_localize_script( 'bpost-shm-checkout', 'bpost_after_checkout', $after_checkout );
		wp_enqueue_script( 'bpost-shm-checkout' );
		wp_enqueue_script( 'bpost-shm-app', BPOST_PLUGIN_URL . 'public/js/app.min.js', array(), BPOST_PLUGIN_VERSION );
	}

	/**
	 * load scripts for order received page
	 */
	public function get_order_receive_page( $order_received ) {
		wp_enqueue_script( 'bpost-shm-app', BPOST_PLUGIN_URL . 'public/js/app.min.js', array(), BPOST_PLUGIN_VERSION );
		wp_enqueue_style( 'checkout_css_file', BPOST_PLUGIN_URL . 'public/css/checkout.min.css', array(), BPOST_PLUGIN_VERSION );
		wp_register_script( 'bpost-order-received', BPOST_PLUGIN_URL . 'public/js/order-received.min.js', array(), BPOST_PLUGIN_VERSION );
		wp_localize_script( 'bpost-order-received', 'bpost_order_received', $order_received );
		wp_enqueue_script( 'bpost-order-received' );
	}

	/**
	 * load scripts for bpost admin page
	 */
	public function get_admin_bpost_page() {
		wp_enqueue_style( 'font_awesome', BPOST_PLUGIN_URL . 'public/css/font-awesome.min.css', array(), BPOST_PLUGIN_VERSION );
		wp_enqueue_style( 'admin_css_file', BPOST_PLUGIN_URL . 'public/css/admin.min.css', array( 'font_awesome' ), BPOST_PLUGIN_VERSION );
	}

	/**
	 * load scripts for order received page
	 *
	 * @param string[] $order_data
	 */
	public function get_admin_order_edit_page( array $order_data ) {
		wp_enqueue_script( 'bpost-shm-app', BPOST_PLUGIN_URL . 'public/js/app.min.js', array(), BPOST_PLUGIN_VERSION );

		wp_register_script( 'bpost-admin-order-edit', BPOST_PLUGIN_URL . 'public/js/admin.min.js', array(), BPOST_PLUGIN_VERSION );
		wp_localize_script( 'bpost-admin-order-edit', 'bpost_order_data', $order_data );
		wp_enqueue_script( 'bpost-admin-order-edit' );
	}

	/**
	 * load scripts for bpost callback
	 *
	 * @param string[] $shm_data
	 */
	public function get_callback_page( $shm_data ) {
		header( 'Content-Type: text/html', true ); // force the content-type. On some cases, the content-type was text/xml
		wp_enqueue_script( 'bpost-shm-app', BPOST_PLUGIN_URL . 'public/js/app.min.js', array(), BPOST_PLUGIN_VERSION );

		add_thickbox();
		wp_enqueue_script(
			'bpost-thickbox-override',
			BPOST_PLUGIN_URL . 'public/js/checkout.min.js',
			array(
				'thickbox',
				'bpost-shm-app',
			),
			BPOST_PLUGIN_VERSION
		);
		$this->enqueue_bpost_shm_js();

		wp_register_script(
			'shm-close',
			BPOST_PLUGIN_URL . 'public/js/shm-close.js',
			array( 'bpost-thickbox-override' ),
			BPOST_PLUGIN_VERSION
		);
		wp_localize_script( 'shm-close', 'shm_data', $shm_data );
		wp_enqueue_script( 'shm-close' );

		wp_print_scripts();
	}

	private function enqueue_bpost_shm_js() {
		wp_enqueue_script( 'bpost-shm', 'https://shippingmanager.bpost.be/ShmFrontEnd/shm.js', array( 'jquery' ), BPOST_PLUGIN_VERSION );
	}
}
