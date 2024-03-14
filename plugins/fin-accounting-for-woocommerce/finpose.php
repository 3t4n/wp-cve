<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://finpose.com
 * @since             1.0.0
 * @package           finpose
 *
 * @wordpress-plugin
 * Plugin Name:       Finpose
 * Plugin URI:        https://finpose.com
 * Description:       Bookkeeping for WooCommerce right from the WordPress Dashboard.
 * Version:           4.5.2
 * WC requires at least:  4.0.0
 * WC tested up to:       6.3.1
 * Author:            Finpose
 * Author URI:        https://finpose.com
 * Text Domain:       finpose
 * Domain Path:       /languages
 *
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !function_exists( 'fin_fs' ) ) {
    // Create a helper function for easy SDK access.
    function fin_fs()
    {
        global  $fin_fs ;
        
        if ( !isset( $fin_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_4942_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_4942_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $fin_fs = fs_dynamic_init( array(
                'id'             => '4942',
                'slug'           => 'finpose',
                'type'           => 'plugin',
                'public_key'     => 'pk_209dd6d3d14b31c8bbee412865cac',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => false,
            ),
                'menu'           => array(
                'slug'       => 'fin_dashboard',
                'first-path' => 'admin.php?page=fin_dashboard',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $fin_fs;
    }
    
    // Init Freemius.
    fin_fs();
    // Signal that SDK was initiated.
    do_action( 'fin_fs_loaded' );
}

function fin_support_forum_url( $wp_org_support_forum_url )
{
    return 'https://wordpress.org/support/plugin/fin-accounting-for-woocommerce/';
}

fin_fs()->add_filter( 'support_forum_url', 'fin_support_forum_url' );
define( 'FINPOSE_VERSION', '4.5.2' );
define( 'FINPOSE_DBVERSION', '2.4.0' );
define( 'FINPOSE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FINPOSE_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'FINPOSE_ENV', 'production' );
define( 'FINPOSE_WP_URL', get_site_url() );
define( 'FINPOSE_WPADMIN_URL', get_admin_url() );
/**
 * Check if WooCommerce is installed & activated
 */
function fin_is_woocommerce_activated()
{
    $blog_plugins = get_option( 'active_plugins', array() );
    $site_plugins = ( is_multisite() ? (array) maybe_unserialize( get_site_option( 'active_sitewide_plugins' ) ) : array() );
    
    if ( in_array( 'woocommerce/woocommerce.php', $blog_plugins ) || isset( $site_plugins['woocommerce/woocommerce.php'] ) ) {
        return true;
    } else {
        return false;
    }

}

/**
 * Generate error message if WooCommerce is not active
 */
function fin_need_woocommerce()
{
    $plugin_name = "Finpose";
    printf( '<div class="notice error"><p><strong>%s</strong></p></div>', sprintf( esc_html__( '%s requires WooCommerce 3.0 or greater to be installed & activated!', 'finpose' ), $plugin_name ) );
}

/**
 * Return error if WooCommerce is not active
 */

if ( fin_is_woocommerce_activated() ) {
    /**
     * Activation hook
     */
    function finpose_activate()
    {
        require_once FINPOSE_PLUGIN_DIR . 'includes/class-finpose-activator.php';
        finpose_Activator::activate();
    }
    
    /**
     * Deactivation hook
     */
    function finpose_deactivate()
    {
        require_once FINPOSE_PLUGIN_DIR . 'includes/class-finpose-deactivator.php';
        finpose_Deactivator::deactivate();
    }
    
    /**
     * Register activation/deactivation hooks
     */
    register_activation_hook( __FILE__, 'finpose_activate' );
    register_deactivation_hook( __FILE__, 'finpose_deactivate' );
    /**
     * If version mismatch, upgrade
     */
    if ( FINPOSE_VERSION != get_option( 'finpose_version' ) ) {
        add_action( 'plugin_loaded', 'finpose_activate' );
    }
    if ( FINPOSE_DBVERSION != get_option( 'finpose_db_version' ) ) {
        add_action( 'plugin_loaded', 'finpose_activate' );
    }
    /**
     * Handle AJAX requests
     */
    add_action( 'wp_ajax_finpose', 'finpose_ajax_request' );
    function finpose_ajax_request()
    {
        
        if ( current_user_can( 'view_woocommerce_reports' ) ) {
            ob_start();
            require FINPOSE_PLUGIN_DIR . 'includes/class-finpose-ajax.php';
            $ajax = new finpose_Ajax();
            // Sanitize every POST data as string, additional sanitation will be applied inside methods when necessary
            $p = array_map( 'sanitize_text_field', $_POST );
            try {
                $ajax->run( $p );
            } catch ( Exception $e ) {
                echo  json_encode( array(
                    'error' => array(
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                ),
                ) ) ;
            }
            wp_die();
        }
    
    }
    
    /**
     * Adjust Inventory when a sale is made (completed)
     */
    function fin_woocommerce_order_status_completed( $order_id )
    {
        global  $wpdb ;
        $order = wc_get_order( $order_id );
        $items = $order->get_items();
        $itemids = array();
        foreach ( $items as $item ) {
            $productid = $item->get_product_id();
            $variationid = $item->get_variation_id();
            $itemid = ( $variationid ? $variationid : $productid );
            $itemids[] = $itemid;
            $quantity = $item->get_quantity();
            $subtotal = $subtotal = $item->get_subtotal();
            $soldprice = $subtotal / $quantity;
            for ( $i = 1 ;  $i <= $quantity ;  $i++ ) {
                $nextinstock = $wpdb->get_var( "SELECT iid FROM fin_inventory WHERE pid='{$itemid}' AND is_sold='0' ORDER BY timecr ASC LIMIT 1" );
                
                if ( $nextinstock ) {
                    $extdata = json_encode( array(
                        'orderid' => $order_id,
                    ) );
                    $wpdb->update( 'fin_inventory', array(
                        'is_sold'   => '1',
                        'timesold'  => time(),
                        'soldprice' => $soldprice,
                        'data'      => $extdata,
                    ), array(
                        'iid' => $nextinstock,
                    ) );
                }
            
            }
        }
        $accs = get_option( 'finpose_accounts' );
        $pm = $order->get_payment_method();
        
        if ( isset( $accs[$pm] ) ) {
            $acc = $accs[$pm];
            $tt = $order->get_total();
            $fee = $tt / 100 * $acc['fee'];
            $start = rand( 0, 10 );
            $coid = substr( md5( time() ), $start, 8 );
            $siteid = 0;
            if ( function_exists( 'is_multisite' ) && is_multisite() ) {
                $siteid = get_current_blog_id();
            }
            $now = time();
            $exparr = array(
                'coid'     => $coid,
                'siteid'   => $siteid,
                'type'     => 'expense',
                'cat'      => 'gateway_fees',
                'paidwith' => $pm,
                'items'    => implode( ',', $itemids ),
                'amount'   => $fee,
                'tr'       => 0,
                'name'     => 'Transaction Fees',
                'notes'    => 'Order (' . $order_id . ')',
                'datepaid' => $now,
                'timecr'   => $now,
            );
            $wpdb->insert( 'fin_costs', $exparr );
        }
    
    }
    
    add_action(
        'woocommerce_order_status_completed',
        'fin_woocommerce_order_status_completed',
        10,
        1
    );
    $now = time();
    $exparr = array(
        'coid'     => 'abed1234',
        'siteid'   => 0,
        'type'     => 'expense',
        'cat'      => 'gateway_fees',
        'paidwith' => 'paypal',
        'items'    => '21,15',
        'amount'   => 21.1,
        'tr'       => 0,
        'name'     => 'Transaction Fees',
        'notes'    => 'Order (234233)',
        'datepaid' => $now,
        'timecr'   => $now,
    );
    //$wpdb->insert('fin_costs', $exparr);
    /**
     * Adjust Inventory when order is refunded
     */
    function fin_woocommerce_order_refunded( $order_id )
    {
        global  $wpdb ;
        $order = wc_get_order( $order_id );
        $items = $order->get_items();
        foreach ( $items as $item ) {
            $productid = $item->get_product_id();
            $variationid = $item->get_variation_id();
            $itemid = ( $variationid ? $variationid : $productid );
            $quantity = $item->get_quantity();
            $subtotal = $subtotal = $item->get_subtotal();
            $soldprice = $subtotal / $quantity;
            for ( $i = 1 ;  $i <= $quantity ;  $i++ ) {
                $lastitemsold = $wpdb->get_var( "SELECT iid FROM fin_inventory WHERE pid='{$itemid}' AND is_sold='1' ORDER BY timesold DESC LIMIT 1" );
                
                if ( $lastitemsold ) {
                    $extdata = json_encode( array(
                        'orderID'  => $order_id,
                        'refunded' => 1,
                    ) );
                    $wpdb->update( 'fin_inventory', array(
                        'is_sold'   => '0',
                        'timesold'  => 0,
                        'soldprice' => 0,
                        'data'      => $extdata,
                    ), array(
                        'iid' => $lastitemsold,
                    ) );
                }
            
            }
        }
    }
    
    add_action(
        'woocommerce_order_refunded',
        'fin_woocommerce_order_refunded',
        10,
        1
    );
    /**
     * Custom product query / LIKE operator
     */
    add_filter(
        'woocommerce_product_data_store_cpt_get_products_query',
        'handle_custom_query_var',
        10,
        2
    );
    function handle_custom_query_var( $query, $query_vars )
    {
        if ( isset( $query_vars['like_name'] ) && !empty($query_vars['like_name']) ) {
            $query['s'] = esc_sql( $query_vars['like_name'] );
        }
        return $query;
    }
    
    /**
     * Load Finpose
     */
    add_action( 'wp_loaded', function () {
        
        if ( current_user_can( 'view_woocommerce_reports' ) ) {
            $user = wp_get_current_user();
            $roles = (array) $user->roles;
            
            if ( is_admin() || in_array( "shop_manager", $roles ) ) {
                require FINPOSE_PLUGIN_DIR . 'includes/class-finpose.php';
                $plugin = new finpose();
                $plugin->run();
            }
        
        }
    
    }, 30 );
} else {
    add_action( 'admin_notices', 'fin_need_woocommerce' );
    return;
}

function plugin_load_textdomain()
{
    load_plugin_textdomain( 'finpose', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'init', 'plugin_load_textdomain' );