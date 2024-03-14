<?php
/**
 * Plugin Name: Content Slider Block
 * Description: Display your goal to your visitor in bountiful way with content slider block.
 * Version: 3.1.4
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: content-slider-block
   */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

if ( function_exists( 'csb_fs' ) || function_exists( 'csb_init' ) ) {
	register_activation_hook( __FILE__, function () {
		if ( is_plugin_active( 'content-slider-block/plugin.php' ) ){
			deactivate_plugins( 'content-slider-block/plugin.php' );
		}
		if ( is_plugin_active( 'content-slider-block-pro/plugin.php' ) ){
			deactivate_plugins( 'content-slider-block-pro/plugin.php' );
		}
	} );
}else{
	// Constant
	define( 'CSB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '3.1.4' );
	define( 'CSB_DIR_URL', plugin_dir_url( __FILE__ ) );
	define( 'CSB_DIR_PATH', plugin_dir_path( __FILE__ ) );
	define( 'CSB_HAS_FREE', 'content-slider-block/plugin.php' === plugin_basename( __FILE__ ) );
	define( 'CSB_HAS_PRO', 'content-slider-block-pro/plugin.php' === plugin_basename( __FILE__ ) );

	if( CSB_HAS_FREE ){
		if( !function_exists( 'csb_init' ) ) {
			function csb_init() {
				global $csb_bs;
				require_once( CSB_DIR_PATH . 'bplugins_sdk/init.php' );
				$csb_bs = new BPlugins_SDK( __FILE__ );
			}
			csb_init();
		}else {
			$csb_bs->uninstall_plugin( __FILE__ );
		}
	}

	if ( CSB_HAS_PRO ) {
		require_once CSB_DIR_PATH . 'inc/fs-init.php';

		if( function_exists( 'csb_fs' ) ){
			csb_fs()->set_basename( false, __FILE__ );
		}
	}

	function csbIsPremium(){
		if( CSB_HAS_FREE ){
			global $csb_bs;
			return $csb_bs->can_use_premium_feature();
		}

		if ( CSB_HAS_PRO ) {
			return csb_fs()->can_use_premium_code();
		}
	}

	require_once CSB_DIR_PATH . 'inc/block.php';
	require_once CSB_DIR_PATH . 'inc/CustomPost.php';
	require_once CSB_DIR_PATH . 'inc/pattern.php';
	require_once CSB_DIR_PATH . 'inc/HelpPage.php';

	if( CSB_HAS_FREE && !csbIsPremium() ){
		require_once CSB_DIR_PATH . 'inc/UpgradePage.php';
	}

	// Content Slider
	class CSBPlugin{
		function __construct(){
			add_action( 'wp_ajax_csbPipeChecker', [$this, 'csbPipeChecker'] );
			add_action( 'wp_ajax_nopriv_csbPipeChecker', [$this, 'csbPipeChecker'] );
			add_action( 'admin_init', [$this, 'registerSettings'] );
			add_action( 'rest_api_init', [$this, 'registerSettings']);

			add_filter( 'block_categories_all', [$this, 'blockCategories'] );
		}

		function csbPipeChecker(){
			$nonce = $_POST['_wpnonce'] ?? null;

			if( !wp_verify_nonce( $nonce, 'wp_ajax' )){
				wp_send_json_error( 'Invalid Request' );
			}

			wp_send_json_success( [
				'isPipe' => csbIsPremium()
			] );
		}

		function registerSettings(){
			register_setting( 'csbUtils', 'csbUtils', [
				'show_in_rest'		=> [
					'name'			=> 'csbUtils',
					'schema'		=> [ 'type' => 'string' ]
				],
				'type'				=> 'string',
				'default'			=> wp_json_encode( [ 'nonce' => wp_create_nonce( 'wp_ajax' ) ] ),
				'sanitize_callback'	=> 'sanitize_text_field'
			] );
		}

		function blockCategories( $categories ){
			return array_merge( [[
				'slug'	=> 'CSBlock',
				'title'	=> 'Content Slider Block',
			] ], $categories );
		} // Categories
	}
	new CSBPlugin;
}