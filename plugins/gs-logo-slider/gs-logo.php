<?php

/**
 *
 * @package   GS_Logo_Slider
 * @author    GS Plugins <hello@gsplugins.com>
 * @license   GPL-2.0+
 * @link      https://www.gsplugins.com
 * @copyright 2015 GS Plugins
 *
 * @wordpress-plugin
 * Plugin Name:			GS Logo Slider Lite
 * Plugin URI:			https://www.gsplugins.com/wordpress-plugins
 * Description:       	Best Responsive Logo slider to display partners, clients or sponsors Logo on WordPress site. Display anywhere at your site using shortcode like [gslogo id=1] / [gs_logo theme="slider1"] (old style) & widget. Check demo site at <a href="https://logo.gsplugins.com">GS Logo Slider Demo</a> & <a href="https://docs.gsplugins.com/gs-logo-slider">Installation, Documention & Shortcode Usage Guide</a>.
 * Version:           	3.6.5
 * Author:       		GS Plugins
 * Author URI:       	https://www.gsplugins.com
 * Text Domain:       	gslogo
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Defining constants
 */
if ( ! defined( 'GSL_VERSION' ) )
    define( 'GSL_VERSION', '3.6.5' );

if ( ! defined( 'GSL_MIN_PRO_VERSION' ) )
    define( 'GSL_MIN_PRO_VERSION', '3.5.3' );

if ( ! defined( 'GSL_MENU_POSITION' ) )
    define( 'GSL_MENU_POSITION', 33 );

if ( ! defined( 'GSL_PLUGIN_FILE' ) )
    define( 'GSL_PLUGIN_FILE', __FILE__ );

if ( ! defined( 'GSL_PLUGIN_DIR' ) )
    define( 'GSL_PLUGIN_DIR', trailingslashit( plugin_dir_path( GSL_PLUGIN_FILE ) ) );

if ( ! defined( 'GSL_PLUGIN_URI' ) )
    define( 'GSL_PLUGIN_URI', trailingslashit( plugins_url( '', GSL_PLUGIN_FILE ) ) );

if ( ! defined( 'GSL_PRO_PLUGIN' ) )
    define( 'GSL_PRO_PLUGIN', 'gs-logo-slider-pro/gs-logo-slider-pro.php' );


/**
 * Load essential files
 */
require_once GSL_PLUGIN_DIR . 'includes/autoloader.php';
require_once GSL_PLUGIN_DIR . 'includes/functions.php';
require_once GSL_PLUGIN_DIR . 'includes/init.php';