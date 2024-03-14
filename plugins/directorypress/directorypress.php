<?php
/**
 * Plugin Name:       DirectoryPress
 * Plugin URI:        https://designinvento.net/downloads/directorypress-wordpress-directory-plugin/
 * Description:       DirectoryPress offers ultimate functionality for business directory listing, classified ads listing. Real estate, pets directory, yellow pages, automobile or any kind of directory. 
 * Version:           3.6.7
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       DIRECTORYPRESS
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// ==================
// = Plugin Version =
// ==================

define( 'DIRECTORYPRESS_VERSION', '3.6.7' );

$DIRECTORYPRESS_ADIMN_SETTINGS = get_option('directorypress_admin_settings');

function activate_directorypress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-activator.php';
	DirectoryPress_Activator::activate();
}

function deactivate_directorypress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-directorypress-deactivator.php';
	DirectoryPress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_directorypress' );
register_deactivation_hook( __FILE__, 'deactivate_directorypress' );

require plugin_dir_path( __FILE__ ) . 'includes/class-directorypress.php';

// initiate

$directorypress_object = new DirectoryPress();
$directorypress_object->run();

$DIRECTORYPRESS_ADIMN_SETTINGS = get_option('directorypress_admin_settings');
global $DIRECTORYPRESS_ADIMN_SETTINGS;

if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']){
	include_once DIRECTORYPRESS_PATH . 'reviews/reviews.php';
}