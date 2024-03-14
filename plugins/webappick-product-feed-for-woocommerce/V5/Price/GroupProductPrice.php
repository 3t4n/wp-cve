<?php
/**
 * Group Product price.
 *
 * @package CTXFeed\V5\Price
 */

namespace CTXFeed\V5\Price;

/**
 * Group Product price.
 *
 * @package CTXFeed\V5\Price
 */
class GroupProductPrice implements PriceInterface {

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
	 * @return int|string
	 */
	public function regular_price() {
		return $this->get_group_product_price( 'regular_price' );
	}

	/**
	 * Get Price.
	 *
	 * @return int|string
	 */
	public function price() {
		return $this->get_group_product_price( 'price' );
	}

	/**
	 * Get Sale Price.
	 *
	 * @return int|string
	 */
	public function sale_price() {
		return $this->get_group_product_price( 'sale_price' );
	}

	/**
	 * Get Grouped Product Price.
	 *
	 * @param string $price_type price type (regular_price|price|sale_price).
     * @return float
	 */
	protected function get_group_product_price( $price_type = 'price' ) {// phpcs:ignore
		$group_product_ids = $this->product->get_children();
		$price             = 0;

		if ( ! empty( $group_product_ids ) ) {
			foreach ( $group_product_ids as $id ) {
				$product = wc_get_product( $id );

				if ( ! is_object( $product ) ) {
					continue; // make sure that the product exists.
				}

				switch ( $price_type ) {
					case 'regular_price':
						$get_price = $product->get_regular_price();

						break;

					case 'sale_price':
						$get_price = $product->get_sale_price();

						break;

					default:
						$get_price = $product->get_price();

						break;
				}

				if ( empty( $get_price ) ) {
					continue;
				}

				$get_price = (float) $get_price;
				$price    += $get_price;
			}
		}

		if ( $price === 0 ) {
			$price = '';
		}

		return $price;
	}

}
