<?php

namespace Woo_MP\Payment_Gateways\Authorize_Net;

use Woo_MP\Detailed_Exception;
use Woo_MP\Payment_Gateways\Authorize_Net\API\Client;

defined( 'ABSPATH' ) || die;

/**
 * Parent class for Authorize.net transaction processors.
 */
class Transaction_Processor extends \Woo_MP\Payment_Gateway\Transaction_Processor {

    /**
     * Authorize.net API client.
     *
     * @var Client
     */
    private $api;

    /**
     * Initialize Authorize.net API client.
     */
    public function __construct() {
        $this->api = new Client( [
            'login_id'        => get_option( 'woo_mp_authorize_net_login_id' ),
            'transaction_key' => get_option( 'woo_mp_authorize_net_transaction_key' ),
            'sandbox'         => get_option( 'woo_mp_authorize_net_test_mode' ) === 'yes',
        ] );
    }

    /**
     * Make a request to Authorize.net.
     *
     * You do not need to supply 'merchantAuthentication'.
     * It will be added to every request automatically.
     *
     * Errors are automatically handled.
     *
     * @param  array              $request The request data.
     * @return array                       Associative array with the format specified
     *                                     {@see Woo_MP\Payment_Gateways\Authorize_Net\Client::process_response()} here.
     * @throws Detailed_Exception          For detailed errors.
     */
    protected function request( $request ) {
        $response = $this->api->request( $request );

        if ( $response['status'] === 'error' ) {
            $message = $response['message'];

            if ( strpos( $message, 'timed out' ) !== false ) {
                $message = "Sorry, Authorize.net did not respond. This means we don't know whether the transaction was successful. Please check your Authorize.net account to confirm.";
            }

            if ( $response['code'] === 'E00007' ) {
                $message = 'Sorry, the Login ID, Transaction Key, or both, are incorrect. Please check your settings and try again.';
            }

            if ( strpos( $message, "transactionKey' element is invalid" ) !== false ) {
                $message = 'Sorry, the Transaction Key is invalid. Please check your settings and try again.';
            }

            throw new Detailed_Exception( $message, $response['code'], [ 'response' => $response ] );
        }

        return $response;
    }

}
