<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides static methods as helpers.
 *
 * @since 1.0.0
 */
class Woocci_Helper {
    /**
     * Localize messages based on code
     *
     * @since 1.2.1
     * @return array
     */
    public static function get_localized_messages() {
        return apply_filters(
            'wocci_localized_messages',
            array(
                'amount_too_large'        => __( 'Transaction cannot be processed, please contact the merchant', 'zaytech_woocci' ),
                'card_declined'           => __( 'Transaction declined, please use a different card', 'zaytech_woocci' ),
                'card_on_file_missing'    => __( 'Transaction failed, incorrect card data', 'zaytech_woocci' ),
                'charge_already_captured' => __( 'Transaction as already been captured', 'zaytech_woocci' ),
                'charge_already_refunded' => __( 'Transaction has already been refunded', 'zaytech_woocci' ),
                'email_invalid'           => __( 'Email ID is invalid, enter valid email ID and retry', 'zaytech_woocci' ),
                'expired_card'            => __( 'Card expired, enter valid card number and retry', 'zaytech_woocci' ),
                'incorrect_cvc'           => __( 'CVV value is incorrect, enter correct CVV value and retry', 'zaytech_woocci' ),
                'incorrect_number'        => __( 'Card number is invalid, enter valid card number and retry', 'zaytech_woocci' ),
                'incorrect_address'       => __( 'Street address is not provided, enter valid street address and retry', 'zaytech_woocci' ),
                'invalid_card_type'       => __( 'Card brand is invalid or not supported, please use valid card and retry', 'zaytech_woocci' ),
                'invalid_charge_amount'   => __( 'Invalid transaction amount, please contact merchant', 'zaytech_woocci' ),
                'invalid_request'         => __( 'Card is invalid, please retry with a new card', 'zaytech_woocci' ),
                'invalid_tip_amount'      => __( 'Invalid tip amount, please correct and retry', 'zaytech_woocci' ),
                'invalid_tax_amount'      => __( 'Incorrect tax amount, please correct and retry', 'zaytech_woocci' ),
                'missing'                 => __( 'Unable to process transaction', 'zaytech_woocci' ),
                'order_already_paid'      => __( 'Order already paid', 'zaytech_woocci' ),
                'processing_error'        => __( 'Transaction could not be processed', 'zaytech_woocci' ),
                'rate_limit'              => __( 'Transaction could not be processed, please contact the merchant', 'zaytech_woocci' ),
                'resource_missing'        => __( 'Transaction could not be processed due to incorrect or invalid data', 'zaytech_woocci' ),
                'token_already_used'      => __( 'Transaction could not be processed, please renter card details and retry', 'zaytech_woocci' ),
                'invalid_key'             => __( 'Unauthorized, please contact the merchant', 'zaytech_woocci' ),
                'invalid_details'         => __( 'Transaction failed, incorrect data provided', 'zaytech_woocci' ),
                'unexpected'              => __( 'Transaction could not be processed, please retry', 'zaytech_woocci' ),
            )
        );
    }
}
