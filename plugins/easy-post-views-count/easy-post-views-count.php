<?php
/**
 * Plugin Name: Easy Post Views Count
 * Plugin URI: https://wordpress.org/plugins/easy-post-views-count/
 * Description: Easy Post Views Count is easy to use and light weight plugin. it allow you to count of post views 
 * Version: 1.0.5
 * Author: AlphaBPO
 * Author URI: http://www.alphabpo.com
 * Text Domain: epvc
 * Domain Path: languages
 *
 * License: GPLv2 or later
 * Domain Path: languages
 *
 * @package Easy Post View Count
 * @category Core
 * @author Alpha BPO
 */

// Create a helper function for easy SDK access.
function epvc_fs() {
    global $epvc_fs;

    if ( ! isset( $epvc_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $epvc_fs = fs_dynamic_init( array(
            'id'                  => '2767',
            'slug'                => 'easy-post-views-count',
            'type'                => 'plugin',
            'public_key'          => 'pk_d540ce3e38853340d7d4e868e96e8',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'           => 'epvc-settings',
                'account'        => false,
                'contact'        => false,
                'support'        => false,
            ),
        ) );
    }

    return $epvc_fs;
}

// Init Freemius.
epvc_fs();
// Signal that SDK was initiated.
do_action( 'epvc_fs_loaded' );

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package Easy Post Views Count
 * @since 1.0.0
 */
if( !defined( 'EPVC_VERSION' ) ) {
	define( 'EPVC_VERSION', '1.0.5' ); // plugin version
}
if( !defined( 'EPVC_PLUGIN_DIR' ) ) {
	define( 'EPVC_PLUGIN_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'EPVC_ADMIN_DIR' ) ) {
	define( 'EPVC_ADMIN_DIR', EPVC_PLUGIN_DIR . '/includes/admin' ); // plugin admin dir
}
if( !defined( 'EPVC_PLUGIN_URL' ) ) {
	define( 'EPVC_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}

/**
 * Load Text Domain
 * 
 * Locales found in:
 * 
 *@package Easy Post Views Count
 * @since 1.0.3
 */
function epvc_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'epvc' );

	load_textdomain( 'epvc', WP_LANG_DIR . '/easy-post-view-count/epvc-' . $locale . '.mo' );
	load_plugin_textdomain( 'epvc', false, EPVC_PLUGIN_DIR . '/languages' );
}
add_action( 'load_plugins', 'epvc_load_plugin_textdomain' );

/**
 * Activation hook
 * 
 * Register plugin activation hook.
 * 
 * @package Easy Post Views Count
 * @since 1.0.3
 */

register_activation_hook( __FILE__, 'epvc_plugin_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 * 
 * @package Easy Post Views Count
 * @since 1.0.0
 */

register_deactivation_hook( __FILE__, 'epvc_plugin_uninstall' );

/**
 * Plugin Setup Activation hook call back 
 *
 * Initial setup of the plugin setting default options 
 * and database tables creations.
 * 
 * @package Easy Post Views Count
 * @since 1.0.0
 */
function epvc_plugin_install() {
	global $wpdb, $epvc_settings;

	$epvs_version = get_option( 'epvs_version' );
	if( empty($epvs_version) ) {
		$epvc_settings = array( 
			'post_types' => array( 'post' => 'yes' ),
			'display_icon' => 'yes',
		    'display_label' => 'yes',
		    'label_text' => 'Views',
		    'position' => 'before_content',
		    'login_users' => 'no',
		    'ips' => ''
		);
		update_option( 'epvc_settings', $epvc_settings );
		update_option( 'epvs_version', '1.0.5' );
	}
	
	$epvs_version = get_option( 'epvs_version' );
	if( $epvs_version == '1.0.5' ) {
		// Fetuare update code will be here
	}
}

/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 *
 * @package Easy Post Views Count
 * @since 1.0.0
 */
function epvc_plugin_uninstall() {
	global $wpdb;
}

/**
* Change Footer text for Reviews
 */
add_filter( 'admin_footer_text', 'epvc_remove_footer_admin' );
function epvc_remove_footer_admin() {
	$screen =  get_current_screen();
	if( $screen->id == "toplevel_page_epvc-settings" ){
		echo '<span id="footer-thankyou">';
		echo sprintf( __('If you like %1sEasy Post Views Count%2s please leave us a %3s★★★★★%4s rating. A huge thanks in advance!', 'wpens'),
			'<strong>', '</strong>',
			'<a href="https://wordpress.org/support/plugin/easy-post-views-count/reviews/?rate=5#new-post" target="_blank" class="epvc-rating-link">',
			'</a>'
		 );
		echo '</span>';
	}
}

/**
 * Initialize all global variables
 * 
 * @package Easy Post Views Count
 * @since 1.0.0
 */
global $epvc_settings;

$epvc_settings = get_option( 'epvc_settings' );

//Includes public class file
require_once ( EPVC_PLUGIN_DIR . '/includes/class-epvc-public.php');

//Includes plugin functions
require_once ( EPVC_PLUGIN_DIR . '/includes/epvc-misc-functions.php');

//Includes Admin file
require_once ( EPVC_ADMIN_DIR . '/class-epvc-admin.php');
