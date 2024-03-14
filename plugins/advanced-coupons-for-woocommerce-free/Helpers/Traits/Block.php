<?php
namespace ACFWF\Helpers\Traits;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Trait that houses all the helper functions specifically for Block.
 *
 * @since 4.5.9
 */
trait Block {
    /**
     * Check if the current page is using cart or checkout block.
     *
     * @since 4.5.9
     * @access public
     *
     * @return bool Returns true if the cart page is using blocks, false otherwise.
     */
    public function is_current_page_using_cart_checkout_block() {
        global $post;

        // Bail early if post is not set.
        if ( ! $post instanceof \WP_Post ) {
            return false;
        }

        // Check if the content is using regular cart and checkout block shortcode.
        if ( has_shortcode( $post->post_content, 'woocommerce_cart' ) || has_shortcode( $post->post_content, 'woocommerce_checkout' ) ) {
            return false;
        }

        // check if page using cart or checkout block.
        $blocks = parse_blocks( $post->post_content );
        foreach ( $blocks as $block ) {
            $cart_checkout_block = array( 'woocommerce/cart', 'woocommerce/checkout' );
            if ( in_array( $block['blockName'], $cart_checkout_block, true ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the current request is using cart or checkout block.
     *
     * @since 4.6.0
     * @access public
     *
     * @return bool Returns true if the request is using blocks, false otherwise.
     */
    public function is_current_request_using_wpjson_wc_api() {
        $request_uri = $_SERVER['REQUEST_URI']; // phpcs:ignore

        // The old request uri has the /?wc-ajax=xx scheme
        // While the WooCommerce block request uri scheme uses /wc/store/v1/...
        // By examining the request uri, we can check whether this request is used on the WooCommerce block or not.
        return str_contains( $request_uri, '/wc/store/' );
    }
}
