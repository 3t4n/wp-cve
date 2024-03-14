<?php
/**

 * @package Disable User Password Reset Admin Notifications
 
 */

/*

Plugin Name: Disable User Password Reset Admin Notifications
Plugin URI: https://mindspikedesign.com/
Description: Disable admin email notifications when a user changes their password. Simply activate the plugin and you will no longer receive a email notification when a user resets their password.
Version: 1.7
Author: Chris Cook
Author URI: https://chris-cook.net/
Tested up to: 6.4
License: GPL v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: disable-user-password-reset-emails

*/

if ( ! function_exists( 'wp_password_change_notification' ) ) {
    function wp_password_change_notification( $user ) {
        return;
    }
}


/**
 * Get the current time and set it as an option when the plugin is activated.
 *
 * @return null
 */
function winwar_set_activation_date() {

	$now = strtotime( "now" );
	add_option( 'myplugin_activation_date', $now );

}
register_activation_hook( __FILE__, 'winwar_set_activation_date' );



/**
 * Check date on admin initiation and add to admin notice if it was over 10 days ago.
 *
 * @return null
 */
function winwar_check_installation_date() {

	// Added Lines Start
	$nobug = "";
	$nobug = get_option('winwar_no_bug');

	if (!$nobug) {
	// Added Lines End

		$install_date = get_option( 'myplugin_activation_date' );
		$past_date    = strtotime( '+7 days' );

		if ( $past_date >= $install_date ) {

			add_action( 'admin_notices', 'winwar_display_admin_notice' );

		}

	// Added Lines Start
	}
	// Added Lines End
}
add_action( 'admin_init', 'winwar_check_installation_date' );



/**
 * Display Admin Notice, asking for a review
 *
 * @return null
 */
function winwar_display_admin_notice() {

	// Review URL - Change to the URL of your plugin on WordPress.org
	$reviewurl = 'https://wordpress.org/support/plugin/disable-user-password-reset-emails/reviews/';

	$nobugurl = get_admin_url() . '?winwarnobug=1';

	echo '<div class="updated"><p>';
	printf( __( "You have been using the <strong>Disable User Password Reset Admin Notifications</strong> plugin for a week now, do you like it? If so, please leave us a review with your feedback! <br /><br /> <a href='%s' target='_blank'>Leave A Review</a>/<a href='%s'>Leave Me Alone</a>" ), $reviewurl, $nobugurl );
	echo "</p></div>";
}




/**
 * Set the plugin to no longer bug users if user asks not to be.
 *
 * @return null
 */
function winwar_set_no_bug() {

	$nobug = "";

	if ( isset( $_GET['winwarnobug'] ) ) {
		$nobug = esc_attr( $_GET['winwarnobug'] );
	}

	if ( 1 == $nobug ) {

		add_option( 'winwar_no_bug', TRUE );

	}

} add_action( 'admin_init', 'winwar_set_no_bug', 5 );

?>