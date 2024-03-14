<?php

/**
 * The file that defines the server side functionality during AJAX requests
 *
 * @since      1.1.2
 * @link       www.theritesites.com
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/includes
 * @author     TheRiteSites <contact@theritesites.com>
 */

namespace TRS\EAA2C;

defined('ABSPATH') || exit;
if ( ! class_exists( 'TRS\EAA2C\Ajax' ) ) {
    class Ajax {

        public static function init() {

            add_action( 'init', array( __CLASS__, 'eaa2c_define_ajax' ), 0 );
            
            self::add_eaa2c_ajax_events();
            
        }

        public static function eaa2c_define_ajax() {
            // error_log( "this is defining the ajax area in eaa2c" );
            if ( ! empty( $_POST['eaa2c_action'] ) ) {
                if ( ! defined( 'DOING_AJAX' ) ) {
                    define( 'DOING_AJAX', true );
                }
                if ( ! defined( 'WC_DOING_AJAX' ) ) {
                    define( 'WC_DOING_AJAX', true );
                }
                if ( ! defined( 'EAA2C_DOING_AJAX' ) ) {
                    define( 'EAA2C_DOING_AJAX', true );
                }
                if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
                    @ini_set( 'display_errors', 0 );
                }
                $GLOBALS['wpdb']->hide_errors();
            }
        }

        public static function add_eaa2c_ajax_events() {

            add_action( 'wp_ajax_eaa2c_add_to_cart', array( __CLASS__, 'eaa2c_add_to_cart_callback' ) );
            add_action( 'wp_ajax_nopriv_eaa2c_add_to_cart', array( __CLASS__, 'eaa2c_add_to_cart_callback' ) );

            /**
             * Deprecated actions
             */
            add_action( 'wp_ajax_simple_add_to_cart', array( __CLASS__, 'simple_add_to_cart_callback' ) );
            add_action( 'wp_ajax_nopriv_simple_add_to_cart', array( __CLASS__, 'simple_add_to_cart_callback' ) );
            
            add_action( 'wp_ajax_variable_add_to_cart', array( __CLASS__, 'variable_add_to_cart_callback' ) );
            add_action( 'wp_ajax_nopriv_variable_add_to_cart', array( __CLASS__, 'variable_add_to_cart_callback' ) );
            /**
             * End deprecated actions.
             */
        }

        /**
         * The server side callback when the button is pressed to verify and add any product to the current cart
         * 
         * @since 2.0.0
         */
        public static function eaa2c_add_to_cart_callback() {

            ob_start();
            $data = array();

            if ( defined( 'EAA2C_DEBUG' ) && true === EAA2C_DEBUG ) {
                error_log( 'EAA2C into add_to_cart callback from javascript' );
            }

            if ( isset( $_POST['product'] ) && isset( $_POST['variable'] ) && isset( $_POST['quantity'] ) ) {
                try {
                    $product_id   = intval( sanitize_text_field( $_POST['product'] ) );
                    $variation_id = intval( sanitize_text_field( $_POST['variable'] ) );
                    $quantity     = intval( sanitize_text_field( $_POST['quantity'] ) );

                    if ( defined( 'EAA2C_DEBUG' ) && true === EAA2C_DEBUG ) {
                        error_log( '    product id: ' . $product_id );
                        error_log( '    variation id: ' . $variation_id );
                        error_log( '    quantity: ' . $quantity );
                    }

                    if ( true === is_int( $variation_id ) && 0 < $variation_id && $variation_id !== $product_id  ) {
                        $product           = wc_get_product( $variation_id );
                        $variations        = $variation_id ? $product->get_variation_attributes( $variation_id ) : null;
                        $product_status    = get_post_status( $product_id );
                        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $variation_id, $quantity );

                        if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
                            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
                            $data['added'] = $variation_id;
                            // \WC_AJAX::get_refreshed_fragments();

                        } else {

                            $data = array(
                                'error' => true
                            );
                        }
                    } elseif ( true === is_int( $product_id ) && 0 < $product_id ) {
                        $product           = wc_get_product( $product_id );
                        $product_status    = get_post_status( $product_id );
                        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

                        if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, null, null ) && 'publish' === $product_status ) {
                            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
                            $data['added'] = $product_id;
                            // \WC_AJAX::get_refreshed_fragments();

                            
                        } else {
                            $data = array(
                                'error' => true
                            );
                        }

                    }

                } catch ( Exception $e ) {
                    return new WP_Error('add_to_cart_error', $e->getMessage(), array( 'status' => 500 ) );
                }
            }
            else {
                if ( true === WP_DEBUG || true === EAA2C_DEBUG ) {
                    error_log( 'product id: ' . $_POST['product'] . ' variable id: ' .  $_POST['variable'] . ' quantity: ' . $_POST['quantity'] );
                }
                $data['error'] = "no product received";
            }
            wc_get_notices( array() );
            wc_print_notices();
            $html = ob_get_contents();
            ob_end_clean();
            $data['html'] = $html;

            $frags = array();
            $stop_rf = get_option( 'a2cp_stop_refresh_frags', false );
            if ( strcmp( $stop_rf, 'on' ) === 0 || strcmp( $stop_rf, 'true' ) === 0 ) {
                $stop_rf = true;
            }
            if ( ! $stop_rf ) {
                ob_start();
                wc_get_template( 'cart/mini-cart.php', array( 'list_class' => '' ) );
                $mini_cart = ob_get_contents();
                ob_end_clean();
                $frags = apply_filters( 'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                    )
                );
                $data['fragments'] = $frags;
                $data['cart_hash'] = WC()->cart->get_cart_hash();
            }

            wp_send_json( $data );

            wp_die();
        }

        /**
         * Deprecated functions below.
         */

        /**
         * The server side callback when the button is pressed to verify and add the variable product to the current cart
         * 
         * @deprecated Since version 2.0.0 Will be deleted in version 3.0, use eaa2c_add_to_cart_callback instead
         * @since 1.0.0
         */
        public static function variable_add_to_cart_callback() {
            _deprecated_function( __FUNCTION__, '2.0', 'eaa2c_add_to_cart_callback' );

            return self::eaa2c_add_to_cart_callback();
            wp_die();
        }

        /**
         * The server side callback when the button is pressed to verify and add the simple product to the current cart
         * 
         * @deprecated Since version 2.0.0 Will be deleted in version 3.0, use eaa2c_add_to_cart_callback instead
         * @since 1.0.0
         */
        public static function simple_add_to_cart_callback() {
            _deprecated_function( __FUNCTION__, '2.0', 'eaa2c_add_to_cart_callback' );

            return self::eaa2c_add_to_cart_callback();
            wp_die();

        }
        /**
         * End deprecated functions.
         */
    }
    Ajax::init();
}