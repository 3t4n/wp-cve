<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_Pixel_COG
 * plugin weblink: http://www.pixelyoursite.com/
 * plugin-name: Cost of Goods by PixelYourSite
 */

if ( ! class_exists( 'WFACP_Compatibility_Pixel_COG' ) ) {
	#[AllowDynamicProperties] 

  class WFACP_Compatibility_Pixel_COG {
		public function __construct() {
			add_filter( 'wfacp_add_to_cart_tracking_price', [ $this, 'cog_price' ], 10, 5 );
			add_filter( 'wfacp_add_to_cart_tracking_line_subtotal', [ $this, 'cog_line_subtotal' ], 10, 3 );

		}

		public function is_enable() {
			if ( defined( 'PIXEL_COG_VERSION' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * get product cost of goods price
		 * @param $price
		 * @param $product
		 * @param $variation_id
		 * @param $quantity
		 * @param $mode
		 *
		 * @return float|mixed
		 */
		public function cog_price( $price, $product, $quantity, $mode, $settings ) {

			if ( ! $this->is_enable() ) {
				return $price;
			}

			if ( 'pixel' !== $mode && 'pint' !== $mode ) {
				return $price;
			}

			$price = $this->get_cog_product_value( $product, $quantity, $product->get_price() );

			return $price;
		}

		/**
		 * @param $product
		 * @param $quantity
		 * @param $price
		 *
		 * @return float
		 */
		public function get_cog_product_value( $product, $quantity, $price ) {
			$args = array( 'qty' => $quantity, 'price' => $price );
			if ( get_option( '_pixel_cog_tax_calculating' ) === 'no' ) {
				$amount = wc_get_price_excluding_tax( $product, $args );
			} else {
				$amount = wc_get_price_including_tax( $product, $args );
			}

			$cog = $this->get_available_product_cog( $product );

			if ( $cog['val'] ) {
				if ( $cog['type'] === 'fix' ) {
					$value = round( (float) $amount - (float) $cog['val'], 2 );
				} else {
					$value = round( (float) $amount - ( (float) $amount * (float) $cog['val'] / 100 ), 2 );
				}
			} else {
				$value = (float) $amount;
			}

			return $value;
		}

		/**
		 * @param $product
		 *
		 * @return array
		 */
		public function get_available_product_cog( $product ) {
			$cost_type    = get_post_meta( $product->get_id(), '_pixel_cost_of_goods_cost_type', true );
			$product_cost = get_post_meta( $product->get_id(), '_pixel_cost_of_goods_cost_val', true );

			if ( ! $product_cost && $product->is_type( "variation" ) ) {
				$cost_type    = get_post_meta( $product->get_parent_id(), '_pixel_cost_of_goods_cost_type', true );
				$product_cost = get_post_meta( $product->get_parent_id(), '_pixel_cost_of_goods_cost_val', true );
			}


			if ( $product_cost ) {
				$cog = array(
					'type' => $cost_type,
					'val'  => $product_cost
				);
			} else {
				$cog_term_val = $this->get_product_cost_by_cat( $product->get_id() );
				if ( $cog_term_val ) {
					$cog = array(
						'type' => $this->get_product_type_by_cat( $product->get_id() ),
						'val'  => $cog_term_val
					);
				} else {
					$cog = array(
						'type' => get_option( '_pixel_cost_of_goods_cost_type' ),
						'val'  => get_option( '_pixel_cost_of_goods_cost_val' )
					);
				}
			}

			return $cog;

		}

		/**
		 * @param $product_id
		 *
		 * @return false|mixed|string
		 */
		public function get_product_cost_by_cat( $product_id ) {
			$term_list = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
			$cost      = array();
			foreach ( $term_list as $term_id ) {
				$cost[ $term_id ] = get_term_meta( $term_id, '_pixel_cost_of_goods_cost_val', true );
			}
			if ( empty( $cost ) ) {
				return '';
			} else {
				asort( $cost );
				$max = end( $cost );

				return $max;
			}
		}

		/**
		 * @param $product_id
		 *
		 * @return mixed|string
		 */
		public function get_product_type_by_cat( $product_id ) {
			$term_list = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
			$cost      = array();
			foreach ( $term_list as $term_id ) {
				$cost[ $term_id ] = array(
					get_term_meta( $term_id, '_pixel_cost_of_goods_cost_val', true ),
					get_term_meta( $term_id, '_pixel_cost_of_goods_cost_type', true )
				);
			}
			if ( empty( $cost ) ) {
				return '';
			} else {
				asort( $cost );
				$max = end( $cost );

				return $max[1];
			}
		}


		public function cog_line_subtotal($subtotal, $mode, $settings) {

			if ( ! $this->is_enable() ) {
				return $subtotal;
			}

			if ( 'pixel' !== $mode && 'pint' !== $mode ) {
				return $subtotal;
			}

			$cog_value = $this->get_available_product_cog_cart();

			if ( $cog_value !== '' ) {
				return (float) round( $cog_value, 2 );
			}

			return $subtotal;
		}

		public function get_available_product_cog_cart() {
			$cart_total = 0.0;
			$cost = 0;
			$notice = '';
			$custom_total = 0;
			$cat_isset = 0;
			$isWithoutTax = get_option( '_pixel_cog_tax_calculating')  === 'no';

			$shipping = WC()->cart->get_shipping_total();
			$cart_total = WC()->cart->get_total('edit') - $shipping;

			if($isWithoutTax) {
				$cart_total -=  WC()->cart->get_total_tax();
			} else {
				$cart_total -= WC()->cart->get_shipping_tax();
			}

			foreach ( WC()->cart->cart_contents as $cart_item_key => $item ) {
				$product_id = ( isset( $item['variation_id'] ) && 0 != $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );

				$product = wc_get_product($product_id);

				$cost_type = get_post_meta( $product->get_id(), '_pixel_cost_of_goods_cost_type', true );
				$product_cost = get_post_meta( $product->get_id(), '_pixel_cost_of_goods_cost_val', true );

				if(!$product_cost && $product->is_type("variation")) {
					$cost_type = get_post_meta( $product->get_parent_id(), '_pixel_cost_of_goods_cost_type', true );
					$product_cost = get_post_meta( $product->get_parent_id(), '_pixel_cost_of_goods_cost_val', true );
				}

				$args = array( 'qty'   => 1, 'price' => $product->get_price());
				if($isWithoutTax) {
					$price = wc_get_price_excluding_tax($product, $args);
				} else {
					$price = wc_get_price_including_tax($product,$args);
				}
				$qlt = $item['quantity'];

				if ($product_cost) {
					$cost = ($cost_type === 'percent') ? $cost + ($price * ($product_cost / 100) * $qlt) : $cost + ($product_cost * $qlt);
					$custom_total = $custom_total + ($price * $qlt);
				} else {
					$product_cost = $this->get_product_cost_by_cat( $product_id );
					$cost_type = $this->get_product_type_by_cat( $product_id );
					if ($product_cost) {
						$cost = ($cost_type === 'percent') ? $cost + ($price * ($product_cost / 100) * $qlt) : $cost + ($product_cost * $qlt);
						$custom_total = $custom_total + ($price * $qlt);
					} else {
						$product_cost = get_option( '_pixel_cost_of_goods_cost_val');
						$cost_type = get_option( '_pixel_cost_of_goods_cost_type' );
						if ($product_cost) {
							$cost = ($cost_type === 'percent') ? $cost + ((float) $price * ((float) $product_cost / 100) * $qlt) : (float) $cost + ((float) $product_cost * $qlt);
							$custom_total = $custom_total + ($price * $qlt);
						}
					}
				}
			}

			return $cart_total - $cost;

		}
	}


	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Pixel_COG(), 'pixel_cog' );
}
