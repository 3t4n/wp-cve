<?php

namespace WCFM\PaypalMarketplace;
/**
 *
 */

class Helper {
    /**
     * Get unique payment gateway id
     *
     * @since 2.0.0
     *
     * @return string gateway id
     */
    public static function payment_gateway_id() {
        // Unique ID for the gateway
	    return 'wcfm_paypal_marketplace';
    }

    public static function payment_gateway_title() {
        $title = static::get_settings('title');

        return ! empty( $title ) ? $title : __('WCFM PayPal Marketplace', 'wc-frontend-manager-direct-paypal');
    }

    /**
     * Get settings of the gateway
     *
     * @param null $key
     *
     * @since 2.0.0
     *
     * @return mixed|array
     */
    public static function get_settings( $key = null ) {
        $settings = get_option( 'woocommerce_' . static::payment_gateway_id() . '_settings', [] );

        if ( $key && isset( $settings[ $key ] ) ) {
            return $settings[ $key ];
        }

        return $settings;
    }

    public static function is_sandbox_mode() {
        $settings = static::get_settings();

        return ! empty( $settings['test_mode'] ) && 'yes' === $settings['test_mode'];
    }

    /**
     * Get Paypal partner id
     *
     * @since 2.0.0
     *
     * @return string
     */
    public static function get_partner_id() {
        $key      = 'partner_id';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get PayPal client id
     *
     * @since 2.0.0
     *
     * @return string
     */
    public static function get_client_id() {
        $key      = static::is_sandbox_mode() ? 'sandbox_client_id' : 'client_id';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get PayPal client secret key
     *
     * @since 2.0.0
     *
     * @return string
     */
    public static function get_client_secret() {
        $key      = static::is_sandbox_mode() ? 'sandbox_client_secret' : 'client_secret';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get PayPal merchant id key
     *
     * @since 2.0.0
     *
     * @return string
     */
    public static function get_paypal_merchant_id( $vendor_id ) {
        $merchant_info = get_user_meta( $vendor_id, Helper::get_paypal_marketplace_settings_key(), true );

        return ! empty( $merchant_info['merchant_id'] ) ? $merchant_info['merchant_id'] : '';
    }

    /**
     * Get Paypal merchant data
     *
     * @param int $user_id
     * @param null $key
     *
     * @since 2.0.0
     *
     * @return mixed|array
     */
    public static function get_paypal_info( $user_id, $key = null ) {
        $user_id = !empty($user_id) ? $user_id : get_wcfm_current_vendor_id();
        $settings = get_user_meta( $user_id, Helper::get_paypal_marketplace_settings_key(), true );

        if ( $key ) {
            if( isset( $settings[ $key ] ) ) {
                return $settings[ $key ];
            } else {
                return false;
            }
        }

        return $settings;
    }

    public static function is_connected_to_paypal( $user_id ) {
        return 'success' === static::get_paypal_info( $user_id, 'connection_status' );
    }

    /**
     * Get list of supported webhook events
     *
     * @since 2.0.0
     *
     * @see https://developer.paypal.com/api/rest/webhooks/event-names/
     *
     * @return array
     */
    public static function get_supported_webhook_events() {
        return apply_filters(
            'wcfm_paypal_supported_webhook_events', [
                'CHECKOUT.ORDER.APPROVED'       => 'CheckoutOrderApproved',
                'CHECKOUT.ORDER.COMPLETED'      => 'CheckoutOrderCompleted',
                'MERCHANT.ONBOARDING.COMPLETED' => 'MerchantOnboardingCompleted',
                'PAYMENT.CAPTURE.REFUNDED'      => 'PaymentCaptureRefunded',
            ]
        );
    }

    public static function get_webhook_url() {
        /**
         * @TODO: replace the function with WC()->api_request_url( 'wcfm-paypal-webhook' );
         */
        return home_url( 'wc-api/wcfm-paypal-webhook', 'https' );
    }

    /**
     * Check whether it's enabled or not
     *
     * @since 2.0.0
     *
     * @return bool
     */
    public static function is_enabled() {
        $settings = static::get_settings();

        return ! empty( $settings['enabled'] ) && 'yes' === $settings['enabled'];
    }

    /**
     * Check if this gateway is enabled and ready to use
     *
     * @since 2.0.0
     *
     * @return bool
     */
    public static function is_ready() {
        if ( ! static::is_enabled() ||
            empty( static::get_partner_id() ) ||
            empty( static::get_client_id() ) ||
            empty( static::get_client_secret() ) ) {
            return false;
        }

        return true;
    }

    public static function get_webhook_id_key() {
        return static::is_sandbox_mode() ? 'wcfm_paypal_marketplace_sandbox_webhook' : 'wcfm_paypal_marketplace_webhook';
    }

    /**
     * @since 2.0.0
     * @param bool|null $sandbox_mode
     * @return string
     */
    public static function get_paypal_merchant_id_key( $sandbox_mode = null ) {
        if ( null === $sandbox_mode ) {
            $sandbox_mode = static::is_sandbox_mode();
        }
        return $sandbox_mode ? '_wcfm_paypal_sandbox_merchant_id' : '_wcfm_paypal_merchant_id';
    }

    /**
     * Get user id by merchant id
     *
     * @param $merchant_id
     *
     * @since 2.0.0
     *
     * @return int
     */
    public static function get_user_id_by_merchant_id( $merchant_id ) {
        global $wpdb;

        $user_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT `user_id` FROM $wpdb->usermeta WHERE `meta_key` = %s AND `meta_value`= %s",
                static::get_paypal_merchant_id_key(),
                $merchant_id
            )
        );

        return absint( $user_id );
    }

    /**
     * @since 2.0.0
     * @param bool|null $sandbox_mode
     * @return string
     */
    public static function get_paypal_marketplace_settings_key( $sandbox_mode = null ) {
        if ( null === $sandbox_mode ) {
            $sandbox_mode = static::is_sandbox_mode();
        }
        return $sandbox_mode ? '_wcfm_paypal_sandbox_marketplace_settings' : '_wcfm_paypal_marketplace_settings';
    }

    /**
     * @since 2.0.0
     * @param bool|null $sandbox_mode
     * @return string
     */
    public static function get_paypal_enabled_for_received_payment_key( $sandbox_mode = null ) {
        if ( null === $sandbox_mode ) {
            $sandbox_mode = static::is_sandbox_mode();
        }
        return $sandbox_mode ? '_wcfm_paypal_sandbox_enable_for_receive_payment' : '_wcfm_paypal_enable_for_receive_payment';
    }

    /**
     * @since 2.0.0
     * @param bool|null $sandbox_mode
     * @return string
     */
    public static function get_paypal_payments_receivable_key( $sandbox_mode = null ) {
        if ( null === $sandbox_mode ) {
            $sandbox_mode = static::is_sandbox_mode();
        }
        return $sandbox_mode ? '_wcfm_paypal_sandbox_payments_receivable' : '_wcfm_paypal_payments_receivable';
    }

    /**
     * @since 2.0.0
     * @param bool|null $sandbox_mode
     * @return string
     */
    public static function get_paypal_primary_email_confirmed_key( $sandbox_mode = null ) {
        if ( null === $sandbox_mode ) {
            $sandbox_mode = static::is_sandbox_mode();
        }
        return $sandbox_mode ? '_wcfm_paypal_sandbox_primary_email_confirmed' : '_wcfm_paypal_primary_email_confirmed';
    }

    /**
     * @since 2.0.0
     * @param bool|null $sandbox_mode
     * @return string
     */
    public static function get_paypal_enable_for_ucc_key( $sandbox_mode = null ) {
        if ( null === $sandbox_mode ) {
            $sandbox_mode = static::is_sandbox_mode();
        }
        return $sandbox_mode ? '_wcfm_paypal_sandbox_enable_for_ucc' : '_wcfm_paypal_enable_for_ucc';
    }

    public static function update_merchant_info( $user_id, $merchant_info ) {
        update_user_meta( $user_id, Helper::get_paypal_enabled_for_received_payment_key(), false );
        update_user_meta( $user_id, Helper::get_paypal_payments_receivable_key(), $merchant_info->payments_receivable );
        update_user_meta( $user_id, Helper::get_paypal_primary_email_confirmed_key(), $merchant_info->primary_email_confirmed );

        //check if the user is able to receive payment
        if ( $merchant_info->payments_receivable && $merchant_info->primary_email_confirmed ) {
            $oauth_integrations = $merchant_info->oauth_integrations;

            array_map(
                function ( $integration ) use ( $user_id ) {
                    if ( 'OAUTH_THIRD_PARTY' === $integration->integration_type && count( $integration->oauth_third_party ) ) {
                        update_user_meta( $user_id, Helper::get_paypal_enabled_for_received_payment_key(), true );
                    }
                }, $oauth_integrations
            );
        }

        //check if the user is able to use UCC mode
        $products = $merchant_info->products;

        array_map(
            function ( $value ) use ( $user_id ) {
                if ( 'PPCP_CUSTOM' === $value->name && 'SUBSCRIBED' === $value->vetting_status ) {
                    update_user_meta( $user_id, Helper::get_paypal_enable_for_ucc_key(), true );
                }
            }, $products
        );
    }

    /**
     * Get PayPal product type based on country
     *
     * @param string $country
     *
     * @since 2.0.0
     *
     * @return string
     */
    public static function get_product_type( $country ) {
        $product_type   = 'EXPRESS_CHECKOUT';
        $allow_ucc      = static::get_settings('allow_ucc');

        if (!wc_string_to_bool($allow_ucc)) {
            return $product_type;
        }

        $ucc_supported_countries    = get_advanced_credit_and_debit_supported_countries();
        $currency                   = get_woocommerce_currency();

        if ( array_key_exists( $country, $ucc_supported_countries ) ) {
            if( in_array( $currency, $ucc_supported_countries[$country] ) ) {
                $product_type = 'PPCP';
            }
        }

        return $product_type;
    }

}
