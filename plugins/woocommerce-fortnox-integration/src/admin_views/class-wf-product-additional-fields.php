<?php
/**
 * Contains the class that handles the plugins meta fields for WooCommerce products.
 *
 */

declare( strict_types = 1 );

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\WF_Plugin;


/**
 * Handles the plugins meta fields for WooCommerce products.
 *
 * @since 3.1.0
 */
final class WF_Product_Additional_Fields {

    /**
     * Registers the fields with WooCommerce.
     *
     * @return void
     * @since 3.1.0
     */
    public static function init() {
        if ( get_option('fortnox_enable_purchase_price') ) {
            self::add_purchase_price_field();
            self::add_variations_purchase_price_field();

            add_action(
                'woocommerce_admin_process_product_object', function( $wc_product ) {
                self::process_save( $wc_product );
            }
            );

            // Handle saves for variations.
            add_action( 'woocommerce_save_product_variation', function( $post_id, $i ) {
                self::process_save_variation( $post_id, $i );
            }, 10, 2 );
        }
    }

    /**
     *
     */
    private static function add_purchase_price_field() {
        add_action(
            'woocommerce_product_options_pricing', function() {
            global $post;

            if ( 'variable' === wc_get_product( $post->ID )->get_type() ) {
                return;
            }

            echo woocommerce_wp_text_input( // WPCS: XSS ok.
                [
                    'id'          => '_fortnox_purchase_price',
                    'value'       => get_post_meta( $post->ID, '_fortnox_purchase_price', true ),
                    'label'       => __( 'Purchase price', WF_Plugin::TEXTDOMAIN ) . ' (' . get_woocommerce_currency_symbol() . ')',
                    'desc_tip'    => true,
                    'description' => __(
                        'Your total purchase price for this product. Will be synced to Fortnox.',
                        WF_Plugin::TEXTDOMAIN
                    ),
                ]
            );
        }
        );
    }

    private static function add_variations_purchase_price_field() {
        add_action(
            'woocommerce_product_after_variable_attributes',
            function( $loop, $variation_data, $variation ) {
                echo '<div class="variation-custom-fields">';

                woocommerce_wp_text_input(
                    [
                        'id'            => '_fortnox_purchase_price[' . $loop . ']',
                        'label'         => __( 'Purchase price', WF_Plugin::TEXTDOMAIN ) . ' (' . get_woocommerce_currency_symbol() . ')',
                        'wrapper_class' => 'form-row form-row-first',
                        'value'         => get_post_meta(
                            $variation->ID, '_fortnox_purchase_price', true
                        ),
                    ]
                );

                echo '</div>';
            }, 10, 3
        );
    }

    /**
     * Parses string to float value
     * Source: https://stackoverflow.com/a/44110263
     * Tested: http://sandbox.onlinephpfunctions.com/code/830c797eaea978dceea4f5206d6000d3dd244048
     *
     * @param string $val value to parse, accepts dot-separated thousand with comma-separated decimal part and
     * comma-separated thousands with dot-separated decimal part aswell with thousands not separated with comma-
     * or dot-separated decimal part both
     *
     * @since wet-1061 https://favro.com/organization/f082a9aaa638259862d05ccf/6eccc5efa68614da168cfdb2?card=wet-1061
     *
     * @return float
     */
    private static function floatvalue($val){
        $val = str_replace(",",".",$val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return floatval($val);
    }

    private static function process_save( $wc_product ) {
        if ( 'variable' === $wc_product->get_type() ) {
            return;
        }

        $nonce_is_valid = wp_verify_nonce(
            $_REQUEST['woocommerce_meta_nonce'],
            'woocommerce_save_data'
        );

        if ( ! $nonce_is_valid ) {
            die( 'Invalid nonce' );
        }


        if ( isset( $_POST[ '_fortnox_purchase_price' ] ) ) {
            $purchase_price = self::floatvalue( $_POST[ '_fortnox_purchase_price' ] );
            update_post_meta(
                $wc_product->get_id(),
                '_fortnox_purchase_price', esc_attr( $purchase_price )
            );
        }
    }

    private static function process_save_variation( $post_id, $i ) {

        if ( isset( $_POST['_fortnox_purchase_price'][ $i ] ) ) {
            $purchase_price = (int) stripslashes( $_POST['_fortnox_purchase_price'][ $i ] );
            update_post_meta(
                $post_id, '_fortnox_purchase_price', esc_attr( $purchase_price )
            );
        }
    }
}
