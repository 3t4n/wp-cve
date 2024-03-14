<?php

/*
Class Name: WOO_PRE_ORDER_SINGLE_PRODUCT
Author: villatheme
Author URI: http://villatheme.com
Copyright 2020-2021 villatheme.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPRO_WOO_PRE_ORDER_Frontend_single_product {
	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$get_option = get_option( 'pre_order_setting_default' );
			if ( $get_option['enabled'] == 'yes' ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'script_and_css_frontend' ) );
				//add to cart text
				add_filter( 'woocommerce_product_single_add_to_cart_text', array(
					$this,
					'pre_order_label_simple'
				), 20, 2 );
				add_filter( 'woocommerce_product_add_to_cart_text', array(
					$this,
					'pre_order_label_simple'
				), 10, 2 );
				//text cart

				add_filter( 'woocommerce_available_variation', array( $this, 'pre_order_label_variation' ), 10, 3 );

				add_filter( 'woocommerce_cart_item_name', array( $this, 'pre_order_after_name_product' ), 999, 2 );

//				add_filter( 'woocommerce_after_cart_item_name', array( $this, 'pre_order_after_name_cart' ), 999, 2 );
				//Date Simple
				add_filter( 'woocommerce_before_add_to_cart_button', array( $this, 'pre_order_simple_date' ), 10, 0 );
				add_filter( 'woocommerce_after_shop_loop_item', array( $this, 'pre_order_simple_shop_page' ), 10, 0 );
				//Date Variation
				add_filter( 'woocommerce_available_variation', array( $this, 'pre_order_variation_date' ), 10, 3 );
				//Change Price simple
				add_filter( 'woocommerce_product_get_price', array(
					$this,
					'pre_order_price_change_product_simple'
				), 10, 2 );
				add_filter( 'woocommerce_product_get_sale_price', array(
					$this,
					'pre_order_price_change_product_simple'
				), 10, 2 );
				//Change Price variable
				add_action( 'woocommerce_product_variation_get_price', array(
					$this,
					'pre_order_price_variable'
				), 10, 2 );
				add_filter( 'woocommerce_product_variation_get_sale_price', array(
					$this,
					'pre_order_price_variable'
				), 10, 2 );
				add_filter( 'woocommerce_variation_prices_price', array( $this, 'pre_order_price_variable' ), 10, 2 );
				add_filter( 'woocommerce_variation_prices_sale_price', array(
					$this,
					'pre_order_price_variable'
				), 10, 2 );
				//display markup
				add_filter( 'woocommerce_get_price_html', array(
					$this,
					'pre_order_display_price_markup_discount_variation'
				), 10, 2 );
				add_filter( 'woocommerce_variable_get_price_html', array(
					$this,
					'pre_order_display_price_markup_discount_variation'
				), 10, 2 );
				//Min max Price
//				add_filter( 'woocommerce_get_price_html', array( $this, 'pre_order_min_max_price' ), 10, 2 );

			}
		}
	}

	/**
	 *  Javascript and css frontend
	 */
	public function script_and_css_frontend() {
		wp_enqueue_script( 'product-pre-orders-for-woo-setting-frontend-js', WPRO_WOO_PRE_ORDER_JS . 'product-pre-orders-for-woo-setting-frontend.js', array( 'jquery' ), WPRO_WOO_PRE_ORDER_VERSION );
		wp_enqueue_style( 'product-pre-orders-for-woo-setting-frontend', WPRO_WOO_PRE_ORDER_CSS . 'product-pre-orders-for-woo-setting-frontend.css' );
		wp_register_style( 'product-pre-orders-for-woo-style-css', false );
		wp_enqueue_style( 'product-pre-orders-for-woo-style-css' );
		$get_option = get_option( 'pre_order_setting_default' );
		$css        = "
                .wpro-pre-order-availability-date-cart{
                    color:{$get_option['color_date_cart']};
                }
                .wpro-pre-order-availability-date{
                    color:{$get_option['color_date_single']};
                }
                .wpro-pre-order-shop-page{
                    color:{$get_option['color_date_shop_page']};
                }
            ";
		wp_add_inline_style( 'product-pre-orders-for-woo-style-css', $css );
	}

	/** Display min max Price Variable
	 *
	 * @param $price
	 * @param $product
	 *
	 * @return string
	 */
	public function pre_order_min_max_price( $price, $product ) {
        if(class_exists('WC_Bundles')){
            return $price;
        }
		if ( $product->is_type( 'variable' ) ) {
			if ( is_user_logged_in() ) {
				$price_min = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_sale_price( 'min' ) ) );
				$price_max = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_sale_price( 'max' ) ) );
			} else {
				$price_min = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_regular_price( 'min' ) ) );
				$price_max = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_regular_price( 'max' ) ) );
			}

			if ( $price_min != $price_max ) {
				if ( $price_min == 0 && $price_max > 0 ) {
					$price = wc_price( $price_max );
				} elseif ( $price_min > 0 && $price_max == 0 ) {
					$price = wc_price( $price_min );
				} else {
					$price = wc_format_price_range( $price_min, $price_max );
				}
			} else {
				if ( $price_min > 0 ) {
					$price = wc_price( $price_min );
				}
			}
			$price .= $product->get_price_suffix();
		}

		return $price;
	}


	/** Add to cart Label simple Product
	 *
	 * @param $label
	 * @param $product
	 *
	 * @return string
	 */
	public function pre_order_label_simple( $label, $product ) {
		$is_pre_order = get_post_meta( $product->get_id(), '_simple_preorder', true );
		$get_option   = get_option( 'pre_order_setting_default' );
		$pre_label    = get_post_meta( $product->get_id(), '_wpro_label', true );
		if ( $product->is_type( 'simple' ) ) {
			if ( $is_pre_order == 'yes' ) {
				if ( $pre_label ) {
					$label = esc_html( $pre_label );
				} else {
					$label = esc_html( $get_option['default_label_simple'] );
				}
			}
		}

		return $label;
	}

	/** Add to cart Label variable Product
	 *
	 * @param $array
	 * @param $variable_product
	 * @param $variation
	 *
	 * @return mixed
	 */
	public function pre_order_label_variation( $array, $variable_product, $variation ) {
		$is_pre_order    = get_post_meta( $variation->get_id(), '_wpro_variable_is_preorder', true );
		$variation_label = get_post_meta( $variation->get_id(), '_wpro_label_variable', true );
		$get_option      = get_option( 'pre_order_setting_default' );
		if ( $is_pre_order == 'yes' ) {
			if ( $variation_label ) {
				$array['pre_order_label'] = esc_html( $variation_label );
			} else {
				$array['pre_order_label'] = esc_html( $get_option['label_variable'] );
			}
		}

		return $array;
	}

	/** Purchase limit  in the cart
	 *
	 * @param $cart_item
	 */
	public function pre_order_after_name_cart( $cart_item ) {
		$get_option = get_option( 'pre_order_setting_default' );
		$date_now   = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
		$gmt_offdet = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		if ( $cart_item['data']->is_type( 'simple' ) ) {
			$product_id   = $cart_item['product_id'];
			$is_pre_order = get_post_meta( $product_id, '_simple_preorder', true );

			if ( empty( $is_pre_order ) || ( $is_pre_order == 'no' ) ) {
				return;
			}
			$pre_date      = get_post_meta( $product_id, '_wpro_date', true );
			$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
			$time_str      = strtotime( $date_time );
			$time_total    = $gmt_offdet + $time_str;
			$date_format   = date_i18n( get_option( 'date_format' ), $time_total );
			$pre_time      = get_post_meta( $product_id, '_wpro_time', true );
			$time_format   = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
			$date_label    = get_post_meta( $product_id, '_wpro_date_label', true );
			$no_date_label = get_post_meta( $product_id, '_wpro_no_date_label', true );
			if ( $date_label ) {
				$post_date = str_replace( "{availability_date}", $date_format, $date_label );
			} else {
				$post_date = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
			}
			$post_time = str_replace( "{availability_time}", $time_format, $post_date );
			if ( ! empty( $pre_date ) ) {
				if ( $date_now < $time_total ) {
					?>
                    <div class="wpro-pre-order-availability-date-cart">
						<?php
						esc_html_e( $post_time )
						?>
                    </div>
					<?php
				}
			} else {
				?>
                <div class="wpro-pre-order-availability-date-cart">
					<?php
					if ( $no_date_label ) {
						esc_html_e( $no_date_label );
					} else {
						esc_html_e( $get_option['no_date_text'] );
					}
					?>
                </div>
				<?php
			}
		} elseif ( $cart_item['data']->is_type( 'variation' ) ) {
			$variation_id = $cart_item['variation_id'];
			$is_pre_order = get_post_meta( $variation_id, '_wpro_variable_is_preorder', true );

			if ( empty( $is_pre_order ) || ( $is_pre_order == 'no' ) ) {
				return;
			}
			$pre_date      = get_post_meta( $variation_id, '_wpro_date_variable', true );
			$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
			$time_str      = strtotime( $date_time );
			$time_total    = $gmt_offdet + $time_str;
			$date_format   = date_i18n( get_option( 'date_format' ), $time_total );
			$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
			$pre_time      = get_post_meta( $variation_id, '_wpro_time_variable', true );
			$time_format   = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
			$time_total    = strtotime( $date_time );
			$date_label    = get_post_meta( $variation_id, '_wpro_date_label_variable', true );
			$no_date_label = get_post_meta( $variation_id, '_wpro_no_date_label_variable', true );
			if ( $date_label ) {
				$post_date = str_replace( "{availability_date}", $date_format, $date_label );
			} else {
				$post_date = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
			}
			$post_time = str_replace( "{availability_time}", $time_format, $post_date );
			if ( ! empty( $pre_date ) ) {
				if ( $date_now < $time_total ) {
					?>
                    <div class="wpro-pre-order-availability-date-cart">
						<?php
						esc_html_e( $post_time )
						?>
                    </div>
					<?php
				}
			} else {
				?>
                <div class="wpro-pre-order-availability-date-cart">
					<?php
					if ( $no_date_label ) {
						esc_html_e( $no_date_label );
					} else {
						esc_html_e( $get_option['no_date_text'] );
					}
					?>
                </div>
				<?php
			}
		}

	}

	public function pre_order_after_name_product( $name, $cart_item ) {
		$get_option = get_option( 'pre_order_setting_default' );
		$date_now   = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
		$gmt_offdet = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		if ( $cart_item['data']->is_type( 'simple' ) ) {
			$product_id   = $cart_item['product_id'];
			$is_pre_order = get_post_meta( $product_id, '_simple_preorder', true );

			if ( empty( $is_pre_order ) || ( $is_pre_order == 'no' ) ) {
				return $name;
			}
			$pre_date      = get_post_meta( $product_id, '_wpro_date', true );
			$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
			$time_str      = strtotime( $date_time );
			$time_total    = $gmt_offdet + $time_str;
			$date_format   = date_i18n( get_option( 'date_format' ), $time_total );
			$pre_time      = get_post_meta( $product_id, '_wpro_time', true );
			$time_format   = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
			$date_label    = get_post_meta( $product_id, '_wpro_date_label', true );
			$no_date_label = get_post_meta( $product_id, '_wpro_no_date_label', true );
			if ( $date_label ) {
				$post_date = str_replace( "{availability_date}", $date_format, $date_label );
			} else {
				$post_date = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
			}
			$post_time = str_replace( "{availability_time}", $time_format, $post_date );
			if ( ! empty( $pre_date ) ) {
				if ( $date_now < $time_total ) {

					$name .= '<div class="wpro-pre-order-availability-date-cart">' . esc_html( $post_time ) . '</div>';

				}
			} else {
				$text_pre_order = '';
				if ( $no_date_label ) {
					$text_pre_order = esc_html( $no_date_label );
				} else {
					$text_pre_order = esc_html( $get_option['no_date_text'] );
				}
				$name .= '<div class="wpro-pre-order-availability-date-cart">' . esc_html( $text_pre_order ) . '</div>';

			}
		} else if ( $cart_item['data']->is_type( 'variation' ) ) {
			$variation_id = $cart_item['variation_id'];
			$is_pre_order = get_post_meta( $variation_id, '_wpro_variable_is_preorder', true );

			if ( empty( $is_pre_order ) || ( $is_pre_order == 'no' ) ) {
				return $name;
			}
			$pre_date      = get_post_meta( $variation_id, '_wpro_date_variable', true );
			$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
			$time_str      = strtotime( $date_time );
			$time_total    = $gmt_offdet + $time_str;
			$date_format   = date_i18n( get_option( 'date_format' ), $time_total );
			$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
			$pre_time      = get_post_meta( $variation_id, '_wpro_time_variable', true );
			$time_format   = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
			$time_total    = strtotime( $date_time );
			$date_label    = get_post_meta( $variation_id, '_wpro_date_label_variable', true );
			$no_date_label = get_post_meta( $variation_id, '_wpro_no_date_label_variable', true );
			if ( $date_label ) {
				$post_date = str_replace( "{availability_date}", $date_format, $date_label );
			} else {
				$post_date = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
			}
			$post_time = str_replace( "{availability_time}", $time_format, $post_date );
			if ( ! empty( $pre_date ) ) {
				if ( $date_now < $time_total ) {
					$name .= '<div class="wpro-pre-order-availability-date-cart">' . esc_html( $post_time ) . '</div>';
				}
			} else {
				$text_pre_order = '';
				if ( $no_date_label ) {
					$text_pre_order = esc_html( $no_date_label );
				} else {
					$text_pre_order = esc_html( $get_option['no_date_text'] );
				}
				$name .= '<div class="wpro-pre-order-availability-date-cart">' . esc_html( $text_pre_order ) . '</div>';
			}
		}

		return $name;
	}

	/**
	 *  Pre order date simple
	 */
	public function pre_order_simple_date() {
		global $product;
		$id           = $product->get_id();
		$is_pre_order = get_post_meta( $id, '_simple_preorder', true );
		$get_option   = get_option( 'pre_order_setting_default' );
		if ( $is_pre_order == 'yes' ) {
			if ( $product->is_type( 'simple' ) ) {
				$pre_date      = get_post_meta( $id, '_wpro_date', true );
				$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
				$pre_time      = get_post_meta( $id, '_wpro_time', true );
				$gmt_offdet    = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
				$time_str      = strtotime( $date_time );
				$time_total    = $gmt_offdet + $time_str;
				$date_now      = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
				$date_format   = date_i18n( get_option( 'date_format' ), $time_total );
				$time_format   = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
				$date_label    = get_post_meta( $id, '_wpro_date_label', true );
				$no_date_label = get_post_meta( $id, '_wpro_no_date_label', true );
				if ( $date_label ) {
					$post_date = str_replace( "{availability_date}", $date_format, $date_label );
				} else {
					$post_date = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
				}
				$post_time = str_replace( "{availability_time}", $time_format, $post_date );
				if ( ! empty( $pre_date ) ) {
					if ( $date_now < $time_total ) {
						?>
                        <div class="wpro-pre-order-availability-date">
							<?php
							esc_html_e( $post_time )
							?>
                        </div>
						<?php
					}
				} else {
					?>
                    <div class="wpro-pre-order-availability-date">
						<?php
						if ( $no_date_label ) {
							esc_html_e( $no_date_label );
						} else {
							esc_html_e( $get_option['no_date_text'] );
						}
						?>
                    </div>
					<?php
				}
			}
		}
	}

	public function pre_order_simple_shop_page() {
		global $product;
		$id           = $product->get_id();
		$is_pre_order = get_post_meta( $id, '_simple_preorder', true );
		if ( $is_pre_order == 'yes' ) {
			$get_option = get_option( 'pre_order_setting_default' );
			if ( $product->is_type( 'simple' ) ) {
				$pre_date      = get_post_meta( $id, '_wpro_date', true );
				$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
				$gmt_offdet    = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
				$time_str      = strtotime( $date_time );
				$time_total    = $gmt_offdet + $time_str;
				$date_now      = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
				$date_format   = date_i18n( get_option( 'date_format' ), $time_total );
				$pre_time      = get_post_meta( $id, '_wpro_time', true );
				$time_format   = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
				$date_label    = get_post_meta( $id, '_wpro_date_label', true );
				$no_date_label = get_post_meta( $id, '_wpro_no_date_label', true );
				if ( $date_label ) {
					$post_date = str_replace( "{availability_date}", $date_format, $date_label );
				} else {
					$post_date = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
				}
				$post_time = str_replace( "{availability_time}", $time_format, $post_date );
				if ( ! empty( $pre_date ) ) {
					if ( $date_now < $time_total ) {
						?>
                        <div class="wpro-pre-order-shop-page">
							<?php
							esc_html_e( $post_time )
							?>
                        </div>
						<?php
					}
				} else {
					?>
                    <div class="wpro-pre-order-shop-page">
						<?php
						if ( $no_date_label ) {
							esc_html_e( $no_date_label );
						} else {
							esc_html_e( $get_option['no_date_text'] );
						}
						?>
                    </div>
					<?php
				}
			}
		}
	}

	/** Pre order date variable
	 *
	 * @param $data
	 * @param $product
	 * @param $variation
	 *
	 * @return mixed
	 */
	public function pre_order_variation_date( $data, $product, $variation ) {
		if ( $product->is_type( 'variable' ) ) {
			$id           = $variation->get_id();
			$is_pre_order = get_post_meta( $id, '_wpro_variable_is_preorder', true );
			if ( $is_pre_order == 'yes' ) {
				$get_option    = get_option( 'pre_order_setting_default' );
				$pre_date      = get_post_meta( $id, '_wpro_date_variable', true );
				$date_time     = date_i18n( 'Y-m-d H:i:s', $pre_date );
				$gmt_offdet    = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
				$time_str      = strtotime( $date_time );
				$time_total    = $gmt_offdet + $time_str;
				$date_now      = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
				$date_format   = date_i18n( get_option( 'date_format' ), $time_total );
				$pre_time      = get_post_meta( $id, '_wpro_time_variable', true );
				$time_format   = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
				$date_label    = get_post_meta( $id, '_wpro_date_label_variable', true );
				$no_date_label = get_post_meta( $id, '_wpro_no_date_label_variable', true );
				if ( $date_label ) {
					$post_date = str_replace( "{availability_date}", $date_format, $date_label );
				} else {
					$post_date = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
				}
				$post_time = str_replace( "{availability_time}", $time_format, $post_date );
				if ( ! empty( $pre_date ) ) {
					if ( $date_now < $time_total ) {
						$post_label_date = '<div class="wpro-pre-order-availability-date">' . esc_html( $post_time ) . '</div>';
					} else {
						$post_label_date = '';
					}
				} else {
					if ( $no_date_label ) {
						$post_label_date = '<div class="wpro-pre-order-availability-date">' . esc_html( $no_date_label ) . '</div>';
					} else {
						$post_label_date = '<div class="wpro-pre-order-availability-date">' . esc_html( $get_option['no_date_text'] ) . '</div>';
					}
				}
				$data['variation_description'] .= $post_label_date . apply_filters('wpro_break_point_variation_description','<br>');
			}
		}

		return $data;
	}

	/**Pre Order Price simple
	 *
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return float|mixed
	 */
	public function pre_order_price_change_product_simple( $price, $product ) {
		$id               = $product->get_id();
		$get_option       = get_option( 'pre_order_setting_default' );
		$is_pre_order     = get_post_meta( $id, '_simple_preorder', true );
		$manage_price     = get_post_meta( $id, '_wpro_manage_price', true );
		$regular_price    = get_post_meta( $id, '_regular_price', true );
		$adjustment_price = floatval( $product->get_meta( '_wpro_price', true ) );
		if ( $regular_price ) {
			if ( $is_pre_order == 'yes' && $manage_price == 'yes' && $adjustment_price != '' ) {
				if ( $product->is_type( 'simple' ) ) {
					$pre_date     = get_post_meta( $id, '_wpro_date', true );
					$date_time    = date_i18n( 'Y-m-d H:i:s', $pre_date );
					$gmt_offdet   = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
					$time_str     = strtotime( $date_time );
					$time_total   = $gmt_offdet + $time_str;
					$now_time     = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
					$sale_price   = get_post_meta( $id, '_sale_price', true );
					$price_type   = get_post_meta( $id, '_wpro_price_type', true );
					$amount_price = get_post_meta( $id, '_wpro_amount_price', true );
					switch ( $price_type ) {
						case 'manual':
							if ( $time_total > $now_time || $pre_date == '' ) {
								$price = $adjustment_price;
							}
							break;

						case 'discount':
							if ( $get_option['price_calculation'] == 'yes' ) {
								if ( $product->is_on_sale( $id ) ) {
									if ( $amount_price == 'fixed' ) {
										if ( $adjustment_price < $sale_price ) {
											$price = $sale_price - $adjustment_price;
										} else {
											$price = $sale_price - $sale_price;
										}
									} elseif ( $amount_price == 'percentage' ) {
										if ( $adjustment_price < 100 ) {
											$price_percentage = ( $adjustment_price / 100 ) * $sale_price;
											$price            = $sale_price - $price_percentage;
										} else {
											$price = $sale_price - $sale_price;
										}
									}
								} else {
									if ( $amount_price == 'fixed' ) {
										if ( $adjustment_price < $regular_price ) {
											$price = $regular_price - $adjustment_price;
										} else {
											$price = $regular_price - $regular_price;
										}
									} elseif ( $amount_price == 'percentage' ) {
										if ( $adjustment_price < 100 ) {
											$price_percentage = ( $adjustment_price / 100 ) * $regular_price;
											$price            = $regular_price - $price_percentage;
										} else {
											$price = $regular_price - $regular_price;
										}
									}
								}
							} else {
								if ( $time_total > $now_time || $pre_date == '' ) {
									if ( $amount_price == 'fixed' ) {
										if ( $adjustment_price < $regular_price ) {
											$price = $regular_price - $adjustment_price;
										} else {
											$price = $regular_price - $regular_price;
										}
									} elseif ( $amount_price == 'percentage' ) {
										if ( $adjustment_price < 100 ) {
											$price_percentage = ( $adjustment_price / 100 ) * $regular_price;
											$price            = $regular_price - $price_percentage;
										} else {
											$price = $regular_price - $regular_price;
										}
									}
								}
							}
							break;

						case 'markup':
							if ( $time_total > $now_time || $pre_date == '' ) {
								if ( $get_option['price_calculation'] == 'yes' ) {
									if ( $product->is_on_sale( $id ) ) {
										if ( $amount_price == 'fixed' ) {
											$price = $sale_price + $adjustment_price;
										} elseif ( $amount_price == 'percentage' ) {
											$price_percentage = ( $adjustment_price / 100 ) * $sale_price;
											$price            = $sale_price + $price_percentage;
										}
									} else {
										if ( $amount_price == 'fixed' ) {
											$price = $regular_price + $adjustment_price;
										} elseif ( $amount_price == 'percentage' ) {
											$price_percentage = ( $adjustment_price / 100 ) * $regular_price;
											$price            = $regular_price + $price_percentage;
										}
									}
								} else {
									if ( $amount_price == 'fixed' ) {
										$price = $regular_price + $adjustment_price;
									} elseif ( $amount_price == 'percentage' ) {
										$price_percentage = ( $adjustment_price / 100 ) * $regular_price;
										$price            = $regular_price + $price_percentage;
									}
								}
							}
							break;
					}
				}
			}
		}

		return $price;
	}

	/** Display markup price
	 *
	 * @param $price
	 * @param $product
	 *
	 * @return string
	 */
	public function pre_order_display_price_markup_discount_variation( $price, $product ) {
		$id                   = $product->get_id();
		$get_option           = get_option( 'pre_order_setting_default' );
		$is_pre_order         = get_post_meta( $id, '_simple_preorder', true );
		$regular_price        = $product->get_regular_price();
		$sale_price           = $product->get_sale_price();
		$adjustment_price     = floatval( $product->get_meta( '_wpro_price', true ) );
		$adjustment_price_var = floatval( $product->get_meta( '_wpro_price_variable', true ) );
		$manage_price         = get_post_meta( $id, '_wpro_manage_price', true );
		$price_type           = get_post_meta( $id, '_wpro_price_type', true );
		$is_pre_order_var     = get_post_meta( $id, '_wpro_variable_is_preorder', true );
		$price_type_var       = get_post_meta( $id, '_wpro_price_type_variable', true );
		$manage_price_var     = get_post_meta( $id, '_wpro_manage_price_variable', true );
		if ( $product->is_type( 'simple' ) ) {
			if ( $is_pre_order == 'yes' && $manage_price == 'yes' ) {
				if ( $get_option['price_calculation'] == 'yes' ) {
					if ( $product->is_on_sale( $id ) ) {
						if ( $price_type == 'manual' ) {
							$price = wc_format_sale_price( $regular_price, $adjustment_price );
						} elseif ( $price_type == 'discount' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						} elseif ( $price_type == 'markup' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						}
					} else {
						if ( $price_type == 'manual' ) {
							$price = wc_format_sale_price( $regular_price, $adjustment_price );
						} elseif ( $price_type == 'discount' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						} elseif ( $price_type == 'markup' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						}
					}
				} else {
					if ( $price_type == 'manual' ) {
						$price = wc_format_sale_price( $regular_price, $adjustment_price );
					} elseif ( $price_type == 'discount' ) {
						$price = wc_format_sale_price( $regular_price, $sale_price );
					} elseif ( $price_type == 'markup' ) {
						$price = wc_format_sale_price( $regular_price, $sale_price );
					}
				}
			}
		} elseif ( $product->is_type( 'variation' ) ) {
			if ( $is_pre_order_var == 'yes' && $manage_price_var == 'yes' ) {
				if ( $get_option['price_calculation'] == 'yes' ) {
					if ( $product->is_on_sale( $id ) ) {
						if ( $price_type_var == 'manual' ) {
							$price = wc_format_sale_price( $regular_price, $adjustment_price_var );
						} elseif ( $price_type_var == 'discount' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						} elseif ( $price_type_var == 'markup' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						}
					} else {
						if ( $price_type_var == 'manual' ) {
							$price = wc_format_sale_price( $regular_price, $adjustment_price_var );
						} elseif ( $price_type_var == 'discount' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						} elseif ( $price_type_var == 'markup' ) {
							$price = wc_format_sale_price( $regular_price, $sale_price );
						}
					}
				} else {
					if ( $price_type_var == 'manual' ) {
						$price = wc_format_sale_price( $regular_price, $adjustment_price_var );
					} elseif ( $price_type_var == 'discount' ) {
						$price = wc_format_sale_price( $regular_price, $sale_price );
					} elseif ( $price_type_var == 'markup' ) {
						$price = wc_format_sale_price( $regular_price, $sale_price );
					}
				}
			}
		}

		return $price;
	}

	/** Pre Order Price variation
	 *
	 * @param $price
	 * @param $product
	 *
	 * @return float|string
	 */
	public function pre_order_price_variable( $price, $product ) {
		$id            = $product->get_id();
		$get_option    = get_option( 'pre_order_setting_default' );
		$is_pre_order  = get_post_meta( $id, '_wpro_variable_is_preorder', true );
		$manage_price  = get_post_meta( $id, '_wpro_manage_price_variable', true );
		$regular_price = get_post_meta( $id, '_regular_price', true );
		$pre_price     = floatval( $product->get_meta( '_wpro_price_variable', true ) );
		if ( $regular_price ) {
			if ( $is_pre_order == 'yes' && $manage_price == 'yes' && $pre_price != '' ) {
				$sale_price   = get_post_meta( $id, '_sale_price', true );
				$pre_date     = get_post_meta( $id, '_wpro_date_variable', true );
				$date_time    = date_i18n( 'Y-m-d H:i:s', $pre_date );
				$gmt_offdet   = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
				$time_str     = strtotime( $date_time );
				$time_total   = $gmt_offdet + $time_str;
				$now_time     = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
				$price_type   = get_post_meta( $id, '_wpro_price_type_variable', true );
				$amount_price = get_post_meta( $id, '_wpro_amount_price_variable', true );
				switch ( $price_type ) {

					case 'manual':
						if ( $time_total > $now_time || $pre_date == '' ) {
							$price = $pre_price;
						}
						break;

					case 'discount':
						if ( $time_total > $now_time || $pre_date == '' ) {
							if ( $get_option['price_calculation'] == 'yes' ) {
								if ( $product->is_on_sale( $id ) ) {
									if ( $amount_price == 'fixed' ) {
										if ( $pre_price < $sale_price ) {
											$price = $sale_price - $pre_price;
										} else {
											$price = $sale_price - $sale_price;
										}
									} elseif ( $amount_price == 'percentage' ) {
										if ( $pre_price < 100 ) {
											$price_percentage = ( $pre_price / 100 ) * $sale_price;
											$price            = $sale_price - $price_percentage;
										} else {
											$price = $sale_price - $sale_price;
										}
									}
								} else {
									if ( $amount_price == 'fixed' ) {
										if ( $pre_price < $regular_price ) {
											$price = $regular_price - $pre_price;
										} else {
											$price = $regular_price - $regular_price;
										}
									} elseif ( $amount_price == 'percentage' ) {
										if ( $pre_price < 100 ) {
											$price_percentage = ( $pre_price / 100 ) * $regular_price;
											$price            = $regular_price - $price_percentage;
										} else {
											$price = $regular_price - $regular_price;
										}
									}
								}
							} else {
								if ( $amount_price == 'fixed' ) {
									if ( $pre_price < $regular_price ) {
										$price = $regular_price - $pre_price;
									} else {
										$price = $regular_price - $regular_price;
									}
								} elseif ( $amount_price == 'percentage' ) {
									if ( $pre_price < 100 ) {
										$price_percentage = ( $pre_price / 100 ) * $regular_price;
										$price            = $regular_price - $price_percentage;
									} else {
										$price = $regular_price - $regular_price;
									}
								}
							}
						}
						break;

					case 'markup':
						if ( $time_total > $now_time || $pre_date == '' ) {
							if ( $get_option['price_calculation'] == 'yes' ) {
								if ( $product->is_on_sale( $id ) ) {
									if ( $amount_price == 'fixed' ) {
										$price = $sale_price + $pre_price;
									} elseif ( $amount_price == 'percentage' ) {
										$price_percentage = ( $pre_price / 100 ) * $sale_price;
										$price            = $sale_price + $price_percentage;
									}
								} else {
									if ( $amount_price == 'fixed' ) {
										$price = $regular_price + $pre_price;
									} elseif ( $amount_price == 'percentage' ) {
										$price_percentage = ( $pre_price / 100 ) * $regular_price;
										$price            = $regular_price + $price_percentage;
									}
								}
							} else {
								if ( $amount_price == 'fixed' ) {
									$price = $regular_price + $pre_price;
								} elseif ( $amount_price == 'percentage' ) {
									$price_percentage = ( $pre_price / 100 ) * $regular_price;
									$price            = $regular_price + $price_percentage;
								}
							}
						}
						break;
				}
			}
		}

		return $price;
	}
}

