<?php

/**
 * Class SALES_COUNTDOWN_TIMER_Frontend_Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SALES_COUNTDOWN_TIMER_Frontend_Archive_Page {
	protected $settings;
	protected $return;
	protected $progress_bar_html;
	protected $id;
	protected $index;
	protected $position;
	protected $sale_from_date;
	protected $sale_from_time;
	protected $sale_to_date;
	protected $sale_to_time;

	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new SALES_COUNTDOWN_TIMER_Data();
			add_action( 'wp', array( $this, 'update_price' ) );

			add_action( 'woocommerce_new_order_item', array( $this, 'woocommerce_new_order_item' ), 10, 3 );
			/*countdown timer position*/
			add_action( 'woocommerce_before_template_part', array( $this, 'countdown_before_template_loop' ) );
			add_action( 'woocommerce_after_template_part', array( $this, 'countdown_after_template_loop' ) );
			add_filter( 'woocommerce_product_get_image', array( $this, 'woocommerce_product_get_image' ), 99, 2 );
			add_filter( 'woocommerce_loop_add_to_cart_link', array(
				$this,
				'woocommerce_loop_add_to_cart_link'
			), 99, 2 );
		}
	}

	public function update_price() {
		if ( is_admin() ) {
			return;
		}
		if ( is_tax( 'product_cat' ) || is_post_type_archive( 'product' ) ) {
			/*shop and category page*/
			global $wp_query;
			$products = array();
			if ( $wp_query->have_posts() ) {
				while ( $wp_query->have_posts() ) {
					$wp_query->the_post();
					$products[] = wc_get_product( get_the_ID() );
				}
			}
			// Reset Post Data
			wp_reset_postdata();
			if ( count( $products ) ) {
				foreach ( $products as $product ) {
//					$product_id = $product_obj->ID;
//					$product    = wc_get_product( $product_id );
					if ( ! $product || ! is_object( $product ) ) {
						continue;
					}
					$product_id = $product->get_id();
					if ( $product ) {
						if ( $product->is_type( 'variable' ) ) {
							$variations = $product->get_children();
							if ( is_array( $variations ) && count( $variations ) ) {
								foreach ( $variations as $variation_key => $variation_id ) {
									if ( ! get_transient( 'woo_sctr_update_variable_price_start_sale_' . $variation_id ) ) {
										delete_transient( 'wc_var_prices_' . $product_id );
										set_transient( 'woo_sctr_update_variable_price_start_sale_' . $variation_id, $variation_id );
									}
									$variation = wc_get_product( $variation_id );
									if ( $variation ) {
										if ( ! $variation->get_sale_price( 'edit' ) ) {
											continue;
										}
										if ( $variation->get_regular_price( 'edit' ) != $variation->get_price( 'edit' ) ) {
											if ( $variation->get_date_on_sale_from( 'edit' ) && $variation->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $variation->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
												update_post_meta( $variation_id, '_price', $variation->get_regular_price( 'edit' ) );
												$variation->set_price( $variation->get_regular_price( 'edit' ) );
											} elseif ( $variation->get_date_on_sale_to( 'edit' ) && $variation->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $variation->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
												update_post_meta( $variation_id, '_sale_price_old_woo_ctr', $variation->get_sale_price( 'edit' ) );
												$regular_price = $variation->get_regular_price();
												$variation->set_price( $regular_price );
												$variation->set_sale_price( '' );
												$variation->set_date_on_sale_to( '' );
												$variation->set_date_on_sale_from( '' );
												$variation->save();
												delete_post_meta( $variation_id, '_woo_ctr_product_sold_quantity' );
											}
										}
									}
								}
							}

						} else {
							if ( ! $product->get_sale_price( 'edit' ) ) {
								continue;
							}
							if ( $product->get_regular_price( 'edit' ) != $product->get_price( 'edit' ) ) {
								if ( $product->get_date_on_sale_from( 'edit' ) && $product->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $product->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
									update_post_meta( $product_id, '_price', $product->get_regular_price( 'edit' ) );
									$product->set_price( $product->get_regular_price( 'edit' ) );
								} elseif ( $product->get_date_on_sale_to( 'edit' ) && $product->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $product->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
									update_post_meta( $product_id, '_sale_price_old_woo_ctr', $product->get_sale_price( 'edit' ) );
									$regular_price = $product->get_regular_price();
									$product->set_price( $regular_price );
									$product->set_sale_price( '' );
									$product->set_date_on_sale_to( '' );
									$product->set_date_on_sale_from( '' );
									$product->save();
									delete_post_meta( $product_id, '_woo_ctr_product_sold_quantity' );
								}
							} else {
								if ( $product->get_date_on_sale_from( 'edit' ) && $product->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) >= $product->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
									update_post_meta( $product_id, '_price', $product->get_sale_price( 'edit' ) );
									$product->set_price( $product->get_sale_price( 'edit' ) );
								}
							}
						}
					}

				}
			}
		} elseif ( is_checkout() || is_cart() ) {
			$cart     = WC()->cart->get_cart();
			$products = array();
			if ( is_array( $cart ) && count( $cart ) ) {
				foreach ( $cart as $cart_item ) {
					if ( $cart_item['variation_id'] ) {
						$products[] = $cart_item['variation_id'];
					} else {
						$products[] = $cart_item['product_id'];
					}
				}
			}
			if ( count( $products ) ) {
				foreach ( $products as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( $product ) {
						if ( ! $product->get_sale_price( 'edit' ) ) {
							continue;
						}
						if ( $product->get_regular_price( 'edit' ) != $product->get_price( 'edit' ) ) {
							if ( $product->get_date_on_sale_from( 'edit' ) && $product->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $product->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
								update_post_meta( $product_id, '_price', $product->get_regular_price( 'edit' ) );
								$product->set_price( $product->get_regular_price( 'edit' ) );
							} elseif ( $product->get_date_on_sale_to( 'edit' ) && $product->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $product->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
								update_post_meta( $product_id, '_sale_price_old_woo_ctr', $product->get_sale_price( 'edit' ) );
								$regular_price = $product->get_regular_price();
								$product->set_price( $regular_price );
								$product->set_sale_price( '' );
								$product->set_date_on_sale_to( '' );
								$product->set_date_on_sale_from( '' );
								$product->save();
								delete_post_meta( $product_id, '_woo_ctr_product_sold_quantity' );
							}
						}
					}
				}
			}
		}

	}


	public function get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type ) {
		$wc_product                 = wc_get_product( $product_id );
		$progress_bar               = $wc_product->get_meta( '_woo_ctr_enable_progress_bar', true );
		$progress_bar_goal          = $wc_product->get_meta( '_woo_ctr_progress_bar_goal', true );
		$progress_bar_initial       = $wc_product->get_meta( '_woo_ctr_progress_bar_initial', true );
		$progress_bar_width         = absint( $this->settings->get_progress_bar_width()[ $index ] );
		$progress_bar_height        = absint( $this->settings->get_progress_bar_height()[ $index ] );
		$progress_bar_color         = $this->settings->get_progress_bar_color()[ $index ];
		$progress_bar_bg_color      = $this->settings->get_progress_bar_bg_color()[ $index ];
		$progress_bar_border_radius = absint( $this->settings->get_progress_bar_border_radius()[ $index ] );
		$progress_bar_html          = '';
		if ( $progress_bar_real_quantity >= 0 && $progress_bar && $progress_bar_goal ) {
			$progress_bar_real_quantity += $progress_bar_initial;
			$quantity_sold              = $progress_bar_real_quantity;
			$quantity_left              = (int) ( $progress_bar_goal - $progress_bar_real_quantity );
			$percentage_sold            = (int) ( 100 * ( $progress_bar_real_quantity / $progress_bar_goal ) );
			$percentage_left            = 100 - $percentage_sold;
			if ( $progress_bar_real_quantity >= $progress_bar_goal ) {
				$progress_bar_real_quantity = $progress_bar_goal;
			}
			$progress_bar_fill = 100 * ( $progress_bar_real_quantity / $progress_bar_goal );
			if ( $progress_bar_type == 'decrease' ) {
				$progress_bar_fill = 100 - $progress_bar_fill;
			}
			if ( $progress_bar_fill < 0 ) {
				$progress_bar_fill = 0;
			} elseif ( $progress_bar_fill > 100 ) {
				$progress_bar_fill = 100;
			}

			$progress_bar_message = str_replace( '{quantity_left}', $quantity_left, $progress_bar_message );
			$progress_bar_message = str_replace( '{quantity_sold}', $quantity_sold, $progress_bar_message );
			$progress_bar_message = str_replace( '{percentage_sold}', $percentage_sold, $progress_bar_message );
			$progress_bar_message = str_replace( '{percentage_left}', $percentage_left, $progress_bar_message );
			$progress_bar_message = str_replace( '{goal}', $progress_bar_goal, $progress_bar_message );

			ob_start();
			?>
            <div class="woo-sctr-progress-bar-wrap-container">
                <div class="woo-sctr-progress-bar-wrap" style="<?php if ( $progress_bar_width ) {
					echo 'width:' . esc_attr( $progress_bar_width ) . 'px;';
				}
				if ( $progress_bar_height ) {
					echo 'height:' . esc_attr( $progress_bar_height ) . 'px;';
				}
				if ( $progress_bar_bg_color ) {
					echo 'background:' . esc_attr( $progress_bar_bg_color ) . ';';
				}
				if ( '' !== $progress_bar_border_radius ) {
					echo 'border-radius:' . esc_attr( $progress_bar_border_radius ) . 'px;';
				} ?>">
                    <div class="woo-sctr-progress-bar-fill"
                         style="width: <?php echo esc_attr( $progress_bar_fill ) . '%;';
					     if ( $progress_bar_color )
						     echo 'background:' . esc_attr( $progress_bar_color ) . ';' ?>"></div>
                </div>
				<?php
				?>
                <div class="woo-sctr-progress-bar-message"><?php echo wp_kses_post( $progress_bar_message ); ?></div>
				<?php
				?>
            </div>
			<?php
			$progress_bar_html = ob_get_clean();
		}

		return $progress_bar_html;
	}

	public function get_progress_bar_real_quantity( $data, $order_status ) {
		$progress_bar_real_quantity = 0;
		if ( is_array( $order_status ) && empty( $order_status ) ) {
			$order_status = array_keys( wc_get_order_statuses() );
		}
		if ( is_array( $data ) && count( $data ) && is_array( $order_status ) && count( $order_status ) ) {
			foreach ( $data as $key => $value ) {
				$order = get_post( $value['id'] );
				if ( $order && in_array( $order->post_status, $order_status ) ) {
					$progress_bar_real_quantity += $value['quantity'];
				}
			}
		}

		return $progress_bar_real_quantity;
	}

	public function woocommerce_product_get_image( $html, $product ) {
		if ( is_admin() ) {
			return $html;
		}
		if ( ! $product ) {
			return $html;
		}
		if ( ! $product->is_in_stock() ) {
			return $html;
		}
		if ( ! $product->get_sale_price( 'edit' ) ) {
			return $html;
		}
		if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' ) ) {
			return $html;
		}
		$product_id = $product->get_id();
		$id         = $product->get_meta( '_woo_ctr_select_countdown_timer', true );

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_id() );
			if ( $index === false ) {
				return $html;
			}
			if ( ! $this->settings->get_active()[ $index ] ) {
				return $html;
			}
			if ( $this->settings->get_archive_page_position()[ $index ] !== 'product_image' ) {
				return $html;
			}
			if ( is_tax( 'product_cat' ) && ! $this->settings->get_category_page()[ $index ] ) {
				return $html;
			} elseif ( is_post_type_archive( 'product' ) && ! $this->settings->get_shop_page()[ $index ] ) {
				return $html;
			} elseif ( ! is_tax( 'product_cat' ) && ! is_post_type_archive( 'product' ) ) {
				return $html;
			} elseif ( is_cart() ) {
				return $html;
			}
			$offset         = get_option( 'gmt_offset' );
			$sale_from      = ( $product->get_date_on_sale_from( 'edit' ) ) ? ( $product->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_to        = ( $product->get_date_on_sale_to( 'edit' ) ) ? ( $product->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_from_date = date( 'Y-m-d', $sale_from );
			$sale_to_date   = date( 'Y-m-d', $sale_to );
			$sale_from_time = $sale_from - strtotime( $sale_from_date );
			$sale_to_time   = $sale_to - strtotime( $sale_to_date );
			$sale_from_time = woo_ctr_time_revert( $sale_from_time );
			$sale_to_time   = woo_ctr_time_revert( $sale_to_time );
//		    calculate sold quantity during campaign
			$data                       = $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ? ( $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ) : array();
			$order_status               = $this->settings->get_progress_bar_order_status()[ $index ] ? explode( ',', $this->settings->get_progress_bar_order_status()[ $index ] ) : array();
			$progress_bar_message       = $this->settings->get_progress_bar_message()[ $index ];
			$progress_bar_type          = $this->settings->get_progress_bar_type()[ $index ];
			$progress_bar_real_quantity = $this->get_progress_bar_real_quantity( $data, $order_status );

			$progress_bar_html = $this->get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type );
			if ( $this->settings->get_progress_bar_position()[ $index ] == 'above_countdown' ) {
				return $progress_bar_html . '<div class="woo-sctr-countdown-timer-product-image-cate-shop-wrap">' . $html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '"]' ) . '</div>';

			} else {
				return '<div class="woo-sctr-countdown-timer-product-image-cate-shop-wrap">' . $html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '"]' ) . '</div>' . $progress_bar_html;

			}
		}

		return $html;
	}

	public function woocommerce_loop_add_to_cart_link( $html, $product ) {
		if ( is_admin() ) {
			return $html;
		}
		if ( ! $product ) {
			return $html;
		}
		if ( ! $product->is_in_stock() ) {
			return $html;
		}
		if ( ! $product->get_sale_price( 'edit' ) ) {
			return $html;
		}
		if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' ) ) {
			return $html;
		}
		$product_id = $product->get_id();
		$id         = $product->get_meta( '_woo_ctr_select_countdown_timer', true );

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_id() );
			if ( $index === false ) {
				return $html;
			}
			if ( ! $this->settings->get_active()[ $index ] ) {
				return $html;
			}
			if ( ! in_array( $this->settings->get_archive_page_position()[ $index ], array(
				'before_cart',
				'after_cart'
			) ) ) {
				return $html;
			}
			if ( is_tax( 'product_cat' ) && ! $this->settings->get_category_page()[ $index ] ) {
				return $html;
			} elseif ( is_post_type_archive( 'product' ) && ! $this->settings->get_shop_page()[ $index ] ) {
				return $html;
			} elseif ( ! is_tax( 'product_cat' ) && ! is_post_type_archive( 'product' ) ) {
				return $html;
			}
			$offset         = get_option( 'gmt_offset' );
			$sale_from      = ( $product->get_date_on_sale_from( 'edit' ) ) ? ( $product->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_to        = ( $product->get_date_on_sale_to( 'edit' ) ) ? ( $product->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_from_date = date( 'Y-m-d', $sale_from );
			$sale_to_date   = date( 'Y-m-d', $sale_to );
			$sale_from_time = $sale_from - strtotime( $sale_from_date );
			$sale_to_time   = $sale_to - strtotime( $sale_to_date );
			$sale_from_time = woo_ctr_time_revert( $sale_from_time );
			$sale_to_time   = woo_ctr_time_revert( $sale_to_time );
//		    calculate sold quantity during campaign
			$data                       = $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ? ( $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ) : array();
			$order_status               = $this->settings->get_progress_bar_order_status()[ $index ] ? explode( ',', $this->settings->get_progress_bar_order_status()[ $index ] ) : array();
			$progress_bar_message       = $this->settings->get_progress_bar_message()[ $index ];
			$progress_bar_type          = $this->settings->get_progress_bar_type()[ $index ];
			$progress_bar_real_quantity = $this->get_progress_bar_real_quantity( $data, $order_status );

			$progress_bar_html = $this->get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type );
			if ( $this->settings->get_archive_page_position()[ $index ] == 'before_cart' ) {
				if ( $this->settings->get_progress_bar_position()[ $index ] == 'above_countdown' ) {
					return $progress_bar_html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '"]' ) . $html;

				} else {
					return do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '"]' ) . $progress_bar_html . $html;

				}
			} else {
				if ( $this->settings->get_progress_bar_position()[ $index ] == 'above_countdown' ) {
					return $html . $progress_bar_html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '"]' );

				} else {
					return $html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '"]' ) . $progress_bar_html;

				}
			}

		}

		return $html;
	}


	public function woocommerce_new_order_item( $item_id, $item, $order_id ) {
		$product_id = wc_get_order_item_meta( $item_id, '_product_id', true );
		$product    = wc_get_product( $product_id );
		if ( $product && $product->is_on_sale() ) {
			$data   = $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ? ( $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ) : array();
			$data[] = array( 'id' => $order_id, 'quantity' => wc_get_order_item_meta( $item_id, '_qty', true ) );
			update_post_meta( $product_id, '_woo_ctr_product_sold_quantity', $data );
		}

	}

	public function countdown_before_template_loop( $template_name ) {
		if ( ! in_array( $template_name, array(
			'loop/price.php',
			'loop/sale-flash.php',
		) ) ) {
			return;
		}

		global $product;
		if ( ! $product ) {
			return;
		}
		if ( ! $product->is_in_stock() ) {
			return;
		}
		if ( ! $product->get_sale_price( 'edit' ) ) {
			return;
		}
		if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' ) ) {
			return;
		}
		$product_id = $product->get_id();
		$id         = $product->get_meta( '_woo_ctr_select_countdown_timer', true );

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_id() );
			if ( $index === false ) {
				return;
			}
			if ( ! $this->settings->get_active()[ $index ] ) {
				return;
			}
			switch ( $this->settings->get_archive_page_position()[ $index ] ) {
				case 'before_saleflash':
					if ( $template_name !== 'loop/sale-flash.php' ) {
						return;
					}
					break;
				case 'before_price':
					if ( $template_name !== 'loop/price.php' ) {
						return;
					}
					break;
				default:
					return;
			}
			if ( is_tax( 'product_cat' ) && ! $this->settings->get_category_page()[ $index ] ) {
				return;
			} elseif ( is_post_type_archive( 'product' ) && ! $this->settings->get_shop_page()[ $index ] ) {

				return;

			} elseif ( ! is_tax( 'product_cat' ) && ! is_post_type_archive( 'product' ) ) {
				return;
			}
			$offset         = get_option( 'gmt_offset' );
			$sale_from      = ( $product->get_date_on_sale_from( 'edit' ) ) ? ( $product->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_to        = ( $product->get_date_on_sale_to( 'edit' ) ) ? ( $product->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_from_date = date( 'Y-m-d', $sale_from );
			$sale_to_date   = date( 'Y-m-d', $sale_to );
			$sale_from_time = $sale_from - strtotime( $sale_from_date );
			$sale_to_time   = $sale_to - strtotime( $sale_to_date );
			$sale_from_time = woo_ctr_time_revert( $sale_from_time );
			$sale_to_time   = woo_ctr_time_revert( $sale_to_time );
//		    calculate sold quantity during campaign
			$data                       = $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ? ( $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ) : array();
			$order_status               = $this->settings->get_progress_bar_order_status()[ $index ] ? explode( ',', $this->settings->get_progress_bar_order_status()[ $index ] ) : array();
			$progress_bar_message       = $this->settings->get_progress_bar_message()[ $index ];
			$progress_bar_type          = $this->settings->get_progress_bar_type()[ $index ];
			$progress_bar_real_quantity = $this->get_progress_bar_real_quantity( $data, $order_status );
			$progress_bar_html          = $this->get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type );
			if ( $this->settings->get_progress_bar_position()[ $index ] == 'above_countdown' ) {
				echo $progress_bar_html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . esc_attr( $id ) . '" sale_from_date="' . esc_attr( $sale_from_date ) . '" sale_from_time="' . esc_attr( $sale_from_time ) . '" sale_to_date="' . esc_attr( $sale_to_date ) . '" sale_to_time="' . esc_attr( $sale_to_time ) . '"]' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . esc_attr( $id ) . '" sale_from_date="' . esc_attr( $sale_from_date ) . '" sale_from_time="' . esc_attr( $sale_from_time ) . '" sale_to_date="' . esc_attr( $sale_to_date ) . '" sale_to_time="' . esc_attr( $sale_to_time ) . '"]' ) . $progress_bar_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

	}

	public function countdown_after_template_loop( $template_name ) {
		if ( ! in_array( $template_name, array(
			'loop/price.php',
			'loop/sale-flash.php',
		) ) ) {
			return;
		}

		global $product;
		if ( ! $product ) {
			return;
		}
		if ( ! $product->is_in_stock() ) {
			return;
		}
		if ( ! $product->get_sale_price( 'edit' ) ) {
			return;
		}
		if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' ) ) {
			return;
		}
		$product_id = $product->get_id();
		$id         = $product->get_meta( '_woo_ctr_select_countdown_timer', true );

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_id() );
			if ( $index === false ) {
				return;
			}
			if ( ! $this->settings->get_active()[ $index ] ) {
				return;
			}
			switch ( $this->settings->get_archive_page_position()[ $index ] ) {
				case 'after_saleflash':
					if ( $template_name !== 'loop/sale-flash.php' ) {
						return;
					}
					break;
				case 'after_price':
					if ( $template_name !== 'loop/price.php' ) {
						return;
					}
					break;
				default:
					return;
			}

			if ( is_tax( 'product_cat' ) && ! $this->settings->get_category_page()[ $index ] ) {

				return;
			} elseif ( is_post_type_archive( 'product' ) && ! $this->settings->get_shop_page()[ $index ] ) {

				return;

			} elseif ( ! is_tax( 'product_cat' ) && ! is_post_type_archive( 'product' ) ) {
				return;
			}
			$offset         = get_option( 'gmt_offset' );
			$sale_from      = ( $product->get_date_on_sale_from( 'edit' ) ) ? ( $product->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_to        = ( $product->get_date_on_sale_to( 'edit' ) ) ? ( $product->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_from_date = date( 'Y-m-d', $sale_from );
			$sale_to_date   = date( 'Y-m-d', $sale_to );
			$sale_from_time = $sale_from - strtotime( $sale_from_date );
			$sale_to_time   = $sale_to - strtotime( $sale_to_date );
			$sale_from_time = woo_ctr_time_revert( $sale_from_time );
			$sale_to_time   = woo_ctr_time_revert( $sale_to_time );
//		    calculate sold quantity during campaign
			$data                       = $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ? ( $product->get_meta( '_woo_ctr_product_sold_quantity', true ) ) : array();
			$order_status               = $this->settings->get_progress_bar_order_status()[ $index ] ? explode( ',', $this->settings->get_progress_bar_order_status()[ $index ] ) : array();
			$progress_bar_message       = $this->settings->get_progress_bar_message()[ $index ];
			$progress_bar_type          = $this->settings->get_progress_bar_type()[ $index ];
			$progress_bar_real_quantity = $this->get_progress_bar_real_quantity( $data, $order_status );
			$progress_bar_html          = $this->get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type );
			if ( $this->settings->get_progress_bar_position()[ $index ] == 'above_countdown' ) {
				echo $progress_bar_html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . esc_attr( $id ) . '" sale_from_date="' . esc_attr( $sale_from_date ) . '" sale_from_time="' . esc_attr( $sale_from_time ) . '" sale_to_date="' . esc_attr( $sale_to_date ) . '" sale_to_time="' . esc_attr( $sale_to_time ) . '"]' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . esc_attr( $id ) . '" sale_from_date="' . esc_attr( $sale_from_date ) . '" sale_from_time="' . esc_attr( $sale_from_time ) . '" sale_to_date="' . esc_attr( $sale_to_date ) . '" sale_to_time="' . esc_attr( $sale_to_time ) . '"]' ) . $progress_bar_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

	}
}