<?php
/*
Plugin Name: WP Image Borders
Version: 2.02
Description: WP Image Borders makes it easy to add or remove image borders from pictures in your blog posts.
Author: Compete Themes
Author URI: https://www.competethemes.com
Text Domain: bs-wib
Domain Path: /languages
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Image Borders WordPress Plugin, Copyright 2015 Compete Themes
WP Image Borders is distributed under the terms of the GNU GPL

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// set constant for main plugin file
if ( ! defined( 'BS_WIB_FILE' ) ) {
	define( 'BS_WIB_PRO_FILE', __FILE__ );
}

// set constant for plugin directory
if ( ! defined( 'BS_WIB_PRO_PATH' ) ) {
	define( 'BS_WIB_PRO_PATH', plugin_dir_path( BS_WIB_PRO_FILE ) );
}

// set constant for plugin url
if ( ! defined( 'BS_WIB_PRO_URL' ) ) {
	define( 'BS_WIB_PRO_URL', plugin_dir_url( __FILE__ ) );
}

// set constant for plugin basename
if ( ! defined( 'BS_WIB_PRO_BASENAME' ) ) {
	define( 'BS_WIB_PRO_BASENAME', plugin_basename( BS_WIB_PRO_FILE ) );
}

// require options file
require_once( BS_WIB_PRO_PATH . 'inc/options.php' );

// require output file
require_once( BS_WIB_PRO_PATH . 'inc/output.php' );

// Add settings link on plugin page
function bs_wib_settings_link($links) {

	// set url
	$link = admin_url('options-general.php?page=wp-image-borders.php');

	// set link markup
	$settings_link = '<a href="' . esc_url( $link ) . '">Settings</a>';

	// add settings link to plugin's links
	array_unshift($links, $settings_link);

	// return the links
	return $links;
}
add_filter("plugin_action_links_wp-image-borders/wp-image-borders.php", 'bs_wib_settings_link' );

// add menu
function bs_wib_add_page() {
    add_options_page( 
        __('WP Image Borders Options', 'bs-wib'), // Page Title
	    __('WP Image Borders', 'bs-wib'), // Title of Menu
        'manage_options', // capability level
        'wp-image-borders', // Menu slug
        'wp_image_borders_options_content' // callback function
    );
}
add_action('admin_menu', 'bs_wib_add_page');

// creates content in options page and calls settings sections & fields
function wp_image_borders_options_content() {
    ?>
    <div class="wrap">
        <?php screen_icon( 'themes' ); ?>
        <h2><?php __('WP Image Borders Options', 'bs-wib'); ?></h2>
        <form action="options.php" method="post">
            <?php settings_fields( 'wp_image_borders_options' ); ?>
            <?php do_settings_sections( 'wp-image-borders' ); ?>
            <input style="margin: 24px 0;" class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes', 'bs-wib' ); ?>" />
        </form>
        <p><?php
			$link = 'http://wordpress.org/support/view/plugin-reviews/wp-image-borders';
			printf( __('If you liked this plugin, please take <a href="%s">1 minute to leave a review</a>.', 'bs-wib'), $link ); ?>
        </p>
		<a target="_blank" href="https://www.competethemes.com/themes/?utm_source=wp-image-borders&utm_medium=plugin&utm_content=banner-image&utm_campaign=wp-image-borders">
			<img style="margin-top: 48px;" width="800" height="400" src="<?php echo BS_WIB_PRO_URL . 'images/theme-ad.png'; ?>"
		</a>
    </div><?php 
}
?>