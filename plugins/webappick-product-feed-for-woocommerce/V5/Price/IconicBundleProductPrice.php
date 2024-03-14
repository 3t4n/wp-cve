<?php
namespace CTXFeed\V5\Price;
use CTXFeed\V5\Utility\Config;
use WC_Product;
use WC_Product_Bundled;

class IconicBundleProductPrice implements PriceInterface {

	private $product;
	private $config;
	private $bundle;

	/**
	 * @param WC_Product $product
	 * @param Config     $config
	 */
	public function __construct( $product, $config ) {

		$this->product = $product;
		$this->config  = $config;

		if ( class_exists( 'WC_Product_Bundled' ) ) {
			$this->bundle = new WC_Product_Bundled( $this->product->get_id() );
		}
	}

	/**
	 * Get Bundle Product Price.
	 *
	 * @param bool $tax
	 *
	 * @return int|string
	 */
	public function bundle_price( $price_type = 'price', $tax = false ) {

		if ( ! class_exists( 'WC_Product_Bundled' ) ) {
			return $this->product->get_price();
		}

		$price = $this->product->get_price();

		$price_display = ( ! is_null( $this->bundle->options['price_display'] ) ) ? $this->bundle->options['price_display'] : '';
		$product_ids   = $this->bundle->options['product_ids'];

		//Set discount
		$discount = 0;
		if ( ! empty( $this->bundle->options['fixed_discount'] ) ) {
			$discount = $this->bundle->options['fixed_discount'];
		}

		// Get price
		if ( is_array( $product_ids ) ) {
			$prices = [];
			foreach ( $product_ids as $pid ) {
				$product = wc_get_product( $pid );
				switch ( $price_type ) {
					case 'regular_price':
						$prices[] = $product->get_regular_price();
						break;
					case 'sale_price':
						$prices[] = $product->get_sale_price();
						break;
					default:
						$prices[] = $product->get_price();
						break;
				}
			}

			if ( 'range' === $price_display ) {
				$price = min( $prices );
			} else {
				$price = array_sum( $prices );
			}
		}

		// Get sale price if discount enabled
		if ( $discount && 'regular_price' !== $price_type ) {
			$price -= $discount;
		}

		$price = $this->convert_currency( $price, $price_type );
		$price = $this->add_tax( $price, false );

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
		return $this->bundle_price( 'regular_price', $tax );
	}

	/**
	 * Get Price.
	 *
	 * @param bool $tax
	 *
	 * @return int|string
	 */
	public function price( $tax = false ) {
		return $this->bundle_price( 'price', $tax );
	}

	/**
	 * Get Sale Price.
	 *
	 * @param bool $tax
	 *
	 * @return int|string
	 */
	public function sale_price( $tax = false ) {
		return $this->bundle_price( 'sale_price', $tax );
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
