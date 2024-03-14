<?php
/*
Plugin Name: Genesis Featured Images
Plugin URI: http://www.wpsmith.net/genesis-featured-images
Description: Sets a default image for post thumbnails for the Genesis framework.
Version: 0.6.0
Author: Travis Smith
Author URI: http://www.wpsmith.net/
Requires at least: 3.4.0
Tested up to: 4.2.4
License: GPLv2

    Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'GFI_DOMAIN', 'genesis-featured-images' );
define( 'GFI_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'GFI_URL', WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ) );
define( 'GFI_PREFIX', '_gfi_' );

/* Prevent direct access to the plugin */
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Sorry, you are not allowed to access this page directly.', 'GFI' ) );
}

register_activation_hook( __FILE__, 'gfi_activation_check' );

/**
 * Checks for minimum Genesis Theme version before allowing plugin to activate
 *
 * @author  Nathan Rice
 * @uses    gfi_truncate()
 * @since   0.1
 * @version 0.2
 */
function gfi_activation_check() {

	$latest = '2.0';

	$theme_info = wp_get_theme( 'genesis' );

	if ( is_wp_error() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
		wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed and actived %1$sGenesis%2$s or a %3$sGenesis Child Theme%2$s', 'GFI' ), '<a href="http://wpsmith.net/go/genesis">', '</a>', '<a href="http://wpsmith.net/go/spthemes">' ) );
	}

	$version = gfi_truncate( $theme_info->get( 'Version' ), 3 );

	if ( version_compare( $version, $latest, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
		wp_die( sprintf( __( 'Sorry, you can\'t activate without %1$sGenesis %2$s%3$s or greater', 'GFI' ), '<a href="http://wpsmith.net/go/genesis">', $latest, '</a>' ) );
	}
}

/**
 *
 * Used to cutoff a string to a set length if it exceeds the specified length
 *
 * @author  Nick Croft
 * @since   0.1
 * @version 0.2
 *
 * @param string $str Any string that might need to be shortened
 * @param string $length Any whole integer
 *
 * @return string
 */
function gfi_truncate( $str, $length = 10 ) {

	if ( strlen( $str ) > $length ) {
		return substr( $str, 0, $length );
	} else {
		$res = $str;
	}

	return $res;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'gfi_action_links' );
/**
 * Add "Settings" link to plugin page
 */
function gfi_action_links( $links ) {
	$gif_settings_link = sprintf( '<a href="%s">%s</a>',
		admin_url( 'admin.php?page=genesis' ),
		__( 'Settings', GFI_DOMAIN ) );
	array_unshift( $links, $gif_settings_link );

	return $links;
}

// Add metaboxes
require_once( GFI_PLUGIN_DIR . '/lib/functions.php' );

add_action( 'after_setup_theme', 'gfi_init', 15 );
/**
 * Loads admin file
 */
function gfi_init() {
	if ( is_admin() ) {
		require_once( GFI_PLUGIN_DIR . '/lib/metaboxes.php' );
		require_once( GFI_PLUGIN_DIR . '/lib/admin-settings.php' );
	}
}

add_action( 'get_header', 'gfi_remove_do_post_image' );
/**
 * Replace some genesis_* functions hooked into somewhere for some gfi_* functions
 * of the same suffix, at the same hook and priority
 *
 * @author Gary Jones
 *
 * @global array $wp_filter
 */
function gfi_remove_do_post_image() {

	global $wp_filter;

	// List of genesis_* functions to be replaced with gfi_* functions.
	// We save some bytes and add the ubiquitous 'genesis_' later on.
	$functions = array(
		'do_post_image',
	);

	// Loop through all hooks (yes, stored under the $wp_filter global)
	foreach ( $wp_filter as $hook => $priority ) {

		// Loop through our array of functions for each hook
		foreach ( $functions as $function ) {

			// has_action returns int for the priority
			if ( $priority = has_action( $hook, 'genesis_' . $function ) ) {

				// If there's a function hooked in, remove the genesis_* function
				// from whichever hook we're looping through at the time.
				remove_action( $hook, 'genesis_' . $function, $priority );

				// Add a replacement function in at an earlier time.
				add_action( $hook, 'gfi_' . $function, 5 );
			}
		}
	}
}

//add_action( 'genesis_post_content' , 'gfi_do_post_image' );
function gfi_do_post_image() {
	global $prefix;
	if ( ! is_singular() && genesis_get_option( 'content_archive_thumbnail' ) ) {
		if ( genesis_get_custom_field( $prefix . 'custom_feat_img' ) ) {
			$img = genesis_get_image( array(
				'format' => 'html',
				'size'   => genesis_get_custom_field( $prefix . 'custom_feat_img' ),
				'attr'   => array( 'class' => 'alignleft post-image' )
			) );
		} else {
			$img = genesis_get_image( array(
				'format' => 'html',
				'size'   => genesis_get_option( 'image_size' ),
				'attr'   => array( 'class' => 'alignleft post-image' )
			) );
		}
		printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $img );
	}
}


?>