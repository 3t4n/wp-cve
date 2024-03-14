<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Includes
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'YITH_Pre_Order_Frontend' ) ) {
	/**
	 * Class YITH_Pre_Order_Frontend
	 */
	class YITH_Pre_Order_Frontend {

		/**
		 * Compatibility for themes which returns only 2 parameters of "woocommerce_stock_html" filter.
		 *
		 * @var $product_from_availability
		 */
		public $product_from_availability;

		/**
		 * Main Instance
		 *
		 * @var YITH_Pre_Order_Frontend
		 * @since  1.0.0
		 * @access public
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_Pre_Order_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Construct
		 */
		public function __construct() {
			if ( 'no' === get_option( 'yith_wcpo_enable_pre_order', 'yes' ) ) {
				return;
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 12 );

			add_filter( 'woocommerce_get_availability', array( $this, 'get_product_from_availability' ), 10, 2 );
			add_filter( 'woocommerce_post_class', array( $this, 'add_pre_order_class_single_product_page' ), 10, 2 );

			add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'pre_order_label' ), 20, 2 );
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'pre_order_label' ), 10, 2 );
			add_action( 'woocommerce_after_cart_item_name', array( $this, 'pre_order_product_cart_label' ), 80 );
			add_filter( 'woocommerce_available_variation', array( $this, 'add_variable_pre_order_data' ), 10, 3 );

			add_filter( 'woocommerce_get_stock_html', array( $this, 'print_pre_order_info_on_single_product_page' ), 99, 3 );
			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'print_pre_order_info_on_loop' ), 11 );

			add_action( 'woocommerce_new_order_item', array( $this, 'add_order_item_meta' ), 10, 3 );

			add_filter( 'woocommerce_product_get_price', array( $this, 'edit_price' ), 10, 2 );
			add_filter( 'woocommerce_product_variation_get_price', array( $this, 'edit_price' ), 10, 2 );
			add_filter( 'woocommerce_show_variation_price', array( $this, 'show_variation_price' ), 10, 2 );
			add_filter( 'woocommerce_variation_prices_price', array( $this, 'variable_price_range' ), 10, 2 );
			add_filter( 'woocommerce_product_is_on_sale', array( $this, 'force_use_of_sale_price' ), 10, 2 );
		}

		/**
		 * Enqueue the scripts for frontend pages.
		 */
		public function enqueue_scripts() {
			wp_register_script( 'yith-wcpo-frontend-single-product', YITH_WCPO_ASSETS_JS_URL . yit_load_js_file( 'frontend-single-product.js' ), array( 'jquery' ), YITH_WCPO_VERSION, 'true' );
			if ( is_product() ) {
				wp_enqueue_script( 'yith-wcpo-frontend-single-product' );
			}
		}

		/**
		 * Add yith-pre-order-product class to WooCommerce Product Class on the Single Product Page.
		 *
		 * @param array      $classes Array of CSS classes.
		 * @param WC_Product $product Product object.
		 * @return array
		 */
		public function add_pre_order_class_single_product_page( $classes, $product ) {
			if ( YITH_Pre_Order()::is_pre_order_active( $product ) ) {
				$classes[] = 'yith-pre-order-product';
				if ( ywpo_availability_date_not_ready( $product ) ) {
					$classes[] = 'yith-pre-order-product-not-available';
				}
			}
			return $classes;
		}

		/**
		 * Compatibility for themes which returns only 2 parameters of "woocommerce_stock_html" filter.
		 *
		 * @param array      $availability Availability array.
		 * @param WC_Product $product      Product object.
		 *
		 * @return array
		 */
		public function get_product_from_availability( $availability, $product ) {
			$this->product_from_availability = $product;

			return $availability;
		}

		/**
		 * Replaces the Add to cart button text.
		 *
		 * @param string     $text The original Add to cart text.
		 * @param WC_Product $product The WC_Product object.
		 *
		 * @return string
		 */
		public function pre_order_label( $text, $product ) {
			global $sitepress;
			$product_id = $sitepress ? yit_wpml_object_id( $product->get_id(), 'product', true, $sitepress->get_default_language() ) : $product->get_id();

			if ( YITH_Pre_Order()::is_pre_order_active( $product_id ) ) {
				$label = get_option( 'yith_wcpo_default_add_to_cart_label' );
				if ( ! empty( $label ) ) {
					return $label;
				} else {
					return __( 'Pre-order now', 'yith-pre-order-for-woocommerce' );
				}
			}
			return $text;
		}

		/**
		 * Text that announces a product is a pre-order displayed in the Cart page.
		 *
		 * @param array $cart_item The cart item.
		 */
		public function pre_order_product_cart_label( $cart_item ) {
			global $sitepress;

			if ( ! empty( $cart_item['variation_id'] ) ) {
				$id = $sitepress ? yit_wpml_object_id( $cart_item['variation_id'], 'product', true, $sitepress->get_default_language() ) : $cart_item['variation_id'];
			} else {
				$id = $sitepress ? yit_wpml_object_id( $cart_item['product_id'], 'product', true, $sitepress->get_default_language() ) : $cart_item['product_id'];
			}

			if ( ! is_cart() || ! YITH_Pre_Order()::is_pre_order_active( $id ) ) {
				return;
			}

			$pre_order = ywpo_get_pre_order( $id );

			$label  = apply_filters( 'yith_ywpo_pre_order_product_label', __( 'Pre-order product', 'yith-pre-order-for-woocommerce' ), $pre_order, $id, $cart_item );
			$output = apply_filters( 'yith_ywpo_pre_order_product_label_output', '<div style="font-size: 10px;">' . $label . '</div>', $pre_order, $id, $cart_item, $label );
			echo wp_kses_post( $output );
		}

		/**
		 * Add the pre-order data that will be used for replacing the Add to cart text in the pre-order variations.
		 *
		 * @param array                $array            The variable product data array.
		 * @param WC_Product_Variable  $variable_product The WC_Product_Variable object.
		 * @param WC_Product_Variation $variation        The WC_Product_Variation object.
		 *
		 * @return array
		 */
		public function add_variable_pre_order_data( $array, $variable_product, $variation ) {
			global $sitepress;

			$id = $sitepress ? yit_wpml_object_id( $variation->get_id(), 'product', true, $sitepress->get_default_language() ) : $variation->get_id();

			if ( YITH_Pre_Order()::is_pre_order_active( $id ) ) {
				$label = get_option( 'yith_wcpo_default_add_to_cart_label', __( 'Pre-order now', 'yith-pre-order-for-woocommerce' ) );
				if ( ! empty( $label ) ) {
					$pre_order_label = $label;
				} else {
					$pre_order_label = apply_filters( 'ywpo_pre_order_variation_default_label', __( 'Pre-order now', 'yith-pre-order-for-woocommerce' ), $id );
				}

				$array['is_pre_order']    = 'yes';
				$array['pre_order_label'] = apply_filters( 'ywpo_variation_pre_order_label', $pre_order_label, $id );
			}

			return $array;
		}

		/**
		 * Print the pre-order info (start date or availability date) in the single product page.
		 *
		 * @param string     $availability_html The original HTML part where the stock info is printed.
		 * @param array      $availability      Not used.
		 * @param WC_Product $product           The WC_Product object.
		 *
		 * @return string
		 */
		public function print_pre_order_info_on_single_product_page( $availability_html, $availability, $product = false ) {
			global $sitepress;

			if ( ! is_product() ) {
				return $availability_html;
			}

			if ( ! $product ) {
				$product = $this->product_from_availability;
			}

			$id = $sitepress ? yit_wpml_object_id( $product->get_id(), 'product', true, $sitepress->get_default_language() ) : $product->get_id();

			if ( YITH_Pre_Order()::is_pre_order_active( $id ) ) {
				$availability_html = apply_filters( 'ywpo_pre_order_info_on_single_product_page', YITH_Pre_Order_Frontend()::print_pre_order_info( $id, 'pre_order_single' ), $id, $availability_html );
			}

			return $availability_html;
		}

		/**
		 * Print the pre-order info (start date or availability date) in loop pages.
		 */
		public function print_pre_order_info_on_loop() {
			global $product, $sitepress;

			if ( apply_filters( 'ywpo_availability_in_shop', 'yes' !== get_option( 'ywpo_availability_in_shop', 'yes' ) ) ) {
				return;
			}

			$id = $sitepress ? yit_wpml_object_id( $product->get_id(), 'product', true, $sitepress->get_default_language() ) : $product->get_id();

			if ( YITH_Pre_Order()::is_pre_order_active( $id ) ) {
				$pre_order_info = apply_filters( 'ywpo_pre_order_info_on_loop', YITH_Pre_Order_Frontend()::print_pre_order_info( $id, 'pre_order_loop' ), $id );
				echo wp_kses_post( $pre_order_info );
			}

		}

		/**
		 * Adds order item meta
		 *
		 * @param int                   $item_id  Order item ID.
		 * @param WC_Order_Item_Product $item     Order item object.
		 * @param int                   $order_id Order ID.
		 */
		public function add_order_item_meta( $item_id, $item, $order_id ) {
			global $sitepress;

			$order = wc_get_order( $order_id );

			if ( 'line_item' !== $item->get_type() || ! $order instanceof WC_Order ) {
				return;
			}

			$id      = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
			$product = $sitepress ? wc_get_product( yit_wpml_object_id( $id, 'product', true, $sitepress->get_default_language() ) ) : wc_get_product( $id );

			if ( $product instanceof WC_Product && YITH_Pre_Order()::is_pre_order_active( $product ) ) {
				if ( ! ywpo_order_has_pre_order( $order ) ) {
					$order->update_meta_data( '_order_has_preorder', apply_filters( 'ywpo_order_has_preorder', 'yes', $order, $product, $item ) );
					$order->update_meta_data( '_ywpo_status', apply_filters( 'ywpo_status', 'waiting', $order, $product, $item ) );
				}

				$pre_order = ywpo_get_pre_order( $product );

				$item->update_meta_data( '_ywpo_item_preorder', apply_filters( 'ywpo_item_preorder', 'yes', $item, $product, $order ) );
				$item->update_meta_data( '_ywpo_item_status', apply_filters( 'ywpo_item_status', 'waiting', $item, $product, $order ) );
				$item->update_meta_data( '_ywpo_item_for_sale_date', apply_filters( 'ywpo_item_release_date', $pre_order->calculate_availability_date_timestamp(), $item, $product, $order ) );
				$item->save();

				// Add the item to the order meta '_ywpo_pre_order_items' to easily identify the pre-orders inside the order.
				$pre_order_items = $order->get_meta( '_ywpo_pre_order_items' );
				if ( ! $pre_order_items ) {
					$pre_order_items = array();
				}
				$pre_order_items[ $item_id ] = 'waiting';
				$order->update_meta_data( '_ywpo_pre_order_items', apply_filters( 'ywpo_pre_order_items', $pre_order_items, $order, $product, $item ) );

				$order->add_order_note(
					apply_filters(
						'ywpo_pre_ordered_order_note',
						sprintf(
						// translators: %s: item name.
							__( 'Item %s was pre-ordered', 'yith-pre-order-for-woocommerce' ),
							$product->get_formatted_name()
						),
						$order,
						$product,
						$item
					)
				);

				$order->save();

				// Send the email notification.
				WC()->mailer();
				do_action( 'ywpo_confirmed_email', $order, $product, $item->get_id() );
				do_action( 'ywpo_new_pre_order_email', $order, $product, $item->get_id() );
			}
		}

		/**
		 * Edit the product price for the pre-order.
		 *
		 * @param string     $price   The product price.
		 * @param WC_Product $product WC_Product object.
		 *
		 * @return string
		 */
		public function edit_price( $price, $product ) {
			global $sitepress;

			if ( ( 'simple' !== $product->get_type() && 'variation' !== $product->get_type() ) || apply_filters( 'yith_wcpo_return_original_price', false, $product ) ) {
				return $price;
			}

			$id        = $product->get_id();
			$id        = $sitepress ? yit_wpml_object_id( $id, 'product', true, $sitepress->get_default_language() ) : $id;
			$pre_order = ywpo_get_pre_order( $id );

			if ( ! YITH_Pre_Order()::is_pre_order_active( $id ) ) {
				return $price;
			}

			if ( ! get_current_user_id() ) {
				switch ( get_option( 'yith_wcpo_guest_users_price', 'show_pre_order_price' ) ) {
					case 'show_regular_price':
						return $product->get_regular_price();
					case 'hidden_price':
						return '';
				}
			}

			if ( metadata_exists( 'post', $id, '_ywpo_price_adjustment' ) && ! metadata_exists( 'post', $id, '_ywpo_price_mode' ) ) {
				// Backward compatibility.
				$price_adjustment  = $pre_order->get_pre_order_price_adjustment();
				$manual_price      = $pre_order->get_pre_order_price();
				$adjustment_type   = $pre_order->get_pre_order_adjustment_type();
				$adjustment_amount = $pre_order->get_pre_order_adjustment_amount();

				if ( 'yes' === get_option( 'yith_wcpo_show_regular_price' ) && 'manual' === $price_adjustment && '0' !== $manual_price && ! empty( $manual_price ) ) {
					return $this->compute_price( $product->get_regular_price(), $price_adjustment, $manual_price, $adjustment_type, $adjustment_amount );
				} else {
					return $this->compute_price( $price, $price_adjustment, $manual_price, $adjustment_type, $adjustment_amount );
				}
			} else {
				return $this->calculate_pre_order_price( $price, $pre_order );
			}
		}

		/**
		 * If all the variations have the same regular price, the price will be hidden despite the variations use the Pre-Order price. This function fixes this.
		 *
		 * @param bool                $bool             Boolean value.
		 * @param WC_Product_Variable $product_variable The WC_Product_Variable object.
		 *
		 * @return bool
		 */
		public function show_variation_price( $bool, $product_variable ) {
			if ( ! $product_variable instanceof WC_Product_Variable ) {
				return $bool;
			}
			$has_any_preorder_variation = false;
			foreach ( $product_variable->get_children() as $child ) {
				if ( YITH_Pre_Order()::is_pre_order_active( $child ) ) {
					$has_any_preorder_variation = true;
				}
			}
			if ( $has_any_preorder_variation ) {
				return true;
			}

			return $bool;
		}

		/**
		 * Edit the variation prices for the variable price range.
		 *
		 * @param string               $price     The variation price.
		 * @param WC_Product_Variation $variation The WC_Product_Variation object.
		 *
		 * @return string
		 */
		public function variable_price_range( $price, $variation ) {
			global $sitepress;

			$id           = $variation->get_id();
			$variation_id = $sitepress ? yit_wpml_object_id( $id, 'product', true, $sitepress->get_default_language() ) : $id;

			if ( YITH_Pre_Order()::is_pre_order_active( $variation_id ) ) {
				$pre_order = ywpo_get_pre_order( $variation_id );

				if ( ! get_current_user_id() ) {
					switch ( get_option( 'yith_wcpo_guest_users_price', 'show_pre_order_price' ) ) {
						case 'show_regular_price':
							return $variation->get_regular_price();
						case 'hidden_price':
							return '';
					}
				}

				if ( metadata_exists( 'post', $id, '_ywpo_price_adjustment' ) && ! metadata_exists( 'post', $id, '_ywpo_price_mode' ) ) {
					// Backward compatibility.
					$price_adjustment  = $pre_order->get_pre_order_price_adjustment();
					$manual_price      = $pre_order->get_pre_order_price();
					$adjustment_type   = $pre_order->get_pre_order_adjustment_type();
					$adjustment_amount = $pre_order->get_pre_order_adjustment_amount();

					if ( 'yes' === get_option( 'yith_wcpo_show_regular_price' ) && 'manual' === $price_adjustment && '0' !== $manual_price && ! empty( $manual_price ) ) {
						return $this->compute_price( $variation->get_regular_price(), $price_adjustment, $manual_price, $adjustment_type, $adjustment_amount );
					} else {
						return $this->compute_price( $price, $price_adjustment, $manual_price, $adjustment_type, $adjustment_amount );
					}
				} else {
					return $this->calculate_pre_order_price( $price, $pre_order );
				}
			}

			return $price;
		}

		/**
		 * Calculate the pre-order price.
		 *
		 * @param string                 $price      The product price.
		 * @param YITH_Pre_Order_Product $pre_order  Pre-order product object.
		 *
		 * @return string
		 */
		public function calculate_pre_order_price( $price, $pre_order ) {
			$price_mode = $pre_order->get_price_mode();
			if ( 'default' === $price_mode ) {
				return $price;
			}

			if ( 'fixed' === $price_mode ) {
				return $pre_order->get_pre_order_price() ? $pre_order->get_pre_order_price() : $price;
			}

			if ( 'discount_percentage' === $price_mode && $pre_order->get_discount_percentage() ) {
				$price = (float) $price - ( ( (float) $price * (float) $pre_order->get_discount_percentage() ) / 100 );
			}

			if ( 'discount_fixed' === $price_mode && $pre_order->get_discount_fixed() ) {
				$price = (float) $price - (float) $pre_order->get_discount_fixed();
				if ( 0 > $price ) {
					$price = '0';
				}
			}

			if ( 'increase_percentage' === $price_mode && $pre_order->get_increase_percentage() ) {
				$price = (float) $price + ( ( (float) $price * (float) $pre_order->get_increase_percentage() ) / 100 );
			}

			if ( 'increase_fixed' === $price_mode && $pre_order->get_increase_fixed() ) {
				$price = (float) $price + (float) $pre_order->get_increase_fixed();
			}

			return round( (float) $price, apply_filters( 'ywpo_price_decimals', wc_get_price_decimals(), $price, $pre_order ) );
		}

		/**
		 * Backward compatibility function for calculation the pre-order product price.
		 *
		 * @param string $price             The product price.
		 * @param string $price_adjustment  Price adjustment value.
		 * @param string $manual_price      Manual price.
		 * @param string $adjustment_type   Adjustment type.
		 * @param string $adjustment_amount The amount for the adjustment to be calculated.
		 *
		 * @return string
		 */
		public function compute_price( $price, $price_adjustment, $manual_price, $adjustment_type, $adjustment_amount ) {
			if ( 'manual' === $price_adjustment ) {
				if ( ! empty( $manual_price ) ) {
					return (string) round( $manual_price, apply_filters( 'ywpo_price_decimals', wc_get_price_decimals(), $price ) );
				}
			} elseif ( isset( $adjustment_amount ) ) {
				if ( 'fixed' === $adjustment_type ) {
					if ( 'discount' === $price_adjustment ) {
						$price = (float) $price - (float) $adjustment_amount;
						if ( 0 > $price ) {
							$price = '0';
						}
					}
					if ( 'mark-up' === $price_adjustment ) {
						$price = (float) $price + (float) $adjustment_amount;
					}

					return (string) round( $price, apply_filters( 'ywpo_price_decimals', wc_get_price_decimals(), $price ) );
				}
				if ( 'percentage' === $adjustment_type ) {
					if ( 'discount' === $price_adjustment ) {
						$price = (float) $price - ( ( (float) $price * (float) $adjustment_amount ) / 100 );
					}
					if ( 'mark-up' === $price_adjustment ) {
						$price = (float) $price + ( ( (float) $price * (float) $adjustment_amount ) / 100 );
					}

					return (string) round( $price, apply_filters( 'ywpo_price_decimals', wc_get_price_decimals(), $price ) );
				}
			}

			return round( $price, apply_filters( 'ywpo_price_decimals', wc_get_price_decimals(), $price ) );
		}

		/**
		 * Make the pre-order price as Sale price and the option for showing the regular price is enabled.
		 *
		 * @param bool       $on_sale Whether the product is on sale or not.
		 * @param WC_Product $product The WC_Product object.
		 *
		 * @return bool
		 */
		public function force_use_of_sale_price( $on_sale, $product ) {
			$pre_order = ywpo_get_pre_order( $product );

			$price_mode       = $pre_order->get_price_mode(); // Pre-order 2.0 property.
			$price_adjustment = $pre_order->get_pre_order_price_adjustment(); // backward compatibility.
			$manual_price     = $pre_order->get_pre_order_price();

			// If the option guest_users_price is set to show_regular_price, disable the use of Sale price for only see the Regular price without a strikethrough price.
			if ( YITH_Pre_Order()::is_pre_order_active( $product ) && ! get_current_user_id() && 'show_regular_price' === get_option( 'yith_wcpo_guest_users_price', 'show_pre_order_price' ) ) {
				return false;
			}

			if (
				YITH_Pre_Order()::is_pre_order_active( $product ) &&
				( metadata_exists( 'post', $product->get_id(), '_ywpo_price_mode' ) && 'default' === $price_mode ) && ! $product->get_sale_price( 'edit' ) ||
				empty( $price_adjustment )
			) {
				return false;
			}


			if ( YITH_Pre_Order()::is_pre_order_active( $product ) && 'yes' === get_option( 'yith_wcpo_show_regular_price' ) && 'fixed' === $price_mode && ! empty( $manual_price ) ) {
					return true;
				}

			if ( YITH_Pre_Order()::is_pre_order_active( $product ) && 'yes' === get_option( 'yith_wcpo_show_regular_price' ) && 'discount_percentage' === $price_mode && $pre_order->get_discount_percentage() !== '' ) {
					return true;
				}

			if (
				YITH_Pre_Order()::is_pre_order_active( $product ) &&
				(
					( metadata_exists( 'post', $product->get_id(), '_ywpo_price_mode' ) && 'fixed' === $price_mode && empty( $manual_price ) ) ||
					( metadata_exists( 'post', $product->get_id(), '_ywpo_price_adjustment' ) && 'manual' === $price_adjustment && empty( $manual_price ) )
				) &&
				'yes' === get_option( 'yith_wcpo_show_regular_price' )
			) {
				return $on_sale;
			}

			return $on_sale;
		}

		/**
		 * Print the pre-order relative info. It could display the info about the starting date or the release date, depending on
		 * the product's settings.
		 *
		 * @param string|int $id The product ID.
		 * @param string     $context The context the info will be displayed.
		 *
		 * @return string
		 */
		public static function print_pre_order_info( $id, $context ) {
			$pre_order_info = YITH_Pre_Order_Frontend()::print_availability_date( $id, $context );

			return apply_filters( 'ywpo_pre_order_info', $pre_order_info, $id, $context );
		}

		/**
		 * Print the pre-order availability date.
		 *
		 * @param string|int $id      The product ID.
		 * @param string     $context The context the info will be displayed.
		 *
		 * @return string
		 */
		public static function print_availability_date( $id, $context ) {
			$pre_order = ywpo_get_pre_order( $id );

			$timestamp              = $pre_order->calculate_availability_date_timestamp();
			$availability_date_mode = $pre_order->get_availability_date_mode();

			// Checks if there is a date set for the product.
			if ( ! empty( $timestamp ) && 'no_date' !== $availability_date_mode ) {
				$availability_label = get_option( 'yith_wcpo_default_availability_date_label' );

				if ( empty( $availability_label ) ) {
					$availability_label = apply_filters(
						'yith_ywpo_default_availability_date_label',
						// translators: %1$s: date, %2$s: time.
						sprintf( __( 'Available on: %1$s at %2$s', 'yith-pre-order-for-woocommerce' ), '{availability_date}', '{availability_time}' )
					);
				}
				$availability_label = apply_filters( 'yith_ywpo_date_time', $availability_label );

				$date = apply_filters( 'yith_ywpo_availability_date_no_auto_date', ywpo_print_date( $timestamp ), $timestamp );
				$time = apply_filters( 'yith_ywpo_availability_date_no_auto_time', ywpo_print_time( $timestamp ), $timestamp );

				if ( apply_filters( 'ywpo_availability_date_show_offset_label', false, $timestamp, $date, $time ) ) {
					$time = apply_filters( 'yith_ywpo_no_auto_time', $time . ' (' . ywpo_get_timezone_offset_label() . ')', $time );
				}

				$availability_label = str_replace( '{availability_date}', '<span class="availability_date">' . $date . '</span>', $availability_label );
				$availability_label = str_replace( '{availability_time}', '<span class="availability_time">' . $time . '</span>', $availability_label );
				$availability_label = apply_filters( 'yith_ywpo_availability_date_no_auto_label', nl2br( $availability_label ), $id, $timestamp, $date, $time );

				return '<div class="ywpo_availability_date ' . $context . '-no-auto-format">' . $availability_label . '</div>';

			}

			return '';
		}

	}
}

/**
 * Unique access to instance of YITH_Pre_Order_Frontend class
 *
 * @return YITH_Pre_Order_Frontend
 */
function YITH_Pre_Order_Frontend() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_Pre_Order_Frontend::get_instance();
}
