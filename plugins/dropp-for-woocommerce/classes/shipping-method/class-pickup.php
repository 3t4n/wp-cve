<?php
/**
 * Pickup
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Shipping_Method;

use Dropp\API;

/**
 * Pickup
 */
class Pickup extends Shipping_Method {

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'dropp_pickup';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'Pick-up at warehouse (Vatnagarðar 22)', 'dropp-for-woocommerce' );
		$this->default_title      = __( 'Pick-up at warehouse (Vatnagarðar 22)', 'dropp-for-woocommerce' );
		$this->method_description = __( 'Packages are delivered to the warehouse at Vatnagarðar 22 for pickup', 'dropp-for-woocommerce' );

		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);

		$this->init();
	}
}
