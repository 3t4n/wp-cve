<?php

/**
 * Plugin Name:       Robo Maps
 * Plugin URI:        https://robosoft.co/robo-maps
 * Description:       Robo Maps it's simple and really powerful Google maps plugin. You have full set of settings to customize your maps. It’s Mobile optimized fully responsive plugin. You can easily customize styles and settings of every of your map.
 * Version:           1.0.6
 * Author:            RoboSoft (c)
 * Author URI:        https://robosoft.co
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       robo-maps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define(ROBO_MAPS_PATH, plugin_dir_path( __FILE__ ));
define(ROBO_MAPS_URL, plugin_dir_url( __FILE__ ));

define(ROBO_MAPS_VERSION, '1.0.6');

function activate_robo_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-robo-maps-activator.php';
	Robo_Maps_Activator::activate();
}

function deactivate_robo_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-robo-maps-deactivator.php';
	Robo_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_robo_maps' );
register_deactivation_hook( __FILE__, 'deactivate_robo_maps' );

require plugin_dir_path( __FILE__ ) . 'includes/class-robo-maps.php';

add_filter('widget_text', 'do_shortcode');

function run_robo_maps() {
	$plugin = new Robo_Maps();
	$plugin->run();
}

run_robo_maps();