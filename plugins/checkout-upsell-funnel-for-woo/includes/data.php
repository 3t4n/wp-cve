<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data {
	protected $default, $params, $prefix, $class_icons;

	public function __construct() {
		global $viwcuf_params;
		if ( ! $viwcuf_params ) {
			$viwcuf_params = get_option( 'viwcuf_woo_checkout_upsell_funnel', array() );
		}
		$this->class_icons = array(
			'skip_icons'  => array(
				'viwcuf_skip_icons-right-arrow',
				'viwcuf_skip_icons-curve-arrow',
				'viwcuf_skip_icons-right-arrow-1',
				'viwcuf_skip_icons-right-arrow-2',
				'viwcuf_skip_icons-chevron',
				'viwcuf_skip_icons-next',
				'viwcuf_skip_icons-right-arrow-3',
				'viwcuf_skip_icons-next-1',
				'viwcuf_skip_icons-skip',
				'viwcuf_skip_icons-share',
			),
			'cart_icons'  => array(
				'viwcuf_cart_icons-add-to-basket-1',
				'viwcuf_cart_icons-add-to-cart',
				'viwcuf_cart_icons-add-to-cart-1',
				'viwcuf_cart_icons-add-to-cart-2',
				'viwcuf_cart_icons-add-to-cart-3',
				'viwcuf_cart_icons-add-to-basket',
				'viwcuf_cart_icons-add-to-cart-4',
				'viwcuf_cart_icons-add-to-cart-5',
				'viwcuf_cart_icons-add-to-cart-6',
				'viwcuf_cart_icons-shopping-cart',
				'viwcuf_cart_icons-shopping-cart-1',
				'viwcuf_cart_icons-shopping-cart-2',
				'viwcuf_cart_icons-shopping-cart-3',
				'viwcuf_cart_icons-shopping-cart-4',
				'viwcuf_cart_icons-shopping-cart-5',
				'viwcuf_cart_icons-shopping-cart-6',
				'viwcuf_cart_icons-shopping-cart-7',
				'viwcuf_cart_icons-cart',
				'viwcuf_cart_icons-shopping-cart-8',
				'viwcuf_cart_icons-shopping-cart-9',
				'viwcuf_cart_icons-shopping-cart-10',
				'viwcuf_cart_icons-shopping-cart-11',
				'viwcuf_cart_icons-shopping-cart-12',
				'viwcuf_cart_icons-shopping-cart-13',
				'viwcuf_cart_icons-shopping-cart-14',
				'viwcuf_cart_icons-shopping-cart-15',
				'viwcuf_cart_icons-shopping-cart-16',
				'viwcuf_cart_icons-cart-1',
			),
			'pause_icons' => array(
				'viwcuf_pause_icons-pause',
				'viwcuf_pause_icons-pause-1',
				'viwcuf_pause_icons-pause-2',
				'viwcuf_pause_icons-pause-3',
				'viwcuf_pause_icons-pause-4',
				'viwcuf_pause_icons-pause-6',
				'viwcuf_pause_icons-pause-7',
				'viwcuf_pause_icons-pause-5',
				'viwcuf_pause_icons-pause-8',
				'viwcuf_pause_icons-pause-button',
			),
		);
		$order_bump        = array_merge( array(
			'ob_enable'             => 1,
			'ob_mobile_enable'      => 0,
			'ob_vicaio_enable'         => 0,
			'ob_apply_rule'         => 0,
			'ob_position'           => 4,
			'ob_product'            => array(),
			'ob_padding'            => array( '8px' ),
			'ob_border_style'       => array( 'dashed' ),
			'ob_border_width'       => array( 2 ),
			'ob_border_radius'      => array( 0 ),
			'ob_border_color'       => array( '#9e9e9e' ),
			'ob_bg_color'           => array(),
			'ob_title'              => array( 'Yes! I want it!' ),
			'ob_title_bg_color'     => array( '#ffff99' ),
			'ob_title_color'        => array( '' ),
			'ob_title_padding'      => array( '10px' ),
			'ob_title_font_size'    => array( 15 ),
			'ob_content'            => array( '{product_short_desc}' ),
			'ob_image'              => array( 1 ),
			'ob_content_bg_color'   => array( '' ),
			'ob_content_color'      => array( '' ),
			'ob_content_padding'    => array( '' ),
			'ob_content_font_size'  => array( 16 ),
			'ob_content_max_length'  => array( 150 ),
		), $this->get_rule_params( 'ob_', 'order_bump' ) );
		$upsell_funnel     = array_merge( array(
			'us_enable'                     => 0,
			'us_mobile_enable'              => 0,
			'us_vicaio_enable'                => 0,
			'us_pd_redirect'                => 0,
			'us_desktop_style'              => 1,
			'us_mobile_style'               => 1,
			'us_desktop_position'           => 4,
			'us_mobile_position'            => 4,
			'us_redirect_page_endpoint'     => 'upsell-funnel',

			//design
			'us_content'                    => '{countdown_timer}{content}',
			'us_border_style'               => 'none',
			'us_border_color'               => '',
			'us_border_width'               => 0,
			'us_border_radius'              => 0,
			'us_header_content'             => '{title}{continue_button}',
			'us_header_bg_color'            => '',
			'us_header_padding'             => '15px 0',
			'us_container_content'          => '{product_list}',
			'us_container_bg_color'         => '',
			'us_container_padding'          => '',
			'us_footer_content'             => '',
			'us_footer_bg_color'            => '',
			'us_footer_padding'             => '15px 0 0',
			//title
			'us_title'                      => 'Hang on! We have this offer just for you!',
			'us_title_color'                => '#000',
			'us_title_font_size'            => 21,
			//countdown timer
			'us_time_checkout'              => 0,
			'us_time'                       => 10,
			'us_time_reset'                 => 7,
			'us_countdown_message'          => '{progress_bar} Continue checkout in {time} seconds',
			'us_countdown_color'            => 'rgba(210, 211, 214, 1)',
			'us_countdown_font_size'        => 20,
			'us_progress_bar_bt_pause'      => 1,
			'us_progress_bar_border_width'  => 3,
			'us_progress_bar_diameter'      => 30,
			'us_progress_bar_bg_color'      => '#fff',
			'us_progress_bar_border_color1' => '#ececec',
			'us_progress_bar_border_color2' => '#e3e4e2',
			'us_bt_pause_title'             => '{pause_icon}',
			'us_bt_pause_bg_color'          => '',
			'us_bt_pause_color'             => '',
			'us_bt_pause_border_color'      => '',
			'us_bt_pause_border_width'      => 0,
			'us_bt_pause_border_radius'     => 0,
			'us_bt_pause_font_size'         => 14,
			'us_pause_icon'                 => 0,
			'us_pause_icon_color'           => '#000',
			'us_pause_icon_font_size'       => 10,
			//button continue
			'us_bt_continue_title'          => '{skip_icon}',
			'us_bt_continue_bg_color'       => '#fff',
			'us_bt_continue_color'          => '#a9a9a9',
			'us_bt_continue_border_color'   => '',
			'us_bt_continue_border_width'   => 0,
			'us_bt_continue_border_radius'  => 0,
			'us_bt_continue_font_size'      => 18,
			'us_skip_icon'                  => 6,
			'us_skip_icon_color'            => '',
			'us_skip_icon_font_size'        => 18,
			//button add all to cart
			'us_bt_alltc_title'             => 'Add All To Cart',
			'us_bt_alltc_bg_color'          => '',
			'us_bt_alltc_color'             => '',
			'us_bt_alltc_border_color'      => '',
			'us_bt_alltc_border_width'      => 0,
			'us_bt_alltc_border_radius'     => 0,
			'us_bt_alltc_font_size'         => 18,
			'us_alltc_icon'                 => 20,
			'us_alltc_icon_color'           => '',
			'us_alltc_icon_font_size'       => 18,
			//product
			'us_desktop_display_type'       => 'slider',
			'us_mobile_display_type'        => 'scroll',
			'us_desktop_item_per_row'       => 5,
			'us_mobile_item_per_row'        => 2,
			'us_desktop_scroll_limit_rows'  => '',
			'us_mobile_scroll_limit_rows'   => '',
			'us_pd_template'                => 2,
			'us_pd_bg_color'                => '',
			'us_pd_box_shadow_color'        => '',
			'us_pd_border_color'            => '',
			'us_pd_border_radius'           => 0,
			'us_pd_img_padding'             => '',
			'us_pd_img_border_color'        => '',
			'us_pd_img_border_width'        => 0,
			'us_pd_img_border_radius'       => 0,
			'us_pd_details_padding'         => '',
			'us_pd_details_font_size'       => 16,
			'us_pd_details_color'           => '',
			'us_pd_details_text_align'      => 'left',
			'us_pd_qty_bg_color'            => '#eee',
			'us_pd_qty_color'               => '#222',
			'us_pd_qty_border_color'        => '#ded9d9',
			'us_pd_qty_border_radius'       => 0,
			'us_pd_atc_title'               => '{cart_icon}',
			'us_pd_atc_bg_color'            => '#bdbdbd',
			'us_pd_atc_color'               => '#fff',
			'us_pd_atc_border_color'        => '',
			'us_pd_atc_border_width'        => 0,
			'us_pd_atc_border_radius'       => 0,
			'us_pd_atc_font_size'           => 18,
			'us_pd_atc_icon'                => 20,
			'us_pd_atc_icon_color'          => '',
			'us_pd_atc_icon_font_size'      => 20,

			//product rule
			'us_product_type'               => array( 3 ),
			'us_product_limit'              => array( 5 ),
			'us_product_order_by'           => array( 'date' ),
			'us_product_order'              => array( 'desc' ),
			'us_product_rule_type'          => array( 'default' => array() ),
			'us_product_show_variation'     => array( 'default' => 1 ),
			'us_product_visibility'         => array( 'default' => array('visible' )),
			'us_product_include'            => array( 'default' => array() ),
			'us_product_exclude'            => array( 'default' => array() ),
			'us_cats_include'               => array( 'default' => array() ),
			'us_cats_exclude'               => array( 'default' => array() ),
			'us_product_price'              => array(
				'default' => array(
					'min' => 0,
					'max' => '',
				)
			),
			'us_discount_amount'            => array( 10 ),
			'us_discount_type'              => array( 3 ),
		), $this->get_rule_params( 'us_', 'default' ) );
		$general           = array(
			'recent_viewed_cookie' => '',
		);
		$this->default     = array_merge( $general, $upsell_funnel, $order_bump );
		$this->params      = apply_filters( 'viwcuf_woo_checkout_upsell_funnel_params', wp_parse_args( $viwcuf_params, $this->default ) );
	}

	public function enable( $prefix ) {
		if ( ! $prefix ) {
			return false;
		}
		if ( ! $this->get_params( $prefix . 'enable' ) ) {
			return false;
		}
		if ( function_exists('wp_is_mobile') && wp_is_mobile() && ! $this->get_params( $prefix . 'mobile_enable' ) ) {
			return false;
		}

		return true;
	}

	public function get_rule_params( $prefix, $id ) {
		if ( ! $prefix ) {
			return array();
		}
		$id = $id ?: $prefix . current_time( 'timestamp' );

		return array(
			$prefix . 'ids'                        => array( $id ),
			$prefix . 'names'                      => array( ucwords( str_replace( '_', ' ', $id ) ) ),
			$prefix . 'active'                     => array( 1 ),
			$prefix . 'days_show'                  => array( $id => array() ),
			$prefix . 'cart_rule_type'             => array(),
			$prefix . 'cart_item_include'          => array( $id => array() ),
			$prefix . 'cart_item_exclude'          => array( $id => array() ),
			$prefix . 'cart_cats_include'          => array( $id => array() ),
			$prefix . 'cart_cats_exclude'          => array( $id => array() ),
			$prefix . 'cart_coupon_include'        => array( $id => array() ),
			$prefix . 'cart_coupon_exclude'        => array( $id => array() ),
			$prefix . 'billing_countries_include'  => array( $id => array() ),
			$prefix . 'billing_countries_exclude'  => array( $id => array() ),
			$prefix . 'shipping_countries_include' => array( $id => array() ),
			$prefix . 'shipping_countries_exclude' => array( $id => array() ),
			$prefix . 'user_rule_type'             => array( $id => array() ),
			$prefix . 'user_logged'                => array( $id => 0 ),
			$prefix . 'user_include'               => array( $id => array() ),
			$prefix . 'user_exclude'               => array( $id => array() ),
			$prefix . 'user_role_include'          => array( $id => array() ),
			$prefix . 'user_role_exclude'          => array( $id => array() ),
		);
	}

	public function get_class_icons( $type = '' ) {
		if ( ! $type ) {
			return $this->class_icons;
		}

		return $this->class_icons[ $type ] ?? array();
	}

	public function get_class_icon( $index = 0, $type = '' ) {
		if ( ! $type ) {
			return false;
		}
		$icons = $this->get_class_icons( $type ) ?? array();
		if ( empty( $icons ) ) {
			return false;
		} else {
			return $icons[ $index ] ?? $icons[0] ?? '';
		}
	}

	public function get_params( $name = "" ) {
		if ( ! $name ) {
			return $this->params;
		}

		return apply_filters( 'viwcuf_woo_checkout_upsell_funnel_params' . $name, $this->params[ $name ]  ?? false );
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'viwcuf_woo_checkout_upsell_funnel_params_default-' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	public function get_current_setting( $name = "", $i = 0, $default = false ) {
		if ( ! $name ) {
			return false;
		}
		if ( $default !== false ) {
			$result = $this->get_params( $name)[ $i ] ?? $default;
		} else {
			$result = $this->get_params( $name )[ $i ] ?? $this->get_default( $name )[0] ?? false;
		}

		return $result;
	}

	public static function get_data_prefix( $type = 'upsell_funnel' ) {
		$date   = date( "Ymd" );
		$prefix = get_option( 'viwcuf_' . $type . '_prefix', $date );

		return $prefix . $type . $date;
	}

	public static function extend_post_allowed_html() {
		$allow_html = wp_kses_allowed_html( 'post' );
		foreach ( $allow_html as $key => $value ) {
			if ( in_array( $key, array( 'div', 'span', 'a', 'input', 'form', 'select', 'option', 'table' ) ) ) {
				$allow_html[ $key ]['data-*'] = 1;
			}
		}

		return array_merge( $allow_html, array(
				'input' => array(
					'type'         => 1,
					'id'           => 1,
					'name'         => 1,
					'class'        => 1,
					'placeholder'  => 1,
					'autocomplete' => 1,
					'style'        => 1,
					'value'        => 1,
					'data-*'       => 1,
					'size'         => 1,
					'max'          => 1,
					'min'          => 1,
					'step'         => 1,
				),
				'style'  => array(
					'id'     => 1,
					'class'  => 1,
					'type'  => 1,
				),
			)
		);
	}
}

new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();