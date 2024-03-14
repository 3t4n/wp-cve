<?php
/**
 * Home Delivery
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Shipping_Method;

use Dropp\API;

/**
 * Daytime Delivery
 */
class Daytime_Delivery extends Home_Delivery {

	/**
	 * Code (used for price information)
	 *
	 * @var ?string
	 */
	protected ?string $code = 'dayhomecapital';

	/**
	 * No address available
	 *
	 * @var boolean Available when no address is provided
	 */
	protected static bool $no_address_available = false;

	/**
	 * Daytime delivery true or false
	 *
	 * @var boolean
	 */
	public bool $day_delivery = true;

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance.
	 */
	public function __construct( int $instance_id = 0 ) {
		$this->id                 = 'dropp_daytime';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'Dropp Daytime Delivery', 'dropp-for-woocommerce' );
		$this->default_title      = __( 'Dropp - Home Delivery (10:00 - 16:00)', 'dropp-for-woocommerce' );
		$this->method_description = __( 'Home delivery in Iceland between 10:00-16:00', 'dropp-for-woocommerce' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		$this->init();
	}
}
