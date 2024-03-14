<?php

/*
 * Plugin Name: WooCommerce Catalog Mode - Product Pricing, Enquiry Forms & Promotions
 * Plugin URI: https://codecanyon.net/item/woocommerce-catalog-mode-pricing-enquiry-forms-promotions/43498179?ref=zendcrew
 * Description: An All-purpose WooCommerce catalog mode, product pricing and promotion toolkit.
 * Version: 1.1.4
 * Author: zendcrew
 * Author URI: https://codecanyon.net/user/zendcrew?ref=zendcrew
 * Text Domain: wmodes-tdm
 * Domain Path: /languages/
 * Requires at least: 5.8
 * Tested up to: 6.4
 * Requires PHP: 5.6
 * 
 * WC requires at least: 5.6
 * WC tested up to: 8.2
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( is_admin() ) {

    require_once (dirname( __FILE__ ) . '/framework/reon_loader.php');
}

if ( !defined( 'WMODES_VERSION' ) ) {
    
    define( 'WMODES_VERSION', '1.1.4' );
}

if ( !defined( 'WMODES_MAIN_FILE' ) ) {

    define( 'WMODES_MAIN_FILE', __FILE__ );
}

if ( !class_exists( 'WModes_Init' ) ) {

    class WModes_Init {

        public function __construct() {

            add_action( 'plugins_loaded', array( $this, 'plugin_loaded' ), 1 );
            
            add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );

            load_plugin_textdomain( 'wmodes-tdm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        public function plugin_loaded() {

            if ( function_exists( 'WC' ) ) { // Check if WooCommerce is active
                
                $this->init();
            } else {

                add_action( 'admin_notices', array( $this, 'missing_notice' ) );
            }
        }
        
        public function before_woocommerce_init() {

             // Check for HPOS
            if ( !class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {

                return;
            }

            // Adds support for HPOS
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', WMODES_MAIN_FILE, true );

        }

        public function missing_notice() {

            echo '<div class="error"><p><strong>' . esc_html__( 'WooCommerce Catalog Mode - Product Pricing, Enquiry Forms & Promotions requires WooCommerce be installed and activated.', 'wmodes-tdm' ) . '</strong></p></div>';
        }

        private function init() {

            //WModes Main
            if ( !class_exists( 'WModes_Main' ) ) {

                include_once ('main/main.php');

                new WModes_Main();
            }
        }

    }

    new WModes_Init();
}

