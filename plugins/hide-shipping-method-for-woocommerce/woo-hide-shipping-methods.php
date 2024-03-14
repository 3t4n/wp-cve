<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.thedotstore.com/
 * @since             1.0.0
 * @package           Woo_Hide_Shipping_Methods
 *
 * @wordpress-plugin
 * Plugin Name: Hide Shipping Method For WooCommerce
 * Plugin URI:          https://www.thedotstore.com/hide-shipping-method-for-woocommerce
 * Description:         Allows store owners to hide shipping methods based on specific conditions!
 * Version:             1.4.1
 * Author:              theDotstore
 * Author URI:          https://www.thedotstore.com/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         woo-hide-shipping-methods
 * Domain Path:         /languages
 *
 * WC requires at least: 4.1
 * WC tested up to:      8.3.1
 * WP tested up to:      6.4.2
 * Requires PHP:         5.3
 * Requires at least:    5.0
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'whsm_fs' ) ) {
    whsm_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'whsm_fs' ) ) {
        // Create a helper function for easy SDK access.
        function whsm_fs()
        {
            global  $whsm_fs ;
            
            if ( !isset( $whsm_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_4743_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_4743_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $whsm_fs = fs_dynamic_init( array(
                    'id'             => '4743',
                    'slug'           => 'woo-hide-shipping-methods',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_71be4de10d0508098c1b7ca85e591',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'           => 'whsm-start-page',
                    'override_exact' => true,
                    'contact'        => false,
                    'support'        => false,
                    'network'        => true,
                    'parent'         => array(
                    'slug' => 'woocommerce',
                ),
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $whsm_fs;
        }
        
        // Init Freemius.
        whsm_fs();
        // Signal that SDK was initiated.
        do_action( 'whsm_fs_loaded' );
        function whsm_fs_connect_settings_url()
        {
            return admin_url( 'admin.php?page=whsm-start-page' );
        }
        
        function whsm_fs_settings_url()
        {
            return admin_url( 'admin.php?page=whsm-start-page' );
        }
        
        whsm_fs()->add_filter( 'connect_url', 'whsm_fs_connect_settings_url' );
        whsm_fs()->add_filter( 'after_skip_url', 'whsm_fs_settings_url' );
        whsm_fs()->add_filter( 'after_connect_url', 'whsm_fs_settings_url' );
        whsm_fs()->add_filter( 'after_pending_connect_url', 'whsm_fs_settings_url' );
        whsm_fs()->get_upgrade_url();
    }

}

if ( !defined( 'WOO_HIDE_SHIPPING_METHODS_VERSION' ) ) {
    define( 'WOO_HIDE_SHIPPING_METHODS_VERSION', '1.4.1' );
}
if ( !defined( 'WHSM_PLUGIN_URL' ) ) {
    define( 'WHSM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'WHSM_PLUGIN_DIR' ) ) {
    define( 'WHSM_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'WHSM_PLUGIN_DIR_PATH' ) ) {
    define( 'WHSM_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'WHSM_SLUG' ) ) {
    define( 'WHSM_SLUG', 'woo-hide-shipping-methods' );
}
if ( !defined( 'WHSM_PLUGIN_BASENAME' ) ) {
    define( 'WHSM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'WHSM_PLUGIN_NAME' ) ) {
    define( 'WHSM_PLUGIN_NAME', 'Hide Shipping Method For WooCommerce' );
}
if ( !defined( 'WHSM_TEXT_DOMAIN' ) ) {
    define( 'WHSM_TEXT_DOMAIN', 'woo-hide-shipping-methods' );
}
if ( !defined( 'WHSM_PERTICULAR_FEE_AMOUNT_NOTICE' ) ) {
    define( 'WHSM_PERTICULAR_FEE_AMOUNT_NOTICE', 'Use this toggle to enable or disable the advanced hide shipping rules below.' );
}
if ( !defined( 'WHSM_STORE_URL' ) ) {
    define( 'WHSM_STORE_URL', 'https://www.thedotstore.com/' );
}
add_action( 'admin_init', 'whsm__initialize_plugin' );
/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'whsm__initialize_plugin' ) ) {
    function whsm__initialize_plugin()
    {
        /*Check WooCommerce Active or not*/
        $active_plugins = get_option( 'active_plugins', array() );
        
        if ( is_multisite() ) {
            $network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
            $active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
            $active_plugins = array_unique( $active_plugins );
            if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', $active_plugins ), true ) ) {
                deactivate_plugins( '/woo-hide-shipping-methods-premium/woo-hide-shipping-methods.php', true );
            }
        } else {
            if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
                deactivate_plugins( '/woo-hide-shipping-methods-premium/woo-hide-shipping-methods.php', true );
            }
        }
        
        load_plugin_textdomain( 'woo-hide-shipping-methods', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-hide-shipping-methods-activator.php
 */
if ( !function_exists( 'activate_woo_hide_shipping_methods' ) ) {
    function activate_woo_hide_shipping_methods()
    {
        set_transient( 'whsm-admin-notice', true );
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-hide-shipping-methods-activator.php';
        Woo_Hide_Shipping_Methods_Activator::activate();
    }

}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-hide-shipping-methods-deactivator.php
 */
if ( !function_exists( 'deactivate_woo_hide_shipping_methods' ) ) {
    function deactivate_woo_hide_shipping_methods()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-hide-shipping-methods-deactivator.php';
        Woo_Hide_Shipping_Methods_Deactivator::deactivate();
    }

}
register_activation_hook( __FILE__, 'activate_woo_hide_shipping_methods' );
register_deactivation_hook( __FILE__, 'deactivate_woo_hide_shipping_methods' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-hide-shipping-methods.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'run_woo_hide_shipping_methods' ) ) {
    function run_woo_hide_shipping_methods()
    {
        $plugin = new Woo_Hide_Shipping_Methods();
        $plugin->run();
    }

}
run_woo_hide_shipping_methods();
/**
 * Hide freemius account tab
 *
 * @since 1.4.0
 */

if ( !function_exists( 'whsm_hide_account_tab' ) ) {
    function whsm_hide_account_tab()
    {
        return true;
    }
    
    whsm_fs()->add_filter( 'hide_account_tabs', 'whsm_hide_account_tab' );
}

/**
 * Include plugin header on freemius account page
 *
 * @since 1.4.0
 */

if ( !function_exists( 'whsm_load_plugin_header_after_account' ) ) {
    function whsm_load_plugin_header_after_account()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/header/plugin-header.php';
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php 
    }
    
    whsm_fs()->add_action( 'after_account_details', 'whsm_load_plugin_header_after_account' );
}

/**
 * Hide billing and payments details from freemius account page
 *
 * @since 1.4.0
 */

if ( !function_exists( 'whsm_hide_billing_and_payments_info' ) ) {
    function whsm_hide_billing_and_payments_info()
    {
        return true;
    }
    
    whsm_fs()->add_action( 'hide_billing_and_payments_info', 'whsm_hide_billing_and_payments_info' );
}

/**
 * Hide powerd by popup from freemius account page
 *
 * @since 1.4.0
 */

if ( !function_exists( 'whsm_hide_freemius_powered_by' ) ) {
    function whsm_hide_freemius_powered_by()
    {
        return true;
    }
    
    whsm_fs()->add_action( 'hide_freemius_powered_by', 'whsm_hide_freemius_powered_by' );
}

/**
 * Start plugin setup wizard before license activation screen
 *
 * @since 1.4.0
 */

if ( !function_exists( 'whsm_load_plugin_setup_wizard_connect_before' ) ) {
    function whsm_load_plugin_setup_wizard_connect_before()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/dots-plugin-setup-wizard.php';
        ?>
        <div class="tab-panel" id="step5">
            <div class="ds-wizard-wrap">
                <div class="ds-wizard-content">
                    <h2 class="cta-title"><?php 
        echo  esc_html__( 'Activate Plugin', 'woo-hide-shipping-methods' ) ;
        ?></h2>
                </div>
        <?php 
    }
    
    whsm_fs()->add_action( 'connect/before', 'whsm_load_plugin_setup_wizard_connect_before' );
}

/**
 * End plugin setup wizard after license activation screen
 *
 * @since 1.4.0
 */

if ( !function_exists( 'whsm_load_plugin_setup_wizard_connect_after' ) ) {
    function whsm_load_plugin_setup_wizard_connect_after()
    {
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php 
    }
    
    whsm_fs()->add_action( 'connect/after', 'whsm_load_plugin_setup_wizard_connect_after' );
}

/**
 * Plugin compability with WooCommerce HPOS
 *
 * @since    1.4.0
 */
add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );