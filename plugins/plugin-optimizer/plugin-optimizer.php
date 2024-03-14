<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Plugin Optimizer
 * Plugin URI:        https://pluginoptimizer.com
 * Description:       The Most Powerful Performance Plugin for WordPress is now available for FREE.
 * Version:           1.3.7
 * Author:            Plugin Optimizer
 * Author URI:        https://pluginoptimizer.com/about/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-optimizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

include 'config.php';

/**
 * Current plugin version.
 * Use SemVer - https://semver.org
 */
define( 'SOSPO_VERSION', '1.0.8-9' );

// let's install the MU plugin if it's missing or outdated and refresh
if( ! file_exists( WPMU_PLUGIN_DIR . '/class-po-mu.php') || ! function_exists("sospo_mu_plugin") || sospo_mu_plugin()->version !== SOSPO_VERSION ){
    
    if( ! file_exists( WPMU_PLUGIN_DIR ) ){
        
        mkdir( WPMU_PLUGIN_DIR );
        chmod( WPMU_PLUGIN_DIR, 0755 );
    }

    copy( __DIR__ . '/includes/class-po-mu.php', WPMU_PLUGIN_DIR . '/class-po-mu.php' );
    
    header("Refresh:0");
    
    return;
}

/**
 * Initialize the plugin trackers
 *
 * @return void
 */
global $sospo_appsero;
function appsero_init_tracker_plugin_optimizer() {
    
    // This changed the post type of previous version po
    $updated = get_option('po_db_updated');

    if( !$updated ){     

      global $wpdb;

      $count = $wpdb->get_var("SELECT count(post_type) as count FROM {$wpdb->prefix}posts WHERE post_type = 'sos_filter'");

      if( $count ){

        $updated = $wpdb->query("UPDATE {$wpdb->prefix}posts SET post_type = 'plgnoptmzr_filter' WHERE post_type = 'sos_filter'");

        if( $updated ) update_option( 'po_db_updated','true' );
      }
    }

    global $sospo_appsero;
    
    $sospo_appsero = [];
    
    if ( ! class_exists( 'Appsero\Client' ) ) {
        require_once __DIR__ . '/vendor/autoload.php';
    }
    
    $sospo_appsero["free"] = new Appsero\Client( 'c5104b7b-7b26-4f52-b690-45ef58f9ba31', 'Plugin Optimizer', __FILE__ );
    $sospo_appsero["free"]->insights()->init();// Activate insights
    $sospo_appsero["free"]->updater();//          Activate automatic updater
    
    
    $active_plugins = ! empty( sospo_mu_plugin()->original_active_plugins ) ? sospo_mu_plugin()->original_active_plugins : get_option('active_plugins');
    
    if( ! in_array( "plugin-optimizer-premium/plugin-optimizer-premium.php", $active_plugins ) ){
        
        return;
    }
    
    $sospo_appsero["premium"] = new Appsero\Client( 'ae74f660-483b-425f-9c31-eced50ca019f', 'Plugin Optimizer Premium', plugin_dir_path( __DIR__ ) . 'plugin-optimizer-premium/plugin-optimizer-premium.php' );
    $sospo_appsero["premium"]->insights()->init();// Activate insights
    $sospo_appsero["premium"]->updater();//          Activate automatic updater



    
    // Activate license page and checker
    $args = array(
        'type'        => 'submenu', // Can be: menu, options, submenu
        'menu_title'  => 'Premium Settings',
        'page_title'  => 'Plugin Optimizer Premium Settings',
        'menu_slug'   => 'plugin_optimizer_premium_settings',
        'parent_slug' => 'plugin_optimizer',
    );
    // $sospo_appsero["premium"]->license()->add_settings_page( $args );
    
}
add_action('plugins_loaded', 'appsero_init_tracker_plugin_optimizer');

/**
 * The code that runs during plugin activation.
 */
function activate_plugin_optimizer() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-activator.php';
  SOSPO_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_plugin_optimizer' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_plugin_optimizer() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-po-deactivator.php';
  SOSPO_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_plugin_optimizer' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-po.php';

new PluginOptimizer();

// ------------------------ Helpers and Testers

if( ! function_exists( 'write_log' ) ){
    
    function write_log ( $log, $text = "write_log: ", $file_name = "debug.log" )  {
        
        $file = WP_CONTENT_DIR . '/' . $file_name;
        
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( $text . PHP_EOL . print_r( $log, true ) . PHP_EOL, 3, $file );
        } else {
            error_log( $text . PHP_EOL . $log . PHP_EOL . PHP_EOL, 3, $file );
        }
        
    }

}


//http://stackoverflow.com/a/1597788/1287812
function sort_arra_asc_so_1597736( $item1, $item2 )
{
    if ($item1[0] == $item2[0]) return 0;
    return ( $item1[0] > $item2[0] ) ? 1 : -1;
}

add_action( 'admin_menu', 'sort_settings_menu_wpse_2331', 99999 );

function sort_settings_menu_wpse_2331() 
{
    global $menu;

    //echo '<pre>'.print_r($menu, 1).'</pre>';die;

    $map = array();
    foreach( $menu as $key => $m ){

      //seperator
      if( empty($m[0]) ) continue; 

      $map[$m[0]] = $key;
    }

    ksort($map);
    
    $new_menu = array();
    foreach( $map as $m ){
      $new_menu[$m] = $menu[$m];
    }


    //apply_filters('admin_menu', 'custom_menu_order');
    
    /*
    // Sort default items
    $default = array_slice( $submenu['options-general.php'], 0, 6, true );
    usort( $default, 'sort_arra_asc_so_1597736' );

    // Sort rest of items
    $length = count( $submenu['options-general.php'] );
    $extra = array_slice( $submenu['options-general.php'], 6, $length, true );
    usort( $extra, 'sort_arra_asc_so_1597736' );

    // Apply
    $submenu['options-general.php'] = array_merge( $default, $extra );*/
}

add_filter('custom_menu_order', 'custom_menu_order');
add_filter('menu_order', 'custom_menu_order');
function custom_menu_order($menu_ord) {
  // echo '<pre>'.print_r('fire', 1).'</pre>';
  // echo '<pre>'.print_r($menu_ord, 1).'</pre>';die;
       if (!$menu_ord) return true;
       return array('index.php', 'edit.php', 'edit-comments.php');
}

function po_update_db_alert(){
    global $pagenow;


    if ( !get_option( 'po_db_updated-v1.2' ) ) {
         echo '<div class="notice notice-warning is-dismissible">
             <p>It looks like you have a new version of <strong>Plugin Optimizer</strong> and your database needs to be updated in order to take advantage of the newest features. &nbsp;<button id="po_update_database_button" class="po_green_button">Update DB Now</button></p>
         </div>';
    }
}
add_action('admin_notices', 'po_update_db_alert');