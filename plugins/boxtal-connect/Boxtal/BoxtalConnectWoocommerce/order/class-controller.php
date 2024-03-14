<?php
/**
 * Contains code for the order controller class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Order
 */

namespace Boxtal\BoxtalConnectWoocommerce\Order;

use Boxtal\BoxtalPhp\ApiClient;
use Boxtal\BoxtalConnectWoocommerce\Util\Auth_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Controller class.
 *
 * Handles additional info hooks and functions.
 */
class Controller {

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
	}

	/**
	 * Get order tracking.
	 *
	 * @param string $order_id \WC_Order id.
	 * @return object tracking
	 */
	public function get_order_tracking( $order_id ) {
		$lib      = new ApiClient( Auth_Util::get_access_key(), Auth_Util::get_secret_key() );
		$response = $lib->getOrder( $order_id );
		if ( $response->isError() ) {
			return null;
		}
		return $response->response;
	}

	/**
	 * Enqueue tracking styles
	 *
	 * @void
	 */
	public function tracking_styles() {
		wp_enqueue_style( Branding::$branding_short . '_tracking', $this->plugin_url . 'Boxtal/BoxtalConnectWoocommerce/assets/css/tracking.css', array(), $this->plugin_version );
	}
}
