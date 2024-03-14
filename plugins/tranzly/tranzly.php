<?php

/**
 * Plugin Name:       Tranzly - AI DeepL Translation Plugin
 * Plugin URI:        https://tranzly.io
 * Description:       Translate your complete WordPress Website content automatically including WooCommerce and Yoast using Tranzly, A revolutionary AI DeepL WordPress translator Plugin. All the correct Meta Tags are added for your website to ensure the translated pages are correctly tagged for best SEO practices. Including adding the Hreflang Tags.
 * Version:           2.0.0
 * Author:            tranzly
 * Author URI:        https://tranzly.io
 * WordPress 6.1.1 tested
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tranzly
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * Currently plugin version.
 */
define( 'TRANZLY_VERSION', '2.0.0' );




if ( ! function_exists( 'tranzly_fs' ) ) {
    // Create a helper function for easy SDK access.
    function tranzly_fs() {
        global $tranzly_fs;

        if ( ! isset( $tranzly_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $tranzly_fs = fs_dynamic_init( array(
                'id'                  => '6843',
                'slug'                => 'tranzly',
                'type'                => 'plugin',
                'public_key'          => 'pk_41c863827b360a912566ffb91d7fd',
                'is_premium'          => true,
                'premium_suffix'      => 'Pro',
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'has_affiliation'     => 'all',
                'menu'                => array(
                    'slug'           => 'tranzly',
                    'first-path'     => 'admin.php?page=tranzly',
                    'support'        => false,
                ),

            ) );
        }

        return $tranzly_fs;
    }

    // Init Freemius.
    tranzly_fs();
    // Signal that SDK was initiated.
    do_action( 'tranzly_fs_loaded' );
}









/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tranzly.php';
/**
 * @since    1.0.0
 */
function tranzly_run()
{
    $plugin = new Tranzly();
    $plugin->run();
}

tranzly_run();