<?php
/**
 * Plugin Name: Easy Code Snippets
 * Plugin URI: 
 * Description: Easy Code Snippets allows you to create unlimited CSS or JS code snippets, you can add to your website in header and footer.
 * Version: 1.0.1
 * Author: AlphaBPO
 * Author URI: http://www.alphabpo.com
 * Text Domain: ecsnippets
 * Domain Path: languages
 * License: GPLv2 or later
 * Domain Path: languages
 * @package Easy Code Snippets
 * @category Core
 * @author Alpha BPO
 */

// Create a helper function for easy SDK access.
function ecs_fs() {
    global $ecs_fs;

    if ( ! isset( $ecs_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $ecs_fs = fs_dynamic_init( array(
            'id'                  => '2771',
            'slug'                => 'easy-code-snippets',
            'type'                => 'plugin',
            'public_key'          => 'pk_352adb428ba121dc88cf94c58f2b5',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'           => 'ecsnippets-snippets',
                'account'        => false,
                'contact'        => false,
                'support'        => false,
            ),
        ) );
    }

    return $ecs_fs;
}

// Init Freemius.
ecs_fs();
// Signal that SDK was initiated.
do_action( 'ecs_fs_loaded' );

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 */
if( !defined( 'WPCS_VERSION' ) ) {
	define( 'WPCS_VERSION', '1.0.1' ); // plugin version
}
if( !defined( 'WPCS_PLUGIN_URL' ) ) {
	define( 'WPCS_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'WPCS_PLUGIN_DIR' ) ) {
	define( 'WPCS_PLUGIN_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WPCS_ADMIN_DIR' ) ) {
	define( 'WPCS_ADMIN_DIR', WPCS_PLUGIN_DIR . '/includes/admin' ); // plugin admin dir
}

/**
 * Load Text Domain
 * 
 * Locales found in:
 *   - WP_LANG_DIR/plugins/ecsnippets-LOCALE.mo 
 */
function ecsnippets_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'ecsnippets' );
	load_textdomain( 'ecsnippets', WP_LANG_DIR . '/easy-code-snippets/ecsnippets-' . $locale . '.mo' );
	load_plugin_textdomain( 'ecsnippets', false, WPCS_PLUGIN_DIR . '/languages' );
}
add_action( 'load_plugins', 'ecsnippets_load_plugin_textdomain' );

/**
 * Activation hook
 * 
 * Register plugin activation hook.
 */

register_activation_hook( __FILE__, 'ecsnippets_plugin_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 */

register_deactivation_hook( __FILE__, 'ecsnippets_plugin_uninstall' );

/**
 * Plugin Setup Activation hook call back 
 *
 * Initial setup of the plugin setting default options 
 * and database tables creations.
 */
function ecsnippets_plugin_install() {
	
	global $wpdb;

	// Check if pro version is activated.
	if( is_plugin_active('easy-code-snippets-pro/easy-code-snippets-pro.php') )  {
		deactivate_plugins( plugin_basename(__FILE__) );
		wp_die( 'Easy Code Snippets Pro plugin is activated. please deactivate that plugin to activate this.' );
	}

	$wpcs_version = get_option( 'ecsnippets_version' );

	if( empty($wpcs_version) ) {
		
		$charset_collate = $wpdb->get_charset_collate();
		$table_name		= $wpdb->prefix . 'ecs_snippets';
		$sql = "CREATE TABLE $table_name (
				ID bigint(20) NOT NULL AUTO_INCREMENT,
				title varchar(255) NULL,
				code longtext NULL,
				position varchar(30) NULL,
				date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (ID)
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$wpcs_version = '1.0.1';
		update_option( 'ecsnippets_version', $wpcs_version );
	}

	// Check if version 1.0.1
	if( $wpcs_version == '1.0.1' ) {
		// future code will be here
	}
}

/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 */
function ecsnippets_plugin_uninstall() {
	global $wpdb;
}

/**
 * Initialize all global variables
 */

// Includes all scripts class file
require_once ( WPCS_PLUGIN_DIR . '/includes/class-wpcs-scripts.php' );

// Misc function file manage plugin misc functions
require_once ( WPCS_PLUGIN_DIR . '/includes/wpcs-misc-functions.php' );

// Snippet function file manage plugin Snippet functions
require_once ( WPCS_PLUGIN_DIR . '/includes/class-wpcs-snippet.php' );

// Include admin side files
if( is_admin() ) {
	require_once ( WPCS_ADMIN_DIR . '/class-wpcs-admin.php' );
}