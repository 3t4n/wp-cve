<?php

/**
 * Class SALES_COUNTDOWN_TIMER_Frontend_Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SALES_COUNTDOWN_TIMER_Frontend_Single_Product {
	protected $settings;
	protected $return;
	protected $progress_bar_html;
	protected $id;
	protected $index;
	protected $is_ajax_variation;
	protected $position;
	protected $sale_from_date;
	protected $sale_from_time;
	protected $sale_to_date;
	protected $sale_to_time;

	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new SALES_COUNTDOWN_TIMER_Data();
			add_action( 'wp', array( $this, 'update_price' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

			/*countdown timer position*/
			add_action( 'woocommerce_before_template_part', array( $this, 'countdown_before_template' ) );
			add_action( 'woocommerce_after_template_part', array( $this, 'countdown_after_template' ) );
			add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'countdown_cart_before' ) );
			add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'countdown_cart_after' ) );
			add_action( 'woocommerce_product_thumbnails', array( $this, 'woocommerce_product_thumbnails' ), 99, 1 );
			add_action( 'woocommerce_single_product_summary', array(
				$this,
				'woocommerce_single_product_summary'
			), 99, 1 );
			add_filter( 'woocommerce_available_variation', array( $this, 'woocommerce_available_variation' ), 10, 3 );
		}
	}


	public function woocommerce_available_variation( $variation_data, $parent, $variation ) {
		$variation_id = $variation->get_id();
		if ( ! $variation->get_sale_price( 'edit' ) ) {
			return $variation_data;
		}
		if ( $variation->get_regular_price( 'edit' ) != $variation->get_price( 'edit' ) ) {
			if ( $variation->get_date_on_sale_from( 'edit' ) && $variation->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $variation->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
				update_post_meta( $variation_id, '_price', $variation->get_regular_price( 'edit' ) );
				$variation->set_price( $variation->get_regular_price( 'edit' ) );
			}
		}
		if ( $variation->get_date_on_sale_to( 'edit' ) && $variation->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $variation->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
			update_post_meta( $variation_id, '_sale_price_old_woo_ctr', $variation->get_sale_price( 'edit' ) );
			$regular_price = $variation->get_regular_price();
			$variation->set_price( $regular_price );
			$variation->set_sale_price( '' );
			$variation->set_date_on_sale_to( '' );
			$variation->set_date_on_sale_from( '' );
			$variation->save();
			delete_post_meta( $variation_id, '_woo_ctr_product_sold_quantity' );
		}
		if ( ! $variation->is_in_stock() ) {
			return $variation_data;
		}
		if ( ! $variation->get_date_on_sale_from( 'edit' ) && ! $variation->get_date_on_sale_to( 'edit' ) ) {
			return $variation_data;
		}
		$id = $variation->get_meta( '_woo_ctr_select_countdown_timer', true );
		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_id() );
			if ( $index === false ) {
				return $variation_data;
			}
			if ( ! $this->settings->get_active()[ $index ] ) {
				return $variation_data;
			}
			$offset         = get_option( 'gmt_offset' );
			$sale_from      = ( $variation->get_date_on_sale_from( 'edit' ) ) ? ( $variation->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_to        = ( $variation->get_date_on_sale_to( 'edit' ) ) ? ( $variation->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
			$sale_from_date = date( 'Y-m-d', $sale_from );
			$sale_to_date   = date( 'Y-m-d', $sale_to );
			$sale_from_time = $sale_from - strtotime( $sale_from_date );
			$sale_to_time   = $sale_to - strtotime( $sale_to_date );
			$sale_from_time = woo_ctr_time_revert( $sale_from_time );
			$sale_to_time   = woo_ctr_time_revert( $sale_to_time );
//		    calculate sold quantity during campaign
			$data                       = $variation->get_meta( '_woo_ctr_product_sold_quantity', true ) ? ( $variation->get_meta( '_woo_ctr_product_sold_quantity', true ) ) : array();
			$order_status               = $this->settings->get_progress_bar_order_status()[ $index ] ? explode( ',', $this->settings->get_progress_bar_order_status()[ $index ] ) : array();
			$progress_bar_message       = $this->settings->get_progress_bar_message()[ $index ];
			$progress_bar_type          = $this->settings->get_progress_bar_type()[ $index ];
			$progress_bar_real_quantity = $this->get_progress_bar_real_quantity( $data, $order_status );
			$progress_bar_html          = $this->get_progress_bar_html( $variation_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type );
			if ( $this->settings->get_progress_bar_position()[ $index ] == 'above_countdown' ) {
				$variation_data['variation_description'] .= $progress_bar_html . do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '" is_variation="1"]' );
			} else {
				$variation_data['variation_description'] .= do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $id . '" sale_from_date="' . $sale_from_date . '" sale_from_time="' . $sale_from_time . '" sale_to_date="' . $sale_to_date . '" sale_to_time="' . $sale_to_time . '" is_variation="1"]' ) . $progress_bar_html;
			}
		}

		return $variation_data;
	}

	public function update_price() {
		if ( is_admin() ) {
			return;
		}
		if ( is_product() && is_single() ) {
			/*single product page*/
			global $post;
			$product_id = $post->ID;
			$product    = wc_get_product( $product_id );
			if ( $product ) {
				if ( $product->is_type( 'variable' ) ) {
					$variations = $product->get_children();
					if ( is_array( $variations ) && count( $variations ) ) {
						$this->is_ajax_variation = count( $variations ) > apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
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
									}
								}
								if ( $variation->get_date_on_sale_to( 'edit' ) && $variation->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $variation->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
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

				} else {
					if ( ! $product->get_sale_price( 'edit' ) ) {
						return;
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

					if ( ! $product->is_in_stock() ) {
						return;
					}
					if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' ) ) {
						return;
					}

					$id = $product->get_meta( '_woo_ctr_select_countdown_timer', true );

					if ( $id !== '' ) {
						$index = array_search( $id, $this->settings->get_id() );
						if ( $index === false ) {
							return;
						}
						if ( ! $this->settings->get_active()[ $index ] ) {
							return;
						}

						$offset    = get_option( 'gmt_offset' );
						$sale_from = ( $product->get_date_on_sale_from( 'edit' ) ) ? ( $product->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
						$sale_to   = ( $product->get_date_on_sale_to( 'edit' ) ) ? ( $product->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;

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
						$this->id                   = $id;
						$this->index                = $index;
						$this->position             = $this->settings->get_position()[ $index ];
						$this->sale_from_date       = $sale_from_date;
						$this->sale_from_time       = $sale_from_time;
						$this->sale_to_date         = $sale_to_date;
						$this->sale_to_time         = $sale_to_time;
						$this->progress_bar_html    = $this->get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type );
					}
				}
			}
		}
	}

	public function wp_enqueue_scripts() {
		if ( $this->id && isset( $this->settings->get_stick_to_top()[ $this->index ] ) && $this->settings->get_stick_to_top()[ $this->index ] ) {
			wp_enqueue_style( 'sales-countdown-timer-single-product', SALES_COUNTDOWN_TIMER_CSS . 'sales-countdown-timer-single-product.css', array(), SALES_COUNTDOWN_TIMER_VERSION );
			wp_enqueue_script( 'sales-countdown-timer-single-product', SALES_COUNTDOWN_TIMER_JS . 'sales-countdown-timer-single-product.js', array( 'jquery' ), SALES_COUNTDOWN_TIMER_VERSION );
		}

		if ( $this->is_ajax_variation ) {
			if ( ! wp_script_is( 'woo-sctr-shortcode-script', 'enqueued' ) ) {
				wp_enqueue_script( 'woo-sctr-shortcode-script' );
			}
			if ( ! wp_script_is( 'woo-sctr-shortcode-style', 'enqueued' ) ) {
				wp_enqueue_style( 'woo-sctr-shortcode-style' );
			}
		}
	}

	public function woocommerce_product_thumbnails() {
		if ( is_admin() ) {
			return;
		}
		if ( ! is_product() || ! is_single() ) {
			return;
		}
		if ( $this->id && $this->position == 'product_image' ) {
			ob_start();
		}
	}

	public function woocommerce_single_product_summary() {
		if ( is_admin() ) {
			return;
		}
		if ( ! is_product() || ! is_single() ) {
			return;
		}
		if ( $this->id && $this->position == 'product_image' ) {
			$html            = ob_get_clean();
			$html            = str_replace( "\n", '', $html );
			$html            = str_replace( "\r", '', $html );
			$html            = str_replace( "\t", '', $html );
			$html            = str_replace( "\l", '', $html );
			$html            = str_replace( "\0", '', $html );
			$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );
			if ( $this->settings->get_progress_bar_position()[ $this->index ] == 'above_countdown' ) {
				$html = str_replace( '</figure>', '</figure>' . '<div class="woo-sctr-single-product-container">' . $this->progress_bar_html . '<div class="woo-sctr-countdown-timer-product-image-wrap">' . $countdown_timer . '</div></div>', $html );
			} else {
				$html = str_replace( '</figure>', '</figure><div class="woo-sctr-single-product-container"><div class="woo-sctr-countdown-timer-product-image-wrap">' . $countdown_timer . '</div>' . $this->progress_bar_html . '</div>', $html );
			}
			echo $html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type ) {
		$wc_product                 = wc_get_product( $product_id );
		$progress_bar               = $wc_product->get_meta( '_woo_ctr_enable_progress_bar', true );
		$progress_bar_goal          = (int) $wc_product->get_meta( '_woo_ctr_progress_bar_goal', true );
		$progress_bar_initial       = (int) $wc_product->get_meta( '_woo_ctr_progress_bar_initial', true );
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
			$progress_bar_fill = (int) ( 100 * ( $progress_bar_real_quantity / $progress_bar_goal ) );
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
					echo 'width:' . $progress_bar_width . 'px;';
				}
				if ( $progress_bar_height ) {
					echo 'height:' . $progress_bar_height . 'px;';
				}
				if ( $progress_bar_bg_color ) {
					echo 'background:' . $progress_bar_bg_color . ';';
				}
				if ( '' !== $progress_bar_border_radius ) {
					echo 'border-radius:' . $progress_bar_border_radius . 'px;';
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

	public function countdown_before_template( $template_name ) {
		if ( ! $this->id ) {
			return;
		}
		switch ( $template_name ) {
			case 'single-product/sale-flash.php':
				if ( $this->position == 'before_saleflash' ) {
					$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );
					if ( $this->settings->get_progress_bar_position()[ $this->index ] == 'above_countdown' ) {
						echo '<div class="woo-sctr-single-product-container">' . $this->progress_bar_html . $countdown_timer . '</div>';
					} else {
						echo '<div class="woo-sctr-single-product-container">' . $countdown_timer . $this->progress_bar_html . '</div>';
					}
				}
				break;
			case 'single-product/price.php':
				if ( $this->position == 'before_price' ) {
					$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );
					if ( $this->settings->get_progress_bar_position()[ $this->index ] == 'above_countdown' ) {
						echo '<div class="woo-sctr-single-product-container">' . $this->progress_bar_html . $countdown_timer . '</div>';
					} else {
						echo '<div class="woo-sctr-single-product-container">' . $countdown_timer . $this->progress_bar_html . '</div>';
					}
				}
				break;
			default:
				return;
		}
	}

	public function countdown_after_template( $template_name ) {
		if ( ! $this->id ) {
			return;
		}
		switch ( $template_name ) {
			case 'single-product/sale-flash.php':
				if ( $this->position == 'after_saleflash' ) {
					$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );
					if ( $this->settings->get_progress_bar_position()[ $this->index ] == 'above_countdown' ) {
						echo '<div class="woo-sctr-single-product-container">' . $this->progress_bar_html . $countdown_timer . '</div>';
					} else {
						echo '<div class="woo-sctr-single-product-container">' . $countdown_timer . $this->progress_bar_html . '</div>';
					}
				}
				break;
			case 'single-product/price.php':
				if ( $this->position == 'after_price' ) {
					$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );
					if ( $this->settings->get_progress_bar_position()[ $this->index ] == 'above_countdown' ) {
						echo '<div class="woo-sctr-single-product-container">' . $this->progress_bar_html . $countdown_timer . '</div>';
					} else {
						echo '<div class="woo-sctr-single-product-container">' . $countdown_timer . $this->progress_bar_html . '</div>';
					}
				}
				break;
			default:
				return;
		}
	}

	public function countdown_cart_before() {
		if ( ! $this->id ) {
			return;
		}
		if ( $this->position == 'before_cart' ) {
			$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );
			if ( $this->settings->get_progress_bar_position()[ $this->index ] == 'above_countdown' ) {
				echo '<div class="woo-sctr-single-product-container">' . $this->progress_bar_html . $countdown_timer . '</div>';
			} else {
				echo '<div class="woo-sctr-single-product-container">' . $countdown_timer . $this->progress_bar_html . '</div>';
			}
		}
	}

	public function countdown_cart_after() {
		if ( ! $this->id ) {
			return;
		}
		if ( $this->position == 'after_cart' ) {
			$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );
			if ( $this->settings->get_progress_bar_position()[ $this->index ] == 'above_countdown' ) {
				echo '<div class="woo-sctr-single-product-container">' . $this->progress_bar_html . $countdown_timer . '</div>';
			} else {
				echo '<div class="woo-sctr-single-product-container">' . $countdown_timer . $this->progress_bar_html . '</div>';
			}
		}
	}
}