<?php
/*
Plugin Name: FlexMLS - Divi Integration
Plugin URI:  https://www.fbsidx.com/plugin/
Description: Plugin for integrating FlexMLS plugin and Divi Page Builder
Version:     1.0.0
Author:      FBS Data
Author URI:  https://www.flexmls.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: fmcd-divi
Domain Path: /languages

FlexMLS - Divi Integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

FlexMLS - Divi Integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with FlexMLS - Divi Integration. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


if ( ! function_exists( 'fmcd_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function fmcd_initialize_extension() {	
	require_once plugin_dir_path( __FILE__ ) . 'includes/Divi.php';
}
function divi_fmcd_admin(){
	wp_enqueue_style('divi_fmcd_styles', plugins_url('includes/divi-fmc-styles.css', __FILE__));
}
add_action( 'divi_extensions_init', 'fmcd_initialize_extension' );
add_action( 'admin_enqueue_scripts', 'divi_fmcd_admin' );
endif;
