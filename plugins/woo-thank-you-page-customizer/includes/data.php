<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_THANK_YOU_PAGE_DATA {
	private $params;
	private $default;

	/**
	 * VI_WOO_THANK_YOU_PAGE_DATA constructor.
	 * Init setting
	 */
	public function __construct() {
		$this->prefix = 'woocommerce-thank-you-page-';
		global $woo_thank_you_page_settings;
		if ( ! $woo_thank_you_page_settings ) {
			$woo_thank_you_page_settings = get_option( 'woo_thank_you_page_params', array() );
		}
		$this->default = array(
			'enable'                               => 0,
			'auto_update_key'                      => '',
			'order_status'                         => array(
				'wc-completed',
				'wc-processing',
				'wc-pending',
				'wc-on-hold',
			),
			'blocks'                               => json_encode(
				array(
					array(
						array(
							'thank_you_message',
							'coupon',
							'order_confirmation',
						),
					),
					array(
						array(
							'customer_information',
						),
						array(
							'order_details',
							'sale_products',
						),
					),
				)
			),
			'text_editor'                          => json_encode( array() ),
			'products'                             => json_encode( array() ),
			'select_order'                         => '',
			/*order_confirmation*/
			'order_confirmation_bg'                => '#f7f7f7',
			'order_confirmation_padding'           => 10,
			'order_confirmation_border_radius'     => 0,
			'order_confirmation_border_width'      => 0,
			'order_confirmation_border_style'      => 'dotted',
			'order_confirmation_border_color'      => 'black',
			'order_confirmation_vertical_width'    => 1,
			'order_confirmation_vertical_style'    => 'solid',
			'order_confirmation_vertical_color'    => '#dbdbdb',
			'order_confirmation_horizontal_width'  => 1,
			'order_confirmation_horizontal_style'  => 'solid',
			'order_confirmation_horizontal_color'  => '#dddddd',
			'order_confirmation_header'            => 'Order confirmation',
			'order_confirmation_header_color'      => '',
			'order_confirmation_header_bg_color'   => '',
			'order_confirmation_header_font_size'  => 20,
			'order_confirmation_header_text_align' => 'left',
			'order_confirmation_title_color'       => '',
			'order_confirmation_title_bg_color'    => '',
			'order_confirmation_title_font_size'   => 16,
			'order_confirmation_title_text_align'  => 'right',
			'order_confirmation_value_color'       => '',
			'order_confirmation_value_bg_color'    => '',
			'order_confirmation_value_font_size'   => 16,
			'order_confirmation_value_text_align'  => 'left',

			'order_details_color'                     => '',
			'order_details_bg'                        => '',
			'order_details_padding'                   => 0,
			'order_details_border_radius'             => 0,
			'order_details_border_width'              => 0,
			'order_details_border_style'              => 'solid',
			'order_details_border_color'              => '',
			'order_details_font_size'                 => 14,
			'order_details_horizontal_width'          => 1,
			'order_details_horizontal_style'          => 'dotted',
			'order_details_horizontal_color'          => '#ddd',
			'order_details_header'                    => 'Order details',
			'order_details_header_color'              => '',
			'order_details_header_bg_color'           => '',
			'order_details_header_font_size'          => 20,
			'order_details_header_text_align'         => 'left',
			'order_details_product_image'             => 1,
			'order_details_product_image_width'       => 50,
			'order_details_product_quantity_in_image' => 0,

			'customer_information_color'              => '',
			'customer_information_bg'                 => '',
			'customer_information_padding'            => 0,
			'customer_information_border_radius'      => 0,
			'customer_information_border_width'       => 0,
			'customer_information_border_style'       => 'solid',
			'customer_information_border_color'       => '',
			'customer_information_vertical_width'     => 0,
			'customer_information_vertical_style'     => 'dotted',
			'customer_information_vertical_color'     => '#ddd',
			'customer_information_header'             => 'Customer information',
			'customer_information_header_color'       => '',
			'customer_information_header_font_size'   => 20,
			'customer_information_header_text_align'  => 'left',
			'customer_information_address_color'      => '',
			'customer_information_address_bg_color'   => '',
			'customer_information_address_font_size'  => 16,
			'customer_information_address_text_align' => 'left',

			'social_icons_header'           => 'Let\'s keep in touch',
			'social_icons_header_color'     => '',
			'social_icons_header_font_size' => 18,
			'social_icons_align'            => 'left',
			'social_icons_space'            => 5,
			'social_icons_size'             => 40,
			'social_icons_target'           => '_blank',
			'social_icons_facebook_url'     => '',
			'social_icons_facebook_select'  => 'wtyp_social_icons-facebook-app-logo',
			'social_icons_facebook_color'   => '#3b579d',
			'social_icons_twitter_url'      => '',
			'social_icons_twitter_select'   => 'wtyp_social_icons-twitter-1',
			'social_icons_twitter_color'    => '#3CF',
			'social_icons_pinterest_url'    => '',
			'social_icons_pinterest_select' => 'wtyp_social_icons-pinterest-social-logo',
			'social_icons_pinterest_color'  => '#BD081C',
			'social_icons_instagram_url'    => '',
			'social_icons_instagram_select' => 'wtyp_social_icons-instagram',
			'social_icons_instagram_color'  => '#6a453b',
			'social_icons_dribbble_url'     => '',
			'social_icons_dribbble_select'  => 'wtyp_social_icons-dribbble-logo-1',
			'social_icons_dribbble_color'   => '#F26798',
			'social_icons_tumblr_url'       => '',
			'social_icons_tumblr_select'    => 'wtyp_social_icons-tumblr-logo-2',
			'social_icons_tumblr_color'     => '#32506d',
			'social_icons_google_url'       => '',
			'social_icons_google_select'    => 'wtyp_social_icons-google-plus-social-logotype-1',
			'social_icons_google_color'     => '#DC4A38',
			'social_icons_vkontakte_url'    => '',
			'social_icons_vkontakte_select' => 'wtyp_social_icons-vk-social-logotype-1',
			'social_icons_vkontakte_color'  => '#45668e',
			'social_icons_linkedin_url'     => '',
			'social_icons_linkedin_select'  => 'wtyp_social_icons-linkedin-logo-1',
			'social_icons_linkedin_color'   => '#007bb5',
			'social_icons_youtube_url'      => '',
			'social_icons_youtube_select'   => 'wtyp_social_icons-youtube-logotype',
			'social_icons_youtube_color'    => '#ff0000',

			'recently_viewed_products_limit'   => 4,
			'recently_viewed_products_columns' => 4,
			'featured_products_limit'          => 4,
			'featured_products_columns'        => 4,
			'related_products_limit'           => 4,
			'related_products_columns'         => 4,
			'best_selling_products_limit'      => 4,
			'best_selling_products_columns'    => 4,
			'sale_products_limit'              => 4,
			'sale_products_columns'            => 4,
			'recent_products_limit'            => 4,
			'recent_products_columns'          => 4,
			'top_rated_products_limit'         => 4,
			'top_rated_products_columns'       => 4,
			'up_sells_products_limit'          => 4,
			'up_sells_products_columns'        => 4,
			'cross_sells_products_limit'       => 4,
			'cross_sells_products_columns'     => 4,
			'same_category_limit'              => 4,
			'same_category_columns'            => 4,
			'specific_products_limit'          => 4,
			'specific_products_columns'        => 4,

			'thank_you_message_padding'           => '',
			'thank_you_message_color'             => '',
			'thank_you_message_text_align'        => 'left',
			'thank_you_message_header'            => 'Order #{order_number}',
			'thank_you_message_header_font_size'  => '16',
			'thank_you_message_message'           => 'Thank you {billing_first_name}',
			'thank_you_message_message_font_size' => '20',

			'google_map_width'        => 0,
			'google_map_height'       => '500',
			'google_map_api'          => '',
			'google_map_address'      => '{store_address}',
			'google_map_label'        => '<h3>This is my store</h3>
{address}',
			'google_map_zoom_level'   => 16,
			'google_map_style'        => 'default',
			'google_map_custom_style' => '',
			'google_map_marker'       => 'default',

			'coupon_text_align'        => 'center',
			'coupon_text_padding'      => 40,
			'coupon_message'           => 'You have unlocked a {coupon_amount} coupon code',
			'coupon_message_color'     => '',
			'coupon_message_font_size' => '18',
			'coupon_code_color'        => '#000000',
			'coupon_code_bg_color'     => '#efefef',
			'coupon_code_border_width' => 1,
			'coupon_code_border_style' => 'dashed',
			'coupon_code_border_color' => '#160000',
			'coupon_scissors_color'    => '#000000',

			'coupon_type'                               => array( 'unique' ),
			'existing_coupon'                           => array( '' ),
			'coupon_unique_discount_type'               => array( 'percent' ),
			'coupon_unique_amount'                      => array( '10' ),
			'coupon_unique_date_expires'                => array( 30 ),
			'coupon_unique_individual_use'              => array( false ),
			'coupon_unique_product_ids'                 => array( array() ),
			'coupon_unique_excluded_product_ids'        => array( array() ),
			'coupon_unique_usage_limit'                 => array( 1 ),
			'coupon_unique_usage_limit_per_user'        => array( 1 ),
			'coupon_unique_limit_usage_to_x_items'      => array( 1 ),
			'coupon_unique_free_shipping'               => array( false ),
			'coupon_unique_product_categories'          => array( array() ),
			'coupon_unique_excluded_product_categories' => array( array() ),
			'coupon_unique_exclude_sale_items'          => array( false ),
			'coupon_unique_minimum_amount'              => array( '' ),
			'coupon_unique_maximum_amount'              => array( '' ),
			'coupon_unique_email_restrictions'          => array( true ),
			'coupon_unique_prefix'                      => array( '' ),
			'coupon_rule_product_ids'                   => array( array() ),
			'coupon_rule_excluded_product_ids'          => array( array() ),
			'coupon_rule_product_categories'            => array( array() ),
			'coupon_rule_excluded_product_categories'   => array( array() ),
			'coupon_rule_min_total'                     => array( 0 ),
			'coupon_rule_max_total'                     => array( 0 ),
			'coupon_limit_per_day'                      => 1,
			'coupon_limit_per_week'                     => '',
			'coupon_limit_per_month'                    => '',
			'coupon_limit_per_year'                     => '',

			'custom_css' => '',

			'shortcode_products_product_ids'                 => '',
			'shortcode_products_excluded_product_ids'        => '',
			'shortcode_products_product_categories'          => '',
			'shortcode_products_excluded_product_categories' => '',
			'shortcode_products_order_by'                    => 'title',
			'shortcode_products_visibility'                  => 'visible',

			'specific_products_product_ids'                 => '',
			'specific_products_excluded_product_ids'        => '',
			'specific_products_product_categories'          => '',
			'specific_products_excluded_product_categories' => '',
			'specific_products_order_by'                    => 'title',
			'specific_products_visibility'                  => 'visible',

			'coupon_email_send'        => '',
			'coupon_email_enable'      => '',
			'coupon_email_subject'     => 'Your coupon from {shop_title}',
			'coupon_email_heading'     => '{coupon_amount} OFF coupon for you',
			'coupon_email_content'     => 'Hello {billing_first_name},

Thank you for your purchase, this coupon is for you {coupon_code}. Please apply it the next time you shop with us.

We look forward to seeing you again. Have a great day!

{coupon_code_style_1}

&nbsp;

Best Regards',
			//new option
			'my_account_coupon_enable' => '1',
		);

		$this->params = apply_filters( 'woo_thank_you_page_params', wp_parse_args( $woo_thank_you_page_settings, $this->default ) );
	}

	public function get_params( $name = "" ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			return apply_filters( 'woo_thank_you_page_params' . $name, $this->params[ $name ] );
		} else {
			return false;
		}
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'woo_thank_you_page_params_default' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	public function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );

		} else {
			return esc_attr__( $this->prefix . $name );

		}
	}
}

new VI_WOO_THANK_YOU_PAGE_DATA();
