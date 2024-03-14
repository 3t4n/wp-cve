<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.multidots.com/
 * @since             1.0.0
 * @package           SCFW_Size_Chart_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name: Product Size Charts Plugin for WooCommerce
 * Plugin URI:        https://www.thedotstore.com/woocommerce-advanced-product-size-charts/
 * Description:       Add product size charts with default template or custom size chart to any of your WooCommerce products.
 * Version:           2.4.3.2
 * Author:            theDotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       size-chart-for-woocommerce
 * Domain Path:       /languages
 * 
 * WC requires at least: 4.5
 * WP tested up to: 6.2.2
 * WC tested up to: 7.9.0
 * Requires PHP: 7.2
 * Requires at least: 4.0
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'scfw_fs' ) ) {
    scfw_fs()->set_basename( false, __FILE__ );
    return;
}


if ( !function_exists( 'scfw_fs' ) ) {
    /**
     * Freemius init.
     *
     * @return Freemius
     * @throws Freemius_Exception
     */
    function scfw_fs()
    {
        global  $scfw_fs ;
        
        if ( !isset( $scfw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $scfw_fs = fs_dynamic_init( array(
                'id'               => '3495',
                'slug'             => 'size-chart-get-started',
                'type'             => 'plugin',
                'public_key'       => 'pk_921eefb3cf0a9c96d9d187aa72ad1',
                'is_premium'       => false,
                'has_addons'       => false,
                'has_paid_plans'   => true,
                'is_org_compliant' => false,
                'trial'            => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'             => array(
                'slug'       => 'size-chart-get-started',
                'first-path' => 'admin.php?page=size-chart-get-started',
                'contact'    => false,
                'support'    => false,
            ),
                'is_live'          => true,
            ) );
        }
        
        return $scfw_fs;
    }
    
    scfw_fs();
    do_action( 'scfw_fs_loaded' );
    scfw_fs()->get_upgrade_url();
}

if ( !defined( 'SCFW_PLUGIN_URL' ) ) {
    define( 'SCFW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'SCFW_PLUGIN_VERSION' ) ) {
    define( 'SCFW_PLUGIN_VERSION', '2.4.3.2' );
}
if ( !defined( 'SCFW_PLUGIN_NAME' ) ) {
    define( 'SCFW_PLUGIN_NAME', 'Product Size Charts Plugin for WooCommerce' );
}
if ( !defined( 'SCFW_PLUGIN_DIR' ) ) {
    define( 'SCFW_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'SCFW_PLUGIN_DIR_PATH' ) ) {
    define( 'SCFW_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'SCFW_PLUGIN_BASENAME' ) ) {
    define( 'SCFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'SCFW_STORE_URL' ) ) {
    define( 'SCFW_STORE_URL', 'https://www.thedotstore.com/' );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-size-chart-for-woocommerce-activator.php
 */
if ( !function_exists( 'scfw_activate_size_chart_for_woocommerce' ) ) {
    function scfw_activate_size_chart_for_woocommerce()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-size-chart-for-woocommerce-activator.php';
        SCFW_Size_Chart_For_Woocommerce_Activator::activate();
    }

}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-size-chart-for-woocommerce-deactivator.php
 */
if ( !function_exists( 'scfw_deactivate_size_chart_for_woocommerce' ) ) {
    function scfw_deactivate_size_chart_for_woocommerce()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-size-chart-for-woocommerce-deactivator.php';
        SCFW_Size_Chart_For_Woocommerce_Deactivator::deactivate();
    }

}
register_activation_hook( __FILE__, 'scfw_activate_size_chart_for_woocommerce' );
register_deactivation_hook( __FILE__, 'scfw_deactivate_size_chart_for_woocommerce' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-size-chart-for-woocommerce.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
if ( !function_exists( 'scfw_run_size_chart_for_woocommerce' ) ) {
    function scfw_run_size_chart_for_woocommerce()
    {
        $plugin_post_type_name = 'size-chart';
        $plugin_name = esc_attr__( 'Product Size Charts', 'size-chart-for-woocommerce' );
        $plugin_version = esc_attr__( SCFW_PLUGIN_VERSION, 'size-chart-for-woocommerce' );
        $plugin = new SCFW_Size_Chart_For_Woocommerce( $plugin_name, $plugin_version, $plugin_post_type_name );
        $plugin->run();
    }

}
/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since 1.0.0
 */
if ( !function_exists( 'scfw_size_chart_initialize_plugin' ) ) {
    function scfw_size_chart_initialize_plugin()
    {
        /*Check WooCommerce Active or not*/
        $active_plugins = get_option( 'active_plugins', array() );
        
        if ( is_multisite() ) {
            $network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
            $active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
            $active_plugins = array_unique( $active_plugins );
            
            if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', $active_plugins ), true ) ) {
                add_action( 'admin_notices', 'scfw_size_chart_plugin_admin_notice' );
            } else {
                scfw_run_size_chart_for_woocommerce();
            }
        
        } else {
            
            if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
                add_action( 'admin_notices', 'scfw_size_chart_plugin_admin_notice' );
            } else {
                scfw_run_size_chart_for_woocommerce();
            }
        
        }
        
        // Load the language file for translating the plugin strings
        load_plugin_textdomain( 'size-chart-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}
add_action( 'plugins_loaded', 'scfw_size_chart_initialize_plugin' );
/**
 * Show admin notice in case of WooCommerce plugin is missing.
 *
 * @since 1.0.0
 */
if ( !function_exists( 'scfw_size_chart_plugin_admin_notice' ) ) {
    function scfw_size_chart_plugin_admin_notice()
    {
        $size_chart_plugin = esc_html__( 'Size Chart for WooCommerce', 'size-chart-for-woocommerce' );
        $wc_plugin = esc_html__( 'WooCommerce', 'size-chart-for-woocommerce' );
        ?>
        <div class="error">
            <p>
                <?php 
        echo  sprintf( esc_html__( '%1$s requires %2$s to be installed & activated!', 'size-chart-for-woocommerce' ), '<strong>' . esc_html( $size_chart_plugin ) . '</strong>', '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank"><strong>' . esc_html( $wc_plugin ) . '</strong></a>' ) ;
        ?>
            </p>
        </div>
        <?php 
    }

}
/**
 * Hide freemius account tab
 *
 * @since 2.4.3
 */

if ( !function_exists( 'scfw_hide_account_tab' ) ) {
    function scfw_hide_account_tab()
    {
        return true;
    }
    
    scfw_fs()->add_filter( 'hide_account_tabs', 'scfw_hide_account_tab' );
}

/**
 * Include plugin header on freemius account page
 *
 * @since 2.4.3
 */

if ( !function_exists( 'scfw_load_plugin_header_after_account' ) ) {
    function scfw_load_plugin_header_after_account()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/header/plugin-header.php';
    }
    
    scfw_fs()->add_action( 'after_account_details', 'scfw_load_plugin_header_after_account' );
}

/**
 * Hide billing and payments details from freemius account page
 *
 * @since 2.4.3
 */

if ( !function_exists( 'scfw_hide_billing_and_payments_info' ) ) {
    function scfw_hide_billing_and_payments_info()
    {
        return true;
    }
    
    scfw_fs()->add_action( 'hide_billing_and_payments_info', 'scfw_hide_billing_and_payments_info' );
}

/**
 * Hide powerd by popup from freemius account page
 *
 * @since 2.4.3
 */

if ( !function_exists( 'scfw_hide_freemius_powered_by' ) ) {
    function scfw_hide_freemius_powered_by()
    {
        return true;
    }
    
    scfw_fs()->add_action( 'hide_freemius_powered_by', 'scfw_hide_freemius_powered_by' );
}

/**
 * Start plugin setup wizard before license activation screen
 *
 * @since 2.4.3
 */

if ( !function_exists( 'scfw_load_plugin_setup_wizard_connect_before' ) ) {
    function scfw_load_plugin_setup_wizard_connect_before()
    {
        require_once plugin_dir_path( __FILE__ ) . 'admin/partials/dots-plugin-setup-wizard.php';
        ?>
        <div class="tab-panel" id="step5">
            <div class="ds-wizard-wrap">
                <div class="ds-wizard-content">
                    <h2 class="cta-title"><?php 
        echo  esc_html__( 'Activate Plugin', 'size-chart-for-woocommerce' ) ;
        ?></h2>
                </div>
        <?php 
    }
    
    scfw_fs()->add_action( 'connect/before', 'scfw_load_plugin_setup_wizard_connect_before' );
}

/**
 * End plugin setup wizard after license activation screen
 *
 * @since 2.4.3
 */

if ( !function_exists( 'scfw_load_plugin_setup_wizard_connect_after' ) ) {
    function scfw_load_plugin_setup_wizard_connect_after()
    {
        ?>
        </div>
        </div>
        </div>
        </div>
        <?php 
    }
    
    scfw_fs()->add_action( 'connect/after', 'scfw_load_plugin_setup_wizard_connect_after' );
}

/**
 * Plugin compability with WooCommerce HPOS
 *
 * @since 2.4.3
 */
add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );