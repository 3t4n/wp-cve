<?php
/*
Plugin Name: Responsive Portfolio Image Gallery
Plugin URI: http://wordpress.org/plugins/responsive-portfolio-image-gallery/
Description: Responsive image gallery plugin to build powerful, lightweight, filterable portfolio galleries on different posts or pages by SHORTCODE.
Version: 1.2
Author: Realwebcare
Author URI: https://www.realwebcare.com/
Text Domain: rcpig
Domain Path: /languages/
*/

/*  Copyright 2023  Realwebcare  (email : realwebcare@gmail.com)

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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define('RCPIG_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

/* Internationalization */
function rcpig_textdomain() {
	$domain = 'rcpig';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'rcpig_textdomain' );

/* Add plugin action links */
function rcpig_plugin_actions( $links ) {
	$links[] = '<a href="'.menu_page_url('rcpig-settings', false).'">'. __('Settings','rcpig') .'</a>';
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'rcpig_plugin_actions' );

/* Enqueue front js and css files */
function rcpig_scripts() {
	$rcpig_enable = rcpig_get_option( 'rcpig_enable_portfolio_', 'rcpig_general', 'show' );
	$rcpig_css_style = rcpig_get_option( 'rcpig_css_style_', 'rcpig_style', 'light' );
	if($rcpig_enable == 'show') {
		wp_register_script('rcpig-modernizr', plugins_url( '/assets/js/modernizr.custom.js', __FILE__ ), array('jquery'), '2.6.2', false);
		wp_register_script('rcpig-grid-js', plugins_url( '/assets/js/rcpig_grid.min.js', __FILE__ ), array('jquery'), '1.2', false);
		wp_enqueue_script('rcpig-modernizr');
		wp_enqueue_script('rcpig-grid-js');
		if($rcpig_css_style == 'light') {
			wp_enqueue_style('rcpig-grid-light', plugins_url('/assets/css/rcpig_grid_light.css', __FILE__),'','1.2');
		} else {
			wp_enqueue_style('rcpig-grid-dark', plugins_url('/assets/css/rcpig_grid_dark.css', __FILE__),'','1.2');
		}
	}
}
add_action( 'wp_enqueue_scripts', 'rcpig_scripts' );

/* Adding Custom styles */
add_action( 'wp_head', 'rcpig_custom_style' );
function rcpig_custom_style() {
	$custom_css = get_option( 'custom_css' );
	$rcpig_custom_style = rcpig_get_option( 'rcpig_custom_css_', 'rcpig_style', '' );
	if ( isset($rcpig_custom_style) && !empty( $rcpig_custom_style ) ) { ?>
<style type="text/css">
<?php echo $rcpig_custom_style; ?>
</style><?php
	}
}

/* Enqueue CSS & JS For Admin */
function rcpig_admin_adding_style() {
	wp_register_script( 'rcpig-admin', plugin_dir_url( __FILE__ ) . '/assets/js/rcpig-admin.js', array( 'jquery' ), '1.2' );
	wp_enqueue_script( 'rcpig-admin' );
	wp_enqueue_style( 'rcpig_admin_style', plugins_url('/assets/css/rcpig-admin.css', __FILE__),'','1.2', false );
}
add_action( 'admin_enqueue_scripts', 'rcpig_admin_adding_style', 11 );

/* Custom Excerpts */
function rcpig_excerptlength($length) {
	$rcpig_excerpt = rcpig_get_option( 'rcpig_excerpt_length_', 'rcpig_general', 30 );
	return $rcpig_excerpt;
}
function rcpig_excerpt_more($more) {
	global $post;
	return ' [...]';
}
function rcpig_excerpt($length_callback='', $more_callback='') {
	global $post;
	if(function_exists($length_callback)){
		add_filter('excerpt_length', $length_callback);
	}
	if(function_exists($more_callback)){
		add_filter('excerpt_more', $more_callback);
	}
	$output = get_the_excerpt();
	$output = apply_filters('wptexturize', $output);
	$output = apply_filters('convert_chars', $output);
	return $output;
}

require_once dirname( __FILE__ ) . '/rcpig-shortcode.php';
require_once dirname( __FILE__ ) . '/custom-post/rcpig-post.php';
require_once dirname( __FILE__ ) . '/class/rcpig_aq_resizer.php';
require_once dirname( __FILE__ ) . '/inc/rcpig-admin.php';
require_once dirname( __FILE__ ) . '/class/rcpig-class.settings-api.php';
require_once dirname( __FILE__ ) . '/inc/rcpig-settings.php';
require_once dirname( __FILE__ ) . '/custom-post/rcpig_metabox.php';
?>