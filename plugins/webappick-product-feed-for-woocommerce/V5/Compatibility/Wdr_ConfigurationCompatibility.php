<?php
/**
 * Compatibility class for Wdr_ConfigurationCompatibility plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Utility\Cache;
use Wdr\App\Controllers\Configuration;

/**
 * Class Wdr_ConfigurationCompatibility
 *
 * Discount rules compatibility by flycart.
 *
 * @package CTXFeed\V5\Compatibility
 */
class Wdr_ConfigurationCompatibility {
	protected $wdr_config_rules;

	/**
	 * Wdr_ConfigurationCompatibility Constructor.
	 */
	public function __construct() {

		// Get price with discount.
//		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'get_discounted_price' ), 999, 5 );
		add_filter( 'woo_feed_filter_product_price', array( $this, 'get_discounted_price' ), 999, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'get_discounted_price' ), 999, 5 );
//		add_filter( 'woo_feed_filter_product_regular_price_with_tax', array( $this, 'get_discounted_price' ), 999, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'get_discounted_price' ), 999, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'get_discounted_price' ), 999, 5 );
	}


	/**
	 * Get Discounted Price
	 *
	 * @param int $price product price.
	 * @param \WC_Product $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool $with_tax price with tax or without tax.
	 * @param string $price_type price type regular_price, price, sale_price.
	 *
	 * @return int
	 */
	public function get_discounted_price( $price, $product, $config, $with_tax, $price_type ) { //phpcs:ignore

        // In free version we do not support wpml plugin features that's way we make else condition which working only for flycart plugin.
        if( Helper::is_pro() ) {
            if ( class_exists('Wdr\App\Controllers\Configuration' ) && !class_exists('woocommerce_wpml' ) ) {
                if ( !$this->wdr_config_rules ) {
                    $this->wdr_config_rules = new \Wdr\App\Controllers\ManageDiscount();
                    $wdr_config_rules = $this->wdr_config_rules;
                } else {
                    $wdr_config_rules = $this->wdr_config_rules;
                }
                $prices = $wdr_config_rules::calculateInitialAndDiscountedPrice( $product, 1, false, false );

                if (is_bool($prices)) {
                    return $price;
                }

                if ('regular_price' === $price_type) {
                    $price = $prices['initial_price'];

                    if ($with_tax) {
                        $price = $prices['initial_price_with_tax'];
                    }
                } else {
                    $price = $prices['discounted_price'];

                    if ($with_tax) {
                        $price = $prices['discounted_price_with_tax'];
                    }
                }
            } else {
                $price = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $product, 1, $price, 'discounted_price', true, true);
            }
        } else{
            if (class_exists('Wdr\App\Controllers\Configuration') ) {
                if ( !$this->wdr_config_rules ) {
                    $this->wdr_config_rules = new \Wdr\App\Controllers\ManageDiscount();
                    $wdr_config_rules = $this->wdr_config_rules;
                } else {
                    $wdr_config_rules = $this->wdr_config_rules;
                }
                $prices = $wdr_config_rules::calculateInitialAndDiscountedPrice( $product, 1, false, false );

                if ( is_bool( $prices ) ) {
                    return $price;
                }

                if ( 'regular_price' === $price_type ) {
                    $price = $prices['initial_price'];

                    if ( $with_tax ) {
                        $price = $prices['initial_price_with_tax'];
                    }
                } else {
                    $price = $prices['discounted_price'];

                    if ( $with_tax ) {
                        $price = $prices['discounted_price_with_tax'];
                    }
                }
            }
        }

		return $price;
	}

}
