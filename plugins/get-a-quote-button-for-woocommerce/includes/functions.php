<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/* -------------------------------------------------------------------------- */
/* Get settings option 
/* -------------------------------------------------------------------------- */

if( !function_exists('wpb_gqb_get_option') ){
    function wpb_gqb_get_option( $option, $section, $default = '' ) {
 
        $options = get_option( $section );
     
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
     
        return $default;
    }
}



/**
 * Show or hide the product info in the form
 */

add_action( 'init', function(){
    add_filter('wpcf7_form_class_attr', function( $class ){
        $product_info       = wpb_gqb_get_option( 'wpb_gqb_form_product_info', 'woo_settings', 'hide' );

        if( $product_info ){
            $class .= ' wpb_gqb_form_product_info_' . esc_attr($product_info);
        }

        return $class;
    });
});

/**
 * CF7 Post Shortcode
 */

add_action( 'wpcf7_init', 'wpb_gqb_cf7_add_form_tag_for_post_title' );
 
function wpb_gqb_cf7_add_form_tag_for_post_title() {
    wpcf7_add_form_tag( 'post_title', 'wpb_gqb_cf7_post_title_tag_handler' );
    wpcf7_add_form_tag( 'gqb_product_title', 'wpb_gqb_cf7_send_post_title_tag_handler' );
}
 
function wpb_gqb_cf7_post_title_tag_handler( $tag ) {
    if(isset($_POST['wpb_post_id'])){
        $id = intval( wp_unslash( $_POST['wpb_post_id'] ) );
        return '<input type="hidden" name="post-title" value="'. esc_attr( get_the_title($id) ).'">';
    }
}

/**
 * Send the product title
 */

function wpb_gqb_cf7_send_post_title_tag_handler( $tag ) {
    if(isset($_POST['wpb_post_id'])){
        $id = intval( wp_unslash( $_POST['wpb_post_id'] ) );
        return '<input class="gqb_hidden_field gqb_product_title" type="text" name="gqb_product_title" value="'. esc_attr( get_the_title($id) ) .'">';
    }
}


/**
 * Premium Links
 */

add_action( 'wpb_gqb_after_settings_page', function(){
    ?>
    <div class="wpb_gqb_pro_features wrap">
        <h3>Premium Version Features:</h3>
        <ul>
            <li>Advenced custom shortcode builder for multiple quote buttons.</li>
            <li>Different quote button for different products.</li>
            <li>Different contact forms for different quote buttons.</li>
            <li>Adding the custom quote buttons to the WooCommerce hooks directly from the shortcode generator.</li>
            <li>Products, Products categories, Products tags, Featured Products, Products type, Products stock status, User status, User role, etc filter can be added to the quote button.</li>
            <li>Different text and size for each quote buttons.</li>
            <li>Elementor support, adding custom quote button directly from the Elementor editor. </li>
        </ul>
        <div class="wpb-submit-button">
            <a class="button button-primary button-pro" href="https://wpbean.com/downloads/get-a-quote-button-pro-for-woocommerce-and-elementor/" target="_blank">Get the Pro</a>
        </div>
    </div>
    <?php
} );