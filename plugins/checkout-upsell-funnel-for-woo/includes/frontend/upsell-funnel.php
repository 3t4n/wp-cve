<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Upsell_Funnel {
	protected $settings;
	public static $frontend, $is_mobile, $rule, $position,$hook_action;

	public function __construct() {
		$this->settings = new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		if ( ! $this->settings->enable( 'us_' ) ) {
			return;
		}
		self::$is_mobile = wp_is_mobile();
		if ( self::$is_mobile ) {
			$us_mobile_style = $this->settings->get_params( 'us_mobile_style' );
			switch ( $us_mobile_style ) {
				case '1':
					self::$position = $this->settings->get_params( 'us_mobile_position' ) ?: 6;
					break;
				case '2':
					self::$position = 'footer';
					break;
				case '3':
					self::$position = '0';
					break;
			}
		} else {
			$us_desktop_style = $this->settings->get_params( 'us_desktop_style' );
			switch ( $us_desktop_style ) {
				case '1':
					self::$position = $this->settings->get_params( 'us_desktop_position' ) ?? 6;
					break;
				case '2':
					self::$position = 'footer';
					break;
				case '3':
					self::$position = '0';
					break;
			}
		}
		$position_args     = array(
			'1'      => 'woocommerce_before_checkout_form',
			'2'      => 'woocommerce_before_checkout_billing_form',
			'3'      => 'woocommerce_after_checkout_billing_form',
			'4'      => 'woocommerce_review_order_before_cart_contents',
			'5'      => 'woocommerce_review_order_before_payment',
			'6'      => 'woocommerce_review_order_after_payment',
			'7'      => 'woocommerce_after_checkout_form',
			'footer' => 'wp_footer'
		);
		if (is_plugin_active('klarna-checkout-for-woocommerce/klarna-checkout-for-woocommerce.php')){
			$position_args     = array(
				'1'      => 'woocommerce_before_checkout_form',
				'2'      => 'kco_wc_before_wrapper',
				'3'      => 'kco_wc_before_order_review',
				'4'      => 'kco_wc_before_order_review',
				'5'      => 'kco_wc_after_order_review',
				'6'      => 'kco_wc_after_wrapper',
				'7'      => 'woocommerce_after_checkout_form',
			);
		}
		self::$hook_action = in_array( self::$position, [ '0', 'footer' ] ) ? ( $position_args[ self::$position ] ?? '' ) : apply_filters( 'viwcuf_us_get_action', $position_args[ self::$position ] ?? '' );
		self::$frontend = 'VIWCUF_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend';
		add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'viwcuf_us_woocommerce_update_order_review_fragments' ), PHP_INT_MAX, 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'viwcuf_wp_enqueue_scripts' ) );
		if ( $this->settings->get_params( 'recent_viewed_cookie' ) ) {
			add_action( 'wp', array( $this, 'viwcuf_recently_viewed' ) );
		}
		//add new checkout endpoint
		add_filter( 'woocommerce_get_query_vars', array( $this, 'viwcuf_woocommerce_get_query_vars' ), PHP_INT_MAX, 1 );
		add_filter( 'wc_get_template', array( $this, 'viwcuf_wc_get_template' ), PHP_INT_MAX, 2 );
		//don't show upsell funnel on wcaio checkout form
		add_action( 'vi_wcaio_before_checkout_form', array( $this, 'viwcaio_before_checkout_form' ) );
	}
	public function viwcaio_before_checkout_form() {
		if ( ! $this->settings->get_params( 'us_vicaio_enable' ) && self::$hook_action ) {
			remove_action( self::$hook_action, array( $this, 'frontend_html' ) );
		}
	}

	public function viwcuf_wc_get_template( $located, $template_name ) {
		if ( is_wc_endpoint_url( 'viwcuf_us_endpoint' ) && $template_name === 'checkout/form-checkout.php' ) {
			self::$rule = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Us_Cart::$rule ?? self::$frontend::get_rules( 'us_' );
			if ( self::$position ) {
				return $located;
			}
			if ( empty( $product_ids = WC()->session->get( 'viwcuf_us_recommend_pd_ids', '' ) ) ) {
				return $located;
			}
			$shortcode_html = do_shortcode( '[viwcuf_checkout_upsell_funnel rule="' . self::$rule . '" position="' . self::$position . '" product_ids="' . esc_attr( $product_ids ) . '"]' );
			if ( empty( $shortcode_html ) ) {
				return $located;
			}
			$located = VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES . 'checkout-upsell-funnel.php';
			add_filter( 'viwcuf_ob_enable', array( $this, 'viwcuf_disable' ) );
			remove_filter( 'wc_get_template', array( $this, 'viwcuf_wc_get_template' ), PHP_INT_MAX );
		}

		return $located;
	}

	public function viwcuf_disable( $enable ) {
		return false;
	}

	public function viwcuf_woocommerce_get_query_vars( $query ) {
		if ( ! empty( $redirect_page_endpoint = $this->settings->get_params( 'us_redirect_page_endpoint' ) ) ) {
			$query['viwcuf_us_endpoint'] = $redirect_page_endpoint;
		}

		return $query;
	}

	public function viwcuf_recently_viewed() {
		if ( ! is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) && is_single() && is_product() ) {
			$product_id        = get_the_ID();
			$recent_viewed_ids = ! empty( $_COOKIE['viwcuf_recently_viewed'] ) ? explode( '|', wp_unslash( $_COOKIE['viwcuf_recently_viewed'] ) ) : array();
			$key               = array_search( $product_id, $recent_viewed_ids );
			if ( $key !== false ) {
				unset( $recent_viewed_ids[ $key ] );
			}
			$recent_viewed_ids[] = $product_id;
			$recent_viewed_ids   = implode( '|', $recent_viewed_ids );
			wc_setcookie( 'viwcuf_recently_viewed', $recent_viewed_ids );
		}
	}

	public function viwcuf_wp_enqueue_scripts() {
		if ( is_admin() || ! isset( WC()->session ) ) {
			return;
		}
		$now      = current_time( 'timestamp' );
		$time_end = WC()->session->get( 'viwcuf_us_time_end', '' );
		if ( ! WC()->session->get( 'viwcuf_us_time_pause', '' ) && $time_end && $time_end < $now ) {
			$time_reset = $this->settings->get_params( 'us_time_reset' ) ?? 7;
			$time_reset = $time_reset * 864000 + $time_end;
			if ( $time_reset > $now ) {
				return;
			}
			WC()->session->__unset( 'viwcuf_us_time_start' );
			WC()->session->__unset( 'viwcuf_us_time_end' );
		}
		if ( ! isset( WC()->cart ) || WC()->cart->is_empty() ) {
			if ( ! class_exists( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content' ) || ! VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::$sc_checkout ) {
				return;
			}
		}
		$prefix   = $this->settings::get_data_prefix();
		$old_rule = WC()->session->get( 'viwcuf_us_rule', '' );
		self::$rule = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Us_Cart::$rule ?? $old_rule[$prefix] ?? '';
		if ( ( ! class_exists( 'VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content' ) || ! VIWCAIO_CART_ALL_IN_ONE_Frontend_Sidebar_Cart_Content::$sc_checkout ) &&
		     (! is_checkout() || ! apply_filters( 'viwcuf_us_enable', true ) )) {
			return;
		}
		if ( self::$position === false && ! self::$hook_action ) {
			return;
		}
		if ( self::$position === 'footer' && class_exists( 'WC_Gateway_Twocheckout_Inline' ) ) {
			return;
		}
		if ( self::$is_mobile ) {
			$limit_rows      = $this->settings->get_params( 'us_mobile_scroll_limit_rows' );
			$display_type    = $this->settings->get_params( 'us_mobile_display_type' );
		} else {
			$limit_rows       = $this->settings->get_params( 'us_desktop_scroll_limit_rows' );
			$display_type     = $this->settings->get_params( 'us_desktop_display_type' );
		}
		if ( self::$hook_action ) {
			add_action( self::$hook_action, array( $this, 'frontend_html' ) );
		}
		$suffix = WP_DEBUG ? '' : 'min.';
		wp_enqueue_style( 'viwcuf-frontend', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'frontend.' . $suffix . 'css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		wp_enqueue_style( 'viwcuf-frontend-upsell', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'frontend-upsell.' . $suffix . 'css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		wp_enqueue_script( 'viwcuf-frontend-upsell', VICUFFW_CHECKOUT_UPSELL_FUNNEL_JS . 'frontend-upsell.' . $suffix . 'js', array( 'jquery', 'wc-checkout' ), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		wp_enqueue_script( 'viwcuf-frontend-swatches', VICUFFW_CHECKOUT_UPSELL_FUNNEL_JS . 'frontend-swatches.' . $suffix . 'js', array( 'jquery' ), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		if ( $display_type === 'slider' ) {
			wp_enqueue_script( 'viwcuf-flexslider', VICUFFW_CHECKOUT_UPSELL_FUNNEL_JS . 'vi-flexslider.min.js', array( 'jquery' ), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
			wp_enqueue_style( 'viwcuf-frontend-flexslider', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'frontend-flexslider.min.css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		} else {
			wp_enqueue_style( 'viwcuf-frontend-scroll', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'frontend-scroll.min.css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		}
		wp_enqueue_style( 'viwcuf-frontend-cart_icons', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'cart-icons.min.css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		wp_enqueue_style( 'viwcuf-frontend-pause_icons', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'pause-icons.min.css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		wp_enqueue_style( 'viwcuf-frontend-skip_icons', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'skip-icons.min.css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		if ( $this->settings->get_params( 'us_pd_template' ) === '2' ) {
			wp_enqueue_style( 'viwcuf-frontend-checked_icons', VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . 'checked-icon.min.css', array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		}
		if ( self::$position === '0' && is_wc_endpoint_url( 'viwcuf_us_endpoint' ) ) {
			$is_redirect_page = 1;
		}
		$args = array(
			'wc_ajax_url'                => WC_AJAX::get_endpoint( "%%endpoint%%" ),
			'checkout_url'               => esc_js( wc_get_page_permalink( 'checkout' ) ),
			'nonce' => wp_create_nonce('viwcuf_nonce'),
			'is_redirect_page'           => $is_redirect_page ?? '',
			'is_popup'                   => in_array( self::$position, array( '0', 'footer' ) ),
			'position'                   => self::$position ?? '',
			'rule_id'                    => self::$rule ?? '',
			'limit_rows'                 => $limit_rows ?:'',
			'pd_hide_after_atc'          =>  '',
			'i18n_unavailable_text'      => apply_filters( 'vi-wcuf-i18n_unavailable_text', esc_html__( 'Sorry, this product is unavailable. Please choose a different combination.', 'checkout-upsell-funnel-for-woo' ) ),
			'i18n_make_a_selection_text' => apply_filters( 'vi-wcuf-i18n_make_a_selection_text', esc_html__( 'Please select some product options before adding {product_name} to your cart.', 'checkout-upsell-funnel-for-woo' ) ),
			'i18n_quantity_error'        => apply_filters( 'vi-wcuf-i18n_make_error_us_qty_text', esc_html__( 'The maximum number of {product_name} quantity is {pd_limit_quantity}', 'checkout-upsell-funnel-for-woo' ) ),
		);
		wp_localize_script( 'viwcuf-frontend-upsell', 'viwcuf_frontend_us_params', $args );
		$css = $this->get_inline_css();
		wp_add_inline_style( 'viwcuf-frontend', $css );
	}

	public function frontend_html() {
		if ( ! self::$position ) {
			return;
		}
		if ( ! apply_filters( 'viwcuf_us_enable', true ) ) {
			return;
		}
		?>
		<div class="viwcuf-checkout-us-container"></div>
		<?php
	}
	public function viwcuf_us_woocommerce_update_order_review_fragments( $result ) {
		if (in_array( self::$position, [ '0', 'footer' ] )  || ! apply_filters( 'viwcuf_us_enable', true ) ) {
			return $result;
		}
		self::$rule = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Us_Cart::$rule ?? self::$frontend::get_rules( 'us_' );
		$product_ids  = WC()->session->get( 'viwcuf_us_recommend_pd_ids', '' );
		if ( $product_ids ) {
			$display_type   = self::$is_mobile ? $this->settings->get_params( 'us_mobile_display_type' ) : $this->settings->get_params( 'us_desktop_display_type' );
			ob_start();
			printf( '<div class="viwcuf-checkout-us-container">' );
			$shortcode_html = do_shortcode( '[viwcuf_checkout_upsell_funnel rule="' . self::$rule . '" position="' . self::$position . '" product_ids="' . esc_attr( $product_ids ) . '"]' );
			wc_get_template( 'checkout-upsell-funnel.php',
				array(
					'product_ids'    => $product_ids,
					'display_type'   => $display_type,
					'position'       => self::$position,
					'shortcode_html' => $shortcode_html,
					'rule'           => self::$rule
				), '', VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES );
			printf( '</div>' );
			$html                                    = ob_get_clean();
		}
		$result['.viwcuf-checkout-us-container'] = $html ?? '<div class="viwcuf-checkout-us-container"></div>';
		return $result;
	}

	public function get_inline_css() {
		$css = '';
		$css .= str_replace( '\n', ' ', $this->settings->get_params( 'custom_css' ) );
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap',
			),
			array( 'us_border_style', 'us_border_color', 'us_border_width', 'us_border_radius' ),
			array( 'border-style', 'border-color', 'border-width', 'border-radius' ),
			array( '', '', 'px', 'px' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-header-wrap',
			),
			array( 'us_header_bg_color', 'us_header_padding' ),
			array( 'background', 'padding' ),
			array( '', '' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-content-wrap'
			),
			array( 'us_container_bg_color' ),
			array( 'background' ),
			array( '' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-content-wrap .vi-wcuf-us-shortcode-content-1',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-content-wrap .vi-wcuf-us-shortcode-content-2',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-content-wrap .vi-wcuf-us-shortcode-content-3',
			),
			array( 'us_container_padding' ),
			array( 'padding' ),
			array( '' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-footer-wrap'
			),
			array( 'us_footer_bg_color', 'us_footer_padding' ),
			array( 'background', 'padding' ),
			array( '', '' )
		);

		//Title
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-title-wrap',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-title-wrap *',
			),
			array( 'us_title_color', 'us_title_font_size' ),
			array( 'color', 'font-size' ),
			array( '', 'px' )
		);

		//Continue button
		if ( $us_bt_continue_border_width = $this->settings->get_params( 'us_bt_continue_border_width' ) ) {
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-bt-continue{';
			$css .= 'border: ' . $us_bt_continue_border_width . 'px  solid ' . $this->settings->get_params( 'us_bt_continue_border_color' );
			$css .= '}';
		}
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-bt-continue',
			),
			array( 'us_bt_continue_bg_color', 'us_bt_continue_color', 'us_bt_continue_font_size', 'us_bt_continue_border_radius' ),
			array( 'background', 'color', 'font-size', 'border-radius' ),
			array( '', '', 'px', 'px' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-bt-continue i::before',
			),
			array( 'us_skip_icon_color', 'us_skip_icon_font_size', ),
			array( 'color', 'font-size' ),
			array( '', 'px' )
		);

		//Add all to cart button
		if ( $us_bt_alltc_border_width = $this->settings->get_params( 'us_bt_alltc_border_width' ) ) {
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap  .vi-wcuf-us-shortcode-bt-alltc{';
			$css .= 'border: ' . $us_bt_alltc_border_width . 'px  solid ' . $this->settings->get_params( 'us_bt_alltc_border_color' );
			$css .= '}';
		}
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap button.button.vi-wcuf-us-shortcode-bt-alltc',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-bt-alltc',
			),
			array( 'us_bt_alltc_bg_color', 'us_bt_alltc_color', 'us_bt_alltc_font_size', 'us_bt_alltc_border_radius' ),
			array( 'background', 'color', 'font-size', 'border-radius' ),
			array( '', '', 'px', 'px' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-bt-alltc i::before'
			),
			array( 'us_alltc_icon_color', 'us_alltc_icon_font_size' ),
			array( 'color', 'font-size' ),
			array( '', 'px' )
		);

		// Countdown timer
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-countdown-wrap',
			),
			array( 'us_countdown_color', 'us_countdown_font_size', ),
			array( 'color', 'font-size' ),
			array( '', 'px' )
		);
		// Progress bar
		$us_progress_bar_border_color1 = $this->settings->get_params( 'us_progress_bar_border_color1' );
		$diameter                      = intval( $this->settings->get_params( 'us_progress_bar_diameter' ) ?: 30 );
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap{';
		$css                           .= 'width: ' . $diameter . 'px ;';
		$css                           .= 'height: ' . $diameter . 'px ;';
		$css                           .= '}';
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap:not(.vi-wcuf-us-shortcode-progress_bar-wrap-over50),';
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap:not(.vi-wcuf-us-shortcode-progress_bar-wrap-over50) .vi-wcuf-us-shortcode-progress_bar-clipper,';
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap.vi-wcuf-us-shortcode-progress_bar-wrap-over50 .vi-wcuf-us-shortcode-progress_bar-first50{';
		$css                           .= esc_attr__( 'clip: rect(0,' ) . $diameter . 'px,' . $diameter . 'px,' . $diameter / 2 . 'px)';
		$css                           .= '}';
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap .vi-wcuf-us-shortcode-progress_bar-value{';
		$css                           .= esc_attr__( 'clip: rect(0,' ) . $diameter / 2 . 'px,' . $diameter . 'px,0);';
		$css                           .= '}';
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap:after{';
		$css                           .= 'box-shadow: 0 0 0 ' . $this->settings->get_params( 'us_progress_bar_border_width' ) . 'px ' . $us_progress_bar_border_color1 . ' inset;';
		$css                           .= '}';
		$css                           .= '@media screen and (max-width:600px){';
		$mobile_circle_boder           = intval( $this->settings->get_params( 'us_progress_bar_border_width' ) ?: 15 );
		$mobile_circle_boder           = $mobile_circle_boder < 0 || $mobile_circle_boder > 15 ? 15 : $mobile_circle_boder;
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-first50,';
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-value{';
		$css                           .= 'border-width: ' . $mobile_circle_boder . 'px !important;';
		$css                           .= '}';
		$css                           .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap:after{';
		$css                           .= 'box-shadow: 0 0 0 ' . $mobile_circle_boder . 'px ' . $us_progress_bar_border_color1 . ' inset;';
		$css                           .= '}';
		$css                           .= '}';
		$css                           .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap:after'
			),
			array( 'us_progress_bar_bg_color' ),
			array( 'background' ),
			array( '' )
		);
		$css                           .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-progress_bar-wrap.vi-wcuf-us-shortcode-progress_bar-wrap-over50 .vi-wcuf-us-shortcode-progress_bar-first50',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap  .vi-wcuf-us-shortcode-progress_bar-first50',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap  .vi-wcuf-us-shortcode-progress_bar-value',
			),
			array( 'us_progress_bar_border_color2', 'us_progress_bar_border_width' ),
			array( 'border-color', 'border-width' ),
			array( '', 'px' )
		);
		// Pause button
		if ( $us_bt_pause_border_width = $this->settings->get_params( 'us_bt_pause_border_width' ) ) {
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-shortcode-bt-pause{';
			$css .= 'border: ' . $us_bt_pause_border_width . 'px  solid ' . $this->settings->get_params( 'us_bt_pause_border_color' );
			$css .= '}';
		}
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap  .vi-wcuf-us-shortcode-bt-pause'
			),
			array( 'us_bt_pause_bg_color', 'us_bt_pause_color', 'us_bt_pause_font_size', 'us_bt_pause_border_radius' ),
			array( 'background', 'color', 'font-size', 'border-radius' ),
			array( '', '', 'px', 'px' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap  .vi-wcuf-us-shortcode-bt-pause i::before',
			),
			array( 'us_pause_icon_color', 'us_pause_icon_font_size' ),
			array( 'color', 'font-size' ),
			array( '', 'px' )
		);

		// Product list
		if ( $us_pd_box_shadow_color = $this->settings->get_params( 'us_pd_box_shadow_color' ) ) {
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap  .vi-wcuf-us-product-wrap-wrap{';
			$css .= 'box-shadow: 0px 4px 2px -2px ' . $us_pd_box_shadow_color;
			$css .= '}';
		}
		if ( $us_pd_border_color = $this->settings->get_params( 'us_pd_border_color' ) ) {
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap  .vi-wcuf-us-product{';
			$css .= 'border: 1px  solid ' . $us_pd_border_color;
			$css .= '}';
		}
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product'
			),
			array( 'us_pd_bg_color', 'us_pd_border_radius' ),
			array( 'background', 'border-radius' ),
			array( '', 'px' )
		);
		if ( $us_pd_img_border_width = $this->settings->get_params( 'us_pd_img_border_width' ) ) {
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product-top{';
			$css .= 'border: ' . $us_pd_img_border_width . 'px  solid ' . $this->settings->get_params( 'us_pd_img_border_color' );
			$css .= '}';
		}
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product-top'
			),
			array( 'us_pd_img_padding' ),
			array( 'padding' ),
			array( '' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product-top',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product-top img'
			),
			array( 'us_pd_img_border_radius' ),
			array( 'border-radius' ),
			array( 'px' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product-desc'
			),
			array( 'us_pd_details_padding', 'us_pd_details_color', 'us_pd_details_text_align', 'us_pd_details_font_size' ),
			array( 'padding', 'color', 'text-align', 'font-size' ),
			array( '', '', '', 'px' )
		);
		if ( $us_pd_qty_border_color = $this->settings->get_params( 'us_pd_qty_border_color' ) ) {
			$css .= '.vi-wcuf-us-quantity-wrap.vi-wcuf-us-quantity-wrap-minus_plus,';
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product .vi-wcuf-us-quantity-wrap.vi-wcuf-us-quantity-wrap-minus_plus,';
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product .vi-wcuf-us-quantity-wrap:not(.vi-wcuf-us-quantity-wrap-minus_plus) .viwcuf_us_product_qty {';
			$css .= 'border: 1px  solid ' . $us_pd_qty_border_color;
			$css .= '}';
			$css .= '.vi_wcuf_us_minus ,';
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product .vi_wcuf_us_minus {';
			$css .= 'border-right: 1px  solid ' . $us_pd_qty_border_color;
			$css .= '}';
			$css .= '.vi_wcuf_us_plus ,';
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product .vi_wcuf_us_plus {';
			$css .= 'border-left: 1px  solid ' . $us_pd_qty_border_color;
			$css .= '}';
		}
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product.vi-wcuf-us-product-2 .vi-wcuf-us-quantity-wrap',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product.vi-wcuf-us-product-1 .viwcuf_us_product_qty',
			),
			array( 'us_pd_qty_bg_color', 'us_pd_qty_color', 'us_pd_qty_border_radius' ),
			array( 'background', 'color', 'border-radius' ),
			array( '', '', 'px' )
		);
		if ( $us_pd_atc_border_width = $this->settings->get_params( 'us_pd_atc_border_width' ) ) {
			$css .= '.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product-bt-atc{';
			$css .= 'border: ' . $us_pd_atc_border_width . 'px  solid ' . $this->settings->get_params( 'us_pd_atc_border_color' );
			$css .= '}';
		}
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product button.button.vi-wcuf-us-product-bt-atc',
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product .vi-wcuf-us-product-bt-atc'
			),
			array( 'us_pd_atc_bg_color', 'us_pd_atc_color', 'us_pd_atc_font_size', 'us_pd_atc_border_radius' ),
			array( 'background', 'color', 'font-size', 'border-radius' ),
			array( '', '', 'px', 'px' )
		);
		$css .= $this->add_inline_style(
			array(
				'.viwcuf-checkout-funnel-container .vi-wcuf-us-shortcode-wrap .vi-wcuf-us-product .vi-wcuf-us-product-bt-atc i:before',
			),
			array( 'us_pd_atc_icon_color', 'us_pd_atc_icon_font_size' ),
			array( 'color', 'font-size' ),
			array( '', 'px' )
		);

		return $css;
	}

	private function add_inline_style( $element, $name, $style, $suffix = '' ) {
		if ( ! $element || ! is_array( $element ) ) {
			return '';
		}
		$element = implode( ',', $element );
		$return  = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$get_value  = $this->settings->get_params( $value );
				$get_suffix = $suffix[ $key ] ?? '';
				$return     .= $style[ $key ] . ':' . $get_value . $get_suffix . ';';
			}
		}
		$return .= '}';

		return $return;
	}

	public static function get_us_product_ids( $rule, $settings ) {
		if ( ! $rule ) {
			return false;
		}
		$wc_cart = WC()->cart;
		if ( $wc_cart->is_empty() ) {
			return false;
		}
		$index = array_search( $rule, $settings->get_params( 'us_ids' ) );
		if ( $index === false || $index != 0  ) {
			return false;
		}
		$wc_cart_data     = $wc_cart->get_cart();
		$wc_cart_item_ids  = array();
		foreach ( $wc_cart_data as $cart_item ) {
			if ( isset( $cart_item['viwcuf_us_product'] ) || isset( $cart_item['viwcuf_ob_product'] ) ) {
				continue;
			}
			$wc_cart_item_ids[]    = $cart_item['product_id'];
			if ( ! empty( $cart_item['variation_id'] ) ) {
				$wc_cart_item_ids[] = $cart_item['variation_id'];
			}
		}
		if ( empty( $wc_cart_item_ids ) ) {
			return false;
		}
		$found_pd_ids           = array();
		$product_show_variation = 0;
		$product_price_min      = $product_price_max = '';
		$product_visibility     = array();
		$product_parents        = $product_include = array();
		$product_exclude        = $wc_cart_item_ids;
		$cats_include           = $cats_exclude = array();
		$count_variable         = $start_total = 0;
		$product_type           = $settings->get_current_setting( 'us_product_type', $index, 3 );
		$product_order_by       = $settings->get_current_setting( 'us_product_order_by', $index, 'date' );
		$product_order          = $settings->get_current_setting( 'us_product_order', $index, 'date' );
		$product_limit          = $settings->get_current_setting( 'us_product_limit', $index, 4 );
		$conditions             = $settings->get_current_setting( 'us_product_rule_type', $rule, 'desc' );
		if ( $conditions && is_array( $conditions ) && count( $conditions ) ) {
			foreach ( $conditions as $condition ) {
				$prefix = 'us_' . $condition;
				switch ( $condition ) {
					case 'product_show_variation':
						$product_show_variation = $settings->get_current_setting( $prefix, $rule, 0 );
						break;
					case 'product_price':
						$product_price     = $settings->get_current_setting( $prefix, $rule, array() );
						$product_price_min = $product_price['min'] ?? '' ;
						$product_price_max =  $product_price['max'] ?? '' ;
						break;
					case 'product_visibility':
						$product_visibility = $settings->get_current_setting( $prefix, $rule, $product_visibility);
						break;
					case 'product_include':
						$product_include = $settings->get_current_setting( $prefix, $rule, array() );
						break;
					case 'product_exclude':
						$product_exclude = array_merge( $wc_cart_item_ids, $settings->get_current_setting( $prefix, $rule, array() ) );
						$product_exclude = array_unique( $product_exclude );
						break;
					case 'cats_include':
						$cats_include = $settings->get_current_setting( $prefix, $rule, array() );
						break;
					case 'cats_exclude':
						$cats_exclude = $settings->get_current_setting( $prefix, $rule, array() );
						break;
				}
			}
			$product_parents = array_diff( $product_include, $product_exclude );
		}
		switch ( $product_type ) {
			case '0':
				//Featured products
				$featured_pd_ids = wc_get_featured_product_ids();
				if ( empty( $featured_pd_ids ) ) {
					break;
				}
				$product_parents = ! empty( $product_parents ) ? array_intersect( $product_parents, $featured_pd_ids ) : $featured_pd_ids;
				$product_parents = array_diff( $product_parents, $product_exclude );
				if ( empty( $product_parents ) ) {
					break;
				}
				$query_args = self::get_query_product( $product_show_variation, $product_parents, $product_exclude, $cats_include, $cats_exclude, $product_price_min, $product_price_max );
				break;
			case '1':
				//Best selling products
				$query_args = self::get_query_product( $product_show_variation, $product_parents, $product_exclude, $cats_include, $cats_exclude, $product_price_min, $product_price_max );
				if ( empty( $query_args ) ) {
					break;
				}
				$query_args['orderby']  = 'meta_value_num';
				$query_args['meta_key'] = 'total_sales';
				$query_args['order']    = 'DESC';
				break;
			case '2':
				//products on sale
				$sale_pd_ids = wc_get_product_ids_on_sale();
				if ( empty( $sale_pd_ids ) ) {
					break;
				}
				$product_parents = ! empty( $product_parents ) ? array_intersect( $product_parents, $sale_pd_ids ) : $sale_pd_ids;
				$product_parents = array_diff( $product_parents, $product_exclude );
				if ( empty( $product_parents ) ) {
					break;
				}
				$query_args = self::get_query_product( $product_show_variation, $product_parents, $product_exclude, $cats_include, $cats_exclude, $product_price_min, $product_price_max );
				break;
			case '3':
				//Recently published products
				$query_args = self::get_query_product( $product_show_variation, $product_parents, $product_exclude, $cats_include, $cats_exclude, $product_price_min, $product_price_max );
				if ( empty( $query_args ) ) {
					break;
				}
				$query_args['orderby'] = 'date';
				$query_args['order']   = 'DESC';
				break;
			case '4':
				//Recently viewed products
				$recent_viewed_ids = is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) ? ( $_COOKIE['woocommerce_recently_viewed'] ?? '' ) : '';
				$recent_viewed_ids = $recent_viewed_ids ?: ( $_COOKIE['viwcuf_recently_viewed'] ?? '' );
				$recent_viewed_ids = $recent_viewed_ids ? explode( '|', wp_unslash( $recent_viewed_ids ) ) : array();
				if ( empty( $recent_viewed_ids ) ) {
					break;
				}
				$product_parents = ! empty( $product_parents ) ? array_intersect( $product_parents, $recent_viewed_ids ) : $recent_viewed_ids;
				$product_parents = array_diff( $product_parents, $product_exclude );
				if ( empty( $product_parents ) ) {
					break;
				}
				$query_args = self::get_query_product( $product_show_variation, $product_parents, $product_exclude, $cats_include, $cats_exclude, $product_price_min, $product_price_max );
				break;
			case '9':
				//Products in the form Billing
				$orders = self::get_query_order();
				if ( empty( $orders ) ) {
					break;
				}
				$limit_product = 1000;
				foreach ( $orders as $order ) {
					if ( ! $limit_product ) {
						break;
					}
					$order_date_created = $order->get_date_created();
					if ( empty( $order_date_created ) ) {
						continue;
					}
					$items = $order->get_items();
					if ( empty( $items ) ) {
						continue;
					}
					foreach ( $items as $item ) {
						if ( ! $limit_product ) {
							break;
						}
						$product_id = $item->get_variation_id() ?? 0;
						if ( $product_id ) {
							$product_parent_id = $item->get_product_id();
							if ( in_array( $product_id, $product_exclude ) || in_array( $product_parent_id, $product_exclude ) ) {
								continue;
							}
							$product = wc_get_product( $product_id );
							if ( ! $product || ! $product->is_in_stock() || in_array( $product_id, $found_pd_ids ) ) {
								continue;
							}
							if ( is_numeric($product_price_min ) && floatval( $product->get_price( 'edit' ) ) < floatval( $product_price_min ) ) {
								continue;
							}
							if ( is_numeric($product_price_max ) && floatval( $product->get_price( 'edit' ) ) > floatval( $product_price_max ) ) {
								continue;
							}
							$parent_post_id = wp_get_post_parent_id( $product_id );
							if ( ! empty( $product_parents ) ) {
								if ( in_array( $product_id, $product_parents ) || ( $parent_post_id && in_array( $parent_post_id, $product_parents ) ) ) {
									$found_pd_ids[] = $product_id;
								}
							} else {
								$found_pd_ids[] = $product_id;
							}
						} else {
							$product_id = $item->get_product_id();
							if ( in_array( $product_id, $product_exclude ) ) {
								continue;
							}
							$product = wc_get_product( $product_id );
							if ( ! $product || ! $product->is_in_stock() || in_array( $product_id, $found_pd_ids ) ) {
								continue;
							}
							if ( is_numeric($product_price_min ) && floatval( $product->get_price( 'edit' ) ) < floatval( $product_price_min ) ) {
								continue;
							}
							if ( is_numeric($product_price_max ) && floatval( $product->get_price( 'edit' ) ) > floatval( $product_price_max ) ) {
								continue;
							}
							if ( ! empty( $product_parents ) ) {
								if ( in_array( $product_id, $product_parents ) ) {
									$found_pd_ids[] = $product_id;
								}
							} else {
								$found_pd_ids[] = $product_id;
							}
						}
						$limit_product --;
					}
				}
				break;
			default:
				if ( empty( $product_parents ) && empty($cats_include)) {
					break;
				}
				$query_args = self::get_query_product( $product_show_variation, $product_parents, $product_exclude, $cats_include, $cats_exclude, $product_price_min, $product_price_max );
		}
		if ( ! empty( $query_args ) ) {
			$the_query = new WP_Query( $query_args );
			if ( $product_show_variation ) {
				$found_pd_ids = isset($query_args['post_parent__in']) && is_array($query_args['post_parent__in']) ? $query_args['post_parent__in'] : array();
				$start_total  = count( $found_pd_ids );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$post_id        = get_the_ID();
						$parent_post_id = wp_get_post_parent_id( $post_id );
						if ( $parent_post_id ) {
							if ( ( $key = array_search( $parent_post_id, $found_pd_ids ) ) !== false ) {
								$count_variable ++;
								unset( $found_pd_ids[ $key ] );
							}
						} else {
							$pd_tmp = wc_get_product( $post_id );
							if ( $pd_tmp->is_type( 'variable' ) ) {
								$found_pd_ids = array_merge( $pd_tmp->get_children(), $found_pd_ids );
								continue;
							}
						}
						$found_pd_ids[] = $post_id;
					}
				}
				wp_reset_postdata();
			} else {
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$post_id        = get_the_ID();
						$found_pd_ids[] = $post_id;
					}
				}
				wp_reset_postdata();
			}
		}
		$found_pd_ids = array_diff( $found_pd_ids, $product_exclude );
		if ( ! empty( $found_pd_ids ) && empty( $product_ids ) ) {
			$query = array(
				'post_type'      => array( 'product', 'product_variation' ),
				'post_status'    => 'publish',
				'posts_per_page' => 1000,
				'post__in'       => $found_pd_ids,
				'order'          => $product_order,
			);
			switch ( $product_order_by ) {
				case 'id':
					$query['orderby'] = 'ID';
					break;
				case 'popularity':
					$query['orderby']  = 'meta_value_num';
					$query['meta_key'] = 'total_sales';
					break;
				case 'price':
					$query['orderby']  = 'meta_value_num';
					$query['meta_key'] = '_price';
					break;
				case 'rating':
					$query['orderby']  = 'meta_value_num';
					$query['meta_key'] = '_wc_average_rating';
					break;
				default:
					$query['orderby'] = $product_order_by;
			}
			if ( $product_show_variation && ! empty( $query_args ) ) {
				$query['post_parent__not_in'] = $product_exclude;
				if ( is_numeric($product_price_min )) {
					$query['meta_query']['relation'] = 'AND';
					$query['meta_query'][]           = array(
						'key'     => '_price',
						'value'   => $product_price_min,
						'compare' => '>=',
						'type'    => 'DECIMAL(18,3)',
					);
				}
				if ( is_numeric($product_price_max ) ) {
					$query['meta_query']['relation'] = 'AND';
					$query['meta_query'][]           = array(
						'key'     => '_price',
						'value'   => $product_price_max,
						'compare' => '<=',
						'type'    => 'DECIMAL(18,3)',
					);
				}
			}
			$the_query = new WP_Query( $query );
			if ( $count_variable && $start_total ) {
				if ( $the_query->have_posts() ) {
					$variation_limit      = absint( $product_limit - $start_total + $count_variable );
					$variation_item       = array();
					$variation_item_limit = floor( $variation_limit / $count_variable );
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						if ( ! $product_limit ) {
							break;
						}
						$post_id = get_the_ID();
						$product = wc_get_product( $post_id );
						if ( !empty($product_visibility) && !in_array($product->get_catalog_visibility() ,$product_visibility) ) {
							continue;
						}
						if ( in_array( $product->get_type(), array( 'external', 'grouped', ) ) ) {
							continue;
						}
						if ( $product->get_type() === 'variation' ) {
							$parent_pd_id     = $product->get_parent_id();
							$variation_item_t = $variation_item[ $parent_pd_id ] ?? 1;
							if ( ! $variation_limit || ( $variation_limit && $variation_item_t > $variation_item_limit && count( $variation_item ) < $count_variable ) ) {
								continue;
							}
							$variation_item[ $parent_pd_id ] = $variation_item_t + 1;
							$variation_limit --;
						}
						$product_ids[] = $post_id;
						$product_limit --;
					}
				}
				wp_reset_postdata();
			} else {
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						if ( ! $product_limit ) {
							break;
						}
						$post_id = get_the_ID();
						$product = wc_get_product( $post_id );
						if ( in_array( $product->get_type(), array(
							'external',
							'grouped',
						) ) ) {
							continue;
						}
						if ( !empty($product_visibility) && !in_array($product->get_catalog_visibility() ,$product_visibility) ) {
							continue;
						}
						$product_ids[] = $post_id;
						$product_limit --;
					}
				}
				wp_reset_postdata();
			}
		}

		return ! empty( $product_ids ) ? $product_ids : false;
	}

	private static function get_query_order() {
		$order_query = array(
			'limit' => 1000,
			'orderby' => 'date',
			'order' => 'DESC',
		);
		if (is_user_logged_in()){
			$order_query['customer'] = get_current_user_id();
		}else{
			if (check_ajax_referer( 'update-order-review', 'security' ,false) && !empty($_POST['post_data'])){
				parse_str( wp_unslash($_POST['post_data']), $post_data );
				$billing_email = $post_data['billing_email']??'';
			}else{
				$billing_email = WC()->checkout()->get_value( 'billing_email' );
			}
			$order_query['billing_email'] = $billing_email;
		}
		if (empty($order_query['customer']) && empty($order_query['billing_email'])){
			return  array();
		}
		$orders = wc_get_orders($order_query);
		return $orders;
	}

	private static function get_query_product( $show_variation = false, $product_parents = array(), $product_exclude = array(), $cats_include = array(), $cats_exclude = array(), $product_price_min = '', $product_price_max = '' ) {
		if ( $show_variation && is_array( $product_parents ) && count( $product_parents ) ) {
			$product_parents = array_unique( $product_parents );
			foreach ( $product_parents as $k => $id ) {
				$product = wc_get_product( $id );
				if ( ! $product->is_in_stock() ) {
					unset( $product_parents[ $k ] );
				}
			}
			if ( empty( $product_parents ) ) {
				return false;
			}
		}
		$args = array(
			'post_type'      => array( 'product' ),
			'post_status'    => 'publish',
			'posts_per_page' => 1000,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => 'EQUAL',
				),
			),
		);
		if (is_numeric( $product_price_min )) {
			$args['meta_query'][] = array(
				'key'     => '_price',
				'value'   => $product_price_min,
				'compare' => '>=',
				'type'    => 'DECIMAL(18,3)',
			);
		}
		if ( is_numeric($product_price_max ) ) {
			$args['meta_query'][] = array(
				'key'     => '_price',
				'value'   => $product_price_max,
				'compare' => '<=',
				'type'    => 'DECIMAL(18,3)',
			);
		}
		if ( ! empty( $cats_include ) ) {
			$args['tax_query'] ['relation'] = 'AND';
			$args['tax_query'] []           = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $cats_include,
				'operator' => 'IN'
			);
		}
		if ( ! empty( $cats_exclude ) ) {
			$args['tax_query'] ['relation'] = 'AND';
			$args['tax_query'] []           = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $cats_exclude,
				'operator' => 'NOT IN'
			);
		}
		if ( $show_variation ) {
			$args['post_type']       = array( 'product', 'product_variation' );
			$args['post_parent__in'] = $product_parents;
			$args['post__not_in']    = $product_exclude;
		} else {
			if ( ! empty( $product_parents ) ) {
				$args['post_type'] = array( 'product', 'product_variation' );
			}
			$args['post__in']            = $product_parents;
			$args['post_parent__not_in'] = array_merge( $product_exclude );
		}

		return $args;
	}

	public static function remove_session() {
		WC()->session->__unset( 'viwcuf_us_time_end' );
		WC()->session->__unset( 'viwcuf_us_time_start' );
		WC()->session->__unset( 'viwcuf_us_time_pause' );
		WC()->session->__unset( 'viwcuf_us_rule' );
		WC()->session->__unset( 'viwcuf_us_recommend_pd_ids' );
		WC()->session->__unset( 'viwcuf_us_rule_info' );
		WC()->session->__unset( 'viwcuf_us_added_products' );
	}
}