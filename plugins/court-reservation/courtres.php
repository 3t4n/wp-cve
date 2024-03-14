<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.webmuehle.at
 * @since             1.0.5
 * @package           Courtres
 *
 * @wordpress-plugin
 * Plugin Name:       Court Reservation
 * Plugin URI:        https://www.courtreservation.io
 * Description:       Reservation system for tennis, squash and badminton
 * Version:           1.8.6
 * Author:            Webmühle e.U.
 * Author URI:        https://www.webmuehle.at
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       courtres
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Auto deactivation of free when premium is active
if ( function_exists( 'cr_fs' ) ) {
	cr_fs()->set_basename( true, __FILE__ );
	return;
}

// Integration of Freemius SDK
if ( ! function_exists( 'cr_fs' ) ) {
	// Create a helper function for easy SDK access.
	function cr_fs() {
		global $cr_fs;

		if ( ! isset( $cr_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$cr_fs = fs_dynamic_init(
				array(
					'id'                  => '3086',
					'slug'                => 'court-reservation',
					'type'                => 'plugin',
					'public_key'          => 'pk_b5c504d97853f6130b63fd7344155',
					'is_premium'          => true,
					'premium_suffix'      => 'Premium',
					// If your plugin is a serviceware, set this option to false.
					'has_premium_version' => true,
					'has_addons'          => false,
					'has_paid_plans'      => true,
					'menu'                => array(
						'first-path' => 'plugins.php',
						'contact'    => false,
						'support'    => false,
					),
					// Set the SDK to work in a sandbox mode (for development & testing).
					// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
					'secret_key'          => 'sk_GyAY<fJ+WHceE6Qzp{N+RYJk4BH%8',
				)
			);
		}

		return $cr_fs;
	}

	// Init Freemius.
	cr_fs();
	// Signal that SDK was initiated.
	do_action( 'cr_fs_loaded' );
}

// Uninstalling
if ( function_exists( 'cr_fs' ) ) {
	cr_fs()->add_action( 'after_uninstall', 'cr_fs_uninstall_cleanup' );
	function cr_fs_uninstall_cleanup() {
		remove_role( 'player' );

		$role = get_role( 'administrator' );
		$role->remove_cap( 'place_reservation', true );

		// remove tables
		global $wpdb;

		// courts table
		$table_name = $wpdb->prefix . 'courtres_settings';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );

		// reservations table
		$table_name = $wpdb->prefix . 'courtres_reservations';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );

		// events table
		$table_name = $wpdb->prefix . 'courtres_events';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );

		// courts table
		$table_name = $wpdb->prefix . 'courtres_courts';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
	}
}

/**
 * Currently plugin version.
 * Start at version 1.0.4 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'Court_Reservation', '1.8.6' );

require_once plugin_dir_path( __FILE__ ) . 'functions.php';

/**
 * The core plugin class
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/entity/base.php';

/**
 * The core plugin class to work with piramid table
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/entity/piramid.php';
/**
 * The core plugin class to work with piramids-players relations table
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/entity/piramids-players.php';
/**
 * The core plugin class to work with piramid-challenges relations table
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/entity/challenges.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-courtres-activator.php
 */
function activate_courtres() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-courtres-activator.php';
	Courtres_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-courtres-deactivator.php
 */
function deactivate_courtres() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-courtres-deactivator.php';
	Courtres_Deactivator::deactivate();
}

/**
 * The code that runs during plugin unistall.
 * This action is documented in includes/class-courtres-unstaller.php
 */
function uninstall_courtres() {
	 require_once plugin_dir_path( __FILE__ ) . 'includes/class-courtres-uninstaller.php';
	Courtres_Uninstaller::uninstall();
}

register_activation_hook( __FILE__, 'activate_courtres' );
register_deactivation_hook( __FILE__, 'deactivate_courtres' ); // 23.05.2019, astoian - doesnot remove tables on deactivation
register_uninstall_hook( __FILE__, 'uninstall_courtres' ); // 23.05.2019, astoian - remove all tables on uninstall

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-courtres.php';

/**
 * The core plugin class that is base for public and admin classes
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-courtres-base.php';


/**
 * The core plugin class for notifications by emails
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-courtres-notices.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.5
 */
function run_courtres() {
	$plugin = new Courtres();
	$plugin->run();
}
run_courtres();
