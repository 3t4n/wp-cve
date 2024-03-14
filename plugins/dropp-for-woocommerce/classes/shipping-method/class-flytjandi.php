<?php
/**
 * Flytjandi
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Shipping_Method;

use Dropp\API;

/**
 * Flytjandi
 */
class Flytjandi extends Home_Delivery {

	/**
	 * Code (used for price information)
	 *
	 * @var ?string
	 */
	protected ?string $code = 'otherlocations';

	/**
	 * Weight Limit in KG
	 * Flytjandi supports unlimited weight
	 *
	 * @var int
	 */
	public int $weight_limit = 0;

	/**
	 * Capital Area
	 *
	 * @var string One of 'inside', 'outside', '!inside' or 'both'
	 */
	protected static string $capital_area = '!inside';

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
		$this->id                 = 'dropp_flytjandi';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'Dropp - Other pickup locations', 'dropp-for-woocommerce' );
		$this->default_title      = __( 'Dropp - Other pickup locations', 'dropp-for-woocommerce' );
		$this->method_description = __( 'Deliver parcels at delivery locations in Iceland', 'dropp-for-woocommerce' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);

		$this->init();
	}
}
