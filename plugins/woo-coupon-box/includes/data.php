<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_COUPON_BOX_DATA {
	private $params;
	private $default;

	/**
	 * VI_WOO_COUPON_BOX_DATA constructor.
	 * Init setting
	 */
	public function __construct() {

		global $coupon_box_settings;
		if ( ! $coupon_box_settings ) {
			$coupon_box_settings = get_option( 'woo_coupon_box_params', array() );
		}
		$this->default = array(
			/*old option*/
			'wcb_active'                                    => 1,
			'wcb_coupon'                                    => '',
			'wcb_email_campaign'                            => '',
			'wcb_enable_mailchimp'                          => '',
			'wcb_api'                                       => '',
			'wcb_mlists'                                    => '',
			'wcb_assign_home'                               => '',
			'wcb_assign'                                    => '',
			/*new options*/
			'wcb_coupon_select'                             => 'unique',
			'wcb_coupon_custom'                             => '',
			'wcb_coupon_unique_amount'                      => 10,
			'wcb_coupon_unique_date_expires'                => 30,
			'wcb_coupon_unique_discount_type'               => 'percent',
			'wcb_coupon_unique_description'                 => '',
			'wcb_coupon_unique_individual_use'              => false,
			'wcb_coupon_unique_product_ids'                 => array(),
			'wcb_coupon_unique_excluded_product_ids'        => array(),
			'wcb_coupon_unique_usage_limit'                 => 0,
			'wcb_coupon_unique_usage_limit_per_user'        => 0,
			'wcb_coupon_unique_limit_usage_to_x_items'      => null,
			'wcb_coupon_unique_free_shipping'               => false,
			'wcb_coupon_unique_product_categories'          => array(),
			'wcb_coupon_unique_excluded_product_categories' => array(),
			'wcb_coupon_unique_exclude_sale_items'          => false,
			'wcb_coupon_unique_minimum_amount'              => '50',
			'wcb_coupon_unique_maximum_amount'              => '100',
			'wcb_coupon_unique_email_restrictions'          => true,
			'wcb_coupon_unique_prefix'                      => '',

			'wcb_email_subject'                 => 'Thank you for subscribing',
			'wcb_email_heading'                 => '{coupon_value} OFF DISCOUNT COUPON CODE OFFER',
			'wcb_email_content'                 => 'Thanks for signing up for our newsletter.

Enjoy your discount code for {coupon_value} OFF until {last_valid_date}. Don\'t miss out this great chance on our shop.

{coupon_code}

{shop_now}',
			'wcb_button_shop_now_title'         => 'Shop Now',
			'wcb_button_shop_now_url'           => get_bloginfo( 'url' ),
			'wcb_button_shop_now_size'          => '16',
			'wcb_button_shop_now_color'         => '#fff',
			'wcb_button_shop_now_bg_color'      => '#52d2aa',
			'wcb_button_shop_now_border_radius' => '30',

			'wcb_disable_login' => 1,
			'wcb_select_popup'  => 'time',
			'wcb_popup_time'    => '3,10',
			'wcb_popup_scroll'  => '',
			'wcb_popup_exit'    => '',
			'wcb_on_close'      => 'hide',

			'wcb_expire'                 => '1',
			'wcb_expire_unit'            => 'hour',
			'wcb_expire_subscribed'      => '360',
			'wcb_layout'                 => 1,
			'wcb_purchased_code'         => '',
			'wcb_enable_active_campaign' => '',
			'wcb_active_campaign_api'    => '',
			'wcb_active_campaign_url'    => '',
			'wcb_active_campaign_list'   => '',

			'wcb_social_icons_size'             => '50',
			'wcb_social_icons_target'           => '_blank',
			'wcb_social_icons_facebook_url'     => '',
			'wcb_social_icons_facebook_select'  => 'wcb_social_icons-facebook-app-logo',
			'wcb_social_icons_facebook_color'   => '#3b579d',
			'wcb_social_icons_twitter_url'      => '',
			'wcb_social_icons_twitter_select'   => 'wcb_social_icons-twitter-1',
			'wcb_social_icons_twitter_color'    => '#3CF',
			'wcb_social_icons_pinterest_url'    => '',
			'wcb_social_icons_pinterest_select' => 'wcb_social_icons-pinterest-social-logo',
			'wcb_social_icons_pinterest_color'  => '#BD081C',
			'wcb_social_icons_instagram_url'    => '',
			'wcb_social_icons_instagram_select' => 'wcb_social_icons-instagram',
			'wcb_social_icons_instagram_color'  => '#6a453b',
			'wcb_social_icons_dribbble_url'     => '',
			'wcb_social_icons_dribbble_select'  => 'wcb_social_icons-dribbble-logo-1',
			'wcb_social_icons_dribbble_color'   => '#F26798',
			'wcb_social_icons_tumblr_url'       => '',
			'wcb_social_icons_tumblr_select'    => 'wcb_social_icons-tumblr-logo-2',
			'wcb_social_icons_tumblr_color'     => '#32506d',
			'wcb_social_icons_google_url'       => '',
			'wcb_social_icons_google_select'    => 'wcb_social_icons-google-plus-social-logotype-1',
			'wcb_social_icons_google_color'     => '#DC4A38',
			'wcb_social_icons_vkontakte_url'    => '',
			'wcb_social_icons_vkontakte_select' => 'wcb_social_icons-vk-social-logotype-1',
			'wcb_social_icons_vkontakte_color'  => '#45668e',
			'wcb_social_icons_linkedin_url'     => '',
			'wcb_social_icons_linkedin_select'  => 'wcb_social_icons-linkedin-logo-1',
			'wcb_social_icons_linkedin_color'   => '#007bb5',
			'wcb_social_icons_youtube_url'      => '',
			'wcb_social_icons_youtube_select'   => 'wcb_social_icons-youtube-logotype',
			'wcb_social_icons_youtube_color'    => '#ff0000',
			'wcb_view_mode'                     => '1',
			'wcb_show_coupon'                   => '',
			'wcb_border_radius'                 => 25,
			'wcb_title'                         => 'WANT COUPON',
			'wcb_title_size'                    => '40',
			'wcb_title_space'                   => '20',
			'wcb_color_header'                  => '#ffffff',
			'wcb_bg_header'                     => '#1e73be',
			'wcb_header_bg_img'                 => '',
			'wcb_header_bg_img_repeat'          => 'no-repeat',
			'wcb_header_bg_img_size'            => 'contain',
			'wcb_header_bg_img_position'        => 'center',
			'wcb_header_font'                   => wp_json_encode( array( 'font' => '' ) ),
			'wcb_body_font'                     => wp_json_encode( array( 'font' => '' ) ),
			'wcb_body_bg'                       => '#f4fbff',
			'wcb_body_bg_img'                   => '',
			'wcb_body_bg_img_repeat'            => 'no-repeat',
			'wcb_body_bg_img_size'              => 'cover',
			'wcb_body_bg_img_position'          => '0% 0%',
			'wcb_body_text_color'               => '#000000',
			'wcb_message'                       => 'Subscribe now to get free discount coupon code. Don\'t miss out!',
			'wcb_message_after_subscribe'       => 'Congratulation! You have subscribed successfully. Please check out your mailbox to see the coupon code.',
			'wcb_color_message'                 => '',
			'wcb_message_size'                  => '14',
			'wcb_message_align'                 => 'center',
			'wcb_follow_us'                     => '',
			'wcb_color_follow_us'               => '',
			'wcb_button_text'                   => 'SUBSCRIBE',
			'wcb_button_text_color'             => '#ffffff',
			'wcb_button_bg_color'               => '#ff1452',
			'wcb_button_border_radius'          => 0,
			'wcb_email_input_border_radius'     => 0,
			'wcb_email_button_space'            => 0,
			'wcb_footer_text'                   => 'We will never spam you, unsubscribe anytime.',
			'wcb_footer_text_after_subscribe'   => 'Thank you for subscription',
			'wcb_popup_type'                    => 'wcb-md-effect-1',
			'alpha_color_overlay'               => 'rgba(29, 29, 29, 0.8)',
			'wcb_gdpr_checkbox'                 => 1,
			'wcb_gdpr_checkbox_checked'         => 0,
			'wcb_gdpr_message'                  => 'I agree with the <a href="">term and condition</a>',
			'wcb_custom_css'                    => '',
			'wcb_effect'                        => '',

			'wcb_button_close'               => 'wcb_button_close_icons-close',
			'wcb_button_close_size'          => '16',
			'wcb_button_close_width'         => '36',
			'wcb_button_close_color'         => '#000000',
			'wcb_button_close_bg_color'      => 'rgba(255,255,255,0.7)',
			'wcb_button_close_border_radius' => '20',
			'wcb_button_close_position_x'    => '11',
			'wcb_button_close_position_y'    => '11',

			'wcb_right_column_bg'              => '',
			'wcb_right_column_bg_img'          => '',
			'wcb_right_column_bg_img_repeat'   => 'no-repeat',
			'wcb_right_column_bg_img_size'     => 'cover',
			'wcb_right_column_bg_img_position' => 'center',

			'wcb_popup_icon_enable'        => 0,
			'wcb_popup_icon'               => 'wcb_giftbox-gift-with-ribbon',
			'wcb_popup_icon_position'      => 'bottom-right',
			'wcb_popup_icon_size'          => '30',
			'wcb_popup_icon_color'         => '#ffffff',
			'wcb_popup_icon_bg_color'      => '#9632dc',
			'wcb_popup_icon_border_radius' => '8',
			'wcb_popup_icon_mobile'        => '',

			'wcb_input_name'                    => 0,
			'wcb_input_name_required'           => 0,
			'wcb_input_mobile'                  => 0,
			'wcb_input_mobile_required'         => 0,
			'wcb_input_birthday'                => 0,
			'wcb_input_birthday_required'       => 0,
			'wcb_input_gender'                  => 0,
			'wcb_input_gender_required'         => 0,
			'wcb_custom_input_border_radius'    => 0,
			'wcb_pro_version_features'          => 0,

			/*new option 2.0.1*/
			'wcb_title_after_subscribing'       => 'Subscribed successfully!',
			'wcb_recaptcha'                     => 0,
			'wcb_recaptcha_version'             => 2,
			'wcb_recaptcha_site_key'            => '',
			'wcb_recaptcha_secret_key'          => '',
			'wcb_recaptcha_secret_theme'        => 'light',
			'wcb_never_reminder_enable'         => 0,
			'wcb_no_thank_button_enable'        => 0,
			'wcb_no_thank_button_title'         => 'No, thanks',
			'wcb_no_thank_button_color'         => '#c3bbbb',
			'wcb_no_thank_button_bg_color'      => '#eff6fa',
			'wcb_no_thank_button_border_radius' => '4',
		);

		$this->params = apply_filters( 'woo_coupon_box_params', wp_parse_args( $coupon_box_settings, $this->default ) );
	}

	/**
	 * Get add to cart redirect
	 * @return mixed|void
	 */
	public function get_params( $name = "" ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			return apply_filters( 'woo_coupon_box_params' . $name, $this->params[ $name ] );
		} else {
			return false;
		}
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'woo_coupon_box_params_default' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

}

new VI_WOO_COUPON_BOX_DATA();
