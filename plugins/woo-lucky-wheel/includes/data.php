<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_LUCKY_WHEEL_DATA {
	private $params;
	private $default;
	protected static $instance = null;
	/**
	 * VI_WOO_LUCKY_WHEEL_DATA constructor.
	 * Init setting
	 */
	public function __construct() {

		global $woo_lucky_wheel_settings;
		if ( ! $woo_lucky_wheel_settings ) {
			$woo_lucky_wheel_settings = get_option( '_wlwl_settings', array() );
		}
		$this->default = array(
			'general'                           => array(
				'enable'     => "on",
				'mobile'     => "on",
				'spin_num'   => 1,
				'delay'      => 24,
				'delay_unit' => 'h'
			),
			'notify'                            => array(
				'position'      => 'bottom-right',
				'size'          => 40,
				'color'         => '',
				'intent'        => 'popup_icon',
				'hide_popup'    => 'off',
				'show_wheel'    => '1,5',//initial time
				'scroll_amount' => '50',

				'show_again'         => 24,
				'show_again_unit'    => 'h',
				'show_only_front'    => 'off',
				'show_only_blog'     => 'off',
				'show_only_shop'     => 'off',
				'conditional_tags'   => '',
				'time_on_close'      => '1',
				'time_on_close_unit' => 'd',
			),
			'wheel_wrap'                        => array(
				'description'            => '<h2><span style="color: #ffffff;">SPIN TO WIN!</span></h2>
<ul>
 	<li><em><span style="color: #dbdbdb;">Try your lucky to get discount coupon</span></em></li>
 	<li><em><span style="color: #dbdbdb;">1 spin per email</span></em></li>
 	<li><em><span style="color: #dbdbdb;">No cheating</span></em></li>
</ul>',
				'bg_image'               => VI_WOO_LUCKY_WHEEL_IMAGES.'2020.png',
				'bg_color'               => '#189a7a',
				'text_color'             => '#ffffff',
				'spin_button'            => 'Try Your Lucky',
				'spin_button_color'      => '#000000',
				'spin_button_bg_color'   => '#ffbe10',
				'pointer_position'       => 'center',
				'pointer_color'          => '#f70707',
				'wheel_center_image'     => '',
				'wheel_center_color'     => '#ffffff',
				'wheel_border_color'     => '#ffffff',
				'wheel_dot_color'        => '#000000',
				'close_option'           => 'on',
				'font'                   => 'Open+Sans',
				'gdpr'                   => 'off',
				'gdpr_message'           => 'I agree with the <a href="">term and condition</a>',
				'custom_css'             => '',
				'congratulations_effect' => 'firework',
				'background_effect'      => 'snowflakes',
			),
			'wheel'                             => array(
				'label_coupon'     => '{coupon_amount} OFF',
				'spinning_time'    => 8,
				'custom_value'     => array( "", "", "", "", "", "" ),
				'custom_label'     => array(
					"Not Lucky",
					"{coupon_amount} OFF",
					"Not Lucky",
					"{coupon_amount} OFF",
					"Not Lucky",
					"{coupon_amount} OFF"
				),
				'existing_coupon'  => array( "", "", "", "", "", ""),
				'coupon_type'      => array(
					'non',
					'percent',
					'non',
					'fixed_product',
					'non',
					'fixed_cart'
				),
				'coupon_amount'    => array( '0', '5', '0', '10', '0', '15'),
				'probability'      => array( '25', '15', '25', '6', '25', '4' ),
				'bg_color'         => array(
					'#ffe0b2',
					'#e65100',
					'#ffb74d',
					'#fb8c00',
					'#ffe0b2',
					'#e65100',
				),
				'slice_text_color' => '#fff',//free version
				'slices_text_color' => array(
					'#fff',
					'#fff',
					'#fff',
					'#fff',
					'#fff',
					'#fff',
				),
				'currency'         => 'symbol',
				'show_full_wheel'  => 'off',
				'font_size'        => '100',
				'wheel_size'        => '100',
				'random_color'     => 'off',

			),
			'result'                            => array(
				'auto_close'   => 0,
				'email'        => array(
					'subject' => 'Lucky wheel coupon award.',
					'heading' => 'Congratulations!',
					'content' => "Dear {customer_name},\nYou have won a discount coupon by spinning lucky wheel on my website. Please apply the coupon when shopping with us.\nThank you!\nCoupon code :{coupon_code}\nExpiry date: {date_expires}\nYour Sincerely",
				),
				'notification' => array(
					'win'  => 'Congrats! You have won a {coupon_label} discount coupon. The coupon was sent to the email address that you had entered to spin. {checkout} now!',
					'lost' => 'OOPS! You are not lucky today. Sorry.',
				),
			),
			'coupon'                            => array(
				'allow_free_shipping' => 'no',
				'expiry_date'         => null,
				'min_spend'           => '',
				'max_spend'           => '',
				'individual_use'      => 'no',
				'exclude_sale_items'  => 'no',
				'limit_per_coupon'    => 1,
				'limit_to_x_items'    => 1,
				'limit_per_user'      => 1,
				'product_ids'         => array(),
				'exclude_product_ids'         => array(),
				'product_categories'         => array(),
				'exclude_product_categories'         => array(),
				'coupon_code_prefix'  => ''
			),
			'mailchimp'                         => array(
				'enable'  => 'off',
				'api_key' => '',
				'lists'   => ''
			),
			'active_campaign'                   => array(
				'enable' => 'off',
				'key'    => '',
				'url'    => '',
				'list'   => '',
			),
			'key'                               => '',
			'button_shop_title'                 => 'Shop now',
			'button_shop_url'                   => get_bloginfo( 'url' ),
			'button_shop_color'                 => '#fff',
			'button_shop_bg_color'              => '#000',
			'button_shop_size'                  => '20',
			'suggested_products'                => array(),
			'sendgrid'                          => array(
				'enable' => 'off',
				'key'    => '',
				'list'   => 'none',
			),
			'ajax_endpoint'                     => 'ajax',
			'custom_field_mobile_enable'        => 'off',
			'custom_field_mobile_enable_mobile' => 'off',
			'custom_field_mobile_required'      => 'off',
			'custom_field_name_enable'          => 'on',
			'custom_field_name_enable_mobile'   => 'on',
			'custom_field_name_required'        => 'off',
		);

		$this->params = apply_filters( 'woo_lucky_wheel_params', wp_parse_args( $woo_lucky_wheel_settings, $this->default ) );
	}
	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	public function get_params( $name = "", $name_sub = '' ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			if ( $name_sub ) {
				if ( isset( $this->params[ $name ][ $name_sub ] ) ) {
					return apply_filters( 'woo_lucky_wheel_params_' . $name . '__' . $name_sub, $this->params[ $name ] [ $name_sub ] );
				} elseif ( $this->default[ $name ] [ $name_sub ] ) {
					return apply_filters( 'woo_lucky_wheel_params_' . $name . '__' . $name_sub, $this->default[ $name ] [ $name_sub ] );
				} else {
					return false;
				}
			} else {
				return apply_filters( 'woo_lucky_wheel_params_' . $name, $this->params[ $name ] );
			}
		} else {
			return false;
		}
	}

	public function get_default( $name = "", $name_sub = '' ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			if ( $name_sub ) {
				if ( isset( $this->default[ $name ][ $name_sub ] ) ) {
					return apply_filters( 'woo_lucky_wheel_params_default_' . $name . '__' . $name_sub, $this->default[ $name ] [ $name_sub ] );
				} else {
					return false;
				}
			} else {
				return apply_filters( 'woo_lucky_wheel_params_default_' . $name, $this->default[ $name ] );
			}
		} else {
			return false;
		}
	}

}