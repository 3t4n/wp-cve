<?php

namespace Woo_MP;

use Woo_MP\Woo_MP;
use Woo_MP\Payment_Gateway\Payment_Gateway;

defined( 'ABSPATH' ) || die;

/**
 * Provides a single entry point for accessing payment gateways.
 */
class Payment_Gateways {

    /**
     * Get available gateways.
     *
     * @return array Gateway IDs and their associated class names.
     */
    private static function get_gateways() {
        if ( Woo_MP::is_pro() ) {
            return [
                'stripe'        => \Woo_MP_Pro\Payment_Gateways\Stripe\Payment_Gateway::class,
                'authorize_net' => \Woo_MP_Pro\Payment_Gateways\Authorize_Net\Payment_Gateway::class,
                'eway'          => \Woo_MP_Pro\Payment_Gateways\Eway\Payment_Gateway::class,
            ];
        } else {
            return [
                'stripe'        => \Woo_MP\Payment_Gateways\Stripe\Payment_Gateway::class,
                'authorize_net' => \Woo_MP\Payment_Gateways\Authorize_Net\Payment_Gateway::class,
                'eway'          => \Woo_MP\Payment_Gateways\Eway\Payment_Gateway::class,
            ];
        }
    }

    /**
     * Get all payment gateway IDs.
     *
     * @return array Gateway IDs.
     */
    public static function get_all_ids() {
        return array_keys( self::get_gateways() );
    }

    /**
     * Get the active payment gateway ID.
     *
     * @return string|null The gateway ID.
     */
    public static function get_active_id() {
        $id = get_option( 'woo_mp_payment_processor' );

        if ( in_array( $id, self::get_all_ids(), true ) ) {
            return $id;
        }
    }

    /**
     * Get all payment gateways.
     *
     * @return array Gateway instances.
     */
    public static function get_all() {
        return array_map(
            function ( $gateway ) {
                return new $gateway();
            },
            self::get_gateways()
        );
    }

    /**
     * Get the active payment gateway.
     *
     * @return Payment_Gateway|null An instance of the gateway.
     */
    public static function get_active() {
        $id = self::get_active_id();

        if ( $id ) {
            $class = self::get_gateways()[ $id ];

            return new $class();
        }
    }

}
