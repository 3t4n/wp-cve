<?php

namespace Baqend\WordPress\Service;

/**
 * Class WooCommerceService created on 10.10.17.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Service
 */
class WooCommerceService {

    /**
     * Checks whether the WooCommerce shop is active.
     *
     * @return bool True, if WooCommerce happens to be active.
     */
    public function is_shop_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Returns an array of user-specific pathnames used by WooCommerce.
     *
     * @return string[] An array of pathname strings.
     */
    public function load_woo_commerce_rules() {
        // Remove home URL from rules
        $URLs = $this->get_woo_commerce_URLs();
        $home = home_url( '/' );

        $diff = array_values( array_diff( $URLs, [ $home ] ) );

        return strip_protocol( $diff );
    }

    /**
     * Returns an array of user-specific WordPress posts.
     *
     * @return \WP_Post[] An array of WordPress posts.
     */
    private function get_woo_commerce_URLs() {
        $pages = [
            get_int_option( 'woocommerce_cart_page_id' ),
            get_int_option( 'woocommerce_checkout_page_id' ),
            get_int_option( 'woocommerce_pay_page_id' ),
            get_int_option( 'woocommerce_thanks_page_id' ),
            get_int_option( 'woocommerce_custom_thankyou_page_id' ),
            get_int_option( 'woocommerce_myaccount_page_id' ),
            get_int_option( 'woocommerce_edit_address_page_id' ),
            get_int_option( 'woocommerce_view_order_page_id' ),
        ];

        // Check if wish list is active
        if ( class_exists( '\\WC_Wishlists_Pages' ) ) {
            $pages[] = (int) \WC_Wishlists_Pages::get_page_id( 'create-a-list' );
            $pages[] = (int) \WC_Wishlists_Pages::get_page_id( 'view-a-list' );
            $pages[] = (int) \WC_Wishlists_Pages::get_page_id( 'find-a-list' );
            $pages[] = (int) \WC_Wishlists_Pages::get_page_id( 'edit-my-list' );
        }

        return array_values( array_filter(
            array_map(
                function ( $ID ) {
                    $post = get_post( $ID );
                    if ( $post ) {
                        return get_page_link( $post );
                    }

                    return null;
                },
                $pages
            ),
            function ( $link ) {
                return $link !== null;
            }
        ) );
    }
}
