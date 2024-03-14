<?php
/*
Class Name: VI_WOO_THANK_YOU_PAGE_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_THANK_YOU_PAGE_Admin_Design {
	protected $settings;
	protected $order_id;
	protected $key;
	protected $prefix;
	protected $text_editor;
	protected $shortcodes;

	public function __construct() {
		$this->settings   = new VI_WOO_THANK_YOU_PAGE_DATA();
		$this->prefix     = 'woocommerce-thank-you-page-';
		$this->shortcodes = array(
			'order_number'   => '',
			'order_status'   => '',
			'order_date'     => '',
			'order_total'    => '',
			'order_subtotal' => '',
			'items_count'    => '',
			'payment_method' => '',

			'shipping_method'            => '',
			'shipping_address'           => '',
			'formatted_shipping_address' => '',

			'billing_address'           => '',
			'formatted_billing_address' => '',
			'billing_country'           => '',
			'billing_city'              => '',

			'billing_first_name'          => '',
			'billing_last_name'           => '',
			'formatted_billing_full_name' => '',
			'billing_email'               => '',

			'shop_title'    => '',
			'home_url'      => '',
			'shop_url'      => '',
			'store_address' => '',
		);
		add_action( 'customize_register', array( $this, 'design_option_customizer' ) );
		add_action( 'wp_print_styles', array( $this, 'customize_controls_print_styles' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
//		add_action( 'customize_controls_print_scripts', array( $this, 'customize_controls_print_scripts' ), 99 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ), 30 );
		add_action( 'wp_ajax_woo_thank_you_page_get_available_shortcodes', array( $this, 'get_available_shortcodes' ) );
	}

	public function get_available_shortcodes() {
		$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '';
		$order    = wc_get_order( $order_id );
		if ( $order ) {
			$shortcodes    = array(
				'order_number'   => $order_id,
				'order_status'   => $order->get_status(),
				'order_date'     => $order->get_date_created() ? $order->get_date_created()->date_i18n( 'F d, Y' ) : '',
				'order_total'    => $order->get_formatted_order_total(),
				'order_subtotal' => $order->get_subtotal_to_display(),
				'items_count'    => $order->get_item_count(),
				'payment_method' => $order->get_payment_method_title(),

				'shipping_method'            => $order->get_shipping_method(),
				'shipping_address'           => $order->get_shipping_address_1(),
				'formatted_shipping_address' => $order->get_formatted_shipping_address(),

				'billing_address'           => $order->get_billing_address_1(),
				'formatted_billing_address' => $order->get_formatted_billing_address(),
				'billing_country'           => $order->get_billing_country(),
				'billing_city'              => $order->get_billing_city(),

				'billing_first_name'          => ucwords( $order->get_billing_first_name() ),
				'billing_last_name'           => ucwords( $order->get_billing_last_name() ),
				'formatted_billing_full_name' => ucwords( $order->get_formatted_billing_full_name() ),
				'billing_email'               => $order->get_billing_email(),

				'shop_title' => get_bloginfo(),
				'home_url'   => home_url(),
				'shop_url'   => get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '',

			);
			$country       = new WC_Countries();
			$store_address = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
			if ( $country->get_base_city() ) {
				$store_address .= ', ' . $country->get_base_city();
			}
			if ( $country->get_base_state() ) {
				$store_address .= ', ' . $country->get_base_state();
			}
			if ( $country->get_base_country() ) {
				$store_address .= ', ' . $country->get_base_country();
			}
			$shortcodes['store_address'] = $store_address;
			wp_send_json( array(
				'shortcodes' => $shortcodes
			) );
		}
		die;
	}

	public function customize_controls_print_styles() {
		if ( ! is_customize_preview() ) {
			return;
		}
		/*order confirmation*/
		$this->add_preview_style( 'order_confirmation_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'background-color' );
		$this->add_preview_style( 'order_confirmation_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'padding', 'px' );
		$this->add_preview_style( 'order_confirmation_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-radius', 'px' );
		$this->add_preview_style( 'order_confirmation_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-width', 'px' );
		$this->add_preview_style( 'order_confirmation_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-style' );
		$this->add_preview_style( 'order_confirmation_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-color' );

		$this->add_preview_style( 'order_confirmation_vertical_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-width', 'px' );
		$this->add_preview_style( 'order_confirmation_vertical_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-style' );
		$this->add_preview_style( 'order_confirmation_vertical_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-color' );

		$this->add_preview_style( 'order_confirmation_horizontal_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-bottom-width', 'px' );
		$this->add_preview_style( 'order_confirmation_horizontal_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-bottom-style' );
		$this->add_preview_style( 'order_confirmation_horizontal_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-bottom-color' );

		$this->add_preview_style( 'order_confirmation_header_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'color' );
		$this->add_preview_style( 'order_confirmation_header_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'background-color' );
		$this->add_preview_style( 'order_confirmation_header_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'font-size', 'px' );
		$this->add_preview_style( 'order_confirmation_header_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'text-align' );

		$this->add_preview_style( 'order_confirmation_title_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'color' );
		$this->add_preview_style( 'order_confirmation_title_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'background-color' );
		$this->add_preview_style( 'order_confirmation_title_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'font-size', 'px' );
		$this->add_preview_style( 'order_confirmation_title_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'text-align' );

		$this->add_preview_style( 'order_confirmation_value_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'color' );
		$this->add_preview_style( 'order_confirmation_value_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'background-color' );
		$this->add_preview_style( 'order_confirmation_value_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'font-size', 'px' );
		$this->add_preview_style( 'order_confirmation_value_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'text-align' );

		/*order details*/
		$this->add_preview_style( 'order_details_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'color' );
		$this->add_preview_style( 'order_details_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'background-color' );
		$this->add_preview_style( 'order_details_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'padding', 'px' );
		$this->add_preview_style( 'order_details_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-radius', 'px' );
		$this->add_preview_style( 'order_details_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-width', 'px' );
		$this->add_preview_style( 'order_details_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-style' );
		$this->add_preview_style( 'order_details_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-color' );

		$this->add_preview_style( 'order_details_horizontal_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-width', 'px' );
		$this->add_preview_style( 'order_details_horizontal_style', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-style' );
		$this->add_preview_style( 'order_details_horizontal_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-color' );

		$this->add_preview_style( 'order_details_header_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'color' );
		$this->add_preview_style( 'order_details_header_bg_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'background-color' );
		$this->add_preview_style( 'order_details_header_font_size', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'font-size', 'px' );
		$this->add_preview_style( 'order_details_header_text_align', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'text-align' );

		$this->add_preview_style( 'order_details_product_image_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-title a.woocommerce-thank-you-page-order-item-image-wrap', 'width', 'px' );

		/*customer information*/
		$this->add_preview_style( 'customer_information_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'color' );
		$this->add_preview_style( 'customer_information_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'background-color' );
		$this->add_preview_style( 'customer_information_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'padding', 'px' );
		$this->add_preview_style( 'customer_information_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-radius', 'px' );
		$this->add_preview_style( 'customer_information_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-width', 'px' );
		$this->add_preview_style( 'customer_information_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-style' );
		$this->add_preview_style( 'customer_information_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-color' );

		$this->add_preview_style( 'customer_information_vertical_width', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-width', 'px' );
		$this->add_preview_style( 'customer_information_vertical_style', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-style' );
		$this->add_preview_style( 'customer_information_vertical_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-color' );

		$this->add_preview_style( 'customer_information_header_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'color' );
		$this->add_preview_style( 'customer_information_header_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'background-color' );
		$this->add_preview_style( 'customer_information_header_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'font-size', 'px' );
		$this->add_preview_style( 'customer_information_header_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'text-align' );

		$this->add_preview_style( 'customer_information_address_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'color' );
		$this->add_preview_style( 'customer_information_address_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'background-color' );
		$this->add_preview_style( 'customer_information_address_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'font-size', 'px' );
		$this->add_preview_style( 'customer_information_address_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'text-align' );

		/*social icons*/
		$this->add_preview_style( 'social_icons_header_color', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'color' );
		$this->add_preview_style( 'social_icons_header_font_size', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'font-size', 'px' );
		$this->add_preview_style( 'social_icons_align', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials', 'text-align' );
		$this->add_preview_style( 'social_icons_space', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li:not(:last-child)', 'margin-right', 'px' );
		$this->add_preview_style( 'social_icons_size', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li .wtyp-social-button span', 'font-size', 'px' );
		$this->add_preview_style( 'social_icons_facebook_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-facebook-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_twitter_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-twitter-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_pinterest_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-pinterest-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_instagram_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-instagram-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_dribbble_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-dribbble-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_tumblr_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-tumblr-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_google_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-google-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_vkontakte_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-vkontakte-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_linkedin_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-linkedin-follow .wtyp-social-button span:before', 'color' );
		$this->add_preview_style( 'social_icons_youtube_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-youtube-follow .wtyp-social-button span:before', 'color' );

		/*thank you message*/
		$this->add_preview_style( 'thank_you_message_color', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail', 'color' );
		$this->add_preview_style( 'thank_you_message_padding', '.woocommerce-thank-you-page-thank_you_message__container', 'padding', 'px' );
		$this->add_preview_style( 'thank_you_message_text_align', '.woocommerce-thank-you-page-thank_you_message__container', 'text-align' );
		$this->add_preview_style( 'thank_you_message_header_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-header', 'font-size', 'px' );
		$this->add_preview_style( 'thank_you_message_message_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-message', 'font-size', 'px' );

		/*coupon*/
		$this->add_preview_style( 'coupon_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'text-align' );
		$this->add_preview_style( 'coupon_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'padding', 'px' );
		$this->add_preview_style( 'coupon_message_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'color' );
		$this->add_preview_style( 'coupon_message_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'font-size', 'px' );
		$this->add_preview_style( 'coupon_code_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'color' );
		$this->add_preview_style( 'coupon_code_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'background-color' );
		$this->add_preview_style( 'coupon_code_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-width', 'px' );
		$this->add_preview_style( 'coupon_code_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-style' );
		$this->add_preview_style( 'coupon_code_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-color' );
		/*google map*/
		if ( $this->get_params( 'google_map_width' ) ) {
			$this->add_preview_style( 'google_map_width', '#woocommerce-thank-you-page-google-map', 'width', 'px' );

		} else {
			?>
            <style type="text/css" id="<?php echo esc_attr( $this->set( 'preview-google-map-width' ) ) ?>">
                #woocommerce-thank-you-page-google-map {
                    width: 100%;
                }
            </style>
			<?php
		}
		$this->add_preview_style( 'google_map_height', '#woocommerce-thank-you-page-google-map', 'height', 'px' );
		?>
        <style type="text/css" id="<?php echo esc_attr( $this->set( 'coupon-scissors-color-css' ) ) ?>">
            .woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-wrap:before {
                color: <?php echo esc_attr($this->set( 'coupon_scissors_color' )) ?>;
            }
        </style>
        <style type="text/css"
               id="<?php echo esc_attr( $this->set( 'preview-custom-css' ) ) ?>"><?php echo wp_kses_post( $this->get_params( 'custom_css' ) ) ?></style>
		<?php
	}

	public function customize_controls_enqueue_scripts() {
        wp_enqueue_script('woocommerce-thank-you-page-customize',VI_WOO_THANK_YOU_PAGE_JS.'customize-setting.js',array('jquery'),VI_WOO_THANK_YOU_PAGE_VERSION);
		wp_enqueue_style( 'woocommerce-thank-you-page-social-icons', VI_WOO_THANK_YOU_PAGE_CSS . 'social_icons.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
		wp_enqueue_style( 'woocommerce-thank-you-page-available-components-icons', VI_WOO_THANK_YOU_PAGE_CSS . 'available-components-icons.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
		wp_enqueue_style( 'woocommerce-thank-you-page-customize-preview-style', VI_WOO_THANK_YOU_PAGE_CSS . 'customize-preview.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
	}


	public function customize_preview_init() {
		if ( isset( $_REQUEST['key'] ) ) {
			$this->key      = wc_clean( $_REQUEST['key'] );
			$this->order_id = wc_get_order_id_by_order_key( $this->key );
		}
		wp_enqueue_script( 'woocommerce-thank-you-page-customize-preview-js', VI_WOO_THANK_YOU_PAGE_JS . 'customize-preview.js', array(
			'jquery',
			'customize-preview',
			'select2',
		), VI_WOO_THANK_YOU_PAGE_VERSION, true );
		$order              = wc_get_order( $this->order_id );
		$google_map_address = $this->get_params( 'google_map_address' );
		if ( $order ) {
			$this->shortcodes['order_number']   = $this->order_id;
			$this->shortcodes['order_status']   = $order->get_status();
			$this->shortcodes['order_date']     = $order->get_date_created() ? $order->get_date_created()->date_i18n() : '';
			$this->shortcodes['order_total']    = $order->get_formatted_order_total();
			$this->shortcodes['order_subtotal'] = $order->get_subtotal_to_display();
			$this->shortcodes['items_count']    = $order->get_item_count();
			$this->shortcodes['payment_method'] = $order->get_payment_method_title();

			$this->shortcodes['shipping_method']            = $order->get_shipping_method();
			$this->shortcodes['formatted_shipping_address'] = $order->get_formatted_shipping_address();

			$this->shortcodes['formatted_billing_address'] = $order->get_formatted_billing_address();
			$this->shortcodes['billing_country']           = $order->get_billing_country();
			$this->shortcodes['billing_city']              = $order->get_billing_city();

			$this->shortcodes['billing_first_name']          = ucwords( $order->get_billing_first_name() );
			$this->shortcodes['billing_last_name']           = ucwords( $order->get_billing_last_name() );
			$this->shortcodes['formatted_billing_full_name'] = ucwords( $order->get_formatted_billing_full_name() );
			$this->shortcodes['billing_email']               = $order->get_billing_email();

			$this->shortcodes['shop_title']       = get_bloginfo();
			$this->shortcodes['home_url']         = home_url();
			$this->shortcodes['shop_url']         = get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '';
			$billing_address                      = WC()->countries->get_formatted_address( array(
				'address_1' => $order->get_billing_address_1(),
				'address_2' => $order->get_billing_address_2(),
				'city'      => $order->get_billing_city(),
				'state'     => $order->get_billing_state(),
				'country'   => $order->get_billing_country(),
			), ', ' );
			$this->shortcodes['billing_address']  = ucwords( $billing_address );
			$shipping_address                     = WC()->countries->get_formatted_address( array(
				'address_1' => $order->get_shipping_address_1(),
				'address_2' => $order->get_shipping_address_2(),
				'city'      => $order->get_shipping_city(),
				'state'     => $order->get_shipping_state(),
				'country'   => $order->get_shipping_country(),
			), ', ' );
			$this->shortcodes['shipping_address'] = ucwords( $shipping_address );

			$country                           = new WC_Countries();
			$store_address                     = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
			$store_address                     = WC()->countries->get_formatted_address( array(
				'address_1' => $store_address,
				'city'      => $country->get_base_city(),
				'state'     => $country->get_base_state(),
				'country'   => $country->get_base_country(),
			), ', ' );
			$this->shortcodes['store_address'] = ucwords( $store_address );
			$google_map_address                = str_replace( array(
				'{store_address}',
				'{shipping_address}',
				'{billing_address}'
			), array(
				$this->shortcodes['store_address'],
				$this->shortcodes['shipping_address'],
				$this->shortcodes['billing_address']
			), $google_map_address );
		}
		wp_localize_script( 'woocommerce-thank-you-page-customize-preview-js', 'woo_thank_you_page_params', array(
			'url'               => admin_url( 'admin-ajax.php' ),
			'google_map_label'  => str_replace( array(
				'{address}',
				'{store_address}',
				'{shipping_address}',
				'{billing_address}'
			), array(
				$google_map_address,
				$this->shortcodes['store_address'],
				$this->shortcodes['shipping_address'],
				$this->shortcodes['billing_address']
			), $this->get_params( 'google_map_label' ) ),
			'google_map_api'    => $this->get_params( 'google_map_api' ),
			'google_map_marker' => VI_WOO_THANK_YOU_PAGE_MARKERS . $this->get_params( 'google_map_marker' ) . '.png',
			'shortcodes'        => $this->shortcodes,
			'markers_url'       => VI_WOO_THANK_YOU_PAGE_MARKERS,
		) );
	}

	public function customize_controls_print_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}

		?>
        <script type="text/javascript">
            if (typeof wp.customize !== 'undefined') {
                wp.customize.bind('ready', function () {
                    let submenu = [
                        'thank_you_message',
                        'order_confirmation',
                        'order_details',
                        'customer_information',
                        'coupon',
                        'social_icons',
                        'google_map',
                        'order_again',
                    ];
                    jQuery('.customize-section-back').on('click', function () {
                        let id = jQuery(this).parent().parent().parent().prop('id').replace('sub-accordion-section-woo_thank_you_page_design_', '');
                        if (submenu.indexOf(id) > -1) {
                            wp.customize.section('woo_thank_you_page_design_general').expanded(true);
                        }
                    });
                    jQuery('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
                        wp.customize.previewer.send('wtyp_shortcut_to_available_shortcodes', 'show');
                    });
                    wp.customize.previewer.bind('wtyp_open_latest_added_item', function (message) {
                        jQuery('.woocommerce-thank-you-page-latest-item').find('.woocommerce-thank-you-page-edit').click();
                        jQuery('.woocommerce-thank-you-page-item').removeClass('woocommerce-thank-you-page-latest-item');
                    });
                    wp.customize.previewer.bind('wtyp_update_text_editor', function (message) {
                        wp.customize('woo_thank_you_page_params[text_editor]').set(message);
                    });

                    wp.customize.previewer.bind('wtyp_handle_overlay_processing', function (message) {
                        if (message === 'show') {
                            jQuery('.woocommerce-thank-you-page-control-processing').show();
                        } else {
                            jQuery('.woocommerce-thank-you-page-control-processing').hide();
                        }
                    });
                    wp.customize.previewer.bind('wtyp_update_url', function (message) {
                        location.href = message;
                    });
                    wp.customize.previewer.bind('wtyp_shortcut_edit', function (message) {
                        wp.customize.section('woo_thank_you_page_design_' + message).expanded(true);
                    });
                    for (let i in submenu) {
                        focus_on_editing_item_send(submenu[i]);
                    }

                    function focus_on_editing_item_send(name) {
                        wp.customize.section('woo_thank_you_page_design_' + name, function (section) {
                            section.expanded.bind(function (isExpanded) {
                                if (isExpanded) {
                                    wp.customize.previewer.send('wtyp_focus_on_editing_item', 'woocommerce-thank-you-page-' + name + '__container');
                                }
                            })
                        });
                    }

                    wp.customize.section('woo_thank_you_page_design_general', function (section) {
                        section.expanded.bind(function (isExpanded) {
                            if (isExpanded) {
                                jQuery.ajax({
                                    type: 'POST',
                                    dataType: 'json',
                                    url: '<?php echo admin_url( 'admin-ajax.php' )?>',
                                    data: {
                                        action: 'woo_thank_you_page_select_order',
                                        order_id: wp.customize('woo_thank_you_page_params[select_order]').get(),
                                    },
                                    success: function (response) {
                                        if (response && response.hasOwnProperty('url') && response.url) {
                                            wp.customize.previewer.send('wtyp_update_url', response.url);
                                        }
                                    },
                                    error: function (err) {
                                        console.log(err);
                                    }
                                })
                            } else {
                            }
                        })
                    });
                    /*edit item*/
                    jQuery('body').on('click', '.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-edit', function (event) {
                        event.stopPropagation();
                        let parent = jQuery(this).parent();
                        let item = parent.data()['block_item'];
                        if (item == 'text_editor') {
                            let position = jQuery('.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-' + item).index(parent);
                            wp.customize.previewer.send('wtyp_shortcut_edit_' + item + '_from_section', position);
                        } else {
                            wp.customize.previewer.send('wtyp_shortcut_edit_item_from_section', 'woocommerce-thank-you-page-edit-item-shortcut[data-edit_section="' + item + '"]');
                        }
                    });
                    jQuery('.wtyp-button-update-changes-google-map').on('click', function () {
                        let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
                        wp.customize.previewer.send('wtyp_update_google_map_address', address);
                    });
                });
            }


            jQuery(document).ready(function ($) {
            })
        </script>
		<?php
	}

	public function design_option_customizer( $wp_customize ) {
		$this->add_section_design_general( $wp_customize );
		$this->add_section_design_thank_you_message( $wp_customize );
		$this->add_section_design_coupon( $wp_customize );
		$this->add_section_design_order_confirmation( $wp_customize );
		$this->add_section_design_order_details( $wp_customize );
		$this->add_section_design_customer_information( $wp_customize );
		$this->add_section_design_social_icons( $wp_customize );
		$this->add_section_design_google_map( $wp_customize );
	}

	protected function add_section_design_general( $wp_customize ) {
		$wp_customize->add_section( 'woo_thank_you_page_design_general', array(
			'priority'       => 200,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'WooCommerce Thank You Page', 'woo-thank-you-page-customizer' ),
		) );
		$default_order_id = '';

		$args   = array(
			'status' => array_keys(wc_get_order_statuses()) ,
			'limit'  => 20,
			'order'  => 'DESC',
			'return' => 'ids',
		);
		$orders = wc_get_orders( $args );
		$select_orders    = array();
		if ( $this->get_params( 'select_order' ) ) {
			$select_orders[ $this->get_params( 'select_order' ) ] = sprintf( esc_html__( 'Order #%s', 'woo-thank-you-page-customizer' ), $this->get_params( 'select_order' ) );
		}
		if (! empty( $orders )  ) {
			foreach ( $orders as $order ) {
				$default_order_id         = $default_order_id ?: $order;
				$select_orders[ $order ] = sprintf( esc_html__( 'Order #%s', 'woo-thank-you-page-customizer' ), $order );
			}
		} else {
			$args      = array(
				'post_type'      => 'product',
				'post_status'    => 'public',
				'posts_per_page' => 1,
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$product_id = get_the_ID();
					$product    = wc_get_product( $product_id );
					$user       = wp_get_current_user();
					$order      = new WC_Order();
					$address    = array(
						'first_name' => $user->user_firstname,
						'last_name'  => $user->user_lastname,
						'company'    => '',
						'email'      => $user->user_email,
						'phone'      => '',
						'address_1'  => 'Thai Nguyen city',
						'address_2'  => '',
						'city'       => 'Thai Nguyen',
						'state'      => '',
						'postcode'   => '25000',
						'country'    => 'VN'
					);
					$order->add_product( $product, '2' );
					$order->set_address( $address, 'billing' );
					$order->set_address( $address, 'shipping' );
					$order->calculate_totals();
					$order->set_total( 0 );
					$order->update_status( 'completed' );
					$order->save();
					$order_id                   = $order->get_id();
					$default_order_id           = $order_id;
					$select_orders[ $order_id ] = sprintf( esc_html__( 'Order #%s', 'woo-thank-you-page-customizer' ), $order_id );
					break;
				}
			}
		}
		if ( ! $this->settings->get_params( 'select_order' ) && $default_order_id ) {
			$data                 = $this->settings->get_params();
			$data['select_order'] = $default_order_id;
			update_option( 'woo_thank_you_page_params', $data );
		}
		$wp_customize->add_setting( 'woo_thank_you_page_params[select_order]', array(
			'default'           => $this->settings->get_default( 'select_order' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[select_order]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'woo_thank_you_page_design_general',
			'label'    => esc_html__( 'Select order to preview', 'woo-thank-you-page-customizer' ),
			'choices'  => $select_orders,
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[blocks]', array(
			'default'              => $this->settings->get_default( 'blocks' ),
			'type'                 => 'option',
			'capability'           => 'manage_options',
			'sanitize_callback'    => 'wtyp_sanitize_block',
			'sanitize_js_callback' => 'wtyp_sanitize_block',
			'transport'            => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Blocks_Control(
				$wp_customize,
				'woo_thank_you_page_params[blocks]',
				array(
					'label'   => 'Layout',
					'section' => 'woo_thank_you_page_design_general',
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[text_editor]', array(
			'default'              => $this->settings->get_default( 'text_editor' ),
			'type'                 => 'option',
			'capability'           => 'manage_options',
			'sanitize_callback'    => 'wtyp_sanitize_block',
			'sanitize_js_callback' => 'wtyp_sanitize_block',
			'transport'            => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Text_Editor_Control(
				$wp_customize,
				'woo_thank_you_page_params[text_editor]',
				array(
					'section' => 'woo_thank_you_page_design_general',
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[custom_css]', array(
			'default'           => $this->settings->get_default( 'custom_css' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[custom_css]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'woo_thank_you_page_design_general',
			'label'    => esc_html__( 'Custom CSS', 'woo-thank-you-page-customizer' )
		) );
	}


	protected function add_section_design_order_confirmation( $wp_customize ) {

		$wp_customize->add_section( 'woo_thank_you_page_design_order_confirmation', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Order Confirmation', 'woo-thank-you-page-customizer' ),

		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_bg]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_bg' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_bg]',
				array(
					'label'    => esc_html__( 'Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_bg]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_padding]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_padding' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_padding]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Padding(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_border_radius]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_border_radius' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_border_radius]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Rounded Corner(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_border_width]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_border_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_border_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Border Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_border_style]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_border_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_border_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
			'label'   => esc_html__( 'Border Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_border_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_border_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_border_color]',
				array(
					'label'    => esc_html__( 'Border Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_border_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_vertical_width]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_vertical_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_vertical_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Vertical Separator Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_vertical_style]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_vertical_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_vertical_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
			'label'   => esc_html__( 'Vertical Separator Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_vertical_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_vertical_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_vertical_color]',
				array(
					'label'    => esc_html__( 'Vertical Separator Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_vertical_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_horizontal_width]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_horizontal_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_horizontal_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Horizontal Separator Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_horizontal_style]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_horizontal_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_horizontal_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
			'label'   => esc_html__( 'Horizontal Separator Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_horizontal_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_horizontal_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_horizontal_color]',
				array(
					'label'    => esc_html__( 'Horizontal Separator Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_horizontal_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_header]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_header' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_header]', array(
			'type'        => 'textarea',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Header Text', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( '<span class="' . $this->set( 'available-shortcodes-shortcut' ) . '">Shortcodes list</span>' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_header_font_size]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_header_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_header_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Header Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_header_text_align]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_header_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_header_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
			'label'   => esc_html__( 'Header Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_header_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_header_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_header_color]',
				array(
					'label'    => esc_html__( 'Header Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_header_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_header_bg_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_header_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_header_bg_color]',
				array(
					'label'    => esc_html__( 'Header Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_header_bg_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_title_font_size]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_title_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_title_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Title Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_title_text_align]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_title_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_title_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
			'label'   => esc_html__( 'Title Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_title_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_title_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_title_color]',
				array(
					'label'    => esc_html__( 'Title Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_title_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_title_bg_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_title_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_title_bg_color]',
				array(
					'label'    => esc_html__( 'Title Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_title_bg_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_value_font_size]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_value_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_value_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_confirmation',
			'label'       => esc_html__( 'Value Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_value_text_align]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_value_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_confirmation_value_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_confirmation', // Add a default or your own section
			'label'   => esc_html__( 'Value Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_value_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_value_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_value_color]',
				array(
					'label'    => esc_html__( 'Value Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_value_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_confirmation_value_bg_color]', array(
			'default'           => $this->settings->get_default( 'order_confirmation_value_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_confirmation_value_bg_color]',
				array(
					'label'    => esc_html__( 'Value Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_confirmation',
					'settings' => 'woo_thank_you_page_params[order_confirmation_value_bg_color]',
				) )
		);

	}

	protected function add_section_design_thank_you_message( $wp_customize ) {

		$wp_customize->add_section( 'woo_thank_you_page_design_thank_you_message', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Thank You Message', 'woo-thank-you-page-customizer' ),

		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[thank_you_message_color]', array(
			'default'           => $this->settings->get_default( 'thank_you_message_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[thank_you_message_color]',
				array(
					'label'    => esc_html__( 'Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_thank_you_message',
					'settings' => 'woo_thank_you_page_params[thank_you_message_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[thank_you_message_text_align]', array(
			'default'           => $this->settings->get_default( 'thank_you_message_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[thank_you_message_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_thank_you_message', // Add a default or your own section
			'label'   => esc_html__( 'Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[thank_you_message_padding]', array(
			'default'           => $this->settings->get_default( 'thank_you_message_padding' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[thank_you_message_padding]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_thank_you_message',
			'label'       => esc_html__( 'Padding(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[thank_you_message_header]', array(
			'default'           => $this->settings->get_default( 'thank_you_message_header' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[thank_you_message_header]', array(
			'type'        => 'textarea',
			'section'     => 'woo_thank_you_page_design_thank_you_message',
			'label'       => esc_html__( 'Header Text', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( '<span class="' . $this->set( 'available-shortcodes-shortcut' ) . '">Shortcodes list</span>' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[thank_you_message_header_font_size]', array(
			'default'           => $this->settings->get_default( 'thank_you_message_header_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[thank_you_message_header_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_thank_you_message',
			'label'       => esc_html__( 'Header Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[thank_you_message_message]', array(
			'default'           => $this->settings->get_default( 'thank_you_message_message' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[thank_you_message_message]', array(
			'type'        => 'textarea',
			'section'     => 'woo_thank_you_page_design_thank_you_message',
			'label'       => esc_html__( 'Message Text', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( '<span class="' . $this->set( 'available-shortcodes-shortcut' ) . '">Shortcodes list</span>' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[thank_you_message_message_font_size]', array(
			'default'           => $this->settings->get_default( 'thank_you_message_message_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[thank_you_message_message_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_thank_you_message',
			'label'       => esc_html__( 'Message Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );

	}

	protected function add_section_design_coupon( $wp_customize ) {

		$wp_customize->add_section( 'woo_thank_you_page_design_coupon', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Coupon', 'woo-thank-you-page-customizer' ),

		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_text_align]', array(
			'default'           => $this->settings->get_default( 'coupon_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[coupon_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_coupon', // Add a default or your own section
			'label'   => esc_html__( 'Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_padding]', array(
			'default'           => $this->settings->get_default( 'coupon_padding' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[coupon_padding]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_coupon',
			'label'       => esc_html__( 'Padding(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_message]', array(
			'default'           => $this->settings->get_default( 'coupon_message' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[coupon_message]', array(
			'type'        => 'textarea',
			'section'     => 'woo_thank_you_page_design_coupon',
			'label'       => esc_html__( 'Message Text', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( 'Available shortcode: {coupon_amount}, {coupon_date_expires}, {last_valid_date}, {coupon_code}<p><span class="' . $this->set( 'available-shortcodes-shortcut' ) . '">Shortcodes list</span></p>'),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_message_color]', array(
			'default'           => $this->settings->get_default( 'coupon_message_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[coupon_message_color]',
				array(
					'label'    => esc_html__( 'Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_coupon',
					'settings' => 'woo_thank_you_page_params[coupon_message_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_message_font_size]', array(
			'default'           => $this->settings->get_default( 'coupon_message_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[coupon_message_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_coupon',
			'label'       => esc_html__( 'Message Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_code_border_width]', array(
			'default'           => $this->settings->get_default( 'coupon_code_border_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[coupon_code_border_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_coupon',
			'label'       => esc_html__( 'Border Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_code_border_style]', array(
			'default'           => $this->settings->get_default( 'coupon_code_border_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[coupon_code_border_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_coupon', // Add a default or your own section
			'label'   => esc_html__( 'Border Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_code_border_color]', array(
			'default'           => $this->settings->get_default( 'coupon_code_border_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[coupon_code_border_color]',
				array(
					'label'    => esc_html__( 'Border Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_coupon',
					'settings' => 'woo_thank_you_page_params[coupon_code_border_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_code_color]', array(
			'default'           => $this->settings->get_default( 'coupon_code_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[coupon_code_color]',
				array(
					'label'    => esc_html__( 'Coupon Code Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_coupon',
					'settings' => 'woo_thank_you_page_params[coupon_code_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_code_bg_color]', array(
			'default'           => $this->settings->get_default( 'coupon_code_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[coupon_code_bg_color]',
				array(
					'label'    => esc_html__( 'Coupon Code Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_coupon',
					'settings' => 'woo_thank_you_page_params[coupon_code_bg_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_scissors_color]', array(
			'default'           => $this->settings->get_default( 'coupon_scissors_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[coupon_scissors_color]',
				array(
					'label'    => esc_html__( 'Scissors color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_coupon',
					'settings' => 'woo_thank_you_page_params[coupon_scissors_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[coupon_email_enable]', array(
			'default'           => $this->settings->get_default( 'coupon_email_enable' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[coupon_email_enable]', array(
			'type'        => 'checkbox',
			'section'     => 'woo_thank_you_page_design_coupon',
			'label'       => esc_html__( 'Show button to handle coupon code', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'If enabled, when hovering the coupon field, 2 buttons will show to copy coupon code or send code to billing email.', 'woo-thank-you-page-customizer' ),
		) );

	}

	protected function add_section_design_order_details( $wp_customize ) {

		$wp_customize->add_section( 'woo_thank_you_page_design_order_details', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Order Details', 'woo-thank-you-page-customizer' ),

		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_color]', array(
			'default'           => $this->settings->get_default( 'order_details_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_details_color]',
				array(
					'label'    => esc_html__( 'Text Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_details',
					'settings' => 'woo_thank_you_page_params[order_details_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_bg]', array(
			'default'           => $this->settings->get_default( 'order_details_bg' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_details_bg]',
				array(
					'label'    => esc_html__( 'Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_details',
					'settings' => 'woo_thank_you_page_params[order_details_bg]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_padding]', array(
			'default'           => $this->settings->get_default( 'order_details_padding' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_padding]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_details',
			'label'       => esc_html__( 'Padding(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_border_radius]', array(
			'default'           => $this->settings->get_default( 'order_details_border_radius' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_border_radius]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_details',
			'label'       => esc_html__( 'Rounded Corner(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_border_width]', array(
			'default'           => $this->settings->get_default( 'order_details_border_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_border_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_details',
			'label'       => esc_html__( 'Border Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_border_style]', array(
			'default'           => $this->settings->get_default( 'order_details_border_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_border_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
			'label'   => esc_html__( 'Border Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_border_color]', array(
			'default'           => $this->settings->get_default( 'order_details_border_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_details_border_color]',
				array(
					'label'    => esc_html__( 'Border Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_details',
					'settings' => 'woo_thank_you_page_params[order_details_border_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_horizontal_width]', array(
			'default'           => $this->settings->get_default( 'order_details_horizontal_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_horizontal_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_details',
			'label'       => esc_html__( 'Horizontal Separator Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_horizontal_style]', array(
			'default'           => $this->settings->get_default( 'order_details_horizontal_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_horizontal_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
			'label'   => esc_html__( 'Horizontal Separator Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_horizontal_color]', array(
			'default'           => $this->settings->get_default( 'order_details_horizontal_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_details_horizontal_color]',
				array(
					'label'    => esc_html__( 'Horizontal Separator Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_details',
					'settings' => 'woo_thank_you_page_params[order_details_horizontal_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_header]', array(
			'default'           => $this->settings->get_default( 'order_details_header' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_header]', array(
			'type'        => 'textarea',
			'section'     => 'woo_thank_you_page_design_order_details',
			'label'       => esc_html__( 'Header Text', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( '<span class="' . $this->set( 'available-shortcodes-shortcut' ) . '">Shortcodes list</span>' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_header_font_size]', array(
			'default'           => $this->settings->get_default( 'order_details_header_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_header_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_details',
			'label'       => esc_html__( 'Header Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_header_text_align]', array(
			'default'           => $this->settings->get_default( 'order_details_header_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_header_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
			'label'   => esc_html__( 'Header Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_header_color]', array(
			'default'           => $this->settings->get_default( 'order_details_header_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_details_header_color]',
				array(
					'label'    => esc_html__( 'Header Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_details',
					'settings' => 'woo_thank_you_page_params[order_details_header_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_header_bg_color]', array(
			'default'           => $this->settings->get_default( 'order_details_header_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[order_details_header_bg_color]',
				array(
					'label'    => esc_html__( 'Header Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_order_details',
					'settings' => 'woo_thank_you_page_params[order_details_header_bg_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_product_image]', array(
			'default'           => $this->settings->get_default( 'order_details_product_image' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_product_image]', array(
			'type'    => 'checkbox',
			'section' => 'woo_thank_you_page_design_order_details', // Add a default or your own section
			'label'   => esc_html__( 'Display Product Image', 'woo-thank-you-page-customizer' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[order_details_product_image_width]', array(
			'default'           => $this->settings->get_default( 'order_details_product_image_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[order_details_product_image_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_order_details',
			'label'       => esc_html__( 'Product Image width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
	}

	protected function add_section_design_customer_information( $wp_customize ) {

		$wp_customize->add_section( 'woo_thank_you_page_design_customer_information', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Customer Information', 'woo-thank-you-page-customizer' ),

		) );
//		$wp_customize->selective_refresh->add_partial( 'woo_thank_you_page_params[customer_information_color]', array(
//			'selector'            => '.woocommerce-thank-you-page-customer_information__container',
//			'container_inclusive' => true,
//			'fallback_refresh'    => false, // Pre
//		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_color]', array(
			'default'           => $this->settings->get_default( 'customer_information_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_color]',
				array(
					'label'    => esc_html__( 'Text Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_bg]', array(
			'default'           => $this->settings->get_default( 'customer_information_bg' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_bg]',
				array(
					'label'    => esc_html__( 'Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_bg]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_padding]', array(
			'default'           => $this->settings->get_default( 'customer_information_padding' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_padding]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_customer_information',
			'label'       => esc_html__( 'Padding(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_border_radius]', array(
			'default'           => $this->settings->get_default( 'customer_information_border_radius' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_border_radius]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_customer_information',
			'label'       => esc_html__( 'Rounded Corner(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_border_width]', array(
			'default'           => $this->settings->get_default( 'customer_information_border_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_border_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_customer_information',
			'label'       => esc_html__( 'Border Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_border_style]', array(
			'default'           => $this->settings->get_default( 'customer_information_border_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_border_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
			'label'   => esc_html__( 'Border Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_border_color]', array(
			'default'           => $this->settings->get_default( 'customer_information_border_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_border_color]',
				array(
					'label'    => esc_html__( 'Border Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_border_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_vertical_width]', array(
			'default'           => $this->settings->get_default( 'customer_information_vertical_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_vertical_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_customer_information',
			'label'       => esc_html__( 'Vertical Separator Width(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_vertical_style]', array(
			'default'           => $this->settings->get_default( 'customer_information_vertical_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_vertical_style]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
			'label'   => esc_html__( 'Vertical Separator Style', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'solid'  => esc_html__( 'Solid', 'woo-thank-you-page-customizer' ),
				'dotted' => esc_html__( 'Dotted', 'woo-thank-you-page-customizer' ),
				'dashed' => esc_html__( 'Dashed', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_vertical_color]', array(
			'default'           => $this->settings->get_default( 'customer_information_vertical_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_vertical_color]',
				array(
					'label'    => esc_html__( 'Vertical Separator Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_vertical_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_header]', array(
			'default'           => $this->settings->get_default( 'customer_information_header' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_header]', array(
			'type'        => 'textarea',
			'section'     => 'woo_thank_you_page_design_customer_information',
			'label'       => esc_html__( 'Header Text', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( '<span class="' . $this->set( 'available-shortcodes-shortcut' ) . '">Shortcodes list</span>' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_header_font_size]', array(
			'default'           => $this->settings->get_default( 'customer_information_header_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_header_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_customer_information',
			'label'       => esc_html__( 'Header Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_header_text_align]', array(
			'default'           => $this->settings->get_default( 'customer_information_header_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_header_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
			'label'   => esc_html__( 'Header Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_header_color]', array(
			'default'           => $this->settings->get_default( 'customer_information_header_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_header_color]',
				array(
					'label'    => esc_html__( 'Header Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_header_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_header_bg_color]', array(
			'default'           => $this->settings->get_default( 'customer_information_header_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_header_bg_color]',
				array(
					'label'    => esc_html__( 'Header Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_header_bg_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_address_font_size]', array(
			'default'           => $this->settings->get_default( 'customer_information_address_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_address_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_customer_information',
			'label'       => esc_html__( 'Address Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_address_text_align]', array(
			'default'           => $this->settings->get_default( 'customer_information_address_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[customer_information_address_text_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_customer_information', // Add a default or your own section
			'label'   => esc_html__( 'Address Text Align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_address_color]', array(
			'default'           => $this->settings->get_default( 'customer_information_address_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_address_color]',
				array(
					'label'    => esc_html__( 'Address Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_address_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[customer_information_address_bg_color]', array(
			'default'           => $this->settings->get_default( 'customer_information_address_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[customer_information_address_bg_color]',
				array(
					'label'    => esc_html__( 'Address Background Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_customer_information',
					'settings' => 'woo_thank_you_page_params[customer_information_address_bg_color]',
				) )
		);
	}

	private function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );

		} else {
			return esc_attr__( $this->prefix . $name );

		}
	}

	private function get_params( $name = '' ) {
		return $this->settings->get_params( $name );
	}

	private function add_preview_style( $name, $element, $style, $suffix = '', $echo = true ) {
		ob_start();
		?>
        <style type="text/css"
               id="<?php echo esc_attr( $this->set( 'preview-' ) . str_replace( '_', '-', $name ) ) ?>">
            <?php
            $css_param = $this->get_params( $name );
            $css_value = $css_param . $suffix;
            echo esc_attr($element) . '{' . ( ( $css_param === '' ) ? '' : ( $style . ':' . esc_attr($css_value) ) ) . '}' ?></style>
		<?php
		$return = ob_get_clean();
		if ( $echo ) {
			echo ($return);
		}

		return $return;
	}

	protected function add_section_design_social_icons( $wp_customize ) {

		$wp_customize->add_section( 'woo_thank_you_page_design_social_icons', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Social Media', 'woo-thank-you-page-customizer' ),

		) );
		$icons = array(
			"wtyp_social_icons-facebook-circular-logo",
			"wtyp_social_icons-facebook-logo-1",
			"wtyp_social_icons-facebook-square-social-logo",
			"wtyp_social_icons-facebook-app-logo",
			"wtyp_social_icons-facebook-logo",
			"wtyp_social_icons-internet",
			"wtyp_social_icons-twitter-logo-button",
			"wtyp_social_icons-twitter-logo-silhouette",
			"wtyp_social_icons-twitter",
			"wtyp_social_icons-twitter-1",
			"wtyp_social_icons-twitter-logo-on-black-background",
			"wtyp_social_icons-twitter-sign",
			"wtyp_social_icons-pinterest",
			"wtyp_social_icons-pinterest-logo",
			"wtyp_social_icons-pinterest-1",
			"wtyp_social_icons-pinterest-2",
			"wtyp_social_icons-pinterest-social-logo",
			"wtyp_social_icons-pinterest-logo-1",
			"wtyp_social_icons-pinterest-sign",
			"wtyp_social_icons-instagram-logo",
			"wtyp_social_icons-instagram-social-network-logo-of-photo-camera-1",
			"wtyp_social_icons-instagram-1",
			"wtyp_social_icons-social-media",
			"wtyp_social_icons-instagram",
			"wtyp_social_icons-instagram-social-network-logo-of-photo-camera",
			"wtyp_social_icons-instagram-logo-1",
			"wtyp_social_icons-instagram-2",
			"wtyp_social_icons-dribbble-logo",
			"wtyp_social_icons-dribble-logo-button",
			"wtyp_social_icons-dribbble",
			"wtyp_social_icons-dribbble-logo-1",
			"wtyp_social_icons-dribbble-2",
			"wtyp_social_icons-dribbble-1",
			"wtyp_social_icons-tumblr-logo-1",
			"wtyp_social_icons-tumblr-logo-button",
			"wtyp_social_icons-tumblr",
			"wtyp_social_icons-tumblr-logo-2",
			"wtyp_social_icons-tumblr-logo",
			"wtyp_social_icons-tumblr-1",
			"wtyp_social_icons-google-plus-logo",
			"wtyp_social_icons-google-plus-symbol",
			"wtyp_social_icons-google-plus-social-logotype",
			"wtyp_social_icons-google-plus",
			"wtyp_social_icons-google-plus-social-logotype-1",
			"wtyp_social_icons-google-plus-logo-on-black-background",
			"wtyp_social_icons-social-google-plus-square-button",
			"wtyp_social_icons-vk-social-network-logo",
			"wtyp_social_icons-vk-social-logotype",
			"wtyp_social_icons-vk",
			"wtyp_social_icons-vk-social-logotype-1",
			"wtyp_social_icons-vk-reproductor",
			"wtyp_social_icons-vkontakte-logo",
			"wtyp_social_icons-linkedin-logo",
			"wtyp_social_icons-linkedin-button",
			"wtyp_social_icons-linkedin-1",
			"wtyp_social_icons-linkedin-logo-1",
			"wtyp_social_icons-linkedin-sign",
			"wtyp_social_icons-linkedin",
			"wtyp_social_icons-youtube-logo-2",
			"wtyp_social_icons-youtube-logotype-1",
			"wtyp_social_icons-youtube",
			"wtyp_social_icons-youtube-logotype",
			"wtyp_social_icons-youtube-logo",
			"wtyp_social_icons-youtube-logo-1"
		);


		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_header]', array(
			'default'           => $this->settings->get_default( 'social_icons_header' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_header]', array(
			'type'        => 'textarea',
			'priority'    => 10,
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Text Follow Social Network', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( '<span class="' . $this->set( 'available-shortcodes-shortcut' ) . '">Shortcodes list</span>' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_header_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_header_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_header_color]',
				array(
					'label'    => esc_html__( 'Header Color', 'woo-thank-you-page-customizer' ),
					'section'  => 'woo_thank_you_page_design_social_icons',
					'settings' => 'woo_thank_you_page_params[social_icons_header_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_header_font_size]', array(
			'default'           => $this->settings->get_default( 'social_icons_header_font_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_header_font_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Header Font Size(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_size]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Icons size (px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_align]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_align' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_align]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_social_icons',
			'label'   => esc_html__( 'Icons align', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'left'    => esc_html__( 'Left', 'woo-thank-you-page-customizer' ),
				'center'  => esc_html__( 'Center', 'woo-thank-you-page-customizer' ),
				'right'   => esc_html__( 'Right', 'woo-thank-you-page-customizer' ),
				'justify' => esc_html__( 'Justify', 'woo-thank-you-page-customizer' ),
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_space]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_space' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_space]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Spaces Between Icons (px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_target]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_target' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_target]', array(
			'type'    => 'select',
			'section' => 'woo_thank_you_page_design_social_icons',
			'label'   => esc_html__( 'When click on social icons', 'woo-thank-you-page-customizer' ),
			'choices' => array(
				'_blank' => esc_html__( 'Open link in new tab', 'woo-thank-you-page-customizer' ),
				'_self'  => esc_html__( 'Open link in current tab', 'woo-thank-you-page-customizer' ),
			),
		) );
		$facebook = $twitter = $pinterest = $instagram = $dribbble = $tumblr = $google = $vkontakte = $linkedin = $youtube = array();
		for ( $i = 0; $i < sizeof( $icons ); $i ++ ) {
			if ( $i < 6 ) {
				$facebook[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 12 ) {
				$twitter[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 19 ) {
				$pinterest[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 27 ) {
				$instagram[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 33 ) {
				$dribbble[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 39 ) {
				$tumblr[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 46 ) {
				$google[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 52 ) {
				$vkontakte[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 58 ) {
				$linkedin[ $icons[ $i ] ] = $icons[ $i ];
			} else {
				$youtube[ $icons[ $i ] ] = $icons[ $i ];
			}
		}
		/*facebook*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_facebook_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_facebook_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_facebook_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Facebook URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Facebook URL Eg: https://www.facebook.com/villatheme', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_facebook_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_facebook_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_facebook_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $facebook
				)
			)
		);


		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_facebook_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_facebook_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_facebook_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);


		/*twitter*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_twitter_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_twitter_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_twitter_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Twitter URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Twitter URL Eg: https://www.twitter.com/villatheme', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_twitter_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_twitter_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_twitter_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $twitter
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_twitter_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_twitter_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_twitter_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*pinterest*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_pinterest_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_pinterest_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_pinterest_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Pinterest URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Pinterest URL', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_pinterest_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_pinterest_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_pinterest_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $pinterest
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_pinterest_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_pinterest_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_pinterest_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*instagram*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_instagram_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_instagram_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_instagram_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Instagram URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Instagram URL', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_instagram_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_instagram_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_instagram_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $instagram
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_instagram_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_instagram_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_instagram_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*dribbble*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_dribbble_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_dribbble_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_dribbble_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Dribbble URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Dribbble URL', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_dribbble_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_dribbble_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_dribbble_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $dribbble
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_dribbble_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_dribbble_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_dribbble_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*tumblr*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_tumblr_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_tumblr_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_tumblr_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Tumblr URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Tumblr URL', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_tumblr_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_tumblr_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_tumblr_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $tumblr
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_tumblr_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_tumblr_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_tumblr_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*google*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_google_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_google_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_google_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Google Plus ID', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Google Plus URL', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_google_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_google_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_google_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $google
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_google_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_google_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_google_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*vkontakte*/
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_vkontakte_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_vkontakte_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_vkontakte_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'VKontakte URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your VKontakte URL', 'woo-thank-you-page-customizer' ),
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_vkontakte_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_vkontakte_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_vkontakte_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $vkontakte
				)
			)
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_vkontakte_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_vkontakte_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_vkontakte_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*linkedin*/

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_linkedin_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_linkedin_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_linkedin_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $linkedin
				)
			)
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_linkedin_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_linkedin_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_linkedin_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Linkedin URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Linkedin URL', 'woo-thank-you-page-customizer' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_linkedin_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_linkedin_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_linkedin_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);

		/*youtube*/

		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_youtube_select]', array(
			'default'           => $this->settings->get_default( 'social_icons_youtube_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Radio_Icons_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_youtube_select]',
				array(
					'label'   => 'Icons',
					'section' => 'woo_thank_you_page_design_social_icons',
					'choices' => $youtube
				)
			)
		);
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_youtube_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'social_icons_youtube_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[social_icons_youtube_url]', array(
			'type'        => 'url',
			'section'     => 'woo_thank_you_page_design_social_icons',
			'label'       => esc_html__( 'Youtube URL', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Your Youtube URL. Eg: https://www.youtube.com/channel/UCbCfnjbtBZIQfzLvXgNpbKw', 'woo-thank-you-page-customizer' ),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[social_icons_youtube_color]', array(
			'default'           => $this->settings->get_default( 'social_icons_youtube_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_thank_you_page_params[social_icons_youtube_color]',
				array(
					'label'   => esc_html__( 'Icon Color', 'woo-thank-you-page-customizer' ),
					'section' => 'woo_thank_you_page_design_social_icons',
				) )
		);


	}

	protected function add_section_design_google_map( $wp_customize ) {
		$wp_customize->add_section( 'woo_thank_you_page_design_google_map', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Google map', 'woo-thank-you-page-customizer' ),

		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[google_map_width]', array(
			'default'           => $this->settings->get_default( 'google_map_width' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[google_map_width]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_google_map',
			'label'       => esc_html__( 'Width(px)', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'If set 0, width will be 100%.', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_thank_you_page_params[google_map_height]', array(
			'default'           => $this->settings->get_default( 'google_map_height' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[google_map_height]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_google_map',
			'label'       => esc_html__( 'Height(px)', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );


		$wp_customize->add_setting( 'woo_thank_you_page_params[google_map_marker]', array(
			'default'           => $this->settings->get_default( 'google_map_marker' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$google_map_marker_choices = array();
		for ( $i = 1; $i <= 12; $i ++ ) {
			$google_map_marker_choices[ 'if-marker-' . $i ] = array(
				'name'  => esc_html__( 'Marker ' . $i, 'woo-thank-you-page-customizer' ),
				'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'if-marker-' . $i . '.png'
			);
		}
		$google_map_marker_choices['blue']         = array(
			'name'  => esc_html__( 'Blue', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'blue.png'
		);
		$google_map_marker_choices['blue-dot']     = array(
			'name'  => esc_html__( 'Blue dot', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'blue-dot.png'
		);
		$google_map_marker_choices['blue-pushpin'] = array(
			'name'  => esc_html__( 'Blue pushpin', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'blue-pushpin.png'
		);

		$google_map_marker_choices['yellow']         = array(
			'name'  => esc_html__( 'Yellow', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'yellow.png'
		);
		$google_map_marker_choices['yellow-dot']     = array(
			'name'  => esc_html__( 'Yellow dot', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'yellow-dot.png'
		);
		$google_map_marker_choices['yellow-pushpin'] = array(
			'name'  => esc_html__( 'Yellow pushpin', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'yellow-pushpin.png'
		);


		$google_map_marker_choices['green']         = array(
			'name'  => esc_html__( 'Green', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'green.png'
		);
		$google_map_marker_choices['green-dot']     = array(
			'name'  => esc_html__( 'Green dot', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'green-dot.png'
		);
		$google_map_marker_choices['green-pushpin'] = array(
			'name'  => esc_html__( 'Green pushpin', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'green-pushpin.png'
		);


		$google_map_marker_choices['orange']     = array(
			'name'  => esc_html__( 'Orange', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'orange.png'
		);
		$google_map_marker_choices['orange-dot'] = array(
			'name'  => esc_html__( 'Orange dot', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'orange-dot.png'
		);


		$google_map_marker_choices['pink']         = array(
			'name'  => esc_html__( 'Pink', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'pink.png'
		);
		$google_map_marker_choices['pink-dot']     = array(
			'name'  => esc_html__( 'Pink dot', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'pink-dot.png'
		);
		$google_map_marker_choices['pink-pushpin'] = array(
			'name'  => esc_html__( 'Pink pushpin', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'pink-pushpin.png'
		);


		$google_map_marker_choices['purple']         = array(
			'name'  => esc_html__( 'Purple', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'purple.png'
		);
		$google_map_marker_choices['purple-dot']     = array(
			'name'  => esc_html__( 'Purple dot', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'purple-dot.png'
		);
		$google_map_marker_choices['purple-pushpin'] = array(
			'name'  => esc_html__( 'Purple pushpin', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'purple-pushpin.png'
		);


		$google_map_marker_choices['red']         = array(
			'name'  => esc_html__( 'Red', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'red.png'
		);
		$google_map_marker_choices['red-dot']     = array(
			'name'  => esc_html__( 'Red', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'red-dot.png'
		);
		$google_map_marker_choices['red-pushpin'] = array(
			'name'  => esc_html__( 'Red pushpin', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'red-pushpin.png'
		);


		$google_map_marker_choices['default'] = array(

			'name'  => esc_html__( 'Default', 'woo-thank-you-page-customizer' ),
			'image' => VI_WOO_THANK_YOU_PAGE_MARKERS . 'default.png'
		);

		$wp_customize->add_control(
			new WOO_THANK_YOU_PAGE_CUSTOMIZER_Image_Radio_Button_Custom_Control(
				$wp_customize,
				'woo_thank_you_page_params[google_map_marker]',
				array(
					'section' => 'woo_thank_you_page_design_google_map',
					'label'   => esc_html__( 'Marker', 'woo-thank-you-page-customizer' ),
					'choices' => $google_map_marker_choices,
				) )
		);

		$wp_customize->add_setting( 'woo_thank_you_page_params[google_map_address]', array(
			'default'           => $this->settings->get_default( 'google_map_address' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[google_map_address]', array(
			'type'        => 'textarea',
			'priority'    => 10,
			'section'     => 'woo_thank_you_page_design_google_map',
			'label'       => esc_html__( 'Address', 'woo-thank-you-page-customizer' ),
			'description' => wp_kses_post( 'Can be either {billing_address}, {shipping_address}, {store_address} or a specific address. Click "Update map" after modifying address to apply changes to your preview map.<p><span class="wtyp-button-update-changes-google-map">Update map</span></p>' )
		) );


		$wp_customize->add_setting( 'woo_thank_you_page_params[google_map_label]', array(
			'default'           => $this->settings->get_default( 'google_map_label' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[google_map_label]', array(
			'type'        => 'textarea',
			'priority'    => 10,
			'section'     => 'woo_thank_you_page_design_google_map',
			'label'       => esc_html__( 'Marker label', 'woo-thank-you-page-customizer' ),
			'description' => esc_html__( 'Use {address} to refer to the address that you enter above.', 'woo-thank-you-page-customizer' )
		) );

		$wp_customize->add_setting( 'woo_thank_you_page_params[google_map_zoom_level]', array(
			'default'           => $this->settings->get_default( 'google_map_zoom_level' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_thank_you_page_params[google_map_zoom_level]', array(
			'type'        => 'number',
			'section'     => 'woo_thank_you_page_design_google_map',
			'label'       => esc_html__( 'Zoom level', 'woo-thank-you-page-customizer' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
	}
}
