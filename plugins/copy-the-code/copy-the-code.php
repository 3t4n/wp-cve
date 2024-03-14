<?php
/**
 * Plugin Name: Copy Anything to Clipboard
 * Plugin URI: https://clipboard.agency/
 * Description: Effortlessly Copy Text or HTML to Your Clipboard 📋 with Copy Anything to Clipboard. Whether it's Blockquotes, Wishes, Messages, Shayari, Offer Codes, Special Symbols, Code Snippets, Hidden Content, or anything else you desire, our plugin has you covered! 🥳 Explore the possibilities with <a href="https://clipboard.agency/">Copy Anything to Clipboard</a>.
 * Version: 3.6.0
 * Author: Clipboard Team
 * Author URI: https://clipboard.agency/
 * Text Domain: copy-the-code
 *
 * @package Copy the Code
 *
  */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'ctc_fs' ) ) {
	ctc_fs()->set_basename( false, __FILE__ );
} else {
	// Set constants.
	define( 'COPY_THE_CODE_TITLE', esc_html__( 'Copy Anything to Clipboard', 'copy-the-code' ) );
	define( 'COPY_THE_CODE_VER', '3.6.0' );
	define( 'COPY_THE_CODE_FILE', __FILE__ );
	define( 'COPY_THE_CODE_BASE', plugin_basename( COPY_THE_CODE_FILE ) );
	define( 'COPY_THE_CODE_DIR', plugin_dir_path( COPY_THE_CODE_FILE ) );
	define( 'COPY_THE_CODE_URI', plugins_url( '/', COPY_THE_CODE_FILE ) );

	// DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
	if ( ! function_exists( 'ctc_fs' ) ) {
		require_once COPY_THE_CODE_DIR . 'classes/init.php';
	}

	register_activation_hook( COPY_THE_CODE_FILE, 'copy_the_code_set_fresh_user' );

	// Set as fresh user?
	function copy_the_code_set_fresh_user() {
		update_option( 'copy_the_code_fresh_user', 'yes' );
	}

	require_once COPY_THE_CODE_DIR . 'classes/class-copy-the-code.php';
}
