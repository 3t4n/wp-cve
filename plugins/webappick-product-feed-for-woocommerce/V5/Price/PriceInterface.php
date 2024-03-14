<?php

namespace CTXFeed\V5\Price;

/**
 * Interface PriceInterface
 *
 * @package CTXFeed\V5\Price
 */
interface PriceInterface {// phpcs:ignore

	/**
	 * PriceInterface constructor.
	 *
	 * @param \WC_Product                $product WC Product.
	 * @param \CTXFeed\V5\Utility\Config $config  Config.
	 */
	public function __construct( $product, $config );

	/**
	 * Get regular price.
	 *
     * @return float
	 */
	public function regular_price();

	/**
	 * Get price.
	 *
     * @return float
	 */
	public function price();

	/**
	 * Get sale price.
	 *
     * @return float
	 */
	public function sale_price();

}
