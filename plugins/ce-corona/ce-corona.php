<?php
/**
 * Plugin Name:     COVID19 - Coronavirus Outbreak Data
 * Plugin URI:      https://coderexpert.com/corona
 * Description:     Realtime covid19 outbreak data checker and Awareness Plugin coronavirus.
 * Author:          Priyo Mukul
 * Author URI:      https://mukul.me
 * Text Domain:     ce-corona
 * Domain Path:     /languages
 * Version:         0.7.0
 *
 * @package         Corona
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Defines Constants for Future use
 */
define( 'CE_CORONA_FILE', __FILE__ );
define( 'CE_CORONA_VERSION', '0.7.0' );
define( 'CE_CORONA_URL', plugin_dir_url( CE_CORONA_FILE ) );
define( 'CE_CORONA_PATH', plugin_dir_path( CE_CORONA_FILE ) );
define( 'CE_CORONA_ASSETS', CE_CORONA_URL . 'assets/' );
define( 'CE_CORONA_CLASSMAP', CE_CORONA_PATH . 'classes/classmaps.php' );
/**
 * Initiate Autoloader for Class LoadD
 */
if( ! class_exists( 'CoderExpert\Corona\Autoloader' ) ) {
    require CE_CORONA_PATH . 'classes/autoloader.php';
    CoderExpert\Corona\Autoloader::init();
}

if ( class_exists( 'CoderExpert\Corona\Plugin' ) ) {
    CoderExpert\Corona\Plugin::get_instance();
}
