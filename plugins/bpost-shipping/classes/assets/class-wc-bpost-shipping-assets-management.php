<?php

namespace WC_BPost_Shipping\Assets;

/**
 * Class WC_BPost_Shipping_Assets_Management is mistress class to handle assets
 * @package WC_BPost_Shipping\Assets
 */

/**
 * Class WC_BPost_Shipping_Assets_Management handles requirements for assets
 * @package WC_BPost_Shipping\Assets
 */
class WC_BPost_Shipping_Assets_Management {
	/** @var WC_BPost_Shipping_Assets_Detector */
	private $detector;
	/** @var WC_BPost_Shipping_Assets_Resources */
	private $resources;

	/**
	 * WC_BPost_Shipping_Assets_Management constructor.
	 *
	 * @param WC_BPost_Shipping_Assets_Detector $detector
	 * @param WC_BPost_Shipping_Assets_Resources $resources
	 */
	public function __construct(
		WC_BPost_Shipping_Assets_Detector $detector,
		WC_BPost_Shipping_Assets_Resources $resources
	) {
		$this->detector  = $detector;
		$this->resources = $resources;
	}

	/**
	 * Called by wp enqueue script and filter displaying according to requirements
	 */
	public function wp_enqueue_script() {
		if ( ! $this->detector->is_checkout_page() ) {
			return;
		}

		$this->resources->get_checkout_page(
			array(
				'shipping_method' => BPOST_PLUGIN_ID,
				'url'             => WC()->api_request_url( 'shm-loader' ),
			)
		);
	}

	/**
	 * Called by admin enqueue script and filter displaying according to requirements
	 */
	public function admin_enqueue_script() {
		if ( $this->detector->is_admin_bpost_page() ) {
			$this->resources->get_admin_bpost_page();

			return;
		}
	}

	/**
	 * Get resource directly needed by a Controller
	 *
	 * @param string[] $shm_data
	 */
	public function callback_page( $shm_data ) {
		$this->resources->get_callback_page( $shm_data );
	}

	/**
	 * Get resource directly needed by a Controller
	 *
	 * @param string[] $order_receive_page
	 */
	public function order_receive_page( $order_receive_page ) {
		if ( $this->detector->is_checkout_order_receive_page() ) {
			$this->resources->get_order_receive_page( $order_receive_page );

			return;
		}
	}

	/**
	 * Get resource directly needed by a Controller
	 */
	public function admin_order_edit_page( array $js_data ) {
		if ( $this->detector->is_admin_order_edit_page() ) {

			$this->resources->get_admin_order_edit_page( $js_data );

			return;
		}
	}
}
