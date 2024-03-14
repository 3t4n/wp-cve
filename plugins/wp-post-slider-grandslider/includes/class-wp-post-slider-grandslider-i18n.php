<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://forhad.net/
 * @since      1.0.0
 *
 * @package    Wp_Post_Slider_Grandslider
 * @subpackage Wp_Post_Slider_Grandslider/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Post_Slider_Grandslider
 * @subpackage Wp_Post_Slider_Grandslider/includes
 * @author     Forhad <need@forhad.net>
 */
class Wp_Post_Slider_Grandslider_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-post-slider-grandslider',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
