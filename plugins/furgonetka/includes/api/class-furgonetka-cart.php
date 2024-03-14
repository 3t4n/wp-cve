<?php

class Furgonetka_Cart
{
    public static $session;

    public function __construct()
    {
        $session_handler = new WC_Session_Handler();
        self::$session = $session_handler->get_session( $this->get_customer_id_from_session( $session_handler ) );
    }

    /**
     * Get cart items for WP Rest
     *
     * @return WP_REST_Response
     */
    public function get_cart_items()
    {
        return $this->response( $this->internal_get_cart_items() );
    }

    /**
     * Internal get cart items
     *
     * @return array
     */
    private function internal_get_cart_items()
    {
        $cart_items = (array) maybe_unserialize( self::$session['cart'] );
        $items      = array();

        foreach ( $cart_items as $key => $cart_item ) {
            $is_variation = ( $cart_item['variation_id'] !== 0 );
            $product      = wc_get_product( $is_variation ? $cart_item['variation_id'] : $cart_item['product_id'] );

            $attributes = array();
            foreach ( $product->get_attributes() as $name => $attribute ) {
                if ( is_string( $attribute ) ) {
                    $attributes[] = array(
                        'name'   => $name,
                        'label'  => wc_attribute_label( $name ),
                        'values' => array( $attribute ),
                    );
                } else {
                    $attributes[] = array(
                        'name'   => $attribute->get_name(),
                        'label'  => wc_attribute_label( $attribute->get_name() ),
                        'values' => explode( ', ', $product->get_attribute( $attribute->get_name() ) ),
                    );
                }
            }
            $dimensions = $product->get_dimensions( false );
            if ( ! is_array( $dimensions ) ) {
                $dimensions = null;
            }

            $items[] = array(
                'id'                     => $key,
                'product_id'             => $product->get_ID(),
                'product_name'           => $product->get_name(),
                'product_title'          => $product->get_title(),
                'product_virtual'        => $product->is_virtual(),
                'product_downloadable'   => $product->is_downloadable(),
                'product_price'          => (float) ( $product->get_sale_price() ?: $product->get_regular_price() ),
                'product_price_with_tax' => wc_get_price_including_tax($product, array( 'price' => $product->get_sale_price() ?: $product->get_regular_price() ) ),
                'product_image'          => wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' ),
                'currency'               => get_woocommerce_currency(),
                'quantity'               => $cart_item['quantity'],
                'total'                  => $cart_item['line_total'],
                'tax'                    => $cart_item['line_tax'],
                'variation'              => count( $cart_item['variation'] ) ? $cart_item['variation'] : false,
                'attributes'             => $attributes,
                'dimensions'             => $dimensions,
                'weight'                 => (float) $product->get_weight(),
            );
        }

        $cart_response = array(
            'items'   => $items,
            'cart_id' => $this->get_customer_id_from_session( WC()->session )
        );

        return $cart_response;
    }

    /**
     * Get shipping types for WP Rest
     *
     * @return WP_REST_Response
     */
    public function get_shipping()
    {
        return $this->response( $this->internal_get_shipping() );
    }

    /**
     * Internal get shipping types
     *
     * @return array
     */
    private function internal_get_shipping()
    {
        $shipping = array();

        if ( !empty( self::$session['shipping_for_package_0']) ) {
            $shipping = (array) maybe_unserialize( self::$session['shipping_for_package_0'] );
        }

        $shipping_methods = array();

        if ( !empty( $shipping['rates'] ) ) {
            $delivery_to_type = get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' );

            /**
             * Gather assigned payments
             *
             * - enable all shipping methods for non-COD payment
             * - enable selected shipping methods for COD payment
             */
            $payments             = array();
            $payments_without_cod = array();

            $cod_payment_shipping_methods = array();

            foreach ( $this->get_payments()->get_data() as $payment ) {
                /**
                 * Add every payment
                 */
                $payments[] = $payment;

                /**
                 * Gather shipping methods enabled for COD payment
                 */
                if ( ( $payment['id'] === 'cod' ) && ! empty( $payment['enable_for_methods'] ) ) {
                    $cod_payment_shipping_methods = $payment['enable_for_methods'];
                }

                /**
                 * Add non-COD payment to separate list
                 */
                if ( $payment['id'] !== 'cod' ) {
                    $payments_without_cod[] = $payment;
                }
            }

            /** @var WC_Shipping_Rate $shipping_method */
            foreach ( $shipping['rates'] as $shipping_method ) {
                $description = '';
                $metadata    = $shipping_method->get_meta_data();
                if ( isset( $metadata['description'] ) ) {
                    $description = $metadata['description'];
                }

                /**
                 * Filter payment methods for COD
                 */
                $shipping_payment_methods = $payments;

                if ( ! empty( $cod_payment_shipping_methods ) ) {
                    $instance_id = $shipping_method->instance_id;
                    $method_id   = $shipping_method->method_id;

                    /**
                     * Check method_id and method_id:instance_id presence in COD payment shipping methods
                     */
                    if ( ! in_array( $method_id, $cod_payment_shipping_methods, true ) &&
                        ! in_array( "{$method_id}:{$instance_id}", $cod_payment_shipping_methods, true ) ) {
                        $shipping_payment_methods = $payments_without_cod;
                    }
                }

                $method             = array(
                    'id'                => $shipping_method->instance_id,
                    'method_id'         => $shipping_method->method_id,
                    'name'              => $shipping_method->label,
                    'price'             => $shipping_method->cost,
                    'shipping_tax'      => $shipping_method->get_shipping_tax(),
                    'currency'          => get_woocommerce_currency(),
                    'payments'          => $shipping_payment_methods,
                    'description'       => $description,
                    'furgonetkaMapType' => isset( $delivery_to_type[ $shipping_method->id ] ) ? $delivery_to_type[ $shipping_method->id ] : null,
                );
                $shipping_methods[] = $method;
            }
        }

        $shipping_response = array(
            'shipping_methods' => $shipping_methods,
            'cart_needs_shipping' => WC()->cart ? WC()->cart->needs_shipping() : null,
        );

        return $shipping_response;
    }

    /**
     * Get payment types for WP Rest
     *
     * @return WP_REST_Response
     */
    public function get_payments()
    {
        return $this->response( $this->internal_get_payments() );
    }

    /**
     * Internal get payment types
     *
     * @return array
     */
    private function internal_get_payments()
    {
        $woocommerce  = WC();
        $payments_raw = $woocommerce->payment_gateways->get_available_payment_gateways();
        $payments     = array();

        /**
         * Add COD payment when it's not present in available payment gateways
         */
        if ( ! isset( $payments_raw['cod'] ) ) {
            $all_payment_gateways_raw = $woocommerce->payment_gateways->payment_gateways();

            if ( isset( $all_payment_gateways_raw['cod'] ) ) {
                $payments_raw['cod'] = $all_payment_gateways_raw['cod'];
            }
        }

        foreach ( $payments_raw as $key => $payment ) {
            if ( $payment->enabled !== 'yes' ) {
                continue;
            }

            $data = array(
                'id'           => $key,
                'title'        => $payment->title,
                'btn_text'     => $payment->order_button_text,
                'method_title' => $payment->method_title,
                'description'  => $payment->method_description,
            );

            if ( $payment instanceof WC_Gateway_COD ) {
                $data['enable_for_methods'] = $payment->get_option( 'enable_for_methods' );
            }

            $payments[] = $data;
        }

        return $payments;
    }

    /**
     * Get coupons for WP Rest
     *
     * @return WP_REST_Response
     */
    public function get_coupons()
    {
        return $this->response( $this->internal_get_coupons() );
    }

    /**
     * Internal get coupons
     *
     * @return array
     */
    private function internal_get_coupons()
    {
        $coupons_raw = (array) maybe_unserialize( self::$session['coupon_discount_totals'] );
        $coupons     = array();

        foreach ( $coupons_raw as $code => $discount ) {
            $coupons[] = array(
                'code'     => $code,
                'discount' => $discount,
                'currency' => get_woocommerce_currency(),
            );
        }

        return $coupons;
    }

    /**
     * Get totals (prices) for WP Rest
     *
     * @return WP_REST_Response
     */
    public function get_totals()
    {
        return $this->response( $this->internal_get_totals() );
    }

    /**
     * Internal get totals (prices)
     *
     * @return array
     */
    private function internal_get_totals()
    {
        // Gather raw cart totals
        $cart_totals_raw = (array) maybe_unserialize( self::$session['cart_totals'] );

        // Define available cart totals
        $cart_totals = array(
            'subtotal'            => null,
            'subtotal_tax'        => null,
            'shipping_total'      => null,
            'shipping_tax'        => null,
            'discount_total'      => null,
            'discount_tax'        => null,
            'cart_contents_total' => null,
            'cart_contents_tax'   => null,
            'fee_total'           => null,
            'fee_tax'             => null,
            'total'               => null,
            'total_tax'           => null,
        );

        // Assign each value from raw cart, if given key exists
        foreach ( array_keys( $cart_totals ) as $key ) {
            if ( isset( $cart_totals_raw[ $key ] ) ) {
                $cart_totals[ $key ] = (float) $cart_totals_raw[ $key ];
            }
        }

        // Add currency
        $cart_totals['currency'] = get_woocommerce_currency();

        return $cart_totals;
    }

    /**
     * Get cart shipping method for WP Rest
     *
     * @return WP_REST_Response|WP_Error
     */
    public function get_cart_shipping_method()
    {
        if ( $shippingMethod = $this->internal_get_cart_shipping_method() ) {
            return $this->response( $shippingMethod );
        }

        return new WP_Error( 'furgonetka_invalid_resource_id', __( 'Invalid resource ID.', 'furgonetka' ), array( 'status' => 404 ) );
    }

    /**
     * Internal get cart shipping method
     *
     * @return array|false
     */
    private function internal_get_cart_shipping_method()
    {
        $_POST      = array_merge( $_POST, json_decode( file_get_contents( 'php://input' ), true ) );
        $cartId     = $this->get( 'cartId' );
        $instanceId = (int) $this->get( 'instanceId' );

        $session_handler = new WC_Session_Handler();
        self::$session   = $session_handler->get_session( $cartId );

        $shippingMethods = $this->get_shipping()->get_data()['shipping_methods'];

        foreach ( $shippingMethods as $shippingMethod ) {
            if ( $shippingMethod['id'] === $instanceId ) {
                return $shippingMethod;
            }
        }

        return false;
    }

    /**
     * Get all-in-one (Cart, Totals, Coupons, Shipping, Payments) for WP Rest
     *
     * @return WP_REST_Response
     */
    public function get_all_in_one()
    {
        return $this->response( $this->internal_get_all_in_one() );
    }

    /**
     * Internal get all data: Cart, Totals, Coupons, Shipping, Payments
     *
     * @return array
     */
    private function internal_get_all_in_one()
    {
        $all_in_one = array(
            'cart'        => $this->internal_get_cart_items(),
            'cart_totals' => $this->internal_get_totals(),
            'coupons'     => $this->internal_get_coupons(),
            'shipping'    => $this->internal_get_shipping(),
            'payments'    => $this->internal_get_payments()
        );

        return $all_in_one;
    }

    /**
     * Get value from $_POST by key
     *
     * @param string $key      $_POST[$key]
     * @param mixed  $default  default return value if $_POST value does not exist
     * @param bool   $sanitize if to sanitize return value
     *
     * @return mixed
     */
    private function get( $key, $default = '', $sanitize = true )
    {
        if ( isset( $_POST[ $key ] ) ) {
            if ( $sanitize ) {
                return filter_var( $_POST[ $key ], FILTER_SANITIZE_STRING );
            } else {
                return filter_var( $_POST[ $key ], FILTER_SANITIZE_EMAIL );
            }
        }

        return $default;
    }

    /**
     * Get uncached wordpress REST response
     *
     * @param mixed $data    data to be passed to response
     * @param int   $status  http status of response
     * @param array $headers headers to be set on response
     *
     * @return WP_REST_Response
     */
    private function response( $data, $status = 200, $headers = array() )
    {
        /**
         * Apply no-cache headers
         */
        nocache_headers();

        /**
         * Disable litespeed cache
         */
        do_action( 'litespeed_control_set_nocache', 'REST API should be non-cacheable' );

        /**
         * Return WordPress REST response
         */
        return new WP_REST_Response( $data, $status, $headers );
    }

    /**
     * Internal method to get unique customer id from Woocommerce session
     *
     * @param WC_Session_Handler|WC_Session $session_handler
     *
     * @return string
     */
    private function get_customer_id_from_session( &$session_handler )
    {
        $wcSession = WC()->session;

        if ( ! is_null( $wcSession ) ) {
            if ( $wcSession->has_session() && $session_handler->_customer_id ) {
                return $session_handler->_customer_id;
            } elseif ( is_user_logged_in() ) {
                return (string) get_current_user_id();
            }
        }

        return $wcSession->get_customer_id();
    }

    public function maybe_add_coupon()
    {
        $_POST  = array_merge( $_POST, json_decode( file_get_contents( 'php://input' ), true ) );
        $coupon = $this->get( 'coupon' );
        $email = $this->get( 'email' );

        $addCouponResult = Furgonetka_rest_helper::validate_and_add_coupon( $coupon, $email );

        return ( is_wp_error($addCouponResult) ) ? $addCouponResult : $this->response( [] );
    }

    public function remove_coupons()
    {
        global $woocommerce;

        $woocommerce->cart->remove_coupons();

        return $this->response( [] );
    }
}
