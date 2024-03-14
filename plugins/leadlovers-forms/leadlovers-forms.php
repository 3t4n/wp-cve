<?php
/*
Plugin Name: leadlovers forms
Description: Integre qualquer formulário do seu site com o leadlovers de forma simples e intuitiva.
Version: 1.0.2
Author: leadlovers
Author URI: http://www.leadlovers.com
*/
defined( 'ABSPATH' ) or die( );

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_leadlovers_plugin() {
	LeadloversInc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_leadlovers_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_leadlovers_plugin() {
	LeadloversInc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_leadlovers_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'LeadloversInc\\Init' ) ) {
	LeadloversInc\Init::register_services();
}
