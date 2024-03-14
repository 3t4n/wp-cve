<?php
/**
 * Dropp
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Shipping_Method;

/**
 * Shipping method
 */
class Dropp extends Shipping_Method {

	/**
	 * Code (used for price information)
	 *
	 * @var ?string
	 */
	protected ?string $code = 'droppcapital';

	/**
	 * Original title
	 *
	 * @var string
	 */
	protected string $original_title = '';

	/**
	 * Price Type
	 *
	 * @var integer Either 1 or 2. One being inside capital area, and 2 outside.
	 */
	protected static int $price_type = 1;

	/**
	 * No address available
	 *
	 * @var boolean Available when no address is provided
	 */
	protected static bool $no_address_available = true;

	/**
	 * Validate postcode
	 *
	 * @param string $postcode     Postcode.
	 * @param string $capital_area (optional) One of 'inside', 'outside', '!inside' or 'both'.
	 *
	 * @return boolean Valid post code.
	 */
	public function validate_postcode( string $postcode, string $capital_area = 'inside' ): bool {
		if ( is_admin() || ! WC()->session ) {
			return true;
		}
		if (static::$price_type === 1 && 0 === $this->get_pricetype()) {
			// Dropp::$price_type is 1 (Dropp inside capital area) and price type from location is 0
			return true;
		}
		return static::$price_type === $this->get_pricetype();
	}

	/**
	 * Calculate the shipping costs.
	 *
	 * @param array $package Package of items from cart.
	 */
	public function calculate_shipping( $package = array() ): void {
		$location_data = WC()->session->get( 'dropp_session_location' );
		if ( self::get_instance()->location_name_in_label && ! empty( $location_data['name'] ) ) {
			if ( ! $this->original_title ) {
				$this->original_title = $this->title;
			}
			$this->title = $this->original_title . ' - ' . $location_data['name'];
		}
		parent::calculate_shipping( $package );
	}

	/**
	 * Get instance of \Dropp\Shipping_Method\Dropp
	 *
	 * @return Dropp
	 */
	public static function get_instance(): Dropp {
		static $instance = false;
		if (! $instance) {
			if (class_exists('WC_Shipping')) {
				$shipping_methods = \WC_Shipping::instance()->get_shipping_methods();
				$instance = $shipping_methods['dropp_is'] ?? null;
			}
			$instance = $instance ?: new self;
		}
		return $instance;
	}
}
