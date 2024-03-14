<?php
/**
 * @link              catchplugins.com
 * @since             1.0
 * @package           Catch_Gallery
 *
 * @wordpress-plugin
 * Plugin Name: Catch Gallery
 * Plugin URI:  https://catchplugins.com/plugins/catch-gallery/
 * Description: Catch Gallery allows you to add three different types of layouts (in addition to the default layout provided by WordPress – Thumbnail Grid) for your galleries to stand out—Tiled Mosaic, Square Tiles, Circles.
 * Version:     2.0
 * Author:      Catch Plugins
 * Author URI:  catchplugins.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: catch-gallery
 * Tags:        gallery, tiled gallery, image gallery, mosaic, carousel, lightbox, media, jetpack, jetpack lite
 * Domain Path: /languages
 */

/*
Copyright (C) 2018 Catch Plugins, (info@catchplugins.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'CATCH_GALLERY_VERSION' ) ) {
	define( 'CATCH_GALLERY_VERSION', '2.0' );
}

// The URL of the directory that contains the plugin
if ( ! defined( 'CATCH_GALLERY_URL' ) ) {
	define( 'CATCH_GALLERY_URL', plugin_dir_url( __FILE__ ) );
}


// The absolute path of the directory that contains the file
if ( ! defined( 'CATCH_GALLERY_PATH' ) ) {
	define( 'CATCH_GALLERY_PATH', plugin_dir_path( __FILE__ ) );
}


// Gets the path to a plugin file or directory, relative to the plugins directory, without the leading and trailing slashes.
if ( ! defined( 'CATCH_GALLERY_BASENAME' ) ) {
	define( 'CATCH_GALLERY_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! function_exists( 'activate_catch_gallery' ) ) :
	function activate_catch_gallery() {
		/* Check if Catch Gallery Pro is installed and active, abort plugin activation and return with message */
		$required = 'catch-gallery-pro/catch-gallery.php';
		if ( is_plugin_active( $required ) ) {
			$message = esc_html__( 'Sorry, Pro plugin is already active. No need to activate Free version. %1$s&laquo; Return to Plugins%2$s.', 'catch-gallery' );
			$message = sprintf( $message, '<br><a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">', '</a>' );
			wp_die( $message );
		}
	}
endif;
register_activation_hook( __FILE__, 'activate_catch_gallery' );

if ( ! function_exists( 'catch_gallery_load_textdomain' ) ) :
	function catch_gallery_load_textdomain() {
		load_plugin_textdomain( 'catch-gallery', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
endif;
add_action( 'plugins_loaded', 'catch_gallery_load_textdomain' );


// Include admin part.
include( plugin_dir_path( __FILE__ ) . 'admin/admin.php' );

include( plugin_dir_path( __FILE__ ) . 'inc/functions.php' );

include( plugin_dir_path( __FILE__ ) . 'inc/tiled-gallery.php' );

include( plugin_dir_path( __FILE__ ) . 'inc/jetpack-carousel.php' );

/* CTP tabs removal options */
require plugin_dir_path( __FILE__ ) . '/inc/ctp-tabs-removal.php';

 $ctp_options = ctp_get_options();
if ( 1 == $ctp_options['theme_plugin_tabs'] ) {
	/* Adds Catch Themes tab in Add theme page and Themes by Catch Themes in Customizer's change theme option. */
	if ( ! class_exists( 'CatchThemesThemePlugin' ) && ! function_exists( 'add_our_plugins_tab' ) ) {
		require plugin_dir_path( __FILE__ ) . '/inc/CatchThemesThemePlugin.php';
	}
}
