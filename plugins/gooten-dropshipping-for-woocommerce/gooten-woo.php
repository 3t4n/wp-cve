<?php
/**
   Plugin Name: Gooten Shipping for WooCommerce
   Plugin URI: https://wordpress.org/plugins/gooten-dropshipping-for-woocommerce/
   description: Gooten Shipping allows Gooten partners to assign and display different shipping costs to individual items in a customer's WooCommerce shopping cart.
   Version: 2.2.5
   Author: Gooten
   Author URI: http://gooten.com
   License: GPL2
   */


defined('ABSPATH') or die("Cannot access pages directly.");

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if(!function_exists('wp_get_current_user')) {
        /* include(ABSPATH . "wp-includes/pluggable.php"); */
    }

    if ( ! class_exists( 'Gooten_Woo' ) ) {
        class Gooten_Woo {

            public function __construct() {

                add_filter( 'woocommerce_package_rates', array($this, 'gtn_hide_no_cost_shipping_methods'), 10, 2);
                add_filter( 'woocommerce_cart_shipping_packages', array($this, 'gtn_split_woocommerce_cart_shipping_packages'));

                // Activate Hook
                register_activation_hook(__FILE__, [$this, 'activate']);

                // Deactivate Hook
                register_deactivation_hook(__FILE__, [$this, 'deactivate']);

                // Uninstall Hook
                register_uninstall_hook(__FILE__, 'uninstall');
            }

            function gtn_hide_no_cost_shipping_methods( $rates, $packages ) {
                $shipping_rate = array();
                $gooten_api = new Gooten_Api();
                $payload = $gooten_api->items($packages['contents']);
                $res = $gooten_api->shipping_prices_api($payload);
                if(array_key_exists('Result', $res)) {
                    foreach($res['Result'] as $res_items => $item) {
                        foreach($item['ShipOptions'] as $options => $option) {
                            $shipping_rate[] = array($option['Name'] => $option['Price']['Price']);
                        }
                    }
                }
                $filtered_rates = array();
                $rates_regex = '/(Standard|Expedited|Overnight)/';

                foreach ( $rates as $rate_id => $rate ) {
                    if ( 'flat_rate' === $rate->method_id && preg_match($rates_regex, $rate->label) ) {
                        $gooten_rate = array_sum(array_column($shipping_rate, $rate->label));
                        if(floatval($gooten_rate) > 0) {
                            $rate->set_cost($gooten_rate);
                            $filtered_rates[ $rate_id ] = $rate;
                        }
                        elseif(floatval($rate->cost) > 0) {
                            $filtered_rates[ $rate_id ] = $rate;
                        }
                    }
                    else {
                        $filtered_rates[ $rate_id ] = $rate;
                    }
                }
                return $filtered_rates;
            }


            function gtn_split_woocommerce_cart_shipping_packages ($packages) {

                $packages              = array();
                $split_package_items   = array();

                foreach ( WC()->cart->get_cart() as $item_key => $item ) {
                    if ( $item['data']->needs_shipping() ) {
                        $shipping_class_id = $item['data']->get_shipping_class_id();
                        $split_package_items[ $shipping_class_id ][] = $item;
                    }
                }

                foreach ($split_package_items as $key => $item) {
                    $packages[] = array(
                        'contents'        => $item,
                        'contents_cost'   => array_sum( wp_list_pluck( $item, 'line_total' ) ),
                        'applied_coupons' => WC()->cart->get_applied_coupons(),
                        'user'            => array(
                            'ID' => get_current_user_id(),
                        ),
                        'destination'    => array(
                            'country'    => WC()->customer->get_shipping_country(),
                            'state'      => WC()->customer->get_shipping_state(),
                            'postcode'   => WC()->customer->get_shipping_postcode(),
                            'city'       => WC()->customer->get_shipping_city(),
                            'address'    => WC()->customer->get_shipping_address(),
                            'address_2'  => WC()->customer->get_shipping_address_2()
                        )
                    );
                }
                return $packages;
            }


            function gtn_custom_shipping_package_name( $name, $index ) {
                $cart_item_titles = array();

                foreach(WC()->cart->get_cart() as $item => $values) {
                    $_product =  wc_get_product( $values['data']->get_id());
                    $cart_item_titles[] = $_product->get_title();
                }
                $title = strlen($cart_item_titles[$index]) > 14
                    ? substr($cart_item_titles[$index],0,11)."..."
                    : $cart_item_titles[$index];
                return "Shipping costs<br><small>" . $title . "</small>";
            }


            public function activate () {

                $user = (array)wp_get_current_user()->{'data'};
                $payload = array(
                    'action' => 'activate',
                    'user_email' => $user['user_email'],
                    'display_name' => $user['display_name'],
                    'home' => get_option('home'),
                    'siteurl' => get_option('siteurl')
                );
                $args = array(
                    'body' => json_encode($payload),
                    'headers'     => ["content-type" => "application/json"],
                    'timeout'     => '5',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'cookies'     => array()
                );

                // wp_remote_post('https://gooten.com', $args);
            }


            public function deactivate () {

                $user = (array)wp_get_current_user()->{'data'};
                $payload = array(
                    'action' => 'deactivate',
                    'user_email' => $user['user_email'],
                    'display_name' => $user['display_name'],
                    'home' => get_option('home'),
                    'siteurl' => get_option('siteurl')
                );
                $args = array(
                    'body' => json_encode($payload),
                    'headers'     => ["content-type" => "application/json"],
                    'timeout'     => '5',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'cookies'     => array()
                );

                // wp_remote_post('https://gooten.com', $args);
                flush_rewrite_rules();
            }


            public function uninstall () {
                $user = (array)wp_get_current_user()->{'data'};
                $payload = array(
                    'action' => 'uninstall',
                    'user_email' => $user['user_email'],
                    'display_name' => $user['display_name'],
                    'home' => get_option('home'),
                    'siteurl' => get_option('siteurl')
                );
                $args = array(
                    'body' => json_encode($payload),
                    'headers'     => ["content-type" => "application/json"],
                    'timeout'     => '5',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'cookies'     => array()
                );

                // wp_remote_post('https://bogie.ngrok.io/woo/auth/auth.php', $args);
            }
        }
    }

    include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src/gooten_api.php';
    add_action( 'init', 'Gooten_Woo' );
    function Gooten_Woo() {
        global $Gooten_Woo;
        $Gooten_Woo = new Gooten_Woo();
    }
}
