<?php
/**
 * Contains code for the front order page class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Order
 */

namespace Boxtal\BoxtalConnectWoocommerce\Order;

use Boxtal\BoxtalConnectWoocommerce\Util\Order_Util;


/**
 * Front_Order_Page class.
 *
 * Adds additional info to order page.
 */
class Front_Order_Page {

	/**
	 * Plugin url.
	 *
	 * @var string
	 */
	private $plugin_url;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Construct function.
	 *
	 * @param array $plugin plugin array.
	 * @void
	 */
	public function __construct( $plugin ) {
		$this->plugin_url     = $plugin['url'];
		$this->plugin_version = $plugin['version'];
	}

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_filter( 'woocommerce_order_details_after_order_table', array( $this, 'add_tracking_to_front_order_page' ), 10, 2 );
		add_filter( 'woocommerce_order_details_after_order_table', array( $this, 'add_parcelpoint_to_front_order_page' ), 10, 2 );
	}

	/**
	 * Add tracking info to front order page.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @void
	 */
	public function add_tracking_to_front_order_page( $order ) {
		$controller = new Controller(
			array(
				'url'     => $this->plugin_url,
				'version' => $this->plugin_version,
			)
		);
		$controller->tracking_styles();
		$tracking = $controller->get_order_tracking( Order_Util::get_id( $order ) );

		if ( null !== $tracking && property_exists( $tracking, 'shipmentsTracking' ) && ! empty( $tracking->shipmentsTracking ) ) {
			include realpath( plugin_dir_path( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'html-front-order-tracking.php';
		}
	}

	/**
	 * Add parcel point info to front order page.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @void
	 */
	public function add_parcelpoint_to_front_order_page( $order ) {
		$parcelpoint = Order_Util::get_parcelpoint( $order );

		if ( null !== $parcelpoint ) {
			$has_address = null !== $parcelpoint->name
				&& null !== $parcelpoint->address
				&& null !== $parcelpoint->zipcode
				&& null !== $parcelpoint->city
				&& null !== $parcelpoint->country;

			if ( $has_address ) {
				include realpath( plugin_dir_path( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'html-front-order-parcelpoint.php';
			}
		}
	}
}
