<?php

/**
 * Plugin Name: Voltax Video Player
 * Description: Integrates the Voltax Online Video Platform (OVP) into your WordPress instance.
 * Version: 1.6.4
 * Author: the Minute Media Team
 *
 * @package mm-video
 */

defined( 'ABSPATH' ) || exit;
define( 'WP_MM_VIDEOS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once('autoload.php');
require_once('src/WPP/class-logger.php');

\MinuteMedia\Ovp\Plugin::init();

/**
 * This needs to be outside of the class context to work properly
 */
register_deactivation_hook( __FILE__, array( '\MinuteMedia\Ovp\Plugin', 'pluginDeactivate' ) );
