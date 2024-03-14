<?php

/*
Plugin Name: Walletdoc Payment Gateway for WooCommerce
Plugin URI: http://www.walletdoc.com/
Description: Accept card payments on your store with Walletdoc payment gateway
Version:  1.5.1
Author: Walletdoc
Email: support@walletdoc.com
Requires at least: 4.4
Tested up to: 6.3
WC requires at least: 3.0
WC tested up to: 8.0.2
Text Domain: woocommerce-walletdoc
Domain Path: /languages
*/

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

define( 'WC_WALLETDOC_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

// Add settings link on plugin page

// function walletdoc_wc_plugin_settings_link( $links ) {
//     $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=bank2bank">Bank2Bank Settings </a>';
//     array_unshift( $links, $settings_link );
//     return $links;
// }

// $plugin = plugin_basename( __FILE__ );
// add_filter( "plugin_action_links_$plugin", 'walletdoc_wc_plugin_settings_link' );

function walletdoc_wc_required_admin_notice() {
    echo '<div class="updated error notice"><p>';
    echo _e( '<b>Walletdoc</b> Plugin requires WooCommerce to be Installed First!', 'my-text-domain' );
    echo '</p></div>';
}

function walletdoc_wc_required_currency_notice() {
    echo '<div class="updated error notice">'
    . '<p><strong>';
    echo  _e( 'Gateway Disabled', 'woocommerce-walletdoc' );
    echo '</strong>';
    echo sprintf( __( ' Choose South African Rands as your store currency in %1$sGeneral Settings%2$s to enable the Walletdoc Payment Gateway.', 'woocommerce-walletdoc' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=general' ) ) . '">', '</a>' );
    echo '</p></div>';

}

if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
   
    add_action( 'admin_notices', 'walletdoc_wc_required_admin_notice' );

} else {
  
    function WC_Walletdoc_log( $message ) {
        $log = new WC_Logger();
        $log->add( 'walletdoc', $message );
    }

    # register our GET variables

    function add_query_vars_filter( $vars ) {
        $vars[] = 'status';
        $vars[] = 'id';
        return $vars;
    }

    add_filter( 'query_vars', 'add_query_vars_filter' );

    // add js
    add_action( 'admin_enqueue_scripts', 'walletdoc_wc_admin_scripts' );

    function woocommerce_gateway_walletdoc() {

        static $plugin;

        if ( ! isset( $plugin ) ) {

            class WC_Walletdoc {

                /**
                * The *Singleton* instance of this class
                *
                * @var Singleton
                */
                private static $instance;

                /**
                * Returns the *Singleton* instance of this class.
                *
                * @return Singleton The *Singleton* instance.
                */
                public static function get_instance() {
                    if ( null === self::$instance ) {
                        self::$instance = new self();
                    }
                    return self::$instance;
                }

                /**
                * The main Walletdoc gateway instance. Use get_main_Walletdoc_gateway() to access it.
                *
                * @var null|WC_Walletdoc_Payment_Gateway
                */
                protected $Walletdoc_gateway = null;

                /**
                * Protected constructor to prevent creating a new instance of the
                * *Singleton* via the `new` operator from outside of this class.
                */

                public function __construct() {
                    add_action( 'admin_init', [ $this, 'install' ] );

                    $this->init();

                    // add_action( 'rest_api_init', [ $this, 'register_routes' ] );
                }

                /**
                * Init the plugin after plugins_loaded so environment variables are set.
                */
                public function init() {
                    // if ( is_admin() ) {
                    //     require_once dirname( __FILE__ ) . '/includes/admin/class-wc-Walletdoc-privacy.php';
                    // }

                    
                  
                    require_once dirname( __FILE__ ) . '/includes/common/abstract-wc-walletdoc-payment.php';
                    require_once dirname( __FILE__ ) . '/includes/payment-methods/class-wc-gateway-Walletdoc-bank2bank.php';
                

                    add_filter( 'woocommerce_payment_gateways', [ $this, 'add_gateways' ] );
                    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
                    add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );

                    if ( version_compare( WC_VERSION, '3.4', '<' ) ) {
                        add_filter( 'woocommerce_get_sections_checkout', [ $this, 'filter_gateway_order_admin' ] );
                    }

                }

                /**
                * Updates the plugin version in db
                */

                public function update_plugin_version() {
                    delete_option( 'WC_Walletdoc_version' );
                    update_option( 'WC_Walletdoc_version', WC_Walletdoc_VERSION );
                }

                public function install() {
                    if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
                        return;
                    }
                }
    
             
    
                /**
                 * Add plugin action links.
                 */
                public function plugin_action_links( $links ) {
                    $plugin_links = [
                        "<a href='admin.php?page=wc-settings&tab=checkout&section=Walletdoc'>".esc_html__( 'Settings','woocommerce-gateway-Walletdoc').'</a>',
                    ];
                    return array_merge( $plugin_links, $links );
                }
    
                /**
                 * Add plugin action links.
                 *
                 * @param  array  $links Original list of plugin links.
                 * @param  string $file  Name of current file.
                 * @return array  $links Update list of plugin links.
                 */
                public function plugin_row_meta( $links, $file ) {
                    if ( plugin_basename( __FILE__ ) === $file ) {
                        $row_meta = [
                            'docs'    => '<a href = "' . esc_url( apply_filters( 'woocommerce_gateway_walletdoc_docs_url', 'https://woocommerce.com/document/Walletdoc/' ) ) . '" title = "' . esc_attr( __( 'View Documentation', 'woocommerce-gateway-Walletdoc' ) ) . '">' . __( 'Docs', 'woocommerce-gateway-Walletdoc' ) . '</a>',
                            'support' => '<a href = "' . esc_url( apply_filters( 'woocommerce_gateway_walletdoc_support_url', 'https://woocommerce.com/my-account/create-a-ticket?select=18627' ) ) . '" title = "' . esc_attr( __( 'Open a support request at WooCommerce.com', 'woocommerce-gateway-Walletdoc' ) ) . '">' . __( 'Support', 'woocommerce-gateway-Walletdoc' ) . '</a>',
                        ];
                        return array_merge( $links, $row_meta );
                    }
                    return (array) $links;
                }
    
                /**
                 * Add the gateways to WooCommerce.
                 */
                public function add_gateways( $methods ) {
                    // $methods[] = $this->get_main_Walletdoc_gateway();
    
                        $methods[] = WC_Gateway_Walletdoc_Bank2bank::class;

                    return $methods;
                }
    
                /**
                 * Modifies the order of the gateways displayed in admin.
                 */
                public function filter_gateway_order_admin( $sections ) {
                    unset( $sections['Walletdoc'] );
                    unset( $sections['Walletdoc_bank2bank'] );
                
    
                    $sections['Walletdoc'] = 'Walletdoc';
                
                    $sections['Walletdoc_bank2bank'] = __( 'Walletdoc Bank2Bank', 'woocommerce-gateway-Walletdoc' );
              
    
                    return $sections;
                }

                /**
                 * Returns the main Walletdoc payment gateway class instance.
                 *
                 * @return WC_Walletdoc_Payment_Gateway
                 */
                public function get_main_Walletdoc_gateway() {
                    if ( ! is_null( $this->Walletdoc_gateway ) ) {
                        return $this->Walletdoc_gateway;
                    }
    
                    $this->Walletdoc_gateway = new WP_Gateway_Walletdoc();
    
                    return $this->Walletdoc_gateway;
                }
            }
    
            $plugin = WC_Walletdoc::get_instance();
    
        }
    
        return $plugin;
    }


    # initialize your Gateway Class
    add_action( 'plugins_loaded', 'walletdoc_wc_payment_gateway' );

    function walletdoc_wc_payment_gateway() {
        require_once dirname( __FILE__ ) . '/includes/common/abstract-wc-walletdoc-payment.php';

        include_once 'payment_gateway.php';

        woocommerce_gateway_walletdoc();
    }

    # look for redirect from walletdoc.

    add_action( 'template_redirect', 'walletdoc_wc_payment_gateway_server' );
    add_action( 'woocommerce_payment_token_deleted', 'action_woocommerce_payment_token_deleted', 10, 2 );

    function action_woocommerce_payment_token_deleted( $tokenId, $token ) {

        include_once 'payment_token.php';
    }

    function walletdoc_wc_payment_gateway_server() {
        global $woocommerce;

        // echo "<pre>";
        // print_r($_REQUEST); die;
        $status = get_query_var( 'status' );
        $payment_request_id = get_query_var( 'id' );

        if ( isset( $payment_request_id ) && !empty( $payment_request_id ) &&
        isset( $status ) && !empty( $status ) ) {
            $payment_id = $payment_request_id;
            $payment_array =  array( 'payment_request_id' =>$payment_request_id );
            include_once 'payment_confirm.php';
        }

    }

    # add paymetnt method to payment gateway list
    add_filter( 'woocommerce_payment_gateways', 'add_walletdoc' );

    function add_walletdoc( $methods ) {
        $methods[] = 'WP_Gateway_Walletdoc';
        return $methods;
    }

    // include js

    function walletdoc_wc_admin_scripts() {
        wp_enqueue_script( 'woocommerce_walletdoc_admin', plugins_url( 'assets/js/admin-setting.js', __FILE__ ), array(), '1', true );
        wp_enqueue_script( 'woocommerce_bank2bank_admin', plugins_url( 'assets/js/bank2bank-setting.js', __FILE__ ), array(), '1', true );
    }

    // profile update
    add_action( 'profile_update', 'walletdoc_wc_update_user_profile' );

    function walletdoc_wc_update_user_profile( $user_id ) {

        include_once 'customer.php';
    }
    add_action( 'woocommerce_init', 'walletdoc_wc_init' );

    function walletdoc_wc_init() {

        $available_currencies = ( array )apply_filters( 'woocommerce_walletdoc_available_currencies', array( 'ZAR' ) );

        if ( !in_array( get_woocommerce_currency(), $available_currencies ) )
 {
            add_action( 'admin_notices', 'walletdoc_wc_required_currency_notice' );
        }

    }

    function walletdoc_wc_init_test() {
        global $woocommerce;

    }
    add_action( 'woocommerce_init', 'walletdoc_wc_init_test' );

                }
                ?>
