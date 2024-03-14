<?php
/*
 * Plugin Name:       Social Proof (Testimonials) Slider
 * Plugin URI:        https://wordpress.org/plugins/social-proof-testimonials-slider/
 * Description:       Showcase a carousel slider of testimonials on your WordPress website! Use the included shortcode or widget. This plugin adds a new Custom Post Type called "Testimonials," and includes a Settings screen to control the display options. Created by brandiD.
 * Version:           2.2.4
 * Author:            brandiD
 * Author URI:        https://thebrandiD.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Used for referring to the plugin file or basename
if ( ! defined( 'SOCIAL_PROOF_SLIDER_FILE' ) ) {
	define( 'SOCIAL_PROOF_SLIDER_FILE', plugin_basename( __FILE__ ) );
}

// Define Constants
define( 'SPSLIDER_PLUGIN_VERSION', '2.2.4');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-social-proof-slider-activator.php
 */
function activate_social_proof_slider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-proof-slider-activator.php';
	Social_Proof_Slider_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-social-proof-slider-deactivator.php
 */
function deactivate_social_proof_slider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-proof-slider-deactivator.php';
	Social_Proof_Slider_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_social_proof_slider' );
register_deactivation_hook( __FILE__, 'deactivate_social_proof_slider' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-social-proof-slider.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_social_proof_slider() {

	$plugin = new Social_Proof_Slider();
	$plugin->run();

}
run_social_proof_slider();
