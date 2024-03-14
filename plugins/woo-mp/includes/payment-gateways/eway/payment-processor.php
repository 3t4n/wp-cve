<?php

namespace Woo_MP\Payment_Gateways\Eway;

defined( 'ABSPATH' ) || die;

/**
 * Process a payment with Eway.
 */
class Payment_Processor extends Transaction_Processor {

    /**
     * Process a payment.
     *
     * Since this is a multi-step process, this method just routes the request to the appropriate step.
     *
     * @param  array $params The following parameters are required:
     *
     * [
     *     'sub_action' => 'sub_action_name', // The current step in the payment process.
     * ]
     *
     * See the particular sub-action method for parameters required by it.
     *
     * @return array         The response from the sub-action method.
     */
    public function process( $params ) {
        if ( isset( $params['sub_action'] ) && is_callable( [ $this, $params['sub_action'] ] ) ) {
            return $this->{$params['sub_action']}( $params );
        }
    }

    /**
     * Get an access code needed to process payments.
     *
     * This is the first step for processing payments.
     * This access code needs to be returned to the client-side, where it can then be used to actually process a payment.
     *
     * @param  array $params The following parameters are required:
     *
     * [
     *     'redirect_url' => 'https://example.com/', // The redirect URL.
     *
     *     // The following parameters are provided by {@see \Woo_MP\Payment_Processor::process()}.
     *     'order'        => null,
     *     'capture'      => false,
     *     'description'  => ''
     * ]
     *
     * @see \Woo_MP\Payment_Processor::process() For parameters required for all payment processors.
     *
     * @return array         Result:
     *
     * [
     *     'form_action_url' => 'https://example.com/',
     *     'access_code'     => 'abc123'
     * ]
     */
    private function get_access_code( $params ) {
        $amount = $this->to_smallest_unit( $params['amount'], $params['currency'] );

        $request = [
            'Payment'         => [
                'TotalAmount'        => $amount,
                'CurrencyCode'       => $params['currency'],
                'InvoiceNumber'      => $params['order']->get_order_number(),
                'InvoiceReference'   => $params['order']->get_order_number(),
                'InvoiceDescription' => $params['description'],
            ],
            'Capture'         => $params['capture'],
            'CustomerIP'      => $params['order']->get_customer_ip_address(),
            'RedirectUrl'     => $params['redirect_url'],
            'TransactionType' => \Eway\Rapid\Enum\TransactionType::PURCHASE,
        ];

        if ( get_option( 'woo_mp_eway_include_billing_details', 'yes' ) === 'yes' ) {
            $request['Customer'] = [
                'FirstName'   => wc_trim_string( $params['order']->get_billing_first_name(), 50 ),
                'LastName'    => wc_trim_string( $params['order']->get_billing_last_name(), 50 ),
                'CompanyName' => wc_trim_string( $params['order']->get_billing_company(), 50 ), // This field is not functional.
                'Street1'     => wc_trim_string( $params['order']->get_billing_address_1(), 50 ),
                'Street2'     => wc_trim_string( $params['order']->get_billing_address_2(), 50 ),
                'City'        => wc_trim_string( $params['order']->get_billing_city(), 50 ),
                'State'       => wc_trim_string( $params['order']->get_billing_state(), 50 ),
                'PostalCode'  => wc_trim_string( $params['order']->get_billing_postcode(), 30 ),
                'Country'     => wc_trim_string( $params['order']->get_billing_country(), 2 ),
                'Email'       => wc_trim_string( $params['order']->get_billing_email(), 50 ),
                'Phone'       => wc_trim_string( $params['order']->get_billing_phone(), 32 ),
            ];
        }

        if ( get_option( 'woo_mp_eway_include_shipping_details', 'yes' ) === 'yes' ) {
            $request['ShippingAddress'] = [
                'FirstName'  => wc_trim_string( $params['order']->get_shipping_first_name(), 50 ),
                'LastName'   => wc_trim_string( $params['order']->get_shipping_last_name(), 50 ),
                'Street1'    => wc_trim_string( $params['order']->get_shipping_address_1(), 50 ),
                'Street2'    => wc_trim_string( $params['order']->get_shipping_address_2(), 50 ),
                'City'       => wc_trim_string( $params['order']->get_shipping_city(), 50 ),
                'State'      => wc_trim_string( $params['order']->get_shipping_state(), 50 ),
                'PostalCode' => wc_trim_string( $params['order']->get_shipping_postcode(), 30 ),
                'Country'    => wc_trim_string( $params['order']->get_shipping_country(), 2 ),
            ];

            if ( version_compare( WC_VERSION, '5.6.0-beta.1', '>=' ) ) {
                $request['ShippingAddress']['Phone'] = wc_trim_string( $params['order']->get_shipping_phone(), 32 );
            }
        }

        $request = apply_filters( 'woo_mp_eway_charge_request', $request, $params['order']->get_core_order() );

        $response = $this->request( 'createTransaction', \Eway\Rapid\Enum\ApiMethod::TRANSPARENT_REDIRECT, $request );

        return [
            'form_action_url' => $response->FormActionURL,
            'access_code'     => $response->AccessCode,
        ];
    }

    /**
     * Get the status of a transaction.
     *
     * This is the final step for processing payments.
     *
     * @param  array $params The following parameters are required:
     *
     * [
     *     'access_code' => 'abc123' // The transaction access code.
     * ]
     *
     * @return array         Result:
     *
     * [
     *     'trans_id'        => '',
     *     'held_for_review' => false
     * ]
     */
    private function get_transaction_status( $params ) {
        $response = $this->request( 'queryTransaction', $params['access_code'] );

        $transaction = $response->Transactions[0];

        return [
            'trans_id'        => $transaction->TransactionID,
            'held_for_review' => false,
        ];
    }

}
