<?php
/*
Plugin Name: Innozilla Per Product Shipping WooCommerce
Plugin URI: https://innozilla.com/wordpress-plugins/woocommerce-per-product-shipping
Description: WooCommerce Extension that can Define different shipping costs for products, based on customers location.
Author: Innozilla
Author URI: https://innozilla.com/
Text Domain: woocommerce-innozilla-shipping-per-product
Domain Path: /languages/
Version: 1.0.4
*/

define( 'IPPSW_VERSION', '1.0.0' );

define( 'IPPSW_REQUIRED_WP_VERSION', '3.0.0' );

define( 'IPPSW_PLUGIN', __FILE__ );

define( 'IPPSW_PLUGIN_BASENAME', plugin_basename( IPPSW_PLUGIN ) );

define( 'IPPSW_PLUGIN_NAME', trim( dirname( IPPSW_PLUGIN_BASENAME ), '/' ) );

define( 'IPPSW_PLUGIN_DIR', untrailingslashit( dirname( IPPSW_PLUGIN ) ) );

define( 'IPPSW_PLUGIN_URL', untrailingslashit( plugins_url( '', IPPSW_PLUGIN ) ) );

require_once IPPSW_PLUGIN_DIR . '/includes/init.php';

if ( ! class_exists( 'IPPSW_Setup' ) ) {
    require_once dirname( __FILE__ ) . '/classes/Setup.php';
    $IPPSW_setup = new IPPSW_Setup();
    $IPPSW_setup->init();
}

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IPPSW_Shipping_Per_Product_Init {

    /**
     * Constructor
     */
    public function __construct() {
        define( 'IPPSW_PER_PRODUCT_SHIPPING_VERSION', '2.2.8' );
        define( 'IPPSW_PER_PRODUCT_SHIPPING_FILE', __FILE__ );

        if ( is_admin() ) {
            include_once IPPSW_PLUGIN_DIR . '/includes/ippsw-admin.php';
        }

        include_once IPPSW_PLUGIN_DIR . '/includes/ippsw-functions-wc-shipping-per-product.php';

        register_activation_hook( __FILE__, array( $this, 'install' ) );
        add_action( 'woocommerce_shipping_init', array( $this, 'load_shipping_method' ) );
        add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
        add_filter( 'woocommerce_shipping_methods', array( $this, 'register_shipping_method' ) );
        add_filter( 'woocommerce_package_rates', array( $this, 'adjust_package_rates' ), 10, 2 );
    }

    /**
     * Installer
     */
    public function install() {
        include_once( 'installer.php' );
    }

    /**
     * Load shipping method class
     */
    public function load_shipping_method() {
        include_once IPPSW_PLUGIN_DIR . '/includes/ippsw-class-wc-shipping-per-product.php';
    }

    /**
     * Load Upgrade to PRO
     * Version 1.0.3 Update
     */
    public function plugin_action_links( $links ) {
            $plugin_links = array(
                '<a href="https://innozilla.com/wordpress-plugins/woocommerce-per-product-shipping/#pro" style="font-weight:bold; color: #48a05b;">' . __( 'Upgrade to PRO', 'woocommerce-shipping-per-product' ) . '</a>'
            );
            return array_merge( $plugin_links, $links );
        }

    /**
     * Show row meta on the plugin screen.
     * @param   array $links Plugin Row Meta
     * @param   string $file  Plugin Base file
     * @return  array
     */
    public function plugin_row_meta( $links, $file ) {
        if ( $file == plugin_basename( __FILE__ ) ) {
            $row_meta = array(
                'docs'      =>  '<a href="' . esc_url( apply_filters( 'woocommerce_per_product_shipping_docs_url', 'https://innozilla.com/wordpress-plugins/woocommerce-per-product-shipping/#documentation' ) ) . '" title="' . esc_attr( __( 'View Documentation', 'woocommerce-shipping-per-product' ) ) . '">' . __( 'Docs', 'woocommerce-shipping-per-product' ) . '</a>',
                'support'   =>  '<a href="' . esc_url( apply_filters( 'woocommerce_per_product_shipping_support_url', 'https://innozilla.com/wordpress-plugins/woocommerce-per-product-shipping/' ) ) . '" title="' . esc_attr( __( 'Visit Premium Customer Support Forum', 'woocommerce-shipping-per-product' ) ) . '">' . __( 'Premium Support', 'IPPSW_Shipping_Per_Products' ) . '</a>',
            );
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }


    /**
     * Register the shipping method
     *
     * @param array $methods
     * @return array
     */
    public function register_shipping_method( $methods ) {
        $methods[] = 'IPPSW_Shipping_Per_Product';
        return $methods;
    }

    /**
     * Adjust package rates
     * @return array
     */
    public function adjust_package_rates( $rates, $package ) {
        $_tax = new WC_Tax();

        if ( $rates ) {
            foreach ( $rates as $rate_id => $rate ) {

                // Skip free shipping
                if ( 0 === (int) $rate->cost && apply_filters( 'woocommerce_per_product_shipping_skip_free_method_' . $rate->method_id, true ) ) {
                    continue;
                }

                // Skip self
                if ( $rate->method_id == 'per_product' ) {
                    continue;
                }

                if ( sizeof( $package['contents'] ) > 0 ) {
                    foreach ( $package['contents'] as $item_id => $values ) {
                        if ( $values['quantity'] > 0 ) {
                            if ( $values['data']->needs_shipping() ) {

                                $item_shipping_cost = 0;

                                $rule = false;

                                if ( $values['variation_id'] ) {
                                    $rule = innozilla_per_product_shipping_get_matching_rule( $values['variation_id'], $package, false );
                                }

                                if ( $rule === false ) {
                                    $rule = innozilla_per_product_shipping_get_matching_rule( $values['product_id'], $package, false );
                                }

                                if ( empty( $rule ) ) {
                                    continue;
                                }

                                $item_shipping_cost += (float) $rule->iz_rule_item_cost * (int) $values['quantity'];
                                $item_shipping_cost += (float) $rule->iz_rule_cost;

                                $rate->cost += $item_shipping_cost;

                                if ( WC()->shipping->shipping_methods[ $rate->method_id ]->tax_status == 'taxable' ) {

                                    $tax_rates  = $_tax->get_shipping_tax_rates( $values['data']->get_tax_class() );
                                    $item_taxes = $_tax->calc_shipping_tax( $item_shipping_cost, $tax_rates );

                                    // Sum the item taxes
                                    foreach ( array_keys( $rate->taxes + $item_taxes ) as $key ) {
                                        $rate->taxes[ $key ] = ( isset( $item_taxes[ $key ] ) ? $item_taxes[ $key ] : 0 ) + ( isset( $rate->taxes[ $key ] ) ? $rate->taxes[ $key ] : 0 );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $rates;
    }

}

new IPPSW_Shipping_Per_Product_Init();