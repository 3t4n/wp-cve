<?php
/**
 * Terms & Conditions Per Product
 *
 * Set custom Terms and Conditions per WooCommerce product.
 *
 * @link              https://tacpp-pro.com
 * @since             1.0.1
 * @package           terms-per-product
 *
 * @wordpress-plugin
 * Plugin Name:       Terms and Conditions Per Product
 * Plugin URI:        https://tacpp-pro.com
 * Description:       Set custom Terms and Conditions per WooCommerce product, category or tag and display them on the checkout page.
 * Version:           1.2.11
 * Author:            Terms Per Product
 * Author URI:        https://tacpp-pro.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       terms-and-conditions-per-product
 * Domain Path:       /languages
 * WC requires at least: 6.0
 * WC tested up to:     8.2.2
 */

/**
 * Main file, contains the plugin metadata and activation processes
 *
 * @package    terms-per-product
 */
if ( ! defined( 'TACPP4_PLUGIN_VERSION' ) ) {
    /**
     * The version of the plugin.
     */
    define( 'TACPP4_PLUGIN_VERSION', '1.2.11' );
}

if ( ! defined( 'TACPP4_PLUGIN_PATH' ) ) {
    /**
     *  The server file system path to the plugin directory.
     */
    define( 'TACPP4_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'TACPP4_PLUGIN_URL' ) ) {
    /**
     * The url to the plugin directory.
     */
    define( 'TACPP4_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'TACPP4_PLUGIN_BASE_NAME' ) ) {
    /**
     * The url to the plugin directory.
     */
    define( 'TACPP4_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'TACPP4_PLUGIN_FILE' ) ) {
    /**
     * The url to the plugin directory.
     */
    define( 'TACPP4_PLUGIN_FILE', __FILE__ );
}


if ( ! defined( 'TACPP4_PLUGIN_PRO_BUY_URL' ) ) {
    /**
     * The url to the plugin directory.
     */
    define( 'TACPP4_PLUGIN_PRO_BUY_URL',
        get_admin_url( '', 'admin.php?page=tacpp-pricing' ) );
}

if ( ! defined( 'TACPP4_ACCEPT_LOG_TABLE_NAME' ) ) {
    /**
     * The url to the plugin directory.
     */
    define( 'TACPP4_ACCEPT_LOG_TABLE_NAME', 'tacpp_accept_log' );
}


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'tacppp_fs' ) ) {
    tacppp_fs()->set_basename( true, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( ! function_exists( 'tacppp_fs' ) ) {
        // Create a helper function for easy SDK access.
        function tacppp_fs() {
            global $tacppp_fs;

            if ( ! isset( $tacppp_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';

                $tacppp_fs = fs_dynamic_init( array(
                    'id'                  => '11429',
                    'slug'                => 'terms-and-conditions-per-product',
                    'premium_slug'        => 'terms-and-conditions-per-product-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_b45440428f27613181b9ab830fa1b',
                    'is_premium'          => false,
                    'premium_suffix'      => 'Premium',
                    // If your plugin is a serviceware, set this option to false.
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'menu'                => array(
                        'slug'       => 'tacpp',
                        'first-path' => 'admin.php?page=tacpp',
                        'contact'    => false,
                    ),
                ) );
            }

            return $tacppp_fs;
        }

        // Init Freemius.
        tacppp_fs();
        // Signal that SDK was initiated.
        do_action( 'tacppp_fs_loaded' );

        function tacppp_fs_settings_url() {
            return admin_url( 'admin.php?page=tacpp' );
        }

        tacppp_fs()->add_filter( 'connect_url', 'tacppp_fs_settings_url' );
        tacppp_fs()->add_filter( 'after_skip_url', 'tacppp_fs_settings_url' );
        tacppp_fs()->add_filter( 'after_connect_url', 'tacppp_fs_settings_url' );
        tacppp_fs()->add_filter( 'after_pending_connect_url', 'tacppp_fs_settings_url' );
    }

    /**
     * Include files.
     */
    function tacpp_include_extra_plugin_files() {

        // Include Class files
        $files = array(
            'app/main/class-tacpp4-terms-conditions-per-product',
            'app/main/class-tacpp4-terms-conditions-accept-log',
            'app/main/class-tacpp4-terms-conditions-modal',
            'app/main/class-tacpp4-terms-conditions-settings',
            'app/main/class-tacpp4-terms-conditions-per-categories',
            'app/main/class-tacpp4-terms-conditions-admin-notices',
            'app/main/class-tacpp4-terms-and-conditions-checkout-block',
        );


        // Include Includes files
        $includes = array();

        // Merge the two arrays
        $files = array_merge( $files, $includes );

        foreach ( $files as $file ) {
            // Include functions file.
            require TACPP4_PLUGIN_PATH . $file . '.php';
        }
    }

    add_action( 'plugins_loaded', 'tacpp_include_extra_plugin_files' );

    /**
     * Load Terms and Conditions per product textdomain.
     */
    function tacpp_language_textdomain_init() {
        // Localization
        load_plugin_textdomain( 'terms-and-conditions-per-product', false,
            dirname( plugin_basename( __FILE__ ) ) . "/languages" );
    }

    // Add actions
    add_action( 'init', 'tacpp_language_textdomain_init' );

    add_filter( 'plugin_row_meta', 'tacpp_plugin_row_meta', 10, 2 );
    function tacpp_plugin_row_meta( $links, $file ) {
        if ( plugin_basename( __FILE__ ) === $file ) {
            $row_meta = array(
                '<a href="https://tacpp-pro.com/documentation/" target="_blank">' . __( 'Docs', 'terms-and-conditions-per-product' ) . '</a>',
                '<a href="https://tacpp-pro.com/support/" target="_blank">' . __( 'Support', 'terms-and-conditions-per-product' ) . '</a>',
                '<a href="https://tacpp-pro.com/changelog/" target="_blank">' . __( 'Changelog', 'terms-and-conditions-per-product' ) . '</a>',
            );

            return array_merge( $links, $row_meta );
        }

        return (array) $links;
    }

    /*
     * Check if the free plugin is enabled.
     */
    function tacpp_plugin_activate() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
    }

    register_activation_hook( __FILE__, 'tacpp_plugin_activate' );

    /**
     * Clean up on plugin uninstall
     */
    function tacpp_plugin_uninstall() {

        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        // Delete the options
        delete_option( TACPP4_Terms_Conditions_Settings::$tacpp_option_name );
    }

    register_uninstall_hook( __FILE__, 'tacpp_plugin_uninstall' );

    // INCLUDES - Need to run First
    include( TACPP4_PLUGIN_PATH . 'app/main/class-db-management.php' );
}

/**
 * WC Checkout blocks incompatibility
 */
add_action( 'before_woocommerce_init', function () {
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );
