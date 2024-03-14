<?php
/**
 * Plugin Name: AffiliateWP - Tickera Integration
 * Plugin URI: https://affiliatewp.com/
 * Description: Integrates Tickera with AffiliateWP
 * Author: AffiliateWP
 * Author URI: http://affiliatewp.com
 * Version: 1.2
 */

/**
 * Load the conversion script
 */
function affwp_tickera_integration_script() {

    if ( ! function_exists( 'affiliate_wp' ) ) {
        return;
    }

    $confirmation_page = get_option( 'tc_confirmation_page_id', false );
    $order_details_page = get_option( 'tc_order_page_id', false );

    if ( is_page( $confirmation_page ) || is_page( $order_details_page ) ) {

        global $wp;
        $tc_order_return = isset( $wp->query_vars[ 'tc_order_return' ] ) ? sanitize_text_field( $wp->query_vars[ 'tc_order_return' ] ) : '';

        if ( $tc_order_return !== '' ) {
            $order = tc_get_order_id_by_name( $tc_order_return );
            $order = new TC_Order( $order->ID );

        } else {
            $tc_order_return = isset( $wp->query_vars[ 'tc_order' ] ) ? sanitize_text_field( $wp->query_vars[ 'tc_order' ] ) : sanitize_text_field( $_GET[ 'tc_order' ] );
            $order = tc_get_order_id_by_name( $tc_order_return );
            $order = new TC_Order( $order->ID );
        }

        /**
         * Get the gateway name used during the checkout process.
         * Identify if the gateway is set to skip the confirmation page or not.
         */
        $cart_info = get_post_meta( $order->id, 'tc_cart_info', true );
        $gateway = $cart_info[ 'gateway' ] ? $cart_info[ 'gateway' ] : '';
        $skip_confirmation_page = 'no';

        if ( $gateway ) {

            $settings = get_option( 'tc_settings', false );
            $gateways = $settings[ 'gateways' ] ? $settings[ 'gateways' ] : [];

            foreach ( $gateways as $name => $values ) {
                if ( $gateway == $name ) {
                    $skip_confirmation_page = $values[ 'skip_confirmation_page' ] ? $values[ 'skip_confirmation_page' ] : 'no';
                    break;
                }
            }
        }

        /**
         * If the option skip confirmation page is enabled, process affiliate conversion only in order details page.
         * If the option skip confirmation page is disabled, process affiliate conversion
         */
        if ( ( 'yes' == $skip_confirmation_page && is_page( $order_details_page ) )
            || ( 'no' == $skip_confirmation_page && is_page( $confirmation_page ) ) ) {

            $amount = $order->details->tc_payment_info[ 'total' ];
            $status = ( ! apply_filters( 'affwp_auto_complete_referral', true ) ) ? 'pending' : 'unpaid';

            $reference = $order->id;
            $event_ids = get_post_meta( $reference, 'tc_parent_event', true );

            $description = [];
            if ( $event_ids ) {
                foreach ( $event_ids as $id ) {
                    $description[] = get_the_title( $id );
                }
            }

            $description = implode( ', ', $description );

            // Referral arguments
            $args = array(
                'amount' => $amount,
                'status' => $status,
                'reference' => $reference,
                'description' => $description,
                'context' => 'tickera'
            );

            // Add the conversion script to the page
            affiliate_wp()->tracking->conversion_script( $args );
        }
    }
}
add_action( 'wp_head', 'affwp_tickera_integration_script' );

/**
 * Link referral to order
 *
 * @param $reference
 * @param $referral
 * @return int|string
 */
function affwp_tickera_reference_link( $reference, $referral ) {

    $reference = $reference ? $reference : 0;

    if ( empty( $referral->context ) || 'tickera' != $referral->context ) {
        return $reference;
    }

    $url = admin_url( 'post.php?action=edit&post=' . $reference );
    return '<a href="' . esc_url( $url ) . '">' . $reference . '</a>';
}
add_filter( 'affwp_referral_reference_column', 'affwp_tickera_reference_link', 10, 2 );
