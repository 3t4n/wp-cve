<?php
/**
 * Product price.
 *
 * @package CTXFeed\V5\Price
 */

namespace CTXFeed\V5\Price;

/**
 * Class ProductPrice
 *
 * @package CTXFeed\V5\Price
 */
class ProductPrice {

	/**
	 * @var \CTXFeed\V5\Price\PriceInterface
	 */
	private $price;

	/**
	 * @var \WC_Product $product WC Product.
	 */
	private $product;

	/**
	 * ProductPrice constructor.
	 *
	 * @param \CTXFeed\V5\Price\PriceInterface $price Price.
	 */
	public function __construct( PriceInterface $price, $product ) {
		$this->price = $price;
		$this->product = $product;
	}

	/**
	 * Get regular price.
	 *
	 * @param bool $tax Tax.
     * @return float
	 */
	public function regular_price( $tax = false ) {
		$regular_price = $this->price->regular_price();

		if ( $regular_price <= 0 ) {
			return '';
		}

		// Add tax to price.
		if ( $tax ) {
			return wc_get_price_including_tax( $this->product, array( 'price' => $regular_price ) );
		}

		return $regular_price;
	}

	/**
	 * Get price.
	 *
	 * @param bool $tax Tax.
     * @return float
	 */
	public function price( $tax = false ) {
		$price = $this->price->price();

		if ( $price <= 0 ) {
			return '';
		}

		// Add tax to price.
		if ( $tax ) {
			return wc_get_price_including_tax( $this->product, array( 'price' => $price ) );
		}

		return $price;
	}

	/**
	 * Get sale price.
	 *
	 * @param bool $tax Tax.
     * @return float
	 */
	public function sale_price( $tax = false ) {
		$sale_price = $this->price->sale_price();

		if ( $sale_price <= 0 ) {
			return '';
		}

		// Add tax to sale price.
		if ( $tax ) {
			return wc_get_price_including_tax( $this->product, array( 'price' => $sale_price ) );
		}

		return $sale_price;
	}

}
