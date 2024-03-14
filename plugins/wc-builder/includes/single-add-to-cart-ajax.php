<?php
// enqueue scripts
add_action( 'wp_enqueue_scripts','wpbforwpbakery_ajax_add_to_cart_scripts');
function wpbforwpbakery_ajax_add_to_cart_scripts(){
    // Thickbox.
    add_thickbox();

    // enqueue js
    wp_enqueue_script( 'wpbforwpbakery-add-to-cart-ajax', WPBFORWPBAKERY_ADDONS_PL_URL.'/assets/js/single-add-to-cart-ajax.js', array('jquery'), WPBFORWPBAKERY_VERSION, true );
}

/**
 * Ajax add to cart notice div
 */
add_action('wp_footer', 'wpbforwpbakery_notice_blank_div' );
function wpbforwpbakery_notice_blank_div(){
    ?>
    <div id="wpbforwpbakery_notice_popup" style="display:none;"></div>
    <?php
}

/**
 * Generate ajax add to cart notice
 */
add_action( 'wp_ajax_wpbforwpbakery_ajax_add_to_cart_notice', 'wpbforwpbakery_ajax_add_to_cart_notice' );
add_action( 'wp_ajax_nopriv_wpbforwpbakery_ajax_add_to_cart_notice', 'wpbforwpbakery_ajax_add_to_cart_notice' );
function wpbforwpbakery_ajax_add_to_cart_notice() {
    wc_print_notices();
    wp_die();
}

/**
 * AJAX add to cart.
 */
add_action( 'wp_ajax_wpbforwpbakery_ajax_add_to_cart', 'wpbforwpbakery_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_wpbforwpbakery_ajax_add_to_cart', 'wpbforwpbakery_ajax_add_to_cart' );
function wpbforwpbakery_ajax_add_to_cart() {
    if ( ! isset( $_POST['product_id'] ) ) {
        return;
    }

    $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
    $product_title     = get_the_title( $product_id );
    $quantity          = ! empty( $_POST['quantity'] ) ? wc_stock_amount( absint( $_POST['quantity'] ) ) : 1;
    $product_status    = get_post_status( $product_id );
    $variation_id      = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
    $variation         = ! empty( $_POST['variation'] ) ? array_map( 'sanitize_text_field', $_POST['variation'] ) : array();
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation );
    $cart_page_url     = wc_get_cart_url();

    if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

        do_action( 'woocommerce_ajax_added_to_cart', $product_id );

        if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
            wc_add_to_cart_message( array( $product_id ), true );
        } else {
            $added_to_cart_notice = sprintf(
                /* translators: %s: Product title */
                esc_html__( '"%1$s" has been added to your cart. %2$s', 'wpbforwpbakery' ),
                esc_html( $product_title ),
                '<a href="' . esc_url( $cart_page_url ) . '">' . esc_html__( 'View Cart', 'wpbforwpbakery' ) . '</a>'
            );

            wc_add_notice( $added_to_cart_notice );
        }

        WC_AJAX::get_refreshed_fragments();

    } else {

        // If there was an error adding to the cart, redirect to the product page to show any errors.
        $data = array(
            'error'       => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
        );

        wp_send_json( $data );
    }
}