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
class Home_Delivery extends Shipping_Method {

	/**
	 * Code (used for price information)
	 *
	 * @var ?string
	 */
	protected ?string $code = 'homecapital';

	/**
	 * Weight Limit in KG
	 *
	 * @var int
	 */
	public int $weight_limit = 350;

	/**
	 * Capital Area
	 *
	 * @var string One of 'inside', 'outside', '!inside' or 'both'
	 */
	protected static string $capital_area = 'inside';

	/**
	 * No address available
	 *
	 * @var boolean Available when no address is provided
	 */
	protected static bool $no_address_available = true;


	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'dropp_home';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'Dropp Home Delivery', 'dropp-for-woocommerce' );
		$this->default_title      = __( 'Dropp - Home Delivery (17:00 - 22:00)', 'dropp-for-woocommerce' );
		$this->method_description = __( 'Home delivery in Iceland between 17:00-22:00', 'dropp-for-woocommerce' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);

		$this->init();
	}
}
