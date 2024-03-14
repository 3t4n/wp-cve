<?php

/**
 * @link              https://nysoftwarelab.com
 * @since             1.0.0
 * @package           spam-protect-for-contact-form7
 *
 * @wordpress-plugin
 * Plugin Name:       Spam Protect for Contact Form 7
 * Plugin URI:        https://nysoftwarelab.com/spam-protect-for-contact-form7/
 * Description:       Spam Protect for Contact Form 7
 * Version:           1.1.9
 * Author:            New York Software Lab
 * Author URI:        https://nysoftwarelab.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spam-protect-for-contact-form7
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SPCF7_VERSION', '1.1.9' );

/**
 * The code that runs during plugin activation.
 */
function spcf7_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	Spam_Protect_for_Contact_Form7_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function spcf7_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	Spam_Protect_for_Contact_Form7_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'spcf7_activate' );
register_deactivation_hook( __FILE__, 'spcf7_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blocker.php';

/**
 * Begins execution of the plugin...
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function spcf7_run() {

	$plugin = new Spam_Protect_for_Contact_Form7();
	$plugin->run();

}
spcf7_run();