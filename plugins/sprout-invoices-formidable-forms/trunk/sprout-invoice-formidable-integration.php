<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

/*
 * Plugin Name: Sprout Invoices + Formidable Forms
 * Plugin URI: https://sproutapps.co/sprout-invoices/integrations/
 * Description: Allows for a form submitted by Formidable Forms to create all necessary records to send your client an invoice or estimate.
 * Author: Sprout Apps
 * Version: 1.3
 * Author URI: https://sproutapps.co
 * Text Domain: sprout-invoices
 * Domain Path: languages
 */

/**
 * Plugin Info for updates
 */
define( 'SA_ADDON_INVOICE_SUBMISSIONS_FILE', __FILE__ );
define( 'SA_ADDON_INVOICE_SUBMISSIONS_URL', plugins_url( '', __FILE__ ) );

if ( ! function_exists( 'sa_load_formidable_integration_addon' ) ) {

	// Load up after SI is loaded.
	add_action( 'sprout_invoices_loaded', 'sa_load_formidable_integration_addon' );
	function sa_load_formidable_integration_addon() {
		require_once( 'inc/Formidable_Submission.php' );

		if ( function_exists( 'frm_forms_autoloader' ) ) {
			require_once( 'inc/Formidable.php' );
		}
	}
}
