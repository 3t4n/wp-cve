<?php
/*
Plugin Name: Mailster WordPress Newsletter Plugin Compatibility Tester
Plugin URI: http://mailster.co
Description: This is a compatibility test plugin for the Mailster Newsletter plugin
Version: 2.0.0
Author: EverPress
Author URI: https://everpress.co
License: GPLv2 or later
*/

// only backend
if ( ! is_admin() ) {
	return;
}

define( 'MAILSTER_TESTER_FILE', __FILE__ );

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/tester.class.php';
new MailsterTester();

if ( ! function_exists( 'mailster_freemius' ) ) {
	// Create a helper function for easy SDK access.
	function mailster_freemius() {
		global $mailster_freemius;

		if ( ! isset( $mailster_freemius ) ) {
			// Include Freemius SDK.
			require_once __DIR__ . '/vendor/freemius/wordpress-sdk/start.php';

			if ( ! function_exists( 'wp_create_nonce' ) ) {
				require_once ABSPATH . 'wp-includes/pluggable.php';
			}

			$plugin = 'mailster/mailster.php';

			$consent = get_transient( 'mailster_freemius_install' );

			if ( $consent === 'granted' ) {
				$enabled_license_field = true;
			} else {
				$enabled_license_field = false;
			}

			$args = apply_filters(
				'mailster_freemius_args',
				array(
					'id'                  => 12184,
					'slug'                => 'mailster',
					'public_key'          => 'pk_1efa30140fc34f21e5b89959bb877',
					'is_premium'          => false,
					'is_premium_only'     => $enabled_license_field,
					'has_addons'          => false,
					'has_paid_plans'      => true,
					'has_premium_version' => true,
					'is_org_compliant'    => true,
					'trial'               => array(
						'days'               => 14,
						'is_require_payment' => false,
					),
					'menu'                => array(
						// 'slug'        => 'mailster',
						// 'first-path'  => 'plugins.php?action=activate&plugin=mailster%2Fmailster.php&plugin_status=all&paged=1&s&_wpnonce=' . wp_create_nonce( 'activate-plugin_' . $plugin ),
						'first-path'  => 'admin.php?page=mailster-tester',
						'contact'     => true,
						'support'     => true,
						'pricing'     => true,
						'affiliation' => true,
						'account'     => true,
						'parent'      => array( 'slug' => 'index.php' ),

					),
				)
			);

			$mailster_freemius = fs_dynamic_init( $args );

			set_transient( 'mailster_freemius_install', true, HOUR_IN_SECONDS );

		}

		return $mailster_freemius;
	}
}



// Init Freemius.
mailster_freemius();
// Signal that SDK was initiated.
do_action( 'mailster_freemius_loaded' );

mailster_freemius()->add_action( 'after_init_plugin_anonymous', 'mailster_freemius_after_init_plugin_anonymous' );
mailster_freemius()->add_action( 'after_init_plugin_registered', 'mailster_freemius_after_init_plugin_registered' );
mailster_freemius()->add_action( 'after_account_connection', 'mailster_freemius_after_account_connection' );

function mailster_freemius_after_init_plugin_anonymous() {
	set_transient( 'mailster_freemius_install', true, HOUR_IN_SECONDS );
}
function mailster_freemius_after_init_plugin_registered() {
	set_transient( 'mailster_freemius_install', true, HOUR_IN_SECONDS );
}
function mailster_freemius_after_account_connection() {
	set_transient( 'mailster_freemius_install', 'granted', HOUR_IN_SECONDS );
}

mailster_freemius()->add_filter( 'connect-header', 'mailster_freemius_custom_connect_header_on_update' );
mailster_freemius()->add_filter( 'connect-header_on_update', 'mailster_freemius_custom_connect_header_on_update' );

function mailster_freemius_custom_connect_header_on_update( $header_html ) {
	$user = wp_get_current_user();
	return '<h2>' . sprintf( __( 'Thanks %s for your interrest in Mailster!', 'mailster' ), $user->user_firstname ) . '</h2>';
}


mailster_freemius()->add_filter( 'connect_message', 'mailster_freemius_custom_connect_message_on_update', 10, 6 );
mailster_freemius()->add_filter( 'connect_message_on_update', 'mailster_freemius_custom_connect_message_on_update', 10, 6 );

function mailster_freemius_custom_connect_message_on_update( $message, $user_first_name, $product_title, $user_login, $site_link, $freemius_link ) {
	return sprintf(
		__( 'Please help us improve %2$s!<br>If you opt-in, some data about your usage of %2$s will be sent to %5$s.', 'mailster' ),
		$user_first_name,
		'<b>Mailster</b>',
		'<b>' . $user_login . '</b>',
		$site_link,
		$freemius_link
	);
}

mailster_freemius()->add_filter( 'connect-message_on-premium', 'mailster_freemius_custom_connect_message_on_premium', 10, 3 );

function mailster_freemius_custom_connect_message_on_premium( $message, $user_first_name, $product_title ) {

	return sprintf( __( 'Welcome to the %1$s! To get started, please enter your license key:', 'mailster' ), '<b>Mailster</b>' );
}
