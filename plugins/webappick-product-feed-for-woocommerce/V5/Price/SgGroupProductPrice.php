<?php

namespace CTXFeed\V5\Price;
use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Utility\Config;
use WC_Product;

class SgGroupProductPrice implements PriceInterface {

	private $product;
	private $config;

	/**
	 * @param WC_Product $product
	 * @param Config     $config
	 */
	public function __construct( $product, $config ) {

		$this->product = $product;
		$this->config  = $config;
	}

	/**
	 * Get Grouped Product Price.
	 *
	 * @param $price_type
	 * @param $tax
	 *
	 * @return int|string
	 */
	protected function get_sg_group_product_price( $price_type, $tax = false ) {
		$groupProductIds = get_post_meta($this->product->get_id(), 'woosg_ids', true);
		$price           = 0;
		if ( ! empty( $groupProductIds ) ) {
			foreach ( $groupProductIds as $id ) {
				$child_product = wc_get_product( $id['id'] );
				if ( ! is_object( $child_product ) ) {
					continue; // make sure that the product exists.
				}
				switch ( $price_type ) {
					case 'regular_price':
						$get_price = $child_product->get_regular_price();
						break;
					case 'sale_price':
						$get_price = $child_product->get_sale_price();
						break;
					default:
						$get_price = $child_product->get_price();
						break;
				}

				$get_price = (float)$get_price * (float)$id['qty'];
				$get_price = $this->convert_currency( $get_price, $price_type );
				$get_price = $this->add_tax( $get_price, $tax );
				if ( ! empty( $get_price ) ) {
					$price += $get_price;
				}
			}
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get Regular Price.
	 *
	 * @param bool $tax
	 *
	 * @return int|string
	 */
	public function regular_price( $tax = false ) {
		return $this->get_sg_group_product_price( 'regular_price', $tax );
	}

	/**
	 * Get Price.
	 *
	 * @param bool $tax
	 *
	 * @return int|string
	 */
	public function price( $tax = false ) {
		return $this->get_sg_group_product_price( 'price', $tax );
	}

	/**
	 * Get Sale Price.
	 *
	 * @param bool $tax
	 *
	 * @return int|string
	 */
	public function sale_price( $tax = false ) {
		return $this->get_sg_group_product_price( 'sale_price', $tax );
	}

	/**
	 * Convert Currency.
	 *
	 * @param $price
	 * @param string $price_type price type (regular_price|price|sale_price)
	 *
	 * @return mixed|void
	 */
	public function convert_currency( $price, $price_type ) {

		return apply_filters( 'woo_feed_wcml_price',
			$price, $this->product->get_id(), $this->config->get_feed_currency(), '_' . $price_type
		);
	}

	/**
	 * Get Price with Tax.
	 *
	 * @return int
	 */
	public function add_tax( $price, $tax = false ) {
		if ( true === $tax ) {
			return ProductHelper::get_price_with_tax( $price, $this->product );
		}

		return $price;
	}
}
