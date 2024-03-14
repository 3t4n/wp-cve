<?php
/**
 * Simple Product price.
 *
 * @package CTXFeed\V5\Price
 */

namespace CTXFeed\V5\Price;

/**
 * Simple Product price.
 *
 * @package CTXFeed\V5\Price
 */
class SimpleProductPrice implements PriceInterface {

	/**
	 * @var \WC_Product $product WC Product.
	 */
	private $product;

	/**
	 * @var \CTXFeed\V5\Utility\Config $config Config.
	 */
	private $config;

	/**
	 * @param \WC_Product                $product WC Product.
	 * @param \CTXFeed\V5\Utility\Config $config  Config.
	 */
	public function __construct( $product, $config ) {
		$this->product = $product;
		$this->config  = $config;
	}

	/**
	 * Get Regular Price.
	 *
	 * @return string
	 */
	public function regular_price() {
		return $this->product->get_regular_price();
	}

	/**
	 * Get Price.
	 *
	 * @return string
	 */
	public function price() {
		return $this->product->get_price();
	}

	/**
	 * Get Sale Price.
     *
	 * @return string
	 */
	public function sale_price() {
		return $this->product->get_sale_price();
	}

}
