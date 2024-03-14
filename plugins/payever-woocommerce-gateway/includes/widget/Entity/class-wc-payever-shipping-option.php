<?php
/**
 * The shipping option object.
 */
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Widget_Shipping_Option' ) ) {
	return;
}

/**
 * Class WC_Payever_Widget_Shipping_Option
 */
class WC_Payever_Widget_Shipping_Option {

	/**
	 * The name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The carrier.
	 *
	 * @var string
	 */
	protected $carrier;

	/**
	 * The category.
	 *
	 * @var string
	 */
	protected $category;

	/**
	 * The price.
	 *
	 * @var float
	 */
	protected $price;

	/**
	 * The tax rate.
	 *
	 * @var float
	 */
	protected $taxRate;

	/**
	 * The tax amount.
	 *
	 * @var float
	 */
	protected $taxAmount;

	/**
	 * @param string $name
	 * @param float|int $price
	 * @param string|null $carrier
	 * @param string|null $category
	 * @param float|null $taxRate
	 * @param float|null $taxAmount
	 */
	public function __construct(
		$name,
		$price = 0,
		$carrier = null,
		$category = null,
		$taxRate = null,
		$taxAmount = null
	) {
		$this->name      = $name;
		$this->price     = $price;
		$this->carrier   = $carrier;
		$this->category  = $category;
		$this->taxRate   = $taxRate;
		$this->taxAmount = $taxAmount;
	}

	/**
	 * Returns shipping amount
	 *
	 * @return float|int
	 */
	public function price() {
		return $this->price;
	}

	/**
	 * Converts the object of this class to array
	 *
	 * @return array
	 */
	public function toArray() {
		$result = array();

		$object = get_object_vars( $this );

		foreach ( $object as $property => $value ) {
			if ( $value ) {
				$result[ $property ] = $value;
			}
		}

		return $result;
	}

	/**
	 * Converts the object of this class to json string
	 *
	 * @return false|string
	 */
	public function toString() {
		return wp_json_encode( $this->toArray() );
	}
}
