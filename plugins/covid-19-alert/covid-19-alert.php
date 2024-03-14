<?php
/**
 * Plugin Name:       COVID-19 Updates
 * Plugin URI:        https://www.devignstudios.co.uk/resource/covid-19-update-wordpress-plugin
 * Description:       Update your customers with your businesses guidelines and policies around the COVID-19 outbreak.
 * Version:           1.5.1
 * Author:            Devign Studios Ltd.
 * Author URI:        https://www.devignstudios.co.uk/resource/covid-19-update-wordpress-plugin
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       devign-covid-nineteen
 * Domain Path:       /languages
 */


/**
 * Plugin Constants
 */
define( 'DEVIGN_COVID_19_PLUGIN', __FILE__ );
define( 'DEVIGN_COVID_19_BASENAME', plugin_basename( DEVIGN_COVID_19_PLUGIN ) );
define( 'DEVIGN_COVID_19_PLUGIN_DIR', untrailingslashit( dirname( DEVIGN_COVID_19_PLUGIN ) ) );
define( 'DEVIGN_COVID_19_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );

require_once DEVIGN_COVID_19_PLUGIN_DIR.'/inc/activate.php';
require_once DEVIGN_COVID_19_PLUGIN_DIR.'/inc/wp-enqueue.php';
require_once DEVIGN_COVID_19_PLUGIN_DIR.'/inc/wp-settings.php';
require_once DEVIGN_COVID_19_PLUGIN_DIR.'/inc/actions-filters.php';