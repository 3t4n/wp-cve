<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.bergtourentipp-tirol.at
 * @since      1.0.0
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/includes
 * @author     Dominik Luger <admin@bergtourentipp-tirol.at>
 */
class Weather_Forecast_Widget_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'weather-forecast-widget',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
