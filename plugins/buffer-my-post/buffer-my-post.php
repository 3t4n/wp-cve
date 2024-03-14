<?php
/*
#     Plugin Name: Buffer - HYPESocial
#     Plugin URI: http://www.hypestudio.org/
#     Description: Get more social traffic by easily connecting your WordPress to Buffer, and automatically sharing posts & pages to Twitter Facebook, LinkedIn, Google+, Instagram and Pinterest
#
#     Author: hypestudio,dejanmarkovic,nytogroup,freemius
#     Version: 2020.1.0
#     Author URI: http://www.hypestudio.org/
#     */

// Create a helper function for easy SDK access.

function hsb_fs() {
	global $hsb_fs;

	if ( ! isset( $hsb_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/freemius/start.php';

		$hsb_fs = fs_dynamic_init( array(
			'id'                => '133',
			'slug'              => 'buffer-my-post',
			'public_key'        => 'pk_d8db8d28d363893018e772b3b3a67',
			'is_premium'        => false,
			'has_addons'        => false,
			'has_paid_plans'    => false,
			'menu'              => array(
				'slug'       => 'HYPESocialBuffer',
				'account'    => false /*,

				'support'    => false,
				'contact'    => false,
				*/
			),
		) );
	}

	return $hsb_fs;
}

// Init Freemius.
hsb_fs();

// Signal that SDK was initiated.
do_action( 'hsb_fs_loaded' );

// Customize msg
function hsb_custom_connect_message(
	$message,
	$user_first_name,
	$plugin_title,
	$user_login,
	$site_link,
	$freemius_link
) {
	return sprintf(
		fs_text( 'hey-x' ) . '<br>' .
		__( 'In order to enjoy all our features and functionality, %s needs to connect your user, %s at %s, to %s', 'freemius' ),
		$user_first_name,
		'<b>' . $plugin_title . '</b>',
		'<b>' . $user_login . '</b>',
		$site_link,
		$freemius_link
	);
}

hsb_fs()->add_filter('connect_message', 'hsb_custom_connect_message', 10, 6);

// Handle uninstall
function hsb_fs_uninstall_cleanup() {
	delete_option( 'hsb_settings' );
	delete_option( 'hsb_opt_admin_url' );
	delete_option( 'hsb_opt_last_update' );
	delete_option( 'hsb_opt_omit_cats' );
	delete_option( 'hsb_opt_omit_custom_cats' );
	delete_option( 'hsb_opt_omit_cust_cats' );
	delete_option( 'hsb_opt_max_age_limit' );
	delete_option( 'hsb_opt_age_limit' );
	delete_option( 'hsb_opt_excluded_post' );
	delete_option( 'hsb_opt_post_type' );
	delete_option( 'hsb_opt_no_of_post' );
	delete_option( 'hsb_opt_posted_posts' );
	delete_option( 'hsb_opt_add_text' );
	delete_option( 'hsb_opt_add_text_at' );
	delete_option( 'hsb_opt_include_link' );
	delete_option( 'hsb_opt_interval' );
	delete_option( 'hsb_settings' );
	delete_option( 'hsb_enable_log' );
	delete_option( 'hsb_disable_buffer' );
	delete_option( 'hsb_opt_access_token' );
	delete_option( 'hsb_opt_post_format' );
	delete_option( 'hsb_opt_acnt_id' );
}

hsb_fs()->add_action('after_uninstall', 'hsb_fs_uninstall_cleanup');

define( 'hsb_opt_1_HOUR', 60 * 60 );
define( 'hsb_opt_2_HOURS', 2 * hsb_opt_1_HOUR );
define( 'hsb_opt_4_HOURS', 4 * hsb_opt_1_HOUR );
define( 'hsb_opt_8_HOURS', 8 * hsb_opt_1_HOUR );
define( 'hsb_opt_6_HOURS', 6 * hsb_opt_1_HOUR );
define( 'hsb_opt_12_HOURS', 12 * hsb_opt_1_HOUR );
define( 'hsb_opt_24_HOURS', 24 * hsb_opt_1_HOUR );
define( 'hsb_opt_48_HOURS', 48 * hsb_opt_1_HOUR );
define( 'hsb_opt_72_HOURS', 72 * hsb_opt_1_HOUR );
define( 'hsb_opt_168_HOURS', 168 * hsb_opt_1_HOUR );
define( 'hsb_opt_INTERVAL', 4 );
define( 'hsb_opt_AGE_LIMIT', 30 ); // 120 days
define( 'hsb_opt_MAX_AGE_LIMIT', 60 ); // 120 days
define( 'hsb_opt_OMIT_CATS', "" );
define( 'hsb_opt_OMIT_CUSTOM_CATS', "" );
define( 'hsb_opt_HASHTAGS', "" );
define( 'hsb_opt_no_of_post', "1" );
define( 'hsb_opt_post_type', "post" );
define( 'hsb_opt_number_repeats', 10 );  //set this as option in settings?
define( 'hsb_opt_purchase_code', '' );
define( 'hsb_opt_envato_user_name', '' );

require_once( 'hsb-core.php' );
require_once( 'hsb-admin.php' );

require_once dirname( __FILE__ ) . '/includes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'hsb_register_required_plugins1' );

function hsb_register_required_plugins1() {
	$plugins = array (
		array(
			'name' => __( 'Social Web Suite - Social Media Auto Post, Auto Publish and Schedule', 'topcat-lite' ),
			'slug' => 'social-web-suite',
			'required' => false,
		),
	);

	$config = array (
		'id' => 'buffer-my-post',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',

		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'buffer-my-post' ),
			'menu_title'                      => __( 'Install Plugins', 'buffer-my-post' ),

			'installing'                      => __( 'Installing Plugin: %s', 'buffer-my-post' ),

			'updating'                        => __( 'Updating Plugin: %s', 'buffer-my-post' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'buffer-my-post' ),
			'notice_can_install_required'     => _n_noop(
				'This plugin requires the following plugin: %1$s.',
				'This plugin requires the following plugins: %1$s.',
				'buffer-my-post'
			),
			'notice_can_install_recommended'  => _n_noop(
				'This plugin recommends the following plugin: %1$s.',
				'This plugin recommends the following plugins: %1$s.',
				'buffer-my-post'
			),
			'notice_ask_to_update'            => _n_noop(
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'buffer-my-post'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'buffer-my-post'
			),
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'buffer-my-post'
			),
			'notice_can_activate_recommended' => _n_noop(
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'buffer-my-post'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'buffer-my-post'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'buffer-my-post'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'buffer-my-post'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'buffer-my-post' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'buffer-my-post' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'buffer-my-post' ),
		),

	);
	tgmpa( $plugins, $config );
}
