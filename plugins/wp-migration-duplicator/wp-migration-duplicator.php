<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              https://www.webtoffee.com/
 * @since             1.0.0
 * @package           Wp_Migration_Duplicator
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Backup & Migration
 * Plugin URI:        https://wordpress.org/plugins/wp-migration-duplicator/
 * Description:       Migrate WordPress contents and database quickly with ease.
 * Version:           1.4.8
 * Author:            WebToffee
 * Author URI:        https://www.webtoffee.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-migration-duplicator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(!defined('WP_MIGRATION_DUPLICATOR_VERSION')) //check plugin file already included
{
    define('WT_MGDP_PLUGIN_DEVELOPMENT_MODE', false );
    define('WT_MGDP_PLUGIN_BASENAME', plugin_basename(__FILE__) );
    define('WT_MGDP_PLUGIN_PATH', plugin_dir_path(__FILE__) );
    define('WT_MGDP_PLUGIN_URL', plugin_dir_url(__FILE__));
    define('WT_MGDP_PLUGIN_FILENAME',__FILE__);
    define('WT_MGDP_POST_TYPE','wp_migration_duplicator');
    define('WT_MGDP_DOMAIN','wp-migration-duplicator');
    define('WT_MGDP_CLOUD_STORAGE_LOCATION','webtoffee_migrations');

    /**
     * Currently plugin version.
     */
    define('WP_MIGRATION_DUPLICATOR_VERSION', '1.4.8' );
}
if ( !defined( 'WT_MGDP_PLUGIN_DEBUG_BASIC_TROUBLESHOOT' ) ) {
	define( 'WT_MGDP_PLUGIN_DEBUG_BASIC_TROUBLESHOOT', 'https://www.webtoffee.com/finding-php-error-logs-in-migrator-plugin/' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-migration-duplicator-activator.php
 */
function activate_wp_migration_duplicator() {
    if (is_plugin_active('wp-migration-duplicator-pro/wp-migration-duplicator-pro.php')) {
        wp_die(__("Is everything fine? You already have the Premium version installed in your website. For any issues, kindly raise a ticket via <a target='_blank' href='https://www.webtoffee.com/support/'>support</a>", "wp-migration-duplicator"), "", array('back_link' => 1));
    }else{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-migration-duplicator-activator.php';
	Wp_Migration_Duplicator_Activator::activate();
    }
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-migration-duplicator-deactivator.php
 */
function deactivate_wp_migration_duplicator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-migration-duplicator-deactivator.php';
        @delete_option( 'wt_mgdp_options' );
	Wp_Migration_Duplicator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_migration_duplicator' );
register_deactivation_hook( __FILE__, 'deactivate_wp_migration_duplicator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_migration_duplicator() {

 if( !function_exists('is_plugin_active') ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
 }

 if (!is_plugin_active('wp-migration-duplicator-pro/wp-migration-duplicator-pro.php')) {
        require plugin_dir_path( __FILE__ ) . 'includes/class-wp-migration-duplicator.php';
	$plugin = new Wp_Migration_Duplicator();
	$plugin->run();
 }

}

/* Plugin page links */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wt_wp_migration_duplicator_plugin_action_links_basic' );

function wt_wp_migration_duplicator_plugin_action_links_basic( $links ) {

    $plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wp-migration-duplicator' ) . '">' . __( 'Settings' ) . '</a>',
		'<a href="https://www.webtoffee.com/wordpress-backup-migration-user-guide/" target="_blank">' . __( 'Documentation' ) . '</a>',
                '<a target="_blank" href="https://www.webtoffee.com/product/wordpress-backup-and-migration/?utm_source=free_plugin_listing&utm_medium=Migration_free&utm_campaign=WordPress_Backup&utm_content='.WP_MIGRATION_DUPLICATOR_VERSION.'" style="color: #3db634; font-weight: 500;">' . __('Premium Upgrade') . '</a>',
            );

	if ( array_key_exists( 'deactivate', $links ) ) {
		$links[ 'deactivate' ] = str_replace( '<a', '<a class="wtmigrator-deactivate-link"', $links[ 'deactivate' ] );
	}
	return array_merge( $plugin_links, $links );
}

// Add dismissible server info for file restrictions
include_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-migration-non-apache-info.php';
$inform_server_secure					 = new Wt_Mgdb_Inform_Server_Secure( 'Migration' );
$inform_server_secure->plugin_title		 = "WordPress Backup & Migration";
$inform_server_secure->banner_message	 = sprintf( __( "The <b>%s</b> plugin uploads the imported file into <b>wp-content/webtoffee_migrations</b> folder. Please ensure that public access restrictions are set in your server for this folder." ), $inform_server_secure->plugin_title );

/**
* Missing plugins warning.
*/
add_action( 'admin_notices',  'wt_missing_plugins_warning');
if(!function_exists('wt_missing_plugins_warning')){
    function wt_missing_plugins_warning() {
        $screen = get_current_screen();
        if ( $screen->id !== 'toplevel_page_wp-migration-duplicator'){
            return;
        }else{
            if(!extension_loaded('zip') && !extension_loaded('zlib')){
                /* Display the notice*/
                $class = 'notice notice-error';
                $message = sprintf(__('<b>Wordpress Backup & Migration</b> has been activated. To ensure proper functioning, kindly enable the <b>ZipArchive extension</b> in server. '));
               printf( '<div class="%s"><p>%s</p></div>', esc_attr( $class ), ( $message ) );

            }
        }
    }
}
run_wp_migration_duplicator();
