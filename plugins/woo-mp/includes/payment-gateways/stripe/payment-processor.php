<?php

namespace Woo_MP\Payment_Gateways\Stripe;

use Woo_MP\Detailed_Exception;
use Woo_MP\Payment_Gateways\Stripe\API\Exceptions\Stripe_Exception;

defined( 'ABSPATH' ) || die;

/**
 * Process a payment with Stripe.
 */
class Payment_Processor extends Transaction_Processor {

    /**
     * Process a payment.
     *
     * @param array $params The following payment parameters are required:
     *
     * [
     *     'token'       => '', // Token representing the payment source.
     *
     *     // The following parameters are provided by {@see \Woo_MP\Payment_Processor::process()}.
     *     'order'       => null,
     *     'capture'     => false,
     *     'description' => ''
     * ]
     *
     * @see \Woo_MP\Payment_Processor::process() For parameters required for all payment processors.
     *
     * @return array Result:
     *
     * [
     *     'trans_id'        => '',
     *     'held_for_review' => false
     * ]
     *
     * @throws Detailed_Exception For detailed errors.
     */
    public function process( $params ) {
        $amount = $this->to_smallest_unit( $params['amount'], $params['currency'] );

        $moto_enabled = get_option( 'woo_mp_stripe_moto_enabled' ) === 'yes';

        $request = [
            'amount'               => $amount,
            'currency'             => $params['currency'],
            'payment_method_types' => [ 'card' ],
            'payment_method_data'  => [
                'type' => 'card',
                'card' => [ 'token' => $params['token'] ],
            ],
            'confirm'              => true,
            'capture_method'       => $params['capture'] ? 'automatic' : 'manual',
            'description'          => $params['description'],
            'metadata'             => [
                'Order Number'   => $params['order']->get_order_number(),
                'Customer Name'  => $params['order']->get_formatted_billing_full_name(),
                'Customer Email' => $params['order']->get_billing_email(),
            ],
            'expand'               => [ 'latest_charge' ],
        ];

        if ( $moto_enabled ) {
            $request['payment_method_options'] = [ 'card' => [ 'moto' => true ] ];
        } else {
            $request['off_session'] = true;
        }

        $request = apply_filters( 'woo_mp_stripe_charge_request', $request, $params['order']->get_core_order() );

        try {
            $payment_intent = $this->request( 'POST', 'payment_intents', $request );
        } catch ( Detailed_Exception $e ) {
            $this->handle_customer_auth_errors( $e, $moto_enabled );

            throw $e;
        }

        return [
            'trans_id'        => $payment_intent->latest_charge->id,
            'held_for_review' => $payment_intent->latest_charge->outcome->type === 'manual_review',
        ];
    }

    /**
     * Handle errors related to customer authentication and the MOTO exemption.
     *
     * @param  Detailed_Exception $e            The exception.
     * @param  bool               $moto_enabled Whether the MOTO exemption was requested.
     * @return void
     * @throws Detailed_Exception               For detailed errors.
     */
    private function handle_customer_auth_errors( $e, $moto_enabled ) {
        if ( ! ( $e->getPrevious() instanceof Stripe_Exception ) ) {
            return;
        }

        $message      = null;
        $code         = null;
        $stripe_code  = $e->getCode();
        $stripe_error = $e->getPrevious()->get_error();
        $stripe_param = isset( $stripe_error->param ) ? $stripe_error->param : null;

        if ( $stripe_code === 'authentication_required' ) {
            $message = 'Strong Customer Authentication is required for this payment. ';

            if ( $moto_enabled ) {
                $message .= "The cardholder's bank has rejected the MOTO exemption.";
                $code     = 'auth_required_moto_enabled';
            } else {
                $message .= "The MOTO exemption was not requested as the 'Mark Payments as MOTO' setting is disabled.";
                $code     = 'auth_required_moto_disabled';
            }
        }

        if ( $stripe_code === 'parameter_unknown' && $stripe_param === 'payment_method_options[card][moto]' ) {
            $message = "The 'Mark Payments as MOTO' setting is incorrectly enabled.";
            $code    = 'moto_incorrectly_enabled';
        }

        if ( $message ) {
            throw new Detailed_Exception( $message, $code, $e->get_data(), $e );
        }
    }

}
