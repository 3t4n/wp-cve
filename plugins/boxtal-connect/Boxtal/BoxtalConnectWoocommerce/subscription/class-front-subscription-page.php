<?php
/**
 * Contains code for the front subscription page class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Subscription
 */

namespace Boxtal\BoxtalConnectWoocommerce\Subscription;

use Boxtal\BoxtalConnectWoocommerce\Util\Subscription_Util;


/**
 * Front_Subscription_Page class.
 *
 * Adds additional info to subscription page.
 */
class Front_Subscription_Page {

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
		add_filter( 'woocommerce_subscription_details_after_subscription_table', array( $this, 'add_parcelpoint_to_front_subscription_page' ), 10, 2 );
	}

	/**
	 * Add parcel point info to front subscription page.
	 *
	 * @param \WC_Subscription $subscription woocommerce subscription.
	 * @void
	 */
	public function add_parcelpoint_to_front_subscription_page( $subscription ) {
		$parcelpoint = Subscription_Util::get_parcelpoint( $subscription );

		if ( null !== $parcelpoint ) {
			$has_address = null !== $parcelpoint->name
				&& null !== $parcelpoint->address
				&& null !== $parcelpoint->zipcode
				&& null !== $parcelpoint->city
				&& null !== $parcelpoint->country;

			if ( $has_address ) {
				include realpath( plugin_dir_path( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'html-front-subscription-parcelpoint.php';
			}
		}
	}
}
