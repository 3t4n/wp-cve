<?php

class Furgonetka_Order
{

    /**
     * @param  WP_REST_Response $response
     * @return WP_REST_Response
     */
    public static function addLinkToResponse( $response )
    {
        $receivedUrl = wc_get_endpoint_url( 'order-received', $response->data['id'], wc_get_checkout_url() );

        $response->data['summary_page'] = $receivedUrl . '?' . http_build_query( array( 'key' => $response->data['order_key'] ) );

        return $response;
    }

    /**
     * @param object $order
     * @param object $request
     * @param bool $creating
     *
     * @return object $order
     */
    public static function rest_pre_insert_shop_order_object( object $order, object $request, bool $creating )
    {
        if ( $creating ) {
            $request_params = $request->get_params();
            $coupon_lines = $request_params['coupon_lines'];

            if ( isset( $coupon_lines[0]['code'] ) ) {
                $coupon = $coupon_lines[0]['code'];

                if ( isset( $request_params['billing']['email'] ) ) {
                    $email = $request_params['billing']['email'];
                }

                $validation = Furgonetka_rest_helper::validate_coupon( $coupon, $email );
                if ( is_wp_error( $validation ) ) {
                    throw new WC_REST_Exception( 400, $validation->get_error_message(), 400 );
                }
            }
        }

        return $order;
    }
}