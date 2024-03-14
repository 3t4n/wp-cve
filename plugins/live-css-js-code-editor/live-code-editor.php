<?php

/**
 * Plugin Name:       Live Custom CSS JS Code Editor
 * Plugin URI:        http://wordpress.org/plugins/live-css-js-code-editor/
 * Description:       Easily add custom CSS, JavaScript, Header, Footer Code to your site, straight from your WordPress Customizer!
 * Version:           1.0.5
 * Author:            Ozan Canakli
 * Author URI:        http://www.ozanwp.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       live-css-js-code-editor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Defines
define( 'LIVE_CODE_EDITOR_VER', '1.0.5' );
define( 'LIVE_CODE_EDITOR_DIR', plugin_dir_path( __FILE__ ) );
define( 'LIVE_CODE_EDITOR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );


// Classes
require_once LIVE_CODE_EDITOR_DIR . '/includes/class-live-code-editor.php';


// Actions
add_action( 'customize_register',                   'Live_Code_Editor::customizer_register' );
add_action( 'customize_controls_enqueue_scripts',   'Live_Code_Editor::customizer_enqueue_scripts' );
add_action( 'customize_preview_init',               'Live_Code_Editor::public_enqueue_scripts' );
add_action( 'wp_head',                              'Live_Code_Editor::head_codes' );
add_action( 'admin_head',                           'Live_Code_Editor::admin_head_codes' );
add_action( 'wp_footer',                            'Live_Code_Editor::footer_codes' );
add_action( 'plugins_loaded',                       'Live_Code_Editor::load_plugin_textdomain' );