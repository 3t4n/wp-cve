<?php
/**
 * Declare constants
 *
 * @package lasso constants
 */

namespace LassoLite\Admin;

use LassoLite\Classes\Enum;

// ? wp-includes/default-constants.php
if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
	if ( ! function_exists( 'wp_cookie_constants' ) ) {
		require_once ABSPATH . 'wp-includes/default-constants.php';
	}

	wp_cookie_constants();
}

if ( ! defined( 'SENTRY_LITE_DSN' ) ) {
	define( 'SENTRY_LITE_DSN', 'https://6416a56005214ca4966296529ef86479@o51581.ingest.sentry.io/6568051' );
}

class Constant {
	const SENTRY_DSN        = SENTRY_LITE_DSN;
	const DEFAULT_THUMBNAIL = SIMPLE_URLS_URL . '/admin/assets/images/lasso-no-thumbnail.jpg';
	const LASSO_URL         = 'https://getlasso.co';
	const LASSO_SUPPORT_URL = 'https://support.getlasso.co/';
	const LASSO_UPGRADE_URL = 'https://getlasso.co/upgrade/';
	const SITE_ID_KEY       = 'site_id';

	const DEFAULT_SETTINGS = array(
		// ? display
		'theme_name'                           => Enum::THEME_CACTUS,
		'display_color_main'                   => 'black',
		'display_color_title'                  => 'black',
		'display_color_background'             => 'white',
		'display_color_button'                 => '#22BAA0',
		'display_color_secondary_button'       => '#22BAA0',
		'display_color_button_text'            => 'white',
		'display_color_pros'                   => '#22BAA0',
		'display_color_cons'                   => '#E06470',
		'primary_button_text'                  => 'Buy Now',
		'secondary_button_text'                => 'Our Review',
		'lasso_affiliate_URL'                  => 'https://getlasso.co/',
		'disclosure_text'                      => 'We earn a commission if you make a purchase, at no additional cost to you.',
		'show_price'                           => true,
		'show_disclosure'                      => true,
		'enable_brag_mode'                     => false,
		'badge_text'                           => '',

		// ? amazon
		'amazon_access_key_id'                 => '',
		'amazon_secret_key'                    => '',
		'amazon_tracking_id'                   => '',
		'amazon_default_tracking_country'      => 'us',
		'amazon_pricing_daily'                 => true,

		// ? general
		'general_disable_amazon_notifications' => false,
		'general_disable_tooltip'              => false,
		'general_disable_notification'         => false,
		'general_enable_new_ui'                => true,
		'check_duplicate_link'                 => false,

		// ? url detail
		'enable_nofollow'                      => true,
		'open_new_tab'                         => true,
		'enable_sponsored'                     => true,

		Enum::SUPPORT_ENABLED                  => false,
		Enum::IS_SUBSCRIBE                     => false,
		Enum::EMAIL_SUPPORT                    => '',
		Enum::CUSTOMER_FLOW_ENABLED            => false,
	);

	const LASSO_INTERCOM_APP_ID = 'az01idfr';
	const JWT_SECRET_KEY        = '6KpRcC60EgicHWhyEIqj';
	const LASSO_LINK            = 'https://lasso.link/';
	const SSL_VERIFY            = true;
	const TIME_OUT              = 30;

	const LASSO_AMAZON_PRODUCTS_DB  = 'lasso_lite_amazon_products';
	const LASSO_POST_TYPE           = 'surl';
	const LASSO_BRAND               = 'Lasso';
	const LASSO_AMAZON_PRODUCT_TYPE = 'Amazon Product';
	const LASSO_CATEGORY            = 'lasso-lite-cat';

	const LASSO_PRO_POST_TYPE       = 'lasso-urls';

	const LASSO_OPTION_REVIEW_ALLOW = 'review_allow_notification';
	const LASSO_OPTION_REVIEW_SNOOZE = 'review_snooze';
	const LASSO_OPTION_REVIEW_LINK_COUNT = 'review_link_count';
	const LASSO_OPTION_PERFORMANCE = 'performance';
	const LASSO_OPTION_DISMISS_PERFORMANCE_NOTICE = 'dismiss_performance_notice';
	const LASSO_LITE_NONCE = 'simple-urls-nonce';

	const BLOCK_CUSTOMIZE = array(
		'single'            => array(
			'type'       => 'single',
			'name'       => 'Single Product Displays',
			'attributes' => array(
				array(
					'name' => 'Product title',
					'attr' => 'title',
					'desc' => 'Override the product title.<br/>Hide the product title by <code>hide</code> value.',
				),
				array(
					'name' => 'Title url',
					'attr' => 'title_url',
					'desc' => 'Customize the title link destination. Leave blank for the default.<br/>Example: https://getlasso.co',
				),
				array(
					'name' => 'Title tag',
					'attr' => 'title_type',
					'desc' => 'Set the title tag. Common values are: <i>H1, H2, H3, H4</i>',
				),
				array(
					'name' => 'Product description',
					'attr' => 'description',
					'desc' => 'Override the product description<br/>Hide the product description by <code>hide</code> value.',
				),
				array(
					'name' => 'Badge',
					'attr' => 'badge',
					'desc' => 'Override the display badge.<br/>Hide the badge by <code>hide</code> value.',
				),
				array(
					'name' => 'Brag',
					'attr' => 'brag',
					'desc' => 'Promote Lasso on your display and <a href="https://getlasso.co/affiliate-program/" rel="nofollow noopener noreferrer" target="_blank">earn money</a> by <code>true</code> value.',
				),
				array(
					'name' => 'Show/Hide the price',
					'attr' => 'price',
				),
				array(
					'name' => 'First button url',
					'attr' => 'primary_url',
					'desc' => 'Override the first button url.<br/>Example: https://getlasso.co',
				),
				array(
					'name' => 'First button text',
					'attr' => 'primary_text',
					'desc' => 'Override the first button text.',
				),
				array(
					'name' => 'Image url',
					'attr' => 'image_url',
					'desc' => 'Override the image url.<br/>Example: https://getlasso.co/lasso.png',
				),
				array(
					'name' => 'Anchor id',
					'attr' => 'anchor_id',
					'desc' => 'Override the anchor id.<br/>Example: the-anchor-link-id',
				),
			),
		),
		'all_attributes'    => array(
			'title',
			'title_url',
			'title_type',
			'description',
			'badge',
			'price',
			'field',
			'rating',
			'theme',
			'primary_url',
			'primary_text',
			'secondary_url',
			'secondary_text',
			'image_url',
			'disclosure_text',
			'button_type',
			'columns',
			'limit',
			'bullets',
			'compact',
			'brag',
			'anchor_id',
		),
		'toogle_attributes' => array(
			'price',
			'field',
			'rating',
		),
		'notice'            => 'For a detailed list of all customization options, visit our <a href="https://support.getlasso.co/en/articles/4575092-shortcode-reference-guide" target="_blank">Shortcode Reference Guide</a>.',
	);
}
