<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://uriahsvictor.com
 * @since             1.0.0
 * @package           Lpac_DPS
 *
 * @wordpress-plugin
 * Plugin Name:      Chwazi - Delivery & Pickup Scheduling for WooCommerce
 * Plugin URI:       https://chwazidatetime.com
 * Description:       Allows customers to set their delivery/pickup date and time for an order.
 * Version:           1.2.6
 * Author:           Uriahs Victor
 * Author URI:        https://chwazidatetime.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 3.0
 * WC tested up to: 8.6
 * Requires PHP:      7.4
 * Text Domain:       delivery-and-pickup-scheduling-for-woocommerce
 */
if ( !defined( 'WPINC' ) ) {
    die;
}
if ( !defined( 'LPAC_DPS_VERSION' ) ) {
    define( 'LPAC_DPS_VERSION', '1.2.6' );
}
/**
 * Check PHP version
 */
if ( function_exists( 'phpversion' ) ) {
    
    if ( version_compare( phpversion(), '7.4', '<' ) ) {
        add_action( 'admin_notices', function () {
            echo  "<div class='notice notice-error is-dismissible'>" ;
            /* translators: 1: Opening <p> HTML element 2: Opening <strong> HTML element 3: Closing <strong> HTML element 4: Closing <p> HTML element  */
            printf(
                esc_html__( '%1$s%2$s Chwazi - Delivery & Pickup Scheduling NOTICE:%3$s PHP version too low to use this plugin. Please change to at least PHP 7.4. You can contact your web host for assistance in updating your PHP version.%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ),
                '<p>',
                '<strong>',
                '</strong>',
                '</p>'
            );
            echo  '</div>' ;
        } );
        return;
    }

}
/**
 * Check PHP versions
 */
if ( defined( 'PHP_VERSION' ) ) {
    
    if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
        add_action( 'admin_notices', function () {
            echo  "<div class='notice notice-error is-dismissible'>" ;
            /* translators: 1: Opening <p> HTML element 2: Opening <strong> HTML element 3: Closing <strong> HTML element 4: Closing <p> HTML element  */
            printf(
                esc_html__( '%1$s%2$s Chwazi - Delivery & Pickup Scheduling NOTICE:%3$s PHP version too low to use this plugin. Please change to at least PHP 7.4. You can contact your web host for assistance in updating your PHP version.%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ),
                '<p>',
                '<strong>',
                '</strong>',
                '</p>'
            );
            echo  '</div>' ;
        } );
        return;
    }

}
/**
 * Check that WooCommerce is active.
 *
 * This needs to happen before freemius does any work.
 *
 * @since 1.0.0
 */
if ( !function_exists( 'dps_wc_active' ) ) {
    function dps_wc_active()
    {
        $active_plugins = (array) apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }
        return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) || class_exists( 'WooCommerce' );
    }

}

if ( !dps_wc_active() ) {
    add_action( 'admin_notices', function () {
        echo  "<div class='notice notice-error is-dismissible'>" ;
        /* translators: 1: Opening <p> HTML element 2: Opening <strong> HTML element 3: Closing <strong> HTML element 4: Closing <p> HTML element  */
        printf(
            esc_html__( '%1$s%2$sChwazi - Delivery & Pickup Scheduling NOTICE:%3$s WooCommerce is not activated, please activate it to use the plugin.%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            '<p>',
            '<strong>',
            '</strong>',
            '</p>'
        );
        echo  '</div>' ;
    } );
    return;
}


if ( function_exists( 'dps_fs' ) ) {
    dps_fs()->set_basename( false, __FILE__ );
} else {
    // Setup Freemius.
    
    if ( !function_exists( 'dps_fs' ) ) {
        /**
         * Create a helper function for easy SDK access.
         */
        function dps_fs()
        {
            global  $dps_fs ;
            
            if ( !isset( $dps_fs ) ) {
                // Include Freemius SDK.
                require_once __DIR__ . '/vendor/freemius/wordpress-sdk/start.php';
                $dps_fs = fs_dynamic_init( array(
                    'id'              => '11538',
                    'slug'            => 'delivery-and-pickup-scheduling-for-woocommerce',
                    'premium_slug'    => 'delivery-and-pickup-scheduling-for-woocommerce-pro',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_bddcf7d75a54f4e306fdfe9d023df',
                    'is_premium'      => false,
                    'premium_suffix'  => 'PRO',
                    'has_addons'      => true,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'   => 'lpac-dps-menu',
                    'parent' => array(
                    'slug' => 'sl-plugins-menu',
                ),
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $dps_fs;
        }
        
        // Init Freemius.
        dps_fs();
        // Signal that SDK was initiated.
        do_action( 'dps_fs_loaded' );
    }
    
    /**
     * Composer autoload. DO NOT PLACE THIS LINE BEFORE FREEMIUS SDK RUNS.
     *
     * Doing that will cause the plugin to throw an error when trying to activate PRO when the Free version is active or vice versa.
     * This is because both PRO and Free are generated from the same codebase, meaning composer autoloader file would already be
     * present and throw an error when trying to be redefined.
     */
    require_once __DIR__ . '/vendor/autoload.php';
    if ( !function_exists( 'activate_lpac_dps' ) ) {
        /**
         * The code that runs during plugin activation.
         * This action is documented in includes/class-lpac-dps-activator.php
         */
        function activate_lpac_dps()
        {
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-lpac-dps-activator.php';
            Lpac_DPS_Activator::activate();
        }
    
    }
    if ( !function_exists( 'deactivate_lpac_dps' ) ) {
        /**
         * The code that runs during plugin deactivation.
         * This action is documented in includes/class-lpac-dps-deactivator.php
         */
        function deactivate_lpac_dps()
        {
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-lpac-dps-deactivator.php';
            Lpac_DPS_Deactivator::deactivate();
        }
    
    }
    register_activation_hook( __FILE__, 'activate_lpac_dps' );
    register_deactivation_hook( __FILE__, 'deactivate_lpac_dps' );
    require __DIR__ . '/class-uninstall.php';
    
    if ( function_exists( 'dps_fs' ) ) {
        dps_fs()->add_action( 'after_uninstall', array( new DPS_Uninstall(), 'remove_plugin_settings' ) );
        dps_fs()->add_filter( 'plugin_icon', function () {
            return __DIR__ . '/assets/admin/img/logo.png';
        } );
    }
    
    require_once plugin_dir_path( __FILE__ ) . 'lib/codestar-framework/classes/setup.class.php';
    define( 'LPAC_DPS_BASE_FILE', basename( plugin_dir_path( __FILE__ ) ) );
    define( 'LPAC_DPS_PLUGIN_NAME', 'delivery-and-pickup-scheduling-for-woocommerce' );
    define( 'LPAC_DPS_PLUGIN_DIR', __DIR__ . '/' );
    define( 'LPAC_DPS_PLUGIN_ASSETS_DIR', __DIR__ . '/assets/' );
    define( 'LPAC_DPS_PLUGIN_ASSETS_PATH_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
    define( 'LPAC_DPS_PLUGIN_PATH_URL', plugin_dir_url( __FILE__ ) );
    define( 'LPAC_DPS_CSF_ID', 'lpac_dps' );
    $debug = false;
    if ( defined( 'SL_DEV_DEBUGGING' ) ) {
        $debug = true;
    }
    define( 'LPAC_DPS_DEBUG', $debug );
    // HPOS Compatibility.
    add_action( 'before_woocommerce_init', function () {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );
    // Blocks checkout incompatibility.
    add_action( 'before_woocommerce_init', function () {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
        }
    } );
    if ( !function_exists( 'soaringleads_chwazi_init' ) ) {
        /**
         * Bootstrap plugin.
         *
         * @return void
         * @since 1.0.0
         */
        function soaringleads_chwazi_init()
        {
            do_action( 'dps_before_init' );
            $instance = \Lpac_DPS\Bootstrap\Main::get_instance();
            $instance->run();
            do_action( 'dps_after_init' );
        }
    
    }
    add_action( 'plugins_loaded', 'soaringleads_chwazi_init' );
}
