<?php
/**
 * @wordpress-plugin
 * Plugin Name:      	Swiper Js Slider
 * Plugin URI:        	https://github.com/mfarazaly/swiper-js-slider
 * Description:       	Swiper Js Slider/Carousel Simple to use and very effective.
 * Version:           	1.0.1
 * Requires at least: 	5.3
 * Requires PHP:      	7.3
 * Author:            	the speedy team
 * Author URI:         	http://mfarazali.wordpress.com
 * Text Domain:       	swiper-js-slider
 * License:           	GPLv2 or later
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: 	https://github.com/mfarazaly/swiper-js-slider
 */
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !class_exists( 'Swiper_Js_Slider' ) ) {
	class Swiper_Js_Slider
	{
		public $plugin;
		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );

			include_once( plugin_dir_path( __FILE__ ) . 'views/meta-box-options.php' );
			include_once( plugin_dir_path( __FILE__ ) . 'views/front-columns.php' );
			include_once( plugin_dir_path( __FILE__ ) . 'shortcode/slide-js-shortcode.php' );
		}

		function register() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontStyling' ) );
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
			add_action( 'init', array( $this, 'custom_post_type' ) );
		}

		public function settings_link( $links ) {
			$settings_link = '<a href="https://www.getsafepay.com/io/quick-link?ql=link_02a7de15-7e02-4da3-a15f-b08bfc68ab36" target="_blank">Donate Now</a> | ';
			$settings_link .= '<a href="edit.php?post_type=swiper_js_slides">Add Slider</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		function custom_post_type() {
			include_once( plugin_dir_path( __FILE__ ) . 'inc/post-types.php' );
		}

		function enqueue() {
			wp_enqueue_media();
			wp_enqueue_style( 'admincss', plugins_url( '/admin/css/admin.css', __FILE__ ) );
			wp_enqueue_script( 'adminjs', plugins_url( '/admin/js/func-admin.js', __FILE__ ), array('jquery'), '1.0.0', true );
		}

		function frontStyling() {
			wp_enqueue_style( 'swiper-css-library', plugins_url( '/public/css/library.css', __FILE__ ) );
			wp_enqueue_style( 'swiper-css-main', plugins_url( '/public/css/main.css', __FILE__ ) );
			wp_enqueue_script( 'swiper-js-library', plugins_url( '/public/js/library.js', __FILE__ ), array('jquery'), '4.5.0', true );
			wp_enqueue_script( 'swiper-js-main', plugins_url( '/public/js/main.js', __FILE__ ), array('jquery'), '1.0.0', true );
		}

		function activate() {
			flush_rewrite_rules();
		}

		function deactivate() {
			flush_rewrite_rules();
		}
	}
}

$Swiper_Js_Slider = new Swiper_Js_Slider();
$Swiper_Js_Slider->register();

register_activation_hook( __FILE__, array( $Swiper_Js_Slider, 'activate' ) );
register_deactivation_hook( __FILE__, array( $Swiper_Js_Slider, 'deactivate' ));