<?php

// n360 default configuration
$n360_defaults = array (
	'enable_n360_ss' => '0',
	'splash_image' => N360_SPLASH_PAGE_ROOT_URL . 'assets/images/sample-logo.png',
	'enable_bg_img' => '0',
	'background_image' => N360_SPLASH_PAGE_ROOT_URL . 'assets/images/sample-bg.jpg',
	'background_type' => 'image', 
	'background_color' => '#fff',
	'timing' => array ( 'delay' => '0.5', 
						'fadein' => '1.0', 
						'sustain' => '2.0', 
						'fadeout' => '1.0',
						'resume' => '0.5' ),
	'cookie_expiration' => 30,
	'run_always' => '0'
);

// this function is called when the plugin is being installed

function n360_activation() {

	$defaults = array (
		'enable_n360_ss' => '0',
		'splash_image' => N360_SPLASH_PAGE_ROOT_URL . 'assets/images/sample-logo.png',
		'enable_bg_img' => '0',
		'background_image' => N360_SPLASH_PAGE_ROOT_URL . 'assets/images/sample-bg.jpg',
		'background_type' => 'image', 
		'background_color' => '#fff',
		'timing' => array ( 'delay' => '0.5', 
							'fadein' => '1.0', 
							'sustain' => '2.0', 
							'fadeout' => '1.0',
							'resume' => '0.5' ),
		'cookie_expiration' => 30,
		'run_always' => '0'
	);
		
	// n360 version & cookie
	$old_version_cookie = get_option( 'n360_version_cookie' );

	$version_cookie = array (
		'version' => N360_SPLASH_PAGE_VERSION,
		'cookie_name' => 'n360_cookie_' . wp_create_nonce( 'n360_cookie_name' )
	);

	$new_version_cookie	= wp_parse_args( $old_version_cookie, $version_cookie );

	update_option( 'n360_version_cookie', $new_version_cookie );

	// If plugin settings don't exist, then create them
	if ( false == get_option( 'n360_config' ) ) {
		add_option( 'n360_config' );
	}
  
	$old_config = get_option( 'n360_config' );
	$new_config = wp_parse_args( $old_config, $defaults );
	delete_option( 'n360_config' );
	update_option( 'n360_config', $new_config );
}

register_activation_hook( N360_SPLASH_PAGE_ROOT_PATH . '/n360-splash-screen.php', 'n360_activation' );

// database cleanup when the plugin is uninstalled ... it's a nice thing to do!
function n360_remove_db_options() {
	delete_option( 'n360_version_cookie' );
	delete_option( 'n360_config' );
}

register_uninstall_hook( N360_SPLASH_PAGE_ROOT_PATH . '/n360-splash-screen.php', 'n360_remove_db_options' );

// we do this during debugging only ...
// register_deactivation_hook( N360_SPLASH_PAGE_ROOT_PATH . '/n360-splash-screen.php', 'n360_remove_db_options' );