<?php

namespace Woo_MP\Payment_Gateways\Authorize_Net;

use Woo_MP\Woo_MP_Order;
use WC_Order;
use WC_Order_Item_Product;

defined( 'ABSPATH' ) || die;

/**
 * Process a payment with Authorize.net.
 */
class Payment_Processor extends Transaction_Processor {

    /**
     * Process a payment.
     *
     * @param  array $params The following payment parameters are required:
     *
     * [
     *     'token'          => 'abc123', // The payment nonce.
     *     'tax_amount'     => 1.23,     // The portion of the order total that is made up of taxes.
     *     'duty_amount'    => 4.56,     // The portion of the order total that is made up of duties.
     *     'freight_amount' => 7.89,     // The portion of the order total that is made up of freight/shipping fees.
     *     'tax_exempt'     => false,    // Whether the order is tax-exempt.
     *     'po_number'      => 'xyz789'  // The purchase order number.
     *
     *     // The following parameters are provided by {@see \Woo_MP\Payment_Processor::process()}.
     *     'order'          => null,
     *     'capture'        => false,
     *     'description'    => ''
     * ]
     *
     * @see \Woo_MP\Payment_Processor::process() For parameters required for all payment processors.
     *
     * @return array         Result:
     *
     * [
     *     'trans_id'        => '',
     *     'held_for_review' => false
     * ]
     */
    public function process( $params ) {
        $transaction_type = $params['capture'] ? 'authCaptureTransaction' : 'authOnlyTransaction';

        $request = [
            'createTransactionRequest' => [
                'transactionRequest' => [
                    'transactionType' => $transaction_type,
                    'amount'          => $params['amount'],
                    'payment'         => [
                        'opaqueData' => [
                            'dataDescriptor' => 'COMMON.ACCEPT.INAPP.PAYMENT',
                            'dataValue'      => $params['token'],
                        ],
                    ],
                    'order'           => [
                        'invoiceNumber' => $params['order']->get_order_number(),
                        'description'   => wc_trim_string( $params['description'], 255 ),
                    ],
                    'lineItems'       => [
                        'lineItem' => $this->get_line_items( $params['order'] ),
                    ],
                    'tax'             => [
                        'amount' => (float) $params['tax_amount'],
                    ],
                    'duty'            => [
                        'amount' => (float) $params['duty_amount'],
                    ],
                    'shipping'        => [
                        'amount' => (float) $params['freight_amount'],
                    ],
                    'taxExempt'       => $params['tax_exempt'],
                    'poNumber'        => $params['po_number'],
                    'customer'        => $this->get_customer( $params['order'] ),
                    'billTo'          => [],
                    'shipTo'          => [],
                ],
            ],
        ];

        if ( get_option( 'woo_mp_authorize_net_include_billing_details', 'yes' ) === 'yes' ) {
            $address = $params['order']->get_billing_address_1() . ' ' . $params['order']->get_billing_address_2();

            $request['createTransactionRequest']['transactionRequest']['billTo'] = [
                'firstName'   => wc_trim_string( $params['order']->get_billing_first_name(), 50 ),
                'lastName'    => wc_trim_string( $params['order']->get_billing_last_name(), 50 ),
                'company'     => wc_trim_string( $params['order']->get_billing_company(), 50 ),
                'address'     => wc_trim_string( trim( $address ), 60 ),
                'city'        => wc_trim_string( $params['order']->get_billing_city(), 40 ),
                'state'       => wc_trim_string( $params['order']->get_billing_state(), 40 ),
                'zip'         => wc_trim_string( $params['order']->get_billing_postcode(), 20 ),
                'country'     => wc_trim_string( $params['order']->get_billing_country(), 60 ),
                'phoneNumber' => wc_trim_string( $params['order']->get_billing_phone(), 25 ),
            ];
        }

        if ( get_option( 'woo_mp_authorize_net_include_shipping_details', 'yes' ) === 'yes' ) {
            $address = $params['order']->get_shipping_address_1() . ' ' . $params['order']->get_shipping_address_2();

            $request['createTransactionRequest']['transactionRequest']['shipTo'] = [
                'firstName' => wc_trim_string( $params['order']->get_shipping_first_name(), 50 ),
                'lastName'  => wc_trim_string( $params['order']->get_shipping_last_name(), 50 ),
                'company'   => wc_trim_string( $params['order']->get_shipping_company(), 50 ),
                'address'   => wc_trim_string( trim( $address ), 60 ),
                'city'      => wc_trim_string( $params['order']->get_shipping_city(), 40 ),
                'state'     => wc_trim_string( $params['order']->get_shipping_state(), 40 ),
                'zip'       => wc_trim_string( $params['order']->get_shipping_postcode(), 20 ),
                'country'   => wc_trim_string( $params['order']->get_shipping_country(), 60 ),
            ];
        }

        $request = apply_filters( 'woo_mp_authorize_net_charge_request', $request, $params['order']->get_core_order() );

        $response = $this->request( $request );

        return [
            'trans_id'        => $response['response']['transactionResponse']['transId'],
            'held_for_review' => $response['response']['transactionResponse']['responseCode'] === '4',
        ];
    }

    /**
     * Get line items to send to Authorize.net.
     *
     * @param  Woo_MP_Order|WC_Order $order The order.
     * @return array[]                      The line items.
     */
    private function get_line_items( $order ) {
        $line_items = [];

        /** @var WC_Order_Item_Product $item */
        foreach ( $order->get_items() as $item ) {
            $product     = $item->get_product();
            $item_id     = ( $product ? $product->get_sku() : '' ) ?: $item->get_product_id();
            $name        = $item->get_name() ?: $item_id;
            $description = $product ? ( $product->get_short_description() ?: $product->get_description() ) : '';
            $quantity    = $item->get_quantity();

            $line_items[] = [
                'itemId'      => wc_trim_string( $item_id, 31 ),
                'name'        => wc_trim_string( $name, 31 ),
                'description' => wc_trim_string( wp_strip_all_tags( strip_shortcodes( $description ) ), 255 ),
                'quantity'    => $quantity,
                'unitPrice'   => (float) ( $product ? $product->get_price() : 0 ),
                'taxable'     => $product ? $product->is_taxable() : true,
            ];
        }

        return $line_items;
    }

    /**
     * Get customer information to send to Authorize.net.
     *
     * @param  Woo_MP_Order|WC_Order $order The order.
     * @return (int|string)[]               The customer information.
     */
    private function get_customer( $order ) {
        $field = [];

        $customer_id   = $order->get_customer_id();
        $billing_email = $order->get_billing_email();

        if ( $customer_id && strlen( $customer_id ) <= 20 ) {
            $field['id'] = $customer_id;
        }

        if ( $billing_email && strlen( $billing_email ) <= 255 ) {
            $field['email'] = $billing_email;
        }

        return $field;
    }

}
