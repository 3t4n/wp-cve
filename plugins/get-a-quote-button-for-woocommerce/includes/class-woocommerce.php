<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * WooCommerce Configuration
 */
class WPB_GQB_WooCommerce_Handler {

    public function __construct() {
        
        $single_show        = wpb_gqb_get_option( 'woo_single_show_quote_form', 'woo_settings', 'on' );
        $loop_show          = wpb_gqb_get_option( 'woo_loop_show_quote_form', 'woo_settings' );
        $btn_position       = wpb_gqb_get_option( 'wpb_gqb_btn_position', 'woo_settings', 'after_cart' );
        $single_position    = ( $btn_position == 'after_cart' ? 30 : 20 );
        $loop_position      = ( $btn_position == 'after_cart' ? 10 : 6 );

        if( $single_show == 'on' ){
            add_action( apply_filters( 'wpb_gqb_woo_single_position', 'woocommerce_single_product_summary' ), array( $this, 'woo_add_contact_form_button' ), apply_filters( 'wpb_gqb_woo_single_priority', $single_position ) );
        }

        if( $loop_show == 'on' ){
            add_action( apply_filters( 'wpb_gqb_woo_loop_position', 'woocommerce_after_shop_loop_item' ), array( $this, 'woo_add_contact_form_button' ), apply_filters( 'wpb_gqb_woo_loop_priority', $loop_position ) );
        }

        add_action( 'wpb_gqb_custom_wc_hook', [ $this, 'woo_add_contact_form_button' ] );

        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'woo_add_meta_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'woo_save_product_meta' ), 10, 2 );
    }

    /**
     * Add Contact Form Button to Product
     */

    public function woo_add_contact_form_button() {
        global $product;
        $woo_show_only      = wpb_gqb_get_option( 'wpb_gqb_woo_show_only_for', 'woo_settings', 'all_products' );
        $woo_btn_guest      = wpb_gqb_get_option( 'wpb_gqb_woo_btn_guest', 'woo_settings', 'on' );
        $woo_form           = apply_filters( 'wpb_gqb_woo_product_contact_form_id', wpb_gqb_get_option( 'wpb_gqb_cf7_form_id', 'form_settings' ) );
        $wpb_gqb_disable    = get_post_meta( $product->get_id(), '_wpb_gqb_disable', true );
        $Shortcode_Handler  = new WPB_GQB_Shortcode_Handler();

        if( $woo_btn_guest != 'on' && !is_user_logged_in() ){
            return false;
        }

        if( $wpb_gqb_disable == 'yes' ){
            return false;
        }

        if( $woo_show_only == 'out_of_stock' ){
            $stock_status = get_post_meta( get_the_ID(), '_stock_status', true ); 
            
            if( $product->get_type() == 'variable' ){
                echo $Shortcode_Handler->contact_form_button( ['id'=> $woo_form, 'class' => 'wpb-gqb-product-type-variable'] );
            }else{
                if( $stock_status == 'outofstock' ) {
                    echo $Shortcode_Handler->contact_form_button( ['id'=> $woo_form ] );
                }
            }
            
        }elseif( $woo_show_only == 'featured' ){
            if( $product->is_featured() ){
                echo $Shortcode_Handler->contact_form_button( ['id'=> $woo_form ] );
            }
        }else{
            echo $Shortcode_Handler->contact_form_button( ['id'=> $woo_form ] );
        }
    }

    /**
     * Add meta box to the WooCommerce product
     */
    public function woo_add_meta_fields() {
        ?>
            <div class="options_group">
                <?php
                    woocommerce_wp_checkbox(
                        array(
                            'id'            => '_wpb_gqb_disable',
                            'wrapper_class' => 'show_if_simple show_if_variable WPB_GQB_disable',
                            'label'         => esc_html__( 'Disable Quote Button?', 'wpb-get-a-quote-button' ),
                            'description'   => esc_html__( 'Disable quote button for this product', 'wpb-get-a-quote-button' ),
                        )
                    );
                ?>
            </div>
        <?php
    }

    /**
     * Save meta box to the WooCommerce product
     */
    public function woo_save_product_meta( $post_id, $post ) {
        $wpb_gqb_disable = isset( $_POST['_wpb_gqb_disable'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_wpb_gqb_disable', $wpb_gqb_disable );
    }
}