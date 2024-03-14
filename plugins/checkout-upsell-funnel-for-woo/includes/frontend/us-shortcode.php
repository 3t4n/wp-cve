<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Us_Shortcode {
	protected $frontend, $settings, $is_mobile, $pd_template;

	public function __construct() {
		$this->settings = new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		if ( ! $this->settings->enable( 'us_' ) ) {
			return;
		}
		$this->frontend    = 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend';
		$this->is_mobile   = wp_is_mobile();
		$this->pd_template = $this->settings->get_params( 'us_pd_template' ) ?: 1;
		add_action( 'init', array( $this, 'shortcode_init' ) );
		add_action( 'viwcuf_us_before_shop_loop_item_title', array( $this, 'viwcuf_us_before_shop_loop_item_title' ) );
		add_action( 'viwcuf_us_shop_loop_item_title', array( $this, 'viwcuf_us_shop_loop_item_title' ) );
		add_action( 'viwcuf_us_single_product_summary', array( $this, 'viwcuf_us_single_product_summary' ), 10, 5 );
		add_action( 'viwcuf_us_simple_add_to_cart', array( $this, 'viwcuf_us_simple_add_to_cart' ), 10, 4 );
		add_action( 'viwcuf_us_variable_add_to_cart', array( $this, 'viwcuf_us_variable_add_to_cart' ), 10, 4 );
		add_action( 'viwcuf_us_variation_add_to_cart', array( $this, 'viwcuf_us_variation_add_to_cart' ), 10, 4 );
		if ( $this->pd_template == 1 ) {
			add_action( 'viwcuf_us_after_shop_loop_item_title', array( $this, 'viwcuf_us_product_rate' ), 5, 1 );
			add_action( 'viwcuf_us_after_shop_loop_item_title', array( $this, 'viwcuf_us_product_price' ), 10, 3 );
		}
	}

	public function shortcode_init() {
		add_shortcode( 'viwcuf_checkout_upsell_funnel', array( $this, 'viwcuf_checkout_upsell_funnel' ) );
	}

	public function viwcuf_checkout_upsell_funnel( $atts ) {
		extract( shortcode_atts( array(
			'rule'        => '',
			'position'    => '',
			'product_ids' => '',
			'column'      => '4',
			'row'         => '1',
		), $atts ) );
		if ( ! $rule ) {
			return false;
		}
		$ids   = (array)( $this->settings->get_params( 'us_ids' ) ?? array());
		$index = array_search( $rule, $ids );
		if ( $index !=0 ) {
			return false;
		}
		$product_ids = $product_ids ? explode( ',', $product_ids ) : false;
		if ( empty( $product_ids ) ) {
			return false;
		}
		$content1 = $this->settings->get_params( 'us_content' );
		$content  = explode( '{content}', $content1 );
		if ( count( $content ) >= 2 ) {
			$popup_before = $content[0];
			$popup_after  = $content[1];
		} else {
			return false;
		}
		$container_content1 = $this->settings->get_params( 'us_container_content');
		$container_content  = explode( '{product_list}', $container_content1 );
		if ( count( $container_content ) < 2 ) {
			return false;
		}
		$header_content       = $this->settings->get_params( 'us_header_content' );
		$footer_content       = $this->settings->get_params( 'us_footer_content' );
		$checkout_time_enable = $this->settings->get_params( 'us_time_checkout' );
		$time                 = $this->settings->get_params( 'us_time' );
		if ( ! $time || ! empty( WC()->session->get( 'viwcuf_us_time_pause', 0 ) ) ) {
			$countdown_timer = 0;
		} elseif ( ! $checkout_time_enable && ! in_array( $position, array( '0', 'footer' ) ) ) {
			$countdown_timer = 0;
		} elseif ( strstr( $content1 . $header_content . $footer_content . $container_content1, '{countdown_timer}' ) === false ) {
			$countdown_timer = 0;
		} else {
			$time_start = WC()->session->get( 'viwcuf_us_time_start', 0 );
			$now        = current_time( 'timestamp' );
			if ( $time_start ) {
				$now             = (int) $now;
				$time_start      = (int) $time_start;
				$countdown_timer = $time - $now + $time_start;
			} else {
				WC()->session->set( 'viwcuf_us_time_start', $now );
				WC()->session->set( 'viwcuf_us_time_end', $now + $time );
				$countdown_timer = $time;
			}
			if ( $countdown_timer < 1 ) {
				return false;
			}
		}
		$discount_type   = $this->settings->get_current_setting( 'us_discount_type', $index );
		$discount_amount = (int) $this->settings->get_current_setting( 'us_discount_amount', $index );
		$product_qty     = 1;
		$discount_amount = $discount_amount < 0 ? 0 : $discount_amount;
		if ( in_array( $discount_type, array( '1', '3' ) ) ) {
			$discount_amount = $discount_amount > 100 ? 100 : $discount_amount;
		}
		$wrap_class = array( 'vi-wcuf-us-shortcode-wrap' );
		$wrap_class = implode( ' ', $wrap_class );
		if ( ! ( $product_html = $this->get_product_list( $position, $product_ids, $product_qty, $discount_type, $discount_amount ) ) ) {
			return false;
		}
		ob_start();
		?>
        <div class="<?php echo esc_attr( $wrap_class ); ?>">
			<?php
			if ( ! empty( $popup_before ) ) {
				?>
                <div class="vi-wcuf-us-shortcode-element-wrap vi-wcuf-us-shortcode-element1-wrap">
                    <div class="vi-wcuf-us-shortcode-element vi-wcuf-us-shortcode-element1">
						<?php echo wp_kses_post( $popup_before ); ?>
                    </div>
                </div>
				<?php
			}
			?>
            <div class="vi-wcuf-us-shortcode-element-wrap vi-wcuf-us-shortcode-element2-wrap">
                <div class="vi-wcuf-us-shortcode-element vi-wcuf-us-shortcode-element2">
					<?php
					if ( ! empty( $header_content ) ) {
						?>
                        <div class="vi-wcuf-us-shortcode-header-wrap">
							<?php echo wp_kses_post( $header_content ); ?>
                        </div>
						<?php
					}
					?>
                    <div class="vi-wcuf-us-shortcode-content-wrap">
                        <div class="vi-wcuf-us-shortcode-content">
							<?php
							if ( ! empty( $container_content[0] ) ) {
								?>
                                <div class="vi-wcuf-us-shortcode-content-1">
									<?php echo wp_kses_post( $container_content[0] ); ?>
                                </div>
								<?php
							}
							?>
                            <div class="vi-wcuf-us-shortcode-content-2">
                                <div class="vi-wcuf-us-shortcode-products-wrap"
                                     data-item_per_row="<?php echo esc_attr( $this->settings->get_params( 'us_desktop_item_per_row' ) ?: 4 ); ?>"
                                     data-item_per_row_mobile="<?php echo esc_attr( $this->settings->get_params( 'us_mobile_item_per_row' ) ?: 1 ); ?>"
                                     data-rtl="<?php echo esc_attr( is_rtl() ? 1 : 0 ); ?>">
                                    {product_list}
                                </div>
                            </div>
							<?php
							if ( ! empty( $container_content[1] ) ) {
								?>
                                <div class="vi-wcuf-us-shortcode-content-3">
									<?php echo wp_kses_post( $container_content[1] ); ?>
                                </div>
								<?php
							}
							?>
                        </div>
                    </div>
					<?php
					if ( ! empty( $footer_content ) ) {
						?>
                        <div class="vi-wcuf-us-shortcode-footer-wrap">
							<?php echo wp_kses_post( $footer_content ); ?>
                        </div>
						<?php
					}
					?>
                </div>
            </div>
			<?php
			if ( ! empty( $popup_after ) ) {
				?>
                <div class="vi-wcuf-us-shortcode-element-wrap vi-wcuf-us-shortcode-element3-wrap">
                    <div class="vi-wcuf-us-shortcode-element vi-wcuf-us-shortcode-element3">
						<?php echo wp_kses_post( $popup_after ); ?>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
		<?php
		$html = ob_get_clean();
		$html = str_replace( '{title}', $this->get_popup_title( $index, $discount_type, $discount_amount ), $html );
		$html = str_replace( '{product_list}', $product_html, $html );
		$html = str_replace( '{countdown_timer}', $this->get_countdown_timer( $countdown_timer, strpos( $html, '{continue_button}' ) ), $html );
		$html = str_replace( '{continue_button}', $this->get_popup_bt_conitnue( $position ), $html );
		$html = str_replace( '{add_all_to_cart}', $this->pd_template === '2' ? '' : $this->get_popup_bt_alltc(), $html );

		return $html;
	}

	public function get_popup_title( $index, $discount_type, $discount_amount ) {
		ob_start();
		?>
        <div class="vi-wcuf-us-shortcode-title-wrap">
			<?php echo wp_kses_post( $this->settings->get_params( 'us_title' ) ); ?>
        </div>
		<?php
		$title = ob_get_clean();
		if ( $index === false ) {
			return $title;
		}
		switch ( $discount_type ) {
			case '1':
				//Percentage(%) regular price
				$discount_type   = esc_html__( 'regular price', 'checkout-upsell-funnel-for-woo' );
				$discount_amount = $discount_amount . '%';
				break;
			case '2':
				//Fixed($) regular price
				$discount_type   = esc_html__( 'regular price', 'checkout-upsell-funnel-for-woo' );
				$discount_amount = $this->frontend::change_price_3rd( $discount_amount );
				$discount_amount = wc_price( $discount_amount );
				break;
			case '3':
				//Percentage(%) current price
				$discount_type   = esc_html__( 'current price', 'checkout-upsell-funnel-for-woo' );
				$discount_amount = $discount_amount . '%';
				break;
			case '4':
				//Fixed($) current price
				$discount_type   = esc_html__( 'current price', 'checkout-upsell-funnel-for-woo' );
				$discount_amount = $this->frontend::change_price_3rd( $discount_amount );
				$discount_amount = wc_price( $discount_amount );
				break;
			default:
				$discount_type   = '';
				$discount_amount = 0;
				$discount_amount = wc_price( $discount_amount );
		}
		$title = str_replace( '{discount_type}', $discount_type, $title );
		$title = str_replace( '{discount_amount}', $discount_amount, $title );

		return $title;
	}

	public function get_product_list( $position, $product_ids, $product_qty, $discount_type, $discount_amount ) {
		if ( empty( $product_ids ) ) {
			return false;
		}
		$check_position       = in_array( $position, array( '0', 'footer' ) ) ? 1 : 0;
		$us_pd_redirect       =  $this->settings->get_params( 'us_pd_redirect' );
		$html                 = '';
		$product_class        = array(
			'vi-wcuf-product vi-wcuf-us-product',
			'vi-wcuf-us-product-' . $this->pd_template,
		);
		$product_class[]      = ! $us_pd_redirect ? 'vi-wcuf-us-product-not-redirect' : '';
		$product_class        = trim( implode( ' ', $product_class ) );
		if ( is_plugin_active('litespeed-cache/litespeed-cache.php')){
			if ( function_exists( 'wp_calculate_image_srcset' ) ) {
				remove_all_filters('wp_calculate_image_srcset');
			}
			remove_all_filters('wp_get_attachment_image_src');
			remove_all_filters('wp_get_attachment_url');
		}
		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				continue;
			}
			if ( $product->managing_stock() && $product->get_stock_quantity() <= get_option( 'woocommerce_notify_no_stock_amount', 0 ) && 'no' === $product->get_backorders() ) {
				continue;
			}
			if ( $product->is_sold_individually() && $this->frontend::get_pd_qty_in_cart( $product_id ) ) {
				continue;
			}
			$product_url = $check_position || ! $us_pd_redirect ? '' : $product->get_permalink();
			ob_start();
			?>
            <div class="vi-wcuf-us-product-wrap-wrap">
                <div class="<?php echo esc_attr( $product_class ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>">
                    <div class="vi-wcuf-us-product-top">
						<?php
						if ( $product_url ) {
							?>
                            <a href="<?php echo $product_url ?>" target="_blank" class="vi-wcuf-us-item-url">
								<?php
								do_action( 'viwcuf_us_before_shop_loop_item_title', $product );
								?>
                            </a>
							<?php
						} else {
							do_action( 'viwcuf_us_before_shop_loop_item_title', $product );
						}
						?>
                    </div>
                    <div class="vi-wcuf-us-product-desc">
						<?php if ( $product_url ) {
							?>
                            <a href="<?php echo $product_url ?>" target="_blank" class="vi-wcuf-us-item-url">
								<?php
								do_action( 'viwcuf_us_shop_loop_item_title', $product );
								?>
                            </a>
							<?php
						} else {
							do_action( 'viwcuf_us_shop_loop_item_title', $product );
						}
						do_action( 'viwcuf_us_after_shop_loop_item_title', $product, $discount_type, $discount_amount );
						?>
                    </div>
                    <div class="vi-wcuf-us-product-controls">
                        <div class="vi-wcuf-us-cart">
							<?php do_action( 'viwcuf_us_single_product_summary', $product, $product_url, $product_qty, $discount_type, $discount_amount ); ?>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			$pd_html = ob_get_clean();
			$html    .= $pd_html;
		}

		return $html;
	}

	public function get_countdown_timer( $time, $continue_button = false ) {
		if ( ! $time ) {
			return '';
		}
		$time --;
		$message = $this->settings->get_params( 'us_countdown_message' );
		$message = explode( '{time}', $message );
		if ( empty( $message ) || count( $message ) < 2 ) {
			return '';
		}
		ob_start();
		?>
        <div class="vi-wcuf-us-shortcode-countdown-wrap">
			<?php
			if ( ! empty( $message[0] ) ) {
				?>
                <div class="vi-wcuf-us-shortcode-countdown-before-wrap">
					<?php echo wp_kses_post( $message[0] ); ?>
                </div>
				<?php
			}
			?>
            <div class="vi-wcuf-us-shortcode-countdown-container-wrap" data-count="<?php echo esc_attr( $time ); ?>">
                <span class="vi-wcuf-us-shortcode-countdown-value"><?php echo esc_html( $time ); ?></span>
            </div>
			<?php
			if ( ! empty( $message[1] ) ) {
				?>
                <div class="vi-wcuf-us-shortcode-countdown-after-wrap">
					<?php echo wp_kses_post( $message[1] ); ?>
                </div>
				<?php
			}
			?>
        </div>
		<?php
		$html = ob_get_clean();
		$html = str_replace( '{progress_bar}', $this->get_popup_progress_bar( $time, $continue_button ), $html );
		$html = str_replace( '{pause_button}', $this->get_popup_bt_pause( $continue_button ), $html );

		return $html;
	}

	public function get_popup_progress_bar( $time, $continue_button = false ) {
		$deg = floor( $time % 60 );
		$deg *= 6;
		ob_start();
		?>
        <div class="vi-wcuf-us-shortcode-progress_bar-wrap<?php echo  esc_attr($deg <= 180 ? '' : ' vi-wcuf-us-shortcode-progress_bar-wrap-over50' ); ?>">
            <div class="vi-wcuf-us-shortcode-progress_bar-circle">
				<?php
				if ( $this->settings->get_params( 'us_progress_bar_bt_pause' ) ) {
					echo sprintf( '{pause_button}' );
				}
				?>
            </div>
            <div class="vi-wcuf-us-shortcode-progress_bar-clipper">
                <div class="vi-wcuf-us-shortcode-progress_bar-first50<?php echo esc_attr($deg <= 180 ?  ' vi-wcuf-hidden'  : '') ?>"></div>
                <div class="vi-wcuf-us-shortcode-progress_bar-value" data-deg="<?php esc_attr_e( $deg ); ?>"></div>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		$html = str_replace( '{pause_button}', $this->get_popup_bt_pause( $continue_button ), $html );

		return $html;
	}

	public function get_popup_bt_pause( $continue_button = false ) {
		if ( $continue_button === false ) {
			return '';
		}
		ob_start();
		?>
        <div class="vi-wcuf-us-button-wrap vi-wcuf-us-shortcode-bt-pause-wrap">
            <div class="vi-wcuf-us-button vi-wcuf-us-shortcode-bt-pause">
				<?php echo wp_kses_post( $this->settings->get_params( 'us_bt_pause_title' ) ); ?>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		$html = str_replace( '{pause_icon}', '<i class="' . esc_attr( $this->settings->get_class_icon( $this->settings->get_params( 'us_pause_icon' ), 'pause_icons' ) ) . '"></i>', $html );

		return $html;
	}

	public function get_popup_bt_conitnue( $position ) {
		if ( ! in_array( $position, array( '0', 'footer' ) ) ) {
			return '';
		}
		ob_start();
		?>
        <div class="vi-wcuf-us-button-wrap vi-wcuf-us-shortcode-bt-continue-wrap">
            <div class="vi-wcuf-us-button vi-wcuf-us-shortcode-bt-continue">
				<?php echo wp_kses_post( $this->settings->get_params( 'us_bt_continue_title') ); ?>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		$html = str_replace( '{skip_icon}', '<i class="' . esc_attr( $this->settings->get_class_icon( $this->settings->get_params( 'us_skip_icon' ), 'skip_icons' ) ) . '"></i>', $html );

		return $html;
	}

	public function get_popup_bt_alltc() {
		ob_start();
		?>
        <button type="button" class="vi-wcuf-us-shortcode-bt-alltc button alt">
			<?php echo wp_kses_post( $this->settings->get_params( 'us_bt_alltc_title' ) ); ?>
        </button>
		<?php
		$html       = ob_get_clean();
		$icon       = $this->settings->get_params( 'us_alltc_icon' );
		$icon_class = $this->settings->get_class_icon( $icon, 'cart_icons' );
		$html       = str_replace( '{cart_icon}', '<i class="viwcuf_us_cart_icons ' . esc_attr( $icon_class ) . '"></i>', $html );

		return $html;
	}

	public function viwcuf_us_before_shop_loop_item_title( $product ) {
		$product_img = $product->get_image( 'woocommerce_thumbnail' );
		echo wp_kses_post( $product_img );
	}

	public function viwcuf_us_shop_loop_item_title( $product ) {
		$product_name = $product->get_name();
		echo sprintf( '<span class="woocommerce-loop-product__title" title="%s">%s</span>', $product_name, $product_name );
	}

	public function viwcuf_us_product_rate( $product ) {
		if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
			return;
		}
		$rating = $product->get_average_rating();
		if ( $rating > 0 ) {
			echo '<div class="vi-wcuf-us-item-rating">'. wc_get_rating_html( $rating ).'</div>';
		}
	}

	public function viwcuf_us_product_price( $product, $discount_type, $discount_amount ) {
		$this->frontend::product_price_html( $product, $discount_type, $discount_amount );
	}

	public function viwcuf_us_single_product_summary( $product, $product_url, $product_qty, $discount_type, $discount_amount ) {
		do_action( 'viwcuf_us_' . $product->get_type() . '_add_to_cart', $product, $product_qty, $discount_type, $discount_amount );
		switch ( $this->pd_template ) {
			case '2':
				$this->viwcuf_us_product_price( $product, $discount_type, $discount_amount );
				break;
			default:
				$this->get_product_bt_view_more( $product_url );
		}
	}

	private function get_product_bt_view_more( $product_url ) {
		if ( ! $product_url ) {
			return;
		}
		$title = apply_filters( 'viwcuf_make_product_view_more_text', esc_html__( 'View More', 'checkout-upsell-funnel-for-woo' ) );
		ob_start();
		?>
        <a href="<?php echo esc_attr( esc_url( $product_url ) ); ?>" target="_blank" class="vi-wcuf-us-item-view-more vi-wcuf-us-item-url button">
			<?php echo esc_html( $title ); ?>
        </a>
		<?php
		$html = ob_get_clean();
		echo wp_kses_post( $html );
	}

	public function viwcuf_us_simple_add_to_cart( $product, $product_qty, $discount_type, $discount_amount ) {
		$product_id   = $product->get_id();
		$product_name = $product->get_name();
		if ( $this->pd_template ==1 && $this->frontend::get_pd_qty_in_cart( $product_id, 'viwcuf_us_product' ) ) {
			return;
		}
		?>
        <div class="vi-wcuf-us-cart-form" data-product_id="<?php echo esc_attr( $product_id ); ?>">
			<?php
			switch ( $this->pd_template ) {
				case '2':
					$this->get_product_bt_atc();
					$this->get_product_quantity( $product, $product_id, $product_name, $product_qty );
					break;
				default:
					$this->get_product_quantity( $product, $product_id, $product_name, $product_qty );
					$this->get_product_bt_atc();
			}
			?>
            <input type="hidden" name="add-to-cart" class="vi-wcuf-add-to-cart" value=""/>
            <input type="hidden" name="product_id" class="vi-wcuf-product_id" value=""/>
            <input type="hidden" name="variation_id" class="variation_id" value="0"/>
            <input type="hidden" name="viwcuf_us_product_id" class="viwcuf_us_product_id" value="1"/>
        </div>
		<?php
	}

	public function viwcuf_us_variable_add_to_cart( $product, $product_qty, $discount_type, $discount_amount ) {
		$product_id          = $product->get_id();
		if ( $this->pd_template ==1 && $this->frontend::get_pd_qty_in_cart( $product_id, 'viwcuf_us_product' ) ) {
			return;
		}
		$product_name        = $product->get_name();
		$variation_count     = count( $product->get_children() );
		$get_variations      = $variation_count <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
		$attributes          = $product->get_variation_attributes();
		$selected_attributes = $product->get_default_attributes();
		if ( empty( $attributes ) ) {
			return;
		}
		if ( $get_variations ) {
			add_filter( 'sctv_get_countdown_on_available_variation', function () {
				return false;
			} );
			$available_variations = $product->get_available_variations();
			if ( empty( $available_variations ) ) {
				return;
			}
			$available_variations_t = array();
			foreach ( $available_variations as $k => $variation ) {
				$variation_id     = $variation['variation_id'] ?? 0;
				$variation_object = wc_get_product( absint( $variation_id ) );
				if ( ! $variation_object || ! $variation_object->is_in_stock() ) {
					continue;
				}
				if ( $variation_object->managing_stock() && $variation_object->get_stock_quantity() <= get_option( 'woocommerce_notify_no_stock_amount', 0 ) && 'no' === $variation_object->get_backorders() ) {
					continue;
				}
				ob_start();
				$this->viwcuf_us_product_price( $variation_object, $discount_type, $discount_amount );
				$price_html                     = ob_get_clean();
				$variation['viwcuf_price_html'] = $price_html;
				$available_variations_t[]       = $variation;
			}
			$variations_json = wp_json_encode( $available_variations_t );
			$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
		} else {
			$variations_attr = false;
		}
		switch ( $this->pd_template ) {
			case '2':
				?>
                <div class="vi-wcuf-us-cart-form vi-wcuf-cart-form-swatches vi-wcuf-cart-form-variable" data-product_id="<?php echo esc_attr( $product_id ); ?>"
                     data-variation_count="<?php echo esc_attr( $variation_count ); ?>"
                     data-product_variations="<?php echo esc_attr( $variations_attr ); ?>">
					<?php
					$this->get_product_bt_atc();
					?>
                    <div class="vi-wcuf-swatches-selected-wrap"><span class="vi-wcuf-swatches-selected"></span></div>
                    <div class="vi-wcuf-swatches-control-wrap-wrap vi-wcuf-disable">
                        <div class="vi-wcuf-swatches-control-wrap">
                            <div class="vi-wcuf-swatches-control-header-wrap">
								<?php
								echo apply_filters( 'vi_wcuf_us_swatches_control_header', esc_html__( 'Variations', 'checkout-upsell-funnel-for-woo' ) );
								?>
                            </div>
                            <div class="vi-wcuf-swatches-control-content-wrap vi-wcuf-swatches-wrap-wrap">
								<?php
								foreach ( $attributes as $attribute_name => $options ) {
									$selected = $selected_attributes[ $attribute_name ] ?? $product->get_variation_default_attribute( $attribute_name ) ?? '';
									echo sprintf( '<div class="vi-wcuf-swatches-control-content"><div class="vi-wcuf-swatches-control-content-title">%s</div>', wc_attribute_label( $attribute_name, $product ) );
									echo sprintf( '<div class="vi-wcuf-swatches-wrap vi-wcuf-swatches-control-content-value"><div class="vi-wcuf-swatches-value value" data-selected="%s">', esc_attr($selected) );
									wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcuf_us_dropdown_variation_attribute_options', array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
										'selected'  => $selected,
										'class'     => 'viwcuf-attribute-options'
									), $attribute_name, $product ) );
									echo sprintf( '</div></div></div>' );
								}
								?>
                                <div class="vi-wcuf-swatches-control-content vi-wcuf-swatches-control-content-price vi-wcuf-disable">
                                    <div class="vi-wcuf-swatches-control-content-title vi-wcuf-swatches-control-content-price-title">
										<?php
										echo apply_filters( 'vi_wcuf_us_swatches_control_price_title', esc_html__( 'Price', 'checkout-upsell-funnel-for-woo' ) );
										?>
                                    </div>
                                    <div class="vi-wcuf-swatches-control-content-value vi-wcuf-swatches-control-content-price-value">
                                        <div class="woocommerce-variation single_variation"></div>
                                    </div>
                                </div>
                                <div class="vi-wcuf-swatches-control-content vi-wcuf-swatches-control-content-quantity">
                                    <div class="vi-wcuf-swatches-control-content-title vi-wcuf-swatches-control-content-quantity-title">
										<?php
										echo apply_filters( 'vi_wcuf_us_swatches_control_quantity_title', esc_html__( 'Quantity', 'checkout-upsell-funnel-for-woo' ) );
										?>
                                    </div>
                                    <div class="vi-wcuf-swatches-control-content-value vi-wcuf-swatches-control-content-quantity-value">
										<?php
										$this->get_product_quantity( $product, $product_id, $product_name, $product_qty );
										?>
                                    </div>
                                </div>
                            </div>
                            <div class="vi-wcuf-swatches-control-footer-wrap">
                                <button type="button" class="vi-wcuf-swatches-control-footer-bt-ok button">
									<?php
									echo apply_filters( 'vi_wcuf_us_swatches_control_bt_confirm_title', esc_html__( 'ADD TO CART', 'checkout-upsell-funnel-for-woo' ) );
									?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="add-to-cart" class="vi-wcuf-add-to-cart" value=""/>
                    <input type="hidden" name="product_id" class="vi-wcuf-product_id" value=""/>
                    <input type="hidden" name="variation_id" class="variation_id" value="0"/>
                    <input type="hidden" name="viwcuf_us_product_id" class="viwcuf_us_product_id" value="1"/>
                </div>
				<?php
				break;
			default:
				?>
                <div class="vi-wcuf-us-cart-form vi-wcuf-cart-form-swatches vi-wcuf-cart-form-variable" data-product_id="<?php echo esc_attr( $product_id ); ?>"
                     data-variation_count="<?php echo esc_attr( $variation_count ); ?>"
                     data-product_variations="<?php echo esc_attr( $variations_attr ); ?>">
                    <div class="vi-wcuf-swatches-wrap-wrap">
						<?php
						foreach ( $attributes as $attribute_name => $options ) {
							$selected = $selected_attributes[ $attribute_name ] ?? $product->get_variation_default_attribute( $attribute_name ) ?? '';
							echo sprintf( '<div class="vi-wcuf-swatches-wrap"><div class="vi-wcuf-swatches-value value" data-selected="%s">', esc_attr($selected) );
							wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcuf_us_dropdown_variation_attribute_options', array(
								'options'                 => $options,
								'attribute'               => $attribute_name,
								'product'                 => $product,
								'selected'                => $selected,
								'class'                   => 'viwcuf-attribute-options',
								'viwpvs_swatches_disable' => 1,
							), $attribute_name, $product ) );
							echo sprintf( '</div></div>' );
						}
						?>
                    </div>
                    <div class="single_variation_wrap">
                        <div class="woocommerce-variation single_variation"></div>
                        <div class="woocommerce-variation-add-to-cart variations_button">
							<?php
							$this->get_product_quantity( $product, $product_id, $product_name, $product_qty );
							$this->get_product_bt_atc();
							?>
                            <input type="hidden" name="add-to-cart" class="vi-wcuf-add-to-cart" value=""/>
                            <input type="hidden" name="product_id" class="vi-wcuf-product_id" value=""/>
                            <input type="hidden" name="variation_id" class="variation_id" value="0"/>
                            <input type="hidden" name="viwcuf_us_product_id" class="viwcuf_us_product_id" value="1"/>
                        </div>
                    </div>
                </div>
			<?php
		}
	}

	public function viwcuf_us_variation_add_to_cart( $product, $product_qty, $discount_type, $discount_amount ) {
		$product_id    = $product->get_id();
		if ( $this->pd_template ==1 && $this->frontend::get_pd_qty_in_cart( $product_id, 'viwcuf_us_product' ) ) {
			return;
		}
		$product_name  = $product->get_name();
		$pd_parent_ids = $product->get_parent_id();
		$attributes    = $product->get_attributes();
		if ( empty( $attributes ) ) {
			return;
		}
		$count_value = count( $attributes );
		foreach ( $attributes as $attribute_name => $options ) {
			if ( $options ) {
				$count_value --;
			}
		}
		switch ( $this->pd_template ) {
			case 2:
				?>
                <div class="vi-wcuf-us-cart-form<?php echo  esc_attr($count_value ? ' vi-wcuf-cart-form-swatches'  : ''); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>">
					<?php
					$this->get_product_bt_atc();
					if ( $count_value ) {
						$product_parent = wc_get_product( $pd_parent_ids );
						$parent_attr    = $product_parent->get_variation_attributes();
						?>
                        <div class="vi-wcuf-swatches-selected-wrap"><span class="vi-wcuf-swatches-selected"></span></div>
                        <div class="vi-wcuf-swatches-control-wrap-wrap vi-wcuf-disable">
                            <div class="vi-wcuf-swatches-control-wrap">
                                <div class="vi-wcuf-swatches-control-header-wrap">
									<?php
									echo apply_filters( 'vi_wcuf_us_swatches_control_header', esc_html__( 'Variations', 'checkout-upsell-funnel-for-woo' ) );
									?>
                                </div>
                                <div class="vi-wcuf-swatches-control-content-wrap vi-wcuf-swatches-wrap-wrap">
									<?php
									foreach ( $attributes as $attribute_name => $options ) {
										if ( $options ) {
											$name = 'attribute_' . sanitize_title( $attribute_name );
											?>
                                            <input type="hidden" id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" class="viwcuf-attribute-options"
                                                   name="<?php echo esc_attr( $name ) ?>" data-attribute_name="<?php echo esc_attr( $name ); ?>"
                                                   value="<?php echo esc_attr( $options ); ?>">
											<?php
										} else {
											$attribute   = wc_attribute_label( $attribute_name, $product_parent );
											$options     = $parent_attr[ $attribute_name ] ?? $parent_attr[ $attribute ] ?? $options;
											$attribute_t = isset( $parent_attr[ $attribute_name ] ) ? $attribute_name : $attribute;
											echo sprintf( '<div class="vi-wcuf-swatches-control-content"><div class="vi-wcuf-swatches-control-content-title">%s</div>', $attribute );
											echo sprintf( '<div class="vi-wcuf-swatches-wrap vi-wcuf-swatches-control-content-value"><div class="vi-wcuf-swatches-value value">' );
											wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcuf_us_dropdown_variation_attribute_options', array(
												'options'   => $options,
												'attribute' => $attribute_t,
												'product'   => $product_parent ?? '',
												'class'     => 'viwcuf-attribute-options',
											), $attribute_name, $product ) );
											echo sprintf( '</div></div></div>' );
										}
									}
									?>
                                    <div class="vi-wcuf-swatches-control-content vi-wcuf-swatches-control-content-price vi-wcuf-disable">
                                        <div class="vi-wcuf-swatches-control-content-title vi-wcuf-swatches-control-content-price-title">
											<?php
											echo apply_filters( 'vi_wcuf_us_swatches_control_price_title', esc_html__( 'Price', 'checkout-upsell-funnel-for-woo' ) );
											?>
                                        </div>
                                        <div class="vi-wcuf-swatches-control-content-value vi-wcuf-swatches-control-content-price-value">
                                            <div class="woocommerce-variation single_variation"></div>
                                        </div>
                                    </div>
                                    <div class="vi-wcuf-swatches-control-content vi-wcuf-swatches-control-content-quantity">
                                        <div class="vi-wcuf-swatches-control-content-title vi-wcuf-swatches-control-content-quantity-title">
											<?php
											echo apply_filters( 'vi_wcuf_us_swatches_control_quantity_title', esc_html__( 'Quantity', 'checkout-upsell-funnel-for-woo' ) );
											?>
                                        </div>
                                        <div class="vi-wcuf-swatches-control-content-value vi-wcuf-swatches-control-content-quantity-value">
											<?php
											$this->get_product_quantity( $product, $product_id, $product_name, $product_qty );
											?>
                                        </div>
                                    </div>
                                </div>
                                <div class="vi-wcuf-swatches-control-footer-wrap">
                                    <button type="button" class="vi-wcuf-swatches-control-footer-bt-ok button">
										<?php
										echo apply_filters( 'vi_wcuf_us_swatches_control_bt_confirm_title', esc_html__( 'ADD TO CART', 'checkout-upsell-funnel-for-woo' ) );
										?>
                                    </button>
                                </div>
                            </div>
                        </div>
						<?php
					} else {
						$this->get_product_quantity( $product, $product_id, $product_name, $product_qty );
						foreach ( $attributes as $attribute_name => $options ) {
							$name = 'attribute_' . sanitize_title( $attribute_name );
							?>
                            <input type="hidden" id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" class="viwcuf-attribute-options"
                                   name="<?php echo esc_attr( $name ) ?>" data-attribute_name="<?php echo esc_attr( $name ); ?>"
                                   value="<?php echo esc_attr( $options ); ?>">
							<?php
						}
					}
					?>
                    <input type="hidden" name="add-to-cart" class="vi-wcuf-add-to-cart" value=""/>
                    <input type="hidden" name="product_id" class="vi-wcuf-product_id" value=""/>
                    <input type="hidden" name="variation_id" class="variation_id" value="<?php echo esc_attr( $product_id ); ?>"/>
                    <input type="hidden" name="viwcuf_us_product_id" class="viwcuf_us_product_id" value="1"/>
                </div>
				<?php
				break;
			default:
				$div_class = array( 'vi-wcuf-swatches-wrap-wrap' );
				if ( $count_value ) {
					$product_parent = wc_get_product( $pd_parent_ids );
					$parent_attr    = $product_parent->get_variation_attributes();
				} else {
					$div_class[] = 'vi-wcuf-disable';
				}
				$div_class = implode( ' ', $div_class );
				?>
                <div class="vi-wcuf-us-cart-form vi-wcuf-cart-form-swatches" data-product_id="<?php echo esc_attr( $product_id ); ?>">
                    <div class="<?php echo esc_attr( $div_class ) ?>">
						<?php
						foreach ( $attributes as $attribute_name => $options ) {
							if ( $options ) {
								$name = 'attribute_' . sanitize_title( $attribute_name );
								?>
                                <div class="vi-wcuf-swatches-wrap vi-wcuf-disable">
                                    <input type="hidden" id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" class="viwcuf-attribute-options"
                                           name="<?php echo esc_attr( $name ) ?>" data-attribute_name="<?php echo esc_attr( $name ); ?>"
                                           value="<?php echo esc_attr( $options ); ?>">
                                </div>
								<?php
							} else {
								$attribute   = wc_attribute_label( $attribute_name, $product_parent ?? $product );
								$options     = $parent_attr[ $attribute_name ] ?? $parent_attr[ $attribute ] ?? $options;
								$attribute_t = isset( $parent_attr[ $attribute_name ] ) ? $attribute_name : $attribute;
								echo sprintf( '<div class="vi-wcuf-swatches-wrap"><div class="vi-wcuf-swatches-value value">' );
								wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcuf_us_dropdown_variation_attribute_options', array(
									'options'                 => $options,
									'attribute'               => $attribute_t,
									'product'                 => $product_parent ?? '',
									'class'                   => 'viwcuf-attribute-options',
									'viwpvs_swatches_disable' => 1,
								), $attribute_name, $product ) );
								echo sprintf( '</div></div>' );
							}
						}
						?>
                    </div>
                    <div class="single_variation_wrap">
                        <div class="woocommerce-variation single_variation"></div>
                        <div class="woocommerce-variation-add-to-cart variations_button">
							<?php
							$this->get_product_quantity( $product, $product_id, $product_name, $product_qty );
							$this->get_product_bt_atc();
							?>
                            <input type="hidden" name="add-to-cart" class="vi-wcuf-add-to-cart" value=""/>
                            <input type="hidden" name="product_id" class="vi-wcuf-product_id" value=""/>
                            <input type="hidden" name="variation_id" class="variation_id" value="<?php echo esc_attr( $product_id ); ?>"/>
                            <input type="hidden" name="viwcuf_us_product_id" class="viwcuf_us_product_id" value="1"/>
                        </div>
                    </div>
                </div>
			<?php
		}
	}

	private function get_product_bt_atc() {
		switch ( $this->pd_template ) {
			case '2':
				$html = apply_filters( 'vi_wcuf_ob_checkbox_html', '<span class="vi-wcuf-us-checkbox vi-wcuf-us-product-bt-atc"></span>' );
				break;
			default:
				ob_start();
				?>
                <button type="button" class="vi-wcuf-us-product-bt-atc button alt">
					<?php echo wp_kses_post( $this->settings->get_params( 'us_pd_atc_title' ) ); ?>
                </button>
                <button type="button" class="vi-wcuf-us-product-bt-remove button alt">
					<?php echo wp_kses_post(apply_filters( 'viwcuf_make_product_remove_text', esc_html__( 'Remove',  'checkout-upsell-funnel-for-woo' ) )) ; ?>
                </button>
				<?php
				$html       = ob_get_clean();
				$icon       = $this->settings->get_params( 'us_pd_atc_icon' );
				$icon_class = $this->settings->get_class_icon( $icon, 'cart_icons' );
				$html       = str_replace( '{cart_icon}', '<i class="viwcuf_us_cart_icons ' . esc_attr( $icon_class ) . '"></i>', $html );
		}
		echo wp_kses_post( $html );
	}

	private function get_product_quantity( $product, $product_id, $product_name, $product_qty, $count_added = null ) {
		$product_qty = is_numeric($product_qty) ? 1 :-1;
		$min         = 1;
		if ( $product->is_sold_individually() ) {
			$qty_available = $product_qty ? 1 : 0;
			$max           = $qty_available ?: 0;
		} else {
			$max           = $product->get_max_purchase_quantity();
			$max           =  $max < 0 ? $max : $max - $this->frontend::get_pd_qty_in_cart( $product_id );
			$max           = $max < 0 || ($product_qty > -1 && $max > $product_qty) ? $product_qty : $max;
			$count_added   = $count_added ?? $this->frontend::get_pd_qty_in_cart( $product_id, 'viwcuf_us_product' );
			$qty_available = $product_qty < 0 ? $max :( $product_qty > $count_added ? $product_qty - $count_added : '');
			$max           =  $max < 0 || $max > $qty_available ? $qty_available : $max;
		}
		switch ( $this->pd_template ) {
			case '2':
				self::get_product_quantity_html( $product, $product_name, $product_qty, $qty_available, $min, $max, true );
				if ( $count_added ) {
					$cart_item_info = $this->frontend::get_cart_item( $product_id, 'viwcuf_us_product' );
					$cart_item_data = array();
					$cart_item_data[] = 'data-count_added=' . $count_added;
					if ( ! empty( $cart_item_info['product_id'] ) ) {
						$cart_item_data[] = 'data-added_id=' . $cart_item_info['product_id'];
					}
					if ( ! empty( $cart_item_info['cart_item_key'] ) ) {
						$cart_item_data[] = 'data-cart_item_key=' . $cart_item_info['cart_item_key'];
					}
					if ( ! empty( $cart_item_info['variation'] ) && is_array($cart_item_info['variation'])) {
						$data_variation = wp_json_encode( $cart_item_info['variation'] );
						$data_variation = function_exists( 'wc_esc_json' ) ? wc_esc_json( $data_variation ) : _wp_specialchars( $data_variation, ENT_QUOTES, 'UTF-8', true );
					}
					if ( ! empty( $cart_item_data ) || !empty($data_variation)) {
						$cart_item_data = implode( ' ', $cart_item_data );
						printf( '<span class="vi-wcuf-us-cart-item-info vi-wcuf-disable" %s data-variation="%s"></span>',  esc_attr($cart_item_data ) ,  esc_attr($data_variation ??'') );
					}
				}
				break;
			default:
				self::get_product_quantity_html( $product, $product_name, $product_qty, $qty_available, $min, $max );
		}
	}

	public static function get_product_quantity_html( $product, $product_name, $product_qty, $qty_available, $min, $max, $change_qty = false ) {
		$product_qty = is_numeric($product_qty) ? (int)$product_qty :$max;
//		if ( $product->is_sold_individually() ) {
//			echo apply_filters('viwcuf_us_product_quantity',sprintf( '<input type="hidden" name="quantity" value="1" class="viwcuf_us_product_qty" />' ),$product,[]);
//		} else {
//			$quantity_args =apply_filters('viwcuf_quantity_input_args', array(
//				'input_name'   => "quantity",
//				'input_value'  => 1,
//				'max_value'    => $product->get_max_purchase_quantity(),
//				'min_value'    => '0',
//				'classes'    => ['vi-wcaio-sb-product-qty'],
//				'product_name' => $product->get_name()
//			), $product,'upsell_funnel');
//			echo apply_filters('viwcuf_us_product_quantity', self::product_get_quantity_html( $quantity_args),$product,$quantity_args );
//		}
		if ( $product_qty > -1 && $product_qty < 2 ) {
			$html = sprintf( '<div class="vi-wcuf-us-quantity-wrap"><input type="hidden" name="quantity" min="%s" max="%s" step="1" value="1" class="viwcuf_us_product_qty" />
                             <input type="hidden" class="viwcuf_us_qty_available" name="viwcuf_us_quantity" value="%s" data-limit_quantity="%s" data-product_name="%s"></div>',
				esc_attr( $min ), esc_attr( $max ), esc_attr( $qty_available ), esc_attr( $product_qty ), esc_attr( $product_name ) );
			echo apply_filters( 'viwcuf_us_product_quantity', $html, $product_qty, $qty_available, $product );

			return;
		}
		if ( $change_qty ) {
			$html = sprintf( '<div class="vi-wcuf-us-quantity-wrap vi-wcuf-us-quantity-wrap-minus_plus"><span class="vi_wcuf_us_change_qty vi_wcuf_us_minus">-</span>
                            <input type="number" name="quantity" min="%s" max="%s" step="1" value="1" class="viwcuf_us_product_qty" />
                             <input type="hidden" class="viwcuf_us_qty_available" name="viwcuf_us_quantity" value="%s" data-limit_quantity="%s" data-product_name="%s">
                             <span class="vi_wcuf_us_change_qty vi_wcuf_us_plus">+</span></div>',
				esc_attr( $min ), esc_attr( $max ),
				esc_attr( $qty_available ), esc_attr( $product_qty ), esc_attr( $product_name )
			);
		} else {
			$html = sprintf( '<div class="vi-wcuf-us-quantity-wrap"><input type="number" name="quantity" min="%s" max="%s" step="1" value="1" class="viwcuf_us_product_qty" />
                             <input type="hidden" class="viwcuf_us_qty_available" name="viwcuf_us_quantity" value="%s" data-limit_quantity="%s" data-product_name="%s"></div>',
				esc_attr( $min ), esc_attr( $max ),
				esc_attr( $qty_available ), esc_attr( $product_qty ), esc_attr( $product_name ) );
		}
		echo apply_filters( 'viwcuf_us_product_quantity', $html, $product_qty, $qty_available, $product );
	}
}