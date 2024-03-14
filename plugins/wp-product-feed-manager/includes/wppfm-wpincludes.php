<?php

/**
 * Includes the functions to load the correct files.
 *
 * @package WP Product Feed Manager/Functions
 * @version 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Includes all required classes
 *
 * @since 1.0.0
 */
function include_classes() {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		require_once 'setup/class-wppfm-prepare-taxonomy.php';
	}

	if ( ! class_exists( 'WPPFM_Schedules' ) ) {
		require_once __DIR__ . '/application/class-wppfm-schedules.php';
	}
	if ( ! class_exists( 'WPPFM_Async_Request' ) ) {
		require_once __DIR__ . '/libraries/wppfm-async-request.php';
	}
	if ( ! class_exists( 'WPPFM_Background_Process' ) ) {
		require_once __DIR__ . '/libraries/wppfm-background-process.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Master_Class' ) ) {
		require_once __DIR__ . '/application/class-feed-master.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Controller' ) ) {
		require_once __DIR__ . '/application/class-wppfm-feed-controller.php';
	}
	if ( ! class_exists( 'WPPFM_Queries' ) ) {
		require_once __DIR__ . '/data/class-wppfm-queries.php';
	}
	if ( ! class_exists( 'WPPFM_i18n_Scripts' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-i18n-scripts.php';
	}
	if ( ! class_exists( 'WPPFM_File' ) ) {
		require_once __DIR__ . '/data/class-wppfm-file.php';
	}
	if ( ! class_exists( 'WPPFM_Channel' ) ) {
		require_once __DIR__ . '/data/class-wppfm-channel.php';
	}
	if ( ! class_exists( 'WPPFM_Support_Feed_Attributes_List' ) ) {
		require_once __DIR__ . '/data/class-wppfm-support-feed-attributes-list.php';
	}
	if ( ! class_exists( 'WPPFM_Local_Product_Inventory_Attributes_List' ) ) {
		require_once __DIR__ . '/data/class-wppfm-local-product-inventory-attributes-list.php';
	}
	if ( ! class_exists( 'WPPFM_Vehicle_Ads_Attributes_List' ) ) {
		require_once __DIR__ . '/data/class-wppfm-vehicle-ads-attributes-list.php';
	}
	if ( ! class_exists( 'WPPFM_Local_Product_Attributes_List' ) ) {
		require_once __DIR__ . '/data/class-wppfm-local-product-attributes-list.php';
	}
	if ( ! class_exists( 'WPPFM_Dynamic_Remarketing_Attributes_List' ) ) {
		require_once __DIR__ . '/data/class-wppfm-dynamic-remarketing-attributes-list.php';
	}
	if ( ! class_exists( 'WPPFM_Dynamic_Search_Ads_Attributes_List' ) ) {
		require_once __DIR__ . '/data/class-wppfm-dynamic-search-ads-attributes-list.php';
	}
	if ( ! class_exists( 'WPPFM_Variations' ) ) {
		require_once __DIR__ . '/data/class-wppfm-variations.php';
	}
	if ( ! class_exists( 'WPPFM_Data' ) ) {
		require_once __DIR__ . '/data/class-wppfm-data.php';
	}
	if ( ! class_exists( 'WPPFM_Taxonomies' ) ) {
		require_once __DIR__ . '/data/class-wppfm-taxonomies.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_CRUD_Handler' ) ) {
		require_once __DIR__ . '/data/class-wppfm-feed-crud-handler.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Support' ) ) {
		require_once __DIR__ . '/application/class-wppfm-feed-support.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Processor' ) ) {
		require_once __DIR__ . '/application/class-wppfm-feed-processor.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Value_Editors' ) ) {
		require_once __DIR__ . '/application/class-wppfm-feed-value-editors.php';
	}
	if ( ! class_exists( 'WPPFM_Admin_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-admin-page.php';
	}
	if ( ! class_exists( 'WPPFM_Main_Admin_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-main-admin-page.php';
	}
	if ( ! class_exists( 'WPPFM_List_Table' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-list-table.php';
	}
	if ( ! class_exists( 'WPPFM_Ajax_Calls' ) ) {
		require_once __DIR__ . '/data/class-wppfm-ajax-calls.php';
	}
	if ( ! class_exists( 'WPPFM_Main_Header' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-main-header.php';
	}
	if ( ! class_exists( 'WPPFM_Notice' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-notice.php';
	}
	if ( ! class_exists( 'WPPFM_Add_Feed_List_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-add-feed-list-page.php';
	}
	if ( ! class_exists( 'WPPFM_Add_Feed_Editor_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-add-feed-editor-page.php';
	}
	if ( ! class_exists( 'WPPFM_Add_Channel_Manager_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-add-channel-manager-page.php';
	}
	if ( ! class_exists( 'WPPFM_Add_Settings_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-add-settings-page.php';
	}
	if ( ! class_exists( 'WPPFM_Add_Support_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-add-support-page.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_List_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-feed-list-page.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Editor_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-feed-editor-page.php';
	}
	if ( ! class_exists( 'WPPFM_Channel_Manager_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-channel-manager-page.php';
	} // ref MKFRPLGN
	if ( ! class_exists( 'WPPFM_Settings_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-settings-page.php';
	}
	if ( ! class_exists( 'WPPFM_Support_Page' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-support-page.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Form_Control' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-feed-form-control.php';
	}
	if ( ! class_exists( 'WPPFM_Tab' ) ) {
		require_once __DIR__ . '/data/class-wppfm-tab.php';
	}
	if ( ! class_exists( 'WPPFM_Main_Input_Wrapper' ) ) {
		require_once __DIR__ . '/user-interface/abstract-wppfm-main-input-wrapper.php';
	}
	if ( ! class_exists( 'WPPFM_Category_Wrapper' ) ) {
		require_once __DIR__ . '/user-interface/abstract-wppfm-category-wrapper.php';
	}
	if ( ! class_exists( 'WPPFM_Filter_Wrapper' ) ) {
		require_once __DIR__ . '/user-interface/abstract-wppfm-filter-wrapper.php';
	}
	if ( ! class_exists( 'WPPFM_Attribute_Mapping_Wrapper' ) ) {
		require_once __DIR__ . '/user-interface/abstract-wppfm-attribute-mapping-wrapper.php';
	}
	if ( ! class_exists( 'WPPFM_Product_Feed_Main_Input_Wrapper' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-product-feed-main-input-wrapper.php';
	}
	if ( ! class_exists( 'WPPFM_Product_Feed_Category_Wrapper' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-product-feed-category-wrapper.php';
	}
	if ( ! class_exists( 'WPPFM_Product_Feed_Attribute_Mapping_Wrapper' ) ) {
		require_once __DIR__ . '/user-interface/class-wppfm-product-feed-attribute-mapping-wrapper.php';
	}
	if ( ! class_exists( 'WPPFM_Form_Element' ) ) {
		require_once __DIR__ . '/user-interface/elements/class-wppfm-form-element.php';
	}
	if ( ! class_exists( 'WPPFM_Attribute_Selector_Element' ) ) {
		require_once __DIR__ . '/user-interface/elements/class-wppfm-attribute-selector-element.php';
	}
	if ( ! class_exists( 'WPPFM_Category_Selector_Element' ) ) {
		require_once __DIR__ . '/user-interface/elements/class-wppfm-category-selector-element.php';
	}
	if ( ! class_exists( 'WPPFM_Product_Filter_Selector_Element' ) ) {
		require_once __DIR__ . '/user-interface/elements/class-wppfm-product-filter-selector-element.php';
	}
	if ( ! class_exists( 'WPPFM_Main_Input_Selector_Element' ) ) {
		require_once __DIR__ . '/user-interface/elements/class-wppfm-main-input-selector-element.php';
	}
	if ( ! class_exists( 'WPPFM_Register_Scripts' ) ) {
		require_once __DIR__ . '/class-wppfm-register-scripts.php';
	}
	if ( ! class_exists( 'WPPFM_Db_Management' ) ) {
		require_once __DIR__ . '/data/class-wppfm-db-management.php';
	}
	if ( ! class_exists( 'WPPFM_Database_Management' ) ) {
		require_once __DIR__ . '/setup/class-wppfm-database-management.php';
	}
	if ( ! class_exists( 'WPPFM_Ajax_Data' ) ) {
		require_once __DIR__ . '/data/class-wppfm-ajax-data.php';
	}
	if ( ! class_exists( 'WPPFM_Ajax_File' ) ) {
		require_once __DIR__ . '/data/class-wppfm-ajax-file.php';
	}
	if ( ! class_exists( 'WPPFM_Backup' ) ) {
		require_once __DIR__ . '/data/class-wppfm-backup.php';
	}
	if ( ! class_exists( 'WPPFM_Channel_FTP' ) ) {
		require_once __DIR__ . '/data/class-wppfm-channel-ftp.php';
	}
	if ( ! class_exists( 'WPPFM_Feed_Queries' ) ) {
		require_once __DIR__ . '/application/class-wppfm-feed-queries.php';
	}
	if ( ! class_exists( 'WPPFM_Folders' ) ) {
		require_once __DIR__ . '/setup/class-wppfm-folders.php';
	}
	if ( ! class_exists( 'WPPFM_Email' ) ) {
		require_once __DIR__ . '/application/class-wppfm-email.php';
	}
}

/**
 * Includes all required channel classes
 *
 * @since 1.0.0
 */
function include_channels() {

	if ( ! class_exists( 'WPPFM_Google_Feed_Class' ) ) {
		require_once __DIR__ . '/application/google/class-feed.php';
	}
}
