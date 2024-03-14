<?php
/**
 * Includes the required classes.
 *
 * @package WP Merchant Promotions Feed Manager/Functions
 * @since 2.39.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Includes the required classes
 *
 * @return void
 */
function wpppfm_include_classes() {

	if ( ! class_exists( 'WPPPFM_Add_Promotions_Feed_Editor_Page' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-add-promotions-feed-editor-page.php';
	}

	if ( ! class_exists( 'WPPPFM_Promotions_Feed_Editor_Page' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-promotions-feed-editor-page.php';
	}

	if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotion_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-google-merchant-promotion-wrapper.php';
	}

	if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotions_Feed_Main_Input_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-google-merchant-promotions-feed-main-input-wrapper.php';
	}

	if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotions_Feed_Mandatory_Input_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-google-merchant-promotions-feed-mandatory-input-wrapper.php';
	}

	if ( ! class_exists( 'WPPPFM_Register_Scripts' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-register-scripts.php';
	}

	if ( ! class_exists( 'WPPPFM_Data' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-data.php';
	}

	if ( ! class_exists( 'WPPPFM_Attributes_List' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-attributes-list.php';
	}

	if ( ! class_exists( 'WPPPFM_Queries' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-queries.php';
	}

	if ( ! class_exists( 'WPPPFM_Main_Input_Selector_Element' ) ) {
		require_once __DIR__ . '/classes/elements/class-wpppfm-main-input-selector-element.php';
	}

	if ( ! class_exists( 'WPPPFM_Promotions_Details_Selector_Element' ) ) {
		require_once __DIR__ . '/classes/elements/class-wpppfm-promotions-details-selector-element.php';
	}

	if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotions_Feed_Product_Filters_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-google-merchant-promotions-feed-product-filters-wrapper.php';
	}

	if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotions_Feed_Product_Details_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-google-merchant-promotions-feed-product-details-wrapper.php';
	}

	if ( ! class_exists( 'WPPPFM_Promotions_Feed_Processor' ) ) {
		require_once __DIR__ . '/classes/class-wpppfm-promotions-feed-processor.php';
	}
}
