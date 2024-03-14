<?php
/**
 * Plugin Name:  WP Post Disclaimer
 * Plugin URI:   https://wordpress.org/plugins/wp-post-disclaimer/
 * Description:  Add a disclaimer/terms/warnings about post/page/custom post type on top, bottom of content as well as inside post content with shortcode
 * Version:      1.0.4
 * Author:       Krunal Prajapati
 * Author URI:   https://profiles.wordpress.org/krunalprajapati41
 * License:      GPLv2 or later
 * License URI:  https://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses
 * Text Domain:  wp-post-disclaimer
 * Domain Path:	 /languages
 **/
if ( ! defined( 'ABSPATH' ) ) :
	exit; // Exit if accessed directly.
endif;
if( !defined('WPPD_PLUGIN_URL') ) : //Plugin URL
	define('WPPD_PLUGIN_URL', 	plugin_dir_url( __FILE__ ));
endif;
if( !defined('WPPD_PLUGIN_PATH') ) : //Plugin Path
	define('WPPD_PLUGIN_PATH', 	plugin_dir_path( __FILE__ ));
endif;
if( !defined('WPPD_PLUGIN_BASE') ) : //Plugin Base Name
	define('WPPD_PLUGIN_BASE', 	plugin_basename( __FILE__ ));
endif;
if( !defined('WPPD_PLUGIN_VERSION') ) : //Plugin Version
	define('WPPD_PLUGIN_VERSION',	'1.0.2');
endif;

if( !function_exists('wppd_plugin_activation') ) :
/**
 * Plugin Activation 
 * 
 * Handles to run on plugin activation
 * 
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_plugin_activation() {
	
	//Plugin Version
	$wppd_plugin_version = get_option('wppd_plugin_version');
	//Plugin Options
	$wppd_options = get_option('wppd_options');
	
	if( empty( $wppd_plugin_version ) ) : //Check Default Options Set
		//Default Options
		$default_options = array('enable' => 0,
								'display_in_post' 	=> 0,
								'display_in_page' 	=> 0,
								'display_in_post_position' => 'bottom',
								'display_in_page_position' => 'bottom',
								'disclaimer_title'	=> esc_html__('Post Disclaimer', 'wp-post-disclaimer'),
								'disclaimer_content'=> esc_html__('The information contained in this post is for general information purposes only. The information is provided by %%title%% and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the post for any purpose.','wp-post-disclaimer'),
								//Look & Feel Optins
								'style'		=> 'error',
								'icon'		=> '',
								'icon_size'	=> 'sm',
								'custom_css'=> '' );
		update_option('wppd_options', apply_filters( 'wppd_plugin_default_options', $default_options ) );
		//Set Plugin Version
		update_option('wppd_plugin_version', '1.0.0');
	endif; //Endif
}
endif; //Endif
register_activation_hook( __FILE__, 'wppd_plugin_activation' );

if( !function_exists('wppd_plugin_deactivation') ) :
/**
 * Plugin Deactivation 
 * 
 * Handles to run on plugin deactivation
 * 
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_plugin_deactivation() {
	//Deactivation Code
}
endif; //Endif
register_activation_hook( __FILE__, 'wppd_plugin_deactivation' );

if( !function_exists('wppd_plugins_loaded') ) :
/**
 * Load Language Domain
 *
 * Handles to load language translation
 *
 * @since WP Post Disclaimer 1.0.0
 */
function wppd_plugins_loaded() {

	//Add Filter For Plugin Language Directory
	$wppd_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wppd_lang_dir	= apply_filters('wppd_languages_directory', $wppd_lang_dir);

	//Default WordPress Plugin Locale Filter
	$locale	= apply_filters('plugin_locale',  get_locale(), 'wp-post-disclaimer');
	$mofile	= sprintf('%1$s-%2$s.mo', 'wp-post-disclaimer', $locale);

	//Setup Paths to Current Locale File
	$mofile_local	= $wppd_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' . $mofile;

	if( file_exists( $mofile_global ) ) : //Check in global /wp-content/languages/wp-post-disclaimer folder
		load_textdomain( 'wp-post-disclaimer', $mofile_global );
	elseif( file_exists( $mofile_local ) ) : //Checkin local /wp-content/plugins/wp-post-disclaimer/languages/ folder
		load_textdomain( 'wp-post-disclaimer', $mofile_local );
	else : //Else Load Default Language
		load_plugin_textdomain( 'wp-post-disclaimer', false, $wppd_lang_dir );
	endif; //Endif
}
add_action('plugins_loaded', 'wppd_plugins_loaded');
endif;

//Plugin Commonly Used Functions
require_once( WPPD_PLUGIN_PATH . 'includes/functions.php' );
global $wppd_options;
$wppd_options = wppd_get_options();
//Admin Functionality
require_once( WPPD_PLUGIN_PATH . 'includes/class-wppd-admin.php' );
//Public Functionality
require_once( WPPD_PLUGIN_PATH . 'includes/class-wppd-public.php' );