<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.billbee.de
 * @since             1.0.0
 * @package           Billbee
 *
 * @wordpress-plugin
 * Plugin Name:       Billbee - Auftragsabwicklung, Warenwirtschaft, Automatisierung
 * Plugin URI:        https://www.billbee.de
 * Description:       Dies ist ein optionales Plugin, um WordPress bzw. WooCommerce gemeinsam mit Billbee einzusetzen.
 * Version:           1.0.0
 * Author:            Billbee GmbH
 * Author URI:        https://www.billbee.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       billbee
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-billbee-activator.php
 */
function activate_billbee() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-billbee-activator.php';
	Billbee_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-billbee-deactivator.php
 */
function deactivate_billbee() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-billbee-deactivator.php';
	Billbee_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_billbee' );
register_deactivation_hook( __FILE__, 'deactivate_billbee' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-billbee.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_billbee() {

	$plugin = new Billbee();
	$plugin->run();

}

/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );
 
/** Step 1. */
function my_plugin_menu() {
	add_options_page( 'Billbee', 'Billbee', 'manage_options', 'billbee', 'billbee_menu_content' );
}
 
/** Step 3. */
function billbee_menu_content() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h1>Billbee - Auftragsabwicklung, Warenwirtschaft, Automatisierung</h1>';
	echo '<p>Billbee bietet eine umfangreiche, aber einfach zu bedienende Auftragsabwicklung, Warenwirtschaft und Automatisierungsl&ouml;sung f&uuml;r Verk&auml;ufer, die Produkte &uuml;ber einen oder mehrere (Online)-Kan&auml;le verkaufen.  Dabei k&ouml;nnen alle relevanten Prozesse im Handelsumfeld, angefangen bei der Kundenkommunikation bis hin zum Versand und After-Sales, durch Billbee bzw. direkt angebundene und integrierte Partner abgebildet werden. Ein starker Fokus liegt auch darauf, die L&ouml;sung sowohl von der Komplexit&auml;t als auch vom Preis her besonders f&uuml;r kleinere Unternehmen attraktiv zu machen, ohne dass dabei eine f&uuml;r gr&ouml;&szlig;ere Unternehmen notwendige Skalierungsf&auml;higkeit auf der Strecke bleibt. Erg&auml;nzt wird das Angebot um weitere, optionale Zusatzmodule sowie die M&ouml;glichkeit, Premium-Support mit garantierten Reaktionszeiten zu buchen.</p>';
	echo '<p><b>Wie du Billbee mit deinem WooCommerce Shop verbindest, siehst du im folgenden Video:</b></p>';
	echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/_9szAmKmIDE?controls=0" frameborder="0" allowfullscreen></iframe>';
	echo '</div>';
}

run_billbee();
