<?php
/* 
Plugin Name: Disable Thumbnails, Threshold and Image Options
Version: 0.5
Description: Disable Thumbnails, Threshold and Image Options
Author: KGM Servizi
Author URI: https://kgmservizi.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_admin() ) {
	include( plugin_dir_path( __FILE__ ) . 'includes/option-thumbnails.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/option-quality.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/option-threshold-exif.php');
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'kgmimage_action_links' );
	add_action( 'admin_enqueue_scripts', 'kgmimage_admin_styles' );
}

/**
 *  
 * Retrieve options for quality and thresold
 * 
 */
$kgmimgquality_options       = get_option( 'kgmimgquality_option_name' );
$kgmdisablethreshold_options = get_option( 'kgmdisablethreshold_option_name' );

/**
 *  
 * Retrieve image quality value
 * 
 */
if ( is_array( $kgmimgquality_options ) ) {
	if ( $kgmimgquality_options['jpeg_quality'] != null ) {
		$jpeg_quality = $kgmimgquality_options['jpeg_quality'];
	}
}

/**
 *  
 * Set new image quality
 * 
 */
if ( is_array( $kgmimgquality_options ) ) {
	if ( $kgmimgquality_options['jpeg_quality'] != null && intval($kgmimgquality_options['jpeg_quality']) > 0 && intval($kgmimgquality_options['jpeg_quality']) <= 100 ) {
		add_filter("jpeg_quality", function () use ($jpeg_quality) {
			return intval($jpeg_quality);
		});
	}
}

/**
 *  
 * Retrieve thresold size
 * 
 */
if ( is_array( $kgmdisablethreshold_options ) ) {
	if ( $kgmdisablethreshold_options['new_threshold'] != null ) {
		$threshold = $kgmdisablethreshold_options['new_threshold'];
	}
}

/**
 *  
 * Set new thresold
 * 
 */
if ( !empty( $kgmdisablethreshold_options['new_threshold'] ) && intval( $kgmdisablethreshold_options['new_threshold']) > 0 ) {
	add_filter("big_image_size_threshold", function () use ($threshold) {
		return intval($threshold);
	});
}

/**
 *  
 * Remove big image threshold
 * 
 */
if ( is_array( $kgmdisablethreshold_options ) ) {
	if ( $kgmdisablethreshold_options['disable_threshold'] ?? null ) {
		if ($kgmdisablethreshold_options['disable_threshold'] == 'disable_threshold') {
			add_filter( 'big_image_size_threshold', '__return_false' );
		}
	}
}

/**
 *  
 * Unset EXIF rotation
 * 
 */
if ( is_array( $kgmdisablethreshold_options ) ) {
	if ( $kgmdisablethreshold_options['disable_image_rotation_exif'] ?? null ) {
		if ($kgmdisablethreshold_options['disable_image_rotation_exif'] == 'disable_image_rotation_exif') {
			add_filter( 'wp_image_maybe_exif_rotate', '__return_zero', 10, 2 );
		}
	}
}

/**
 *  
 * Disable image sizes checked
 * 
 */
if ( !empty ( get_option( 'kgmdisablethumbnails_option_name' ) ) ) {
	add_filter( 'intermediate_image_sizes', function($sizes) {
		return array_diff( $sizes, get_option( 'kgmdisablethumbnails_option_name' ) );
	});
}

/**
 * 
 * Add link on plugin list page
 * 
 */
function kgmimage_action_links( $actions ) {
	$mylinks = array( '<a href="'. esc_url( get_admin_url(null, 'tools.php?page=kgmdisablethumbnails') ) .'">Image sizes</a>', '<a href="'. esc_url( get_admin_url(null, 'tools.php?page=kgmimgquality') ) .'">Image Quality</a>', '<a href="'. esc_url( get_admin_url(null, 'tools.php?page=kgmdisablethreshold') ) .'">Threshold & EXIF</a>' );
	$actions = array_merge( $mylinks, $actions );
	return $actions;
}

/**
 * 
 * Load admin styles
 * 
 */
function kgmimage_admin_styles($hook) {
	/**
	 * Check if in plugin options page
	 */
	$screen = get_current_screen();
	if ( $screen->base == 'tools_page_kgmdisablethumbnails' || $screen->base == 'tools_page_kgmdisablethreshold' ) {
		wp_enqueue_style( 'kgmdimage_admin_css', plugins_url('includes/admin.css', __FILE__) );
	} else {
		return;
	}
}

/**
 *  
 * Uninstallation
 * 
 */
register_uninstall_hook( __FILE__, 'kgmdttio_plugin_uninstall' );
function kgmdttio_plugin_uninstall() {
    delete_option( 'kgmdisablethumbnails_option_name' );
    delete_option( 'kgmdisablethreshold_option_name' );
    delete_option( 'kgmimgquality_option_name' );
}
