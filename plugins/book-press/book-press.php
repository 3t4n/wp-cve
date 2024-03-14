<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.blackandwhitedigital.net/
 * @since             1.0.0
 * @package           Book_Press
 *
 * @wordpress-plugin
 * Plugin Name: BookPress 
 * Plugin URI:        https://bookpress.net/
 * Description:       Tools for authors to write and display their books easily on WordPress.
 * Version:           1.2.4
 * Author:            Black and White Digital Ltd
 * Author URI:        https://www.blackandwhitedigital.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       book-press
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !function_exists( 'book_press_fs' ) ) {
    // Create a helper function for easy SDK access.
    function book_press_fs()
    {
        global  $book_press_fs ;
        
        if ( !isset( $book_press_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_2660_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_2660_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $book_press_fs = fs_dynamic_init( array(
                'id'              => '2660',
                'slug'            => 'book-press',
                'type'            => 'plugin',
                'public_key'      => 'pk_89eccf2c7c6a18d62ab81adc585d2',
                'is_premium'      => false,
                'premium_suffix'  => 'premium',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'has_affiliation' => 'all',
                'menu'            => array(
                'slug'           => 'book-press-setting',
                'override_exact' => true,
                'first-path'     => 'admin.php?page=book-press-setting',
                'network'        => true,
                'parent'         => array(
                'slug' => 'book-press',
            ),
            ),
                'is_live'         => true,
            ) );
        }
        
        return $book_press_fs;
    }
    
    // Init Freemius.
    book_press_fs();
    // Signal that SDK was initiated.
    do_action( 'book_press_fs_loaded' );
    function book_press_fs_settings_url()
    {
        return admin_url( 'admin.php?page=book-press-setting' );
    }
    
    book_press_fs()->add_filter( 'connect_url', 'book_press_fs_settings_url' );
    book_press_fs()->add_filter( 'after_skip_url', 'book_press_fs_settings_url' );
    book_press_fs()->add_filter( 'after_connect_url', 'book_press_fs_settings_url' );
    book_press_fs()->add_filter( 'after_pending_connect_url', 'book_press_fs_settings_url' );
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOK_PRESS_VERSION', '1.2.4' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-book-press-activator.php
 */
function activate_book_press()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-book-press-activator.php';
    Book_Press_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-book-press-deactivator.php
 */
function deactivate_book_press()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-book-press-deactivator.php';
    add_option( 'my_plugin_do_activation_redirect', true );
    Book_Press_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_book_press' );
register_deactivation_hook( __FILE__, 'deactivate_book_press' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-book-press.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_book_press()
{
    $plugin = new Book_Press();
    $plugin->run();
}

run_book_press();
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'book_press_paypal_link' );
function book_press_paypal_link( $links )
{
    $url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RSXSRDQ7HANFQ&source=in-plugin-donate-link';
    $_link = '<a href="' . $url . '" target="_blank">' . __( 'Donate', 'book-press' ) . '</a>';
    $links[] = $_link;
    return $links;
}
