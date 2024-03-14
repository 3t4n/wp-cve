<?php
/**
 * Plugin Name: Dropship Me
 * Plugin URI: https://dropship.me/
 * Description: DropshipMe allows you to easily import the best AliExpress products from trusted suppliers with already edited product information and images to your WordPress store. The plugin provides the access to DropshipMe database via API.
 * Author: Vitaly Kukin
 * Text Domain: dm
 * License: MIT
 * License URI:  http://www.opensource.org/licenses/mit-license.php
 * Version: 1.0.3.16
 * Author URI: https://yellowduck.me/
 */

load_plugin_textdomain( 'dm' );

if ( ! defined( 'DM_VERSION' ) ) define( 'DM_VERSION', '1.0.3.16' );
if ( ! defined( 'DM_PATH' ) )    define( 'DM_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'DM_URL' ) )     define( 'DM_URL', str_replace( [ 'https:', 'http:' ], '', plugins_url( 'dropship-me' ) ) );
if ( ! defined( 'DM_PLUGIN' ) )  define( 'DM_PLUGIN', dm_activated_plugins() );
if ( ! defined( 'DM_ERROR' ) )   define( 'DM_ERROR', dm_check_server() );
if ( ! defined( 'DM_AEX' ) )     define( 'DM_AEX', dm_check_alidropship_exist() );

function dm_activated_plugins() {
	
	$plugins_local  = (array) get_option( 'active_plugins', [] );
	$plugins_global = (array) get_site_option( 'active_sitewide_plugins', [] );
	
	if( in_array( 'woocommerce/woocommerce.php', $plugins_local ) ||
	    ( is_multisite() && array_key_exists( 'woocommerce/woocommerce.php' , $plugins_global ) ) )
		return 'woocommerce';
	elseif( in_array( 'alids/alids.php', $plugins_local ) )
		return 'alidropship';
	
	return false;
}

function dm_check_server() {
	
	if( ! DM_PLUGIN )
		return __( 'None of the required plugins has been found: please install and activate WooCommerce or AliDropship plugin.', 'dm' );
	
	return false;
}

function dm_admin_notice_error() {

    if( DM_ERROR ) {
        printf( '<div class="notice notice-error"><p>%s</p></div>', DM_ERROR );
    }
}
add_action( 'admin_notices', 'dm_admin_notice_error' );


function dm_check_alidropship_exist()
{
    $plugins_local = (array)get_option('active_plugins', []);
    if (in_array('woocommerce/woocommerce.php', $plugins_local) && !in_array('alids/alids.php', $plugins_local) && !in_array('alidswoo/alidswoo.php', $plugins_local)) {
        return 'not_exist';
    }
}
if ( DM_AEX ) {
    require(DM_PATH . 'core/filters.php');
}
if ( ! DM_ERROR ) {
    require( DM_PATH . 'core/core.php' );
	require( DM_PATH . 'core/cron.php' );
}

if( is_admin() ) :
	
    require( DM_PATH . 'core/setup.php' );
    require( DM_PATH . 'core/hooks.php' );
    
    register_activation_hook( __FILE__, 'dm_install' );
    register_uninstall_hook( __FILE__, 'dm_uninstall' );
    register_activation_hook( __FILE__, 'dm_activate' );

endif;