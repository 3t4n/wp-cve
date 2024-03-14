<?php
/**
 * Home Delivery
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Shipping_Method;

use Dropp\API;

/**
 * Home Delivery
 */
class Dropp_Outside_Capital_Area extends Dropp {

	/**
	 * Code (used for price information)
	 *
	 * @var ?string
	 */
	protected ?string $code = 'droppoutside';

	/**
	 * Price Type
	 *
	 * @var integer Either 1 or 2. One being inside capital area, and 2 outside.
	 */
	protected static int $price_type = 2;

	/**
	 * No address available
	 *
	 * @var boolean Available when no address is provided
	 */
	protected static bool $no_address_available = false;

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'dropp_is_oca';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'Dropp Outside Capital Area', 'dropp-for-woocommerce' );
		$this->default_title      = __( 'Dropp - Pick-up at location', 'dropp-for-woocommerce' );
		$this->method_description = __( 'Deliver parcels at delivery locations in Iceland', 'dropp-for-woocommerce' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);

		$this->init();
	}
}
