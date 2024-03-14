<?php
/**
 * Hooks used by the plugin.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN\Hooks;

use WPC_WPCF7_NTN\Options;
use WPC_WPCF7_NTN\Helpers;
use WPC_WPCF7_NTN\CFP;

defined( 'ABSPATH' ) || exit;


// On plugin activation, keep the current plugin version in an option.
add_action( 'add-on-cf7-for-notion/plugin-activated', 'wpconnect_wpcf7_notion_save_plugin_version', 10, 1 );

// Register WPCF7 Notion service.
add_action( 'wpcf7_init', 'wpconnect_wpcf7_notion_register_service', 1, 0 );

// *******************************
// *** CONTACT FORM PROPERTIES ***
// *******************************

// Register the wpc_notion contact form property.
add_filter( 'wpcf7_pre_construct_contact_form_properties', 'WPC_WPCF7_NTN\CFP\register_property', 10, 1 );
// Build the editor panel for the wpc_notion property.
add_filter( 'wpcf7_editor_panels', 'WPC_WPCF7_NTN\CFP\editor_panels', 10, 1 );
// Save the wpc_notion property value.
add_action( 'wpcf7_save_contact_form', 'WPC_WPCF7_NTN\CFP\save_contact_form', 10, 1 );


// ***********************************
// *** CONTACT FORM FIELDS MAPPING ***
// ***********************************

add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_text' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_email' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_url' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_tel' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_number' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_range' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_textarea' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_select' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_checkbox' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_radio' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_acceptance' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_date' );
add_filter( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', 'WPC_WPCF7_NTN\Fields\map_wpcf7_files' );

// *******************************
// *** ENTRY ***
// *******************************

// Save contact form submission to Notion database.
add_action( 'wpcf7_before_send_mail', 'WPC_WPCF7_NTN\Entry\save_wpcf7_entry_in_notion_database', 10, 3 );