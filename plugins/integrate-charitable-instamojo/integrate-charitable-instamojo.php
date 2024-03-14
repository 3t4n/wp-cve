<?php
/**
 * Plugin Name: 		Charitable - Instamojo Payment Gateway
 * Plugin URI: 			http://go.thearrangers.xyz/instamojo
 * Description: 		Collect donations in INR via Debit Cards, Credit Cards, Net Banking, UPI, Wallets, EMI by integrating Instamojo Indian Payment Gateway.
 * Version: 			1.1.0
 * Author: 				GautamMKGarg
 * Author URI: 			https://x.com/GautamMKGarg
 * Requires at least: 	4.9
 * Tested up to: 		6.4
 *
 * Text Domain: 		charitable-instamojo
 * Domain Path: 		/languages/
 *
 * @package 			Integrate Charitable Instamojo
 * @category 			Core
 * @author 				GautamMKGarg
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 * @return 	void
 * @since 	1.0.0
 */
function charitable_instamojo_load() {	
	require_once( 'includes/class-charitable-instamojo.php' );

	$has_dependencies = true;

	/* Check for Charitable */
	if ( ! class_exists( 'Charitable' ) ) {

		if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {

			require_once 'includes/class-charitable-extension-activation.php';

		}

		$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

		$has_dependencies = false;
	} 
	else {

		new Charitable_Instamojo( __FILE__ );

	}	
}

add_action( 'plugins_loaded', 'charitable_instamojo_load', 1 );