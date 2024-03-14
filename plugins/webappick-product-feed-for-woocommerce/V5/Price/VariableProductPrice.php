<?php

namespace CTXFeed\V5\Price;

class VariableProductPrice implements PriceInterface {

	/**
	 * @var \WC_Product_Variable $product WC Product.
	 */
	private $product;

	/**
	 * @var \CTXFeed\V5\Utility\Config $config Config.
	 */
	private $config;

	/**
	 * VariableProductPrice constructor.
	 *
	 * @param \WC_Product_Variable       $product WC Product.
	 * @param \CTXFeed\V5\Utility\Config $config  Config.
	 */
	public function __construct( $product, $config ) {
		$this->product = $product;
		$this->config  = $config;
	}

	/**
	 * Get Regular Price.
	 *
	 * @return float|int
	 */
	public function regular_price() {
		return $this->variation_price_by_type( 'regular_price' );
	}

	/**
	 * Get Price.
	 *
	 * @return float
	 */
	public function price() {
		return $this->variation_price_by_type( 'price' );
	}

	/**
	 * Get Sale Price.
	 *
	 * @return float|int
	 */
	public function sale_price() {
		return $this->variation_price_by_type( 'sale_price' );
	}

	/**
	 * Get First Variation Price.
	 *
	 * @param string $price_type Price Type (regular_price|price|sale_price).
     * @return int
	 */
	private function variation_price_by_type( $price_type = 'price' ) {
		$price         = '';
		$min_max_first = $this->config->variable_price;
		$prices        = $this->product->get_variation_prices( true );

		if ( empty( $prices[ $price_type ] ) ) {
			return $price;
		}

		$prices_by_type = $prices[ $price_type ];
		$prices_by_type = array_values( $prices_by_type );

		if ( $min_max_first === 'min' ) {
			return min( $prices_by_type );
		}

		if ( $min_max_first === 'max' ) {
			return max( $prices_by_type );
		}

		$prices_by_type = array_values( $prices_by_type );

		return $prices_by_type[0];
	}

}
