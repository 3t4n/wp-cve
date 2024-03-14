<?php
/**
 * YITH Multi Currency Switcher for WooCommerce compatibility.
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\ProductAddons
 */

! defined( 'YITH_WCMCS' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_WCMCS_Compatibility' ) ) {
	/**
	 * Compatibility Class
	 *
	 * @class   YITH_WAPO_WCMCS_Compatibility
	 * @since   3.4.0
	 */
	class YITH_WAPO_WCMCS_Compatibility {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WAPO_WCMCS_Compatibility
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WAPO_WCMCS_Compatibility
		 */
		public static function get_instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * YITH_WAPO_WCMCS_Compatibility constructor
		 */
		private function __construct() {

			add_filter( 'yith_wapo_get_addon_sale_price', array( $this, 'modify_addon_price' ), 10, 5 );
			add_filter( 'yith_wapo_get_addon_price', array( $this, 'modify_addon_price' ), 10, 5 );
			add_filter( 'yith_wapo_get_product_addon_price', array( $this, 'modify_addon_price' ), 10, 5 );
			add_filter( 'yith_wapo_convert_price', array( $this, 'modify_addon_price_for_product' ), 10, 5 );
			add_filter( 'yith_wapo_total_item_price', array( $this, 'modify_cart_item_price_with_addons' ), 10 );
			add_filter( 'yith_wapo_totals_price_args', array( $this, 'modify_totals_price_args' ), 10 );
            add_filter( 'yith_wcmcs_product_price', array( $this, 'filter_manual_price_with_addons' ), 10, 3 );

            // Request a Quote.
            add_filter( 'yith_wapo_ywraq_total_price', array( $this, 'modify_cart_item_price_with_addons' ), 10 );

        }

		/**
		 * Modify the current price depending on currency
		 *
		 * @param float   $price The current price.
		 * @param boolean $allow_modification Force to allow the convert of the price.
		 * @param string  $price_method The price method of the add-on option.
		 * @param string  $price_type The price type of the add-on option.
		 * @param YITH_WAPO_Addon $addon The add-on.
		 *
		 * @return float
		 */
		public function modify_addon_price( $price, $allow_modification = false, $price_method = 'free', $price_type = 'fixed', $addon = null ) {

            if ( $addon instanceof YITH_WAPO_Addon && 'product' === $addon->get_type() && isset( $_POST['action'] ) && 'live_print_blocks' === $_POST['action'] ) {
                $allow_modification = true;
            }

			if ( 'free' !== $price_method || $allow_modification ) {
				if ( 'percentage' !== $price_type || $allow_modification ) {
					if ( function_exists( 'yith_wcmcs_convert_price' ) ) {
                        $args  = apply_filters( 'yith_wapo_wcmcs_convert_price_args', array() );
						$price = yith_wcmcs_convert_price( $price, $args );
					}
				}
			}

			return $price;
		}

        /**
         * Modify the current price depending on currency
         *
         * @param float      $price The current price.
         * @param boolean    $allow_modification Force to allow the convert of the price.
         * @param WC_Product $product The product object.
         *
         * @return float
         */
        public function modify_addon_price_for_product( $price, $allow_modification = false, $product = null ) {

            if ( $allow_modification ) {
                $currency_id = yith_wcmcs_get_current_currency_id();
                if ( $currency_id && yith_wcmcs_get_wc_currency_options( 'currency' ) !== $currency_id && 'yes' === $product->get_meta( '_yith_wcmcs_custom_prices', true ) ) {
                    $prices = yith_wcmcs_get_product_prices( $product, $currency_id );
                    $time = time();
                    if ( '' !== $prices['regular'] ) {
                        $converted_price = ( '' === $prices['sale_from'] || ( $time > $prices['sale_from'] && $time < $prices['sale_to'] ) ) && '' !== $prices['sale'] && $prices['sale'] < $prices['regular'] ? $prices['sale'] : $prices['regular'];
                        $price = is_null( $converted_price ) ? yith_wcmcs_convert_price( $price ) : $converted_price;
                    }
                } else {
                    if ( function_exists( 'yith_wcmcs_convert_price' ) ) {
                        $args  = apply_filters( 'yith_wapo_wcmcs_convert_price_args', array() );
                        $price = yith_wcmcs_convert_price( $price, $args );
                    }
                }
            }

            return $price;
        }

		/**
		 * Modify the cart item price depending on currency
		 *
		 * @param float $price The current price.
		 *
		 * @return float
		 */
		public function modify_cart_item_price_with_addons( $price = 0 ) {

            if ( function_exists( 'yith_wcmcs_convert_price' ) ) {

                $default_currency_id = yith_wcmcs_get_wc_currency_options( 'currency' );
                $convert_args        = array(
                    'from' => yith_wcmcs_get_current_currency_id(),
                    'to'   => $default_currency_id
                );

                $price = yith_wcmcs_convert_price( $price, $convert_args );
            }

			return $price;
		}

		/**
		 * Modify the wc_price args.
		 *
		 * @param array $args The args to pass to wc_price function.
		 *
		 * @return array
		 */
		public function modify_totals_price_args( $args = array() ) {
			if ( function_exists( 'yith_wcmcs_get_current_currency_id' ) ) {
				$args['currency'] = yith_wcmcs_get_current_currency_id();
			}
			return $args;
		}
        /**
         * Modify the manual prices with the add-ons prices.
         *
         * @param int $converted_price The converted price.
         * @param int $price The original price.
         * @param WC_Product $product The product object.
         *
         * @return array
         */
        public function filter_manual_price_with_addons( $converted_price, $price, $product ) {

            $addons_price = $product->get_meta( 'yith_wapo_addons_price' );
            $currency_id  = yith_wcmcs_get_current_currency_id();

            if ( $currency_id && yith_wcmcs_get_wc_currency_options( 'currency' ) !== $currency_id && 'yes' === $product->get_meta( '_yith_wcmcs_custom_prices', true ) && $addons_price ) {
                $converted_price = $converted_price + $addons_price;
            }

            return $converted_price;

        }
	}
}
