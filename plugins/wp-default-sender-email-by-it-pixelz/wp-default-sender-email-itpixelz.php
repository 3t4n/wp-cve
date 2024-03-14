<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://profiles.wordpress.org/itpixelz/
 * @since             1.0.0
 * @package           Wp_Default_Sender_Email_By_It_Pixelz
 *
 * @wordpress-plugin
 * Plugin Name:       Wp Default Sender Email by IT Pixelz
 * Plugin URI:        https://wordpress.org/plugins/wp-default-sender-email-by-it-pixelz/
 * Description:       Elevate your email image: replace default sender email (e.g. wordpress@domain.com) with brand name. Customize sender & from email to avoid spam.
 * Version:           2.1.0
 * Author:            Umar Draz
 * Author URI:        https://profiles.wordpress.org/itpixelz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-default-sender-email-by-it-pixelz
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_VERSION', '2.1.0' );
define( 'WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_OPTIONS_KEY', 'wdsei_itpixelz_options' );
define( 'WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_BASE_FILE', plugin_basename( __FILE__ ) );

if ( ! function_exists( 'wpdse_fs' ) ) {
	// Create a helper function for easy SDK access.
	function wpdse_fs() {
		global $wpdse_fs;

		if ( ! isset( $wpdse_fs ) ) {
			// Include Freemius SDK.
			require_once dirname(__FILE__) . '/freemius/start.php';

			$wpdse_fs = fs_dynamic_init( array(
				'id'                  => '12427',
				'slug'                => 'wp-default-sender-email-by-it-pixelz',
				'type'                => 'plugin',
				'public_key'          => 'pk_59ff53d8fb4dfdc028739cd18eeb3',
				'is_premium'          => false,
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'menu'                => array(
					'slug'           => 'wp-default-sender-email-itpixelz',
					'account'        => false,
					'contact'        => false,
					'support'        => false,
					'parent'         => array(
						'slug' => 'options-general.php',
					),
				),
			) );
		}

		return $wpdse_fs;
	}

	// Init Freemius.
	wpdse_fs();
	// Signal that SDK was initiated.
	do_action( 'wpdse_fs_loaded' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in
 * includes/class-wp-default-sender-email-by-it-pixelz-activator.php
 */
function activate_wp_default_sender_email_by_it_pixelz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-default-sender-email-by-it-pixelz-activator.php';
	Wp_Default_Sender_Email_By_It_Pixelz_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_wp_default_sender_email_by_it_pixelz' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific and common hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-default-sender-email-by-it-pixelz.php';

/**
 * Begins execution of the plugin.
 *
 * @since    2.0.0
 */
function run_wp_default_sender_email_by_it_pixelz() {
	$plugin = new Wp_Default_Sender_Email_By_It_Pixelz();
	$plugin->run();
}

run_wp_default_sender_email_by_it_pixelz();
