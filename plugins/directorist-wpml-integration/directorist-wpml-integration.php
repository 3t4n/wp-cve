<?php
/**
 * Directorist_WPML_Integration
 *
 * @package           Directorist_WPML_Integration
 * @author            wpWax
 * @copyright         2022 wpWax
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Directorist - WPML Integration
 * Plugin URI:        https://github.com/sovware/directorist-wpml-integration
 * Description:       WPML integration plugin for Directorist.
 * Version:           1.0.0
 * Requires at least: 5.7
 * Requires PHP:      7.2
 * Author:            wpWax
 * Author URI:        https://directorist.com/about-us
 * Text Domain:       directorist-wpml-integration
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

require dirname( __FILE__ ) . '/vendor/autoload.php';
require dirname( __FILE__ ) . '/app.php';

if ( ! function_exists( 'Directorist_WPML_Integration' ) ) {
    function Directorist_WPML_Integration() {
        return Directorist_WPML_Integration::get_instance();
    }
}

add_action( 'directorist_loaded', 'Directorist_WPML_Integration' );
