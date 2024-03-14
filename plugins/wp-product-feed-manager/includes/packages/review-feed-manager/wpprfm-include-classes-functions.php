<?php
/**
 * Includes the required classes.
 *
 * @package WP Product Review Feed Manager/Functions
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
function wpprfm_include_classes() {

	if ( ! class_exists( 'WPPRFM_Add_Review_Feed_Editor_Page' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-add-review-feed-editor-page.php';
	}

	if ( ! class_exists( 'WPPRFM_Review_Feed_Editor_Page' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-review-feed-editor-page.php';
	}

	if ( ! class_exists( 'WPPRFM_Google_Product_Review_Feed_Main_Input_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-google-product-review-feed-main-input-wrapper.php';
	}

	if ( ! class_exists( 'WPPRFM_Google_Product_Review_Feed_Category_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-google-product-review-feed-category-wrapper.php';
	}

	if ( ! class_exists( 'WPPRFM_Google_Product_Review_Feed_Attribute_Mapping_Wrapper' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-google-product-review-feed-attribute-mapping-wrapper.php';
	}

	if ( ! class_exists( 'WPPRFM_Register_Scripts' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-register-scripts.php';
	}

	if ( ! class_exists( 'WPPRFM_Ajax_Data' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-ajax-data.php';
	}

	if ( ! class_exists( 'WPPRFM_Data' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-data.php';
	}

	if ( ! class_exists( 'WPPRFM_Queries' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-queries.php';
	}

	if ( ! class_exists( 'WPPRFM_Feed_Sources' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-feed-sources.php';
	}

	if ( ! class_exists( 'WPPRFM_Main_Input_Selector_Element' ) ) {
		require_once __DIR__ . '/classes/elements/class-wpprfm-main-input-selector-element.php';
	}

	if ( ! class_exists( 'WPPRFM_Category_Selector_Element' ) ) {
		require_once __DIR__ . '/classes/elements/class-wpprfm-category-selector-element.php';
	}

	if ( ! class_exists( 'WPPRFM_Attribute_Selector_Element' ) ) {
		require_once __DIR__ . '/classes/elements/class-wpprfm-attribute-selector-element.php';
	}

	if ( ! class_exists( 'WPPRFM_Feed_File_Element' ) ) {
		require_once __DIR__ . '/classes/elements/class-wpprfm-feed-file-element.php';
	}

	if ( ! class_exists( 'WPPRFM_Attributes_List' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-attributes-list.php';
	}

	if ( ! class_exists( 'WPPRFM_Review_Feed_Processor' ) ) {
		require_once __DIR__ . '/classes/class-wpprfm-review-feed-processor.php';
	}
}
