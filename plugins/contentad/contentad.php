<?php

/**
 * Plugin Name: Content.ad
 * Plugin URI: https://wordpress.org/plugins/contentad/
 * Description: Content.ad enables blog owners to display ads or related blog posts (from their own blog) in a "lead me to more content" section. The ads are sourced dynamically from the Content.ad system and can be a source of revenue for the blog owner.
 * Version: 1.3.7
 * Author: Content.ad
 * Author URI: https://www.content.ad
 * Developer: Content.ad
 * Developer URI: https://www.content.ad
 * Text Domain: contentad
 * License: GPLv2
 *
 *  Copyright 2015 Content.ad (info@content.ad)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * [mikes] This handles symlinked plugins, at least if they
 *         are stored in identically named subdirectories.
 */
function get_contentad_file() {
	$file = __FILE__;
	if ( false === strpos( WP_PLUGIN_DIR, $file ) ) {
    $file = WP_PLUGIN_DIR . '/' . basename( dirname( $file ) ) . '/' . basename( $file );
    $file = str_replace( '/plugins/plugins/', '/plugins/', $file );
  }
  return $file;
}

define( 'CONTENTAD_VERSION', '1.3.7' );
define( 'CONTENTAD_FILE', get_contentad_file() );
define( 'CONTENTAD_PATH', plugin_dir_path( CONTENTAD_FILE ) );
define( 'CONTENTAD_URL', plugins_url( '', CONTENTAD_FILE ) );
define( 'CONTENTAD_NAME', __('Content.ad', 'contentad') );
define( 'CONTENTAD_SLUG', 'contentad' );
define( 'CONTENTAD_API_URL', 'https://partners.content.ad/api.svc' );
define( 'CONTENTAD_REMOTE_URL', 'https://app.content.ad/' );
define( 'CONTENTAD_REVIEW_DELAY', WEEK_IN_SECONDS );

/**
 * By default, error logging is disabled.  Define this constant in your wp-config.php file
 * and set to true in order to override this default.
 */
if( ! defined('CONTENTAD_ERROR_LOGGING') ) {
	define( 'CONTENTAD_ERROR_LOGGING', false );
}

/**
 * PHP 5.2+ is required.  Disable this plugin with a friendly message if this requirement
 * is not met in order to avoid cryptic error messages.  All PHP 5 syntax must be avoided
 * in this file to prevent parse errors before this code is run.
 */
if ( version_compare( PHP_VERSION, '5.2.4', '<' ) ) {
	if ( is_admin() && ( ! defined('DOING_AJAX') || ! DOING_AJAX ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		deactivate_plugins( CONTENTAD_FILE );
	    wp_die( printf( __(
			'%s requires PHP version 5.2.4 or later. You are currently running version %s.
			This plugin has now disabled itself.
			Please contact your web host regarding upgrading your PHP version.', 'contentad'
		), CONTENTAD_NAME, PHP_VERSION ) );
	}
}

/**
 * Autoload classes so that only what we need is run exactly when we need it.
 *
 * @param $class
 */
function ContentAd_Autoloader( $class ) {
	$replace = array( '#contentad#' => '', '#__#' => DIRECTORY_SEPARATOR, '#_#' => '-' );
	$path = preg_replace( array_keys( $replace ), array_values( $replace ), strtolower( $class ) );
	if( file_exists( dirname( CONTENTAD_FILE ) . "{$path}.class.php" ) )
		include( dirname( CONTENTAD_FILE ) . "$path.class.php" );
}
spl_autoload_register( 'ContentAd_Autoloader' );

/**
 * Takes a message and appends it to the error log.
 *
 * @param $text
 */
function contentAd_append_to_log( $text ){
	if( CONTENTAD_ERROR_LOGGING ) {
		$file_handle = fopen( CONTENTAD_PATH . '/debug.log', 'a' );
		$plugin_notice = PHP_EOL . '[' . current_time( 'mysql' ) . '] Content.ad Plugin: ';
		fwrite( $file_handle, $plugin_notice . $text . PHP_EOL );
		fclose( $file_handle );
	}
}
 
/**
 * Add Next Page/Page Break Button
 * in WordPress Visual Editor
 * 
 * @link https://shellcreeper.com/?p=889
 */

 /* Add Next Page Button in First Row */
add_filter( 'mce_buttons', 'my_add_next_page_button', 1, 2 ); // 1st row
function my_add_next_page_button( $buttons, $id ){
 
    /* only add this for content editor */
    if ( 'content' != $id )
        return $buttons;
 
    /* add next page after more tag button */
    array_splice( $buttons, 13, 0, 'wp_page' );
 
    return $buttons;
}
    
/**
 * Display an admin alert to review the plugin 1 week after activation
 *
 * @return null
 */
require_once ( dirname( __FILE__ ) . '/includes/plugin-review.class.php' );
register_activation_hook( CONTENTAD_FILE, array( 'Contentad__Plugin__Review', 'check_installation_date' ) );

// Initialize plugin
add_action( 'plugins_loaded', array( 'ContentAd__Includes__Init', 'on_load' ) );

?>
