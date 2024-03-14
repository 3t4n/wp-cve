<?php 
/**
 * Plugin Name:       	Spinner Fix Stop Spinning for Contact Form 7
 * Plugin URI: 			
 * Description:       	Fixes the CF7 infinite spinning problem.
 * Version: 			1.0
 * Author: 				Allround Web
 * Author URI: 			https://www.allroundweb.nl
 * Domain Path: 		/languages
 * License: 			GPLv2 or later
**/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue Theme Scripts and Styles
 *
 * @link https://gist.github.com/bryanwillis/7fd5356a9d18d0c7815f
 */
function awcf7_add_custom_js_script()  {
	wp_enqueue_script('awcf7-stop-spinning-js', plugin_dir_url( __FILE__ ).'/awcf7-stop-spinning.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'awcf7_add_custom_js_script');

/**
 * Create support page
 */
function awcf7_create_menu() {
	add_menu_page('CF7 Stop spinning', 'Allround Web - CF7 Stop spinning', 'administrator', __FILE__, 'awcf7_stop_spinning_support_page', plugin_dir_url( __FILE__ ).'/assets/img/aw-favicon.png' );
}
add_action('admin_menu', 'awcf7_create_menu');

/**
 * Support page content
 */
function awcf7_stop_spinning_support_page() {
	?>
		<div class="wrap" style="background: #fff; padding: 25px; border: 2px solid #dedede;">
			<h1>Allround Web Contact Form 7 - Stop Spinning</h1>
			<p><?php _e( 'Do you need any help? We are here to help! </br>Simply send us an email: <a href="mailto:info@allroundweb.nl">info@allroundweb.nl</a>', 'awcf7-stop-spinning' ); ?></p>
		</div>
	<?php 
}

/**
 * Deactivate the plugin.
 */
function awcf7_stop_spinning_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'awcf7_stop_spinning_deactivate' );

/**
 * Uninstall the plugin.
 */
function awcf7_stop_spinning_uninstall() {
	remove_menu_page( 'CF7 Stop spinning' );
    flush_rewrite_rules();
}
register_uninstall_hook( __FILE__, 'awcf7_stop_spinning_uninstall' );