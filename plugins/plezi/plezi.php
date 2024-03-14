<?php
/**
 * Plugin Name: Plezi
 * Plugin URI:  https://www.plezi.co/en/one/?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=wp-plugins-list
 * Description: Free marketing tool to help small businesses on their journey to digital success : tracking, forms, emails, content management, automation, etc.
 * Version:     1.0.5
 * Author:      Plezi
 * Author URI:  https://www.plezi.co/?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=wp-plugins-list
 * Text Domain: plezi-for-wordpress
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

/**
 * Defines
 */
define( 'PLZ_VERSION', '1.0.5' );

/**
 * Includes
 */
include_once( __DIR__ . '/includes/plz-api.php' );
include_once( __DIR__ . '/includes/plz-admin.php' );
include_once( __DIR__ . '/includes/plz-tracker.php' );
include_once( __DIR__ . '/includes/class-plz-forms.php');
include_once( __DIR__ . '/includes/plz-classic-editor.php' );
include_once( __DIR__ . '/builders/divi-module/divi-plezi-form-module.php' );
include_once( __DIR__ . '/builders/gutenberg-block/plz-gutenberg-block.php' );
include_once( __DIR__ . '/builders/wpbakery-element/plz-wpbakery-element.php' );
include_once( __DIR__ . '/rest-api/class-plz-rest-api-tools.php' );
include_once( __DIR__ . '/rest-api/class-plz-rest-api-configuration.php' );

/**
 * Actions
 */
add_action( 'admin_menu', 'plz_add_pages' );
add_action( 'admin_init', 'plz_page_configuration_init' );
add_action( 'admin_enqueue_scripts', 'plz_admin_include_scripts' );
add_action( 'wp_enqueue_scripts', 'plz_include_scripts' );
add_action( 'load_textdomain_mofile', 'plz_load_textdomain', 10, 2 );
add_action( 'plugins_loaded', 'plz_load_plugin' );
add_action( 'init', 'plz_rest_api_configuration_init' );
add_action( 'wp_dashboard_setup', 'plz_add_dashboard_widget', 99 );
add_action( 'elementor/elements/categories_registered', 'plz_add_elementor_widget_category' );
add_action( 'elementor/widgets/register', 'plz_register_widget' );
add_action( 'divi_extensions_init', 'plz_initialize_extension' );
add_action( 'init', 'plz_create_gutenberg_block' );
add_action( 'vc_before_init', 'plz_wpbakery_element', 99 );

/**
 * Filters
 */
add_filter( 'update_footer', 'plz_footer', 9999 );
add_filter( 'plugin_action_links_plezi/plezi.php', 'plz_settings_link' );
add_filter( 'mce_buttons', 'plz_register_button' );
add_filter( 'mce_external_plugins', 'plz_register_tinymce_javascript' );
add_filter( 'mce_external_languages', 'plz_tinymce_plugin_add_locale' );
add_filter( 'block_categories_all', 'plz_add_gutenberg_category_block', 10, 2 );

/**
* Shortcodes
*/
add_shortcode( 'plezi', 'plz_form_shortcode' );
