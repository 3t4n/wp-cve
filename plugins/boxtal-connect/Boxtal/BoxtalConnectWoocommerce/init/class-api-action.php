<?php
/**
 * Contains code for the environment check class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Init
 */

namespace Boxtal\BoxtalConnectWoocommerce\Init;

use Boxtal\BoxtalConnectWoocommerce\Util\Order_Util;
use Boxtal\BoxtalConnectWoocommerce\Order\Controller;

/**
 * Api_Action class.
 *
 * Init parcelpoints and tracking hooks.
 */
class Api_Action {

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
	 * @param Plugin $plugin plugin array.
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
		add_action( 'boxtal_connect_get_parcelpoint', array( $this, 'get_order_parcelpoint' ) );
		add_action( 'boxtal_connect_print_parcelpoint', array( $this, 'print_order_parcelpoint' ) );
		add_action( 'boxtal_connect_get_tracking', array( $this, 'get_tracking' ) );
		add_action( 'boxtal_connect_print_tracking_number', array( $this, 'print_tracking_number' ) );
	}

	/**
	 * Order parcelpoint.
	 *
	 * @param array $order plugin array.
	 */
	public function get_order_parcelpoint( $order ) {
		return Order_Util::get_parcelpoint( $order );
	}

	/**
	 * Order parcelpoint with HTML.
	 *
	 * @param array $order plugin array.
	 */
	public function print_order_parcelpoint( $order ) {
		$parcelpoint = Order_Util::get_parcelpoint( $order );
		if ( $parcelpoint ) {
			include_once dirname( __DIR__ ) . '/assets/views/html-order-parcelpoint.php';
		}
	}

	/**
	 * Order tracking information.
	 *
	 * @param array $order plugin array.
	 */
	public function get_tracking( $order ) {
		$controller = new Controller(
			array(
				'url'     => null,
				'version' => null,
			)
		);
		$tracking   = $controller->get_order_tracking( Order_Util::get_id( $order ) );
		if ( null !== $tracking && property_exists( $tracking, 'shipmentsTracking' ) && ! empty( $tracking->shipmentsTracking ) ) {
			return $tracking;
		}
	}


	/**
	 * Order tracking number.
	 *
	 * @param array $order plugin array.
	 */
	public function print_tracking_number( $order ) {
		$controller = new Controller(
			array(
				'url'     => null,
				'version' => null,
			)
		);
		$tracking   = $controller->get_order_tracking( Order_Util::get_id( $order ) );
		if ( null !== $tracking && property_exists( $tracking, 'shipmentsTracking' ) && ! empty( $tracking->shipmentsTracking ) ) {
			include_once dirname( __DIR__ ) . '/assets/views/html-order-tracking.php';

		}
	}

}
