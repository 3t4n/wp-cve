<?php
/*
Plugin Name: Turn Rank Math FAQ Block to Accordion
Description: A plugin to turn Rank Math FAQ blocks into accordion easily.
Author: WPHowKnow
Author URI: https://wphowknow.com/
Version: 1.1.0
Text Domain: turn-rank-math-faq-block-to-accordion
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define( 'RMFA_Plugin_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'RMFA_Plugin_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'RMFA_CURRENT_VERSION', '1.1.0' );

// Load Asset Files
function RMFA_load_plugin_asset_files() {
    wp_enqueue_style( 'RMFA', RMFA_Plugin_URL . '/assets/css/style.min.css', array(), RMFA_CURRENT_VERSION );
    wp_enqueue_script( 'RMFA-js', RMFA_Plugin_URL . '/assets/js/RMFA-JS.min.js', array('jquery'), RMFA_CURRENT_VERSION, 'true' );
}
add_action( 'wp_enqueue_scripts', 'RMFA_load_plugin_asset_files' );