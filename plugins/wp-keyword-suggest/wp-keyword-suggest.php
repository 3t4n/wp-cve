<?php
/*
Plugin Name: WP Keyword Suggest
Plugin URI: http://seowp.es/wp-keyword-suggest
Description: This SEO plugin offers keyword suggestions, taken from autocomplete google, yahoo, bing... up to 250 keywords ideas
Version: 1.2
Author: nicolasmarin
Author URI: http://www.nicolasmarin.com/
*/

if ('wp-keyword-suggest.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');

class wp_keyword_suggest_suggestions
{
	function __construct()
	{
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'wp_ajax_wpks_keyword_suggestions', array( &$this, 'ajax_suggestions' ) );
		add_action( 'add_meta_boxes', array(__CLASS__, 'add_meta_boxes') );
		//add_action( 'admin_init', 'wpks_add_custom_box', 1 ); // backwards compatible (before WP 3.0)
	}
	
	static function activation()
	{
		add_option( 'wpks_intense', 'low');
	}
	
	static function deactivation()
	{
		delete_option( 'wpks_intense' );
	}
	
	function admin_init()
	{
		if ( is_admin() && ( strpos( $_SERVER['SCRIPT_NAME'], 'post-new.php' ) || strpos( $_SERVER['SCRIPT_NAME'], 'post.php' ) ) !== false ) :
			wp_enqueue_script( 'wp-keyword-gcomplete', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/js/jquery.gcomplete.0.1.2.js' );
		    wp_enqueue_script( 'wp-keyword-suggest_suggestions', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/js/wp-keyword-suggest.js' );
			wp_localize_script( 'wp-keyword-suggest_suggestions', 'objectL10n', array(
				'please_enter_keyword'  => __('Please enter a keyword and try again.', _PLUGIN_NAME_)
			) );
			wp_enqueue_style( 'wp-keyword-gcomplete', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/css/jquery.gcomplete.default-themes.css' );
			wp_enqueue_style( 'wp-keyword-suggest_suggestions', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/css/wp-keyword-suggest.css' );
		endif;
		if ( is_admin() && ( isset( $_GET['page'] ) && $_GET['page'] == 'wpks_options' ) ) :
			wp_enqueue_style( 'wp-keyword-suggest_suggestions', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/css/wp-keyword-suggest.css' );
		endif;
	}

	function admin_menu()
	{
		if (function_exists('add_options_page')) {
			add_options_page(__('WP Keyword Suggest', _PLUGIN_NAME_), __('WP Keyword Suggest', _PLUGIN_NAME_), 'manage_options', 'wp-keyword-suggest-options', array( $this, 'admin_options' )) ;
		}
	}

	function admin_options()
	{
		include 'inc/wpks-admin.php';
	}

	function add_meta_boxes()
	{
	    add_meta_box( 'wp-keyword-suggest', __( 'WP Keyword Suggest', _PLUGIN_NAME_ ), array(__CLASS__, 'display_wp_keyword_suggest_meta_box'), $post->post_type, 'side', 'high' );
	}

	public static function display_wp_keyword_suggest_meta_box($post)
	{		
		// Use nonce for verification
  		wp_nonce_field( plugin_basename( __FILE__ ), 'wp_keyword_suggest' );

  		include 'inc/meta-box-wp-keyword-suggest.php';
	}
	
	function ajax_suggestions()
	{
		require_once('inc/functions.php');
		
		global $blog_id;
		$wpks_api = new wpks_api();

		$keywords_list = $wpks_api->request($_POST['wpks_keyword']);
		echo $keywords_list;

		/* not return 0 */
		die();
	}
}
/*  */
define('_PLUGIN_NAME_', 'wp-keyword-suggest');
define( '_wpks_PATH_', plugins_url( '', __FILE__ ) );
load_plugin_textdomain( _PLUGIN_NAME_, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );	

register_activation_hook( __FILE__, array( 'wp_keyword_suggest_suggestions', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'wp_keyword_suggest_suggestions', 'deactivation' ) );

$auto_title_suggestions = new wp_keyword_suggest_suggestions();

?>
