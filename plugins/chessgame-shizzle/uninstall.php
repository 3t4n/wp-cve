<?php
/*
 * This file will be called when pressing 'Delete' on Dashboard > Plugins.
 */


// if uninstall.php is not called by WordPress, die.
if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
	die();
}

$option_names = array(
		'chessgame_shizzle-boardtheme',
		'chessgame_shizzle-piecetheme',
		'chessgame_shizzle-honeypot',
		'chessgame_shizzle-honeypot_value',
		'chessgame_shizzle-nonce',
		'chessgame_shizzle-timeout',
		'chessgame_shizzle-notifybymail',
		'chessgame_shizzle-mail-from',
		'chessgame_shizzle-rss',
		'chessgame_shizzle-simple-list-search',
		'chessgame_shizzle-version',
	);

foreach ( $option_names as $option_name ) {

	delete_option( $option_name );

	// for site options in Multisite
	delete_site_option( $option_name );

}
