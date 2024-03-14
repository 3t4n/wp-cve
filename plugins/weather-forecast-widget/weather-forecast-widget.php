<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.bergtourentipp-tirol.at
 * @since             1.0.0
 * @package           Weather_Forecast_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       Weather Forecast Widget
 * Plugin URI:        https://www.bergtourentipp-tirol.at
 * Description:       The plugin "Weather Forecast Widget" shows a widget with current weather and hourly/daily forecast weather data, implemented with the help of a shortcode.
 * Version:           1.1.5
 * Author:            Dominik Luger
 * Author URI:        https://www.bergtourentipp-tirol.at
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weather-forecast-widget
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WEATHER_FORECAST_WIDGET_VERSION', '1.1.5' );
define( 'WEATHER_FORECAST_WIDGET_BASE_URL', plugin_dir_url( __FILE__ ) );
define('PATH_ANIMATED_ICONS_PUBLIC_PARTIALS', WEATHER_FORECAST_WIDGET_BASE_URL . 'public/partials/');
define('PATH_ANIMATED_ICONS_FILLED', WEATHER_FORECAST_WIDGET_BASE_URL . 'public/partials/weather-icons-master/production/fill/openweathermap/');
define('PATH_ANIMATED_ICONS_NOT_FILLED', WEATHER_FORECAST_WIDGET_BASE_URL . 'public/partials/weather-icons-master/production/line/openweathermap/');
define('PATH_ANIMATED_ICONS_FILLED_ALL', WEATHER_FORECAST_WIDGET_BASE_URL . 'public/partials/weather-icons-master/production/fill/all/');
define('PATH_ANIMATED_ICONS_NOT_FILLED_ALL', WEATHER_FORECAST_WIDGET_BASE_URL . 'public/partials/weather-icons-master/production/line/all/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-zwfz-weather-forecast-activator.php
 */
function activate_weather_forecast_widget() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-weather-forecast-widget-activator.php';
	Weather_Forecast_Widget_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-zwfz-weather-forecast-deactivator.php
 */
function deactivate_weather_forecast_widget() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-weather-forecast-widget-deactivator.php';
	Weather_Forecast_Widget_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_weather_forecast_widget' );
register_deactivation_hook( __FILE__, 'deactivate_weather_forecast_widget' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-weather-forecast-widget.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_weather_forecast_widget() {

	$plugin = new weather_forecast_widget();
	$plugin->run();

}
run_weather_forecast_widget();
