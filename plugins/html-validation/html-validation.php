<?php
/**
 * Plugin Name: HTML Validation
 * Description:  The HTML Validation Plugin runs in the background, identifies and report HTML validation errors on your website. Once activated, the HTML Validation plugin uses WordPress cron to scan your website content in the background. A progress bar on the report screen indicates scan progress. HTML Validation is provided by Validator.nu. Please refer to the provided privacy policy and terms of use. Posts may also be scanned using the Validate HTML link provided on the "All Posts" screen.
 * Version: 1.0.13
 * Plugin URI: https://wordpress.org/plugins/html-validation
 * Author: AlumniOnline Web Services LLC
 * Author URI: https://www.alumnionlineservices.com
 * Text Domain: html-validation
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// add other files.
require plugin_dir_path( __FILE__ ) . 'res/reports.php';
require plugin_dir_path( __FILE__ ) . 'res/installation.php';
require plugin_dir_path( __FILE__ ) . 'res/cron.php';
require plugin_dir_path( __FILE__ ) . 'res/scan.php';
require plugin_dir_path( __FILE__ ) . 'res/html_validation.php';
require plugin_dir_path( __FILE__ ) . 'res/settings.php';
require plugin_dir_path( __FILE__ ) . 'res/purge.php';


/**
 * PLUGIN INSTALLATION
 */
register_activation_hook( __FILE__, 'html_validation_install' );
register_uninstall_hook( __FILE__, 'html_validation_uninstall' );
register_deactivation_hook( __FILE__, 'html_validation_deactivate' );


/**
 * Include css and scripts for admin features
 **/
function html_validation_scripts() {

	if ( is_admin() ) {

		// tabs for admin page.
		wp_enqueue_script( 'jquery-ui-tabs' );

		wp_register_style( 'html-validation-styles', plugin_dir_url( __FILE__ ) . 'styles.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'styles.css' ) );
		wp_enqueue_style( 'html-validation-styles' );

		// font awesome.
		wp_register_style( 'html-validation-font-awesome-styles', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css', array(), '' );
		wp_enqueue_style( 'html-validation-font-awesome-styles' );

		// scripts to use jquery to ignore.
		wp_register_script( 'html-validation-script', plugin_dir_url( __FILE__ ) . 'scripts.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'scripts.js' ) );
		wp_localize_script(
			'html-validation-script',
			'htmlvalidateVariables',
			array(
				'resturl'     => esc_url_raw( get_rest_url() ),
				'wait'        => __( '<i class="fas fa-spinner" aria-hidden="true"></i> Please wait while we refresh the results.', 'html-validation' ),
				'refresh'     => __( 'Results were refreshed.', 'html-validation' ),
				'recheck'     => __( 'Recheck complete.', 'html-validation' ),
				'ignore'      => __( 'Error ignored.', 'html-validation' ),
				'ignoreX'     => __( 'Error is no longer ignored.', 'html-validation' ),
				'ignoreLink'  => __( 'Link ignored.', 'html-validation' ),
				'ignoreDups'  => __( 'All error like this have been ignored.', 'html-validation' ),
				'ignoreDupsX' => __( 'Errors like this are no longer being ignored.', 'html-validation' ),
				'nonce'       => wp_create_nonce( 'wp_rest' ),
			)
		);
		wp_enqueue_script( 'html-validation-script' );

	}
}
add_action( 'admin_enqueue_scripts', 'html_validation_scripts' );

/**
 * Returns current plugin info.
 **/
function html_validation_plugin_get( $i ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugin_folder = get_plugins( '/' . plugin_basename( __DIR__ ) );
	$plugin_file   = basename( ( __FILE__ ) );
	return $plugin_folder[ $plugin_file ][ $i ];
}
