<?php

namespace Woo_MP\Payment_Gateways\Stripe;

use stdClass;
use Woo_MP\Detailed_Exception;
use Woo_MP\Payment_Gateways\Stripe\API\Client;
use Woo_MP\Payment_Gateways\Stripe\API\Config;
use Woo_MP\Payment_Gateways\Stripe\API\Exceptions\API_Exception;
use Woo_MP\Payment_Gateways\Stripe\API\Exceptions\Stripe_Exception;

defined( 'ABSPATH' ) || die;

/**
 * Parent class for Stripe transaction processors.
 */
class Transaction_Processor extends \Woo_MP\Payment_Gateway\Transaction_Processor {

    /**
     * Stripe API client.
     *
     * @var Client
     */
    private $client;

    /**
     * Initialize Stripe API client.
     */
    public function __construct() {
        $this->client = new Client( [
            'api_version' => '2023-10-16',
            'secret_key'  => get_option( 'woo_mp_stripe_secret_key' ),
            'headers'     => $this->get_headers(),
        ] );
    }

    /**
     * Send a request to Stripe.
     *
     * Errors are automatically handled.
     *
     * @param  string             $method   The HTTP method.
     * @param  string             $endpoint The endpoint.
     * @param  array              $args     Request arguments.
     * @param  array              $config   Associative array of {@see Config configuration options}
     *                                      to be used for this request.
     * @return stdClass                     The response.
     * @throws Detailed_Exception           For detailed errors.
     */
    protected function request( $method, $endpoint, $args = [], $config = [] ) {
        try {
            $response = $this->client->request( $method, $endpoint, $args, $config );
        } catch ( API_Exception $e ) {
            $message = $e->getMessage();

            if ( strpos( $message, 'timed out' ) !== false ) {
                $message =
                    'Sorry, Stripe did not respond. ' .
                    "This means we don't know whether the transaction was successful. " .
                    'Please check your Stripe account to confirm.';
            }

            $context = [
                'error'    => $e instanceof Stripe_Exception ? $e->get_error() : null,
                'request'  => $e->get_request(),
                'response' => $e->get_response(),
            ];

            throw new Detailed_Exception( $message, $e->getCode(), $context, $e );
        }

        return $response;
    }

    /**
     * Get custom request headers for the API client.
     *
     * @return string[] The headers.
     */
    private function get_headers() {
        /**
         * Filter the Stripe request headers for all API requests.
         *
         * Headers added here will override default headers.
         *
         * @param string[] $headers An associative array of headers.
         */
        return apply_filters( 'woo_mp_stripe_request_headers', [] );
    }

}
