<?php

namespace Woo_MP\Payment_Gateways\Authorize_Net\API;

defined( 'ABSPATH' ) || die;

/**
 * Simple Authorize.net API client using WordPress HTTP functions.
 */
class Client {

    /**
     * Production API endpoint.
     *
     * @var string
     */
    const PRODUCTION_ENDPOINT = 'https://api.authorize.net/xml/v1/request.api';

    /**
     * Sandbox API endpoint.
     *
     * @var string
     */
    const SANDBOX_ENDPOINT = 'https://apitest.authorize.net/xml/v1/request.api';

    /**
     * API URL.
     *
     * @var string
     */
    private $api_url;

    /**
     * Login ID.
     *
     * @var string
     */
    private $login_id;

    /**
     * Transaction key.
     *
     * @var string
     */
    private $transaction_key;

    /**
     * Set up initial values.
     *
     * @param array $args Associative array of the following format:
     *
     * [
     *     'login_id'        => '',
     *     'transaction_key' => '',
     *     'sandbox'         => false
     * ]
     *
     * All fields are required.
     */
    public function __construct( $args ) {
        $this->api_url         = $args['sandbox'] ? self::SANDBOX_ENDPOINT : self::PRODUCTION_ENDPOINT;
        $this->login_id        = $args['login_id'];
        $this->transaction_key = $args['transaction_key'];
    }

    /**
     * Make a request.
     *
     * You do not need to supply 'merchantAuthentication'.
     * It will be added to every request automatically.
     *
     * @param  array $request The request data.
     * @return array          Associative array with the format specified
     *                        {@see self::process_response()} here.
     */
    public function request( $request ) {
        $request[ array_keys( $request )[0] ] = [
            'merchantAuthentication' => [
                'name'           => $this->login_id,
                'transactionKey' => $this->transaction_key,
            ],
        ] + $request[ array_keys( $request )[0] ];

        $response = wp_remote_post(
            $this->api_url,
            [
                'timeout' => 45,
                'body'    => json_encode( $request ),
            ]
        );

        $processed_response = $this->process_response( $response );

        return $processed_response;
    }

    /**
     * Process a response from the API.
     *
     * @param  array|\WP_Error $response The response from Authorize.net as returned by wp_remote_post() et al.
     * @return array                     Associative array of the following format:
     *
     * [
     *     'status'                           => '',  // The status of the request. This can be 'success' or 'error'.
     *     'code'                             => '',  // The response code, if applicable.
     *     'message'                          => '',  // The response message. If there was an error, this will describe it.
     *                                                // If the operation was successful, this will describe what was accomplished.
     *     'additional_response_code_details' => [],  // Each string in this array is an additional note about
     *                                                // the response code. Some Authorize.net error messages
     *                                                // are vague, hence the need for this field.
     *                                                // Note that these strings may contain HTML.
     *                                                // This data is sourced from: https://developer.authorize.net/api/reference/dist/json/responseCodes.json
     *     'response'                         => null // The decoded response body.
     * ]
     */
    private function process_response( $response ) {
        if ( is_wp_error( $response ) ) {
            return [
                'status'                           => 'error',
                'code'                             => $response->get_error_code(),
                'message'                          => $response->get_error_message(),
                'additional_response_code_details' => [],
                'response'                         => null,
            ];
        }

        if ( ! $response || empty( $response['body'] ) ) {
            return [
                'status'                           => 'error',
                'code'                             => null,
                'message'                          => 'No response.',
                'additional_response_code_details' => [],
                'response'                         => null,
            ];
        }

        // The API returns the JSON with a BOM character.
        // This must be removed for json_decode() to work.
        $response_body = str_replace( "\xEF\xBB\xBF", '', $response['body'] );

        $decoded_response_body = json_decode( $response_body, true );

        if ( ! $decoded_response_body ) {
            return [
                'status'                           => 'error',
                'code'                             => null,
                'message'                          => 'Unable to decode response.',
                'additional_response_code_details' => [],
                'response'                         => $response_body,
            ];
        }

        $status  = $decoded_response_body['messages']['resultCode'] === 'Ok' ? 'success' : 'error';
        $code    = $decoded_response_body['messages']['message'][0]['code'];
        $message = $decoded_response_body['messages']['message'][0]['text'];

        if ( ! empty( $decoded_response_body['transactionResponse'] ) ) {
            $trans_response = $decoded_response_body['transactionResponse'];

            $response_code = isset( $trans_response['responseCode'] ) ? $trans_response['responseCode'] : null;

            $status = in_array( $response_code, [ '0', '1', '4' ], true ) ? 'success' : 'error';

            if ( isset( $trans_response['messages'][0] ) ) {
                $code    = $trans_response['messages'][0]['code'];
                $message = $trans_response['messages'][0]['description'];
            }

            if ( isset( $trans_response['errors'][0] ) ) {
                $code    = $trans_response['errors'][0]['errorCode'];
                $message = $trans_response['errors'][0]['errorText'];
            }
        }

        return [
            'status'                           => $status,
            'code'                             => $code,
            'message'                          => $message,
            'additional_response_code_details' => $this->get_additional_response_code_details( $code ),
            'response'                         => $decoded_response_body,
        ];
    }

    /**
     * Get additional details about an Authorize.net response code.
     *
     * This data is sourced from: https://developer.authorize.net/api/reference/dist/json/responseCodes.json
     *
     * @param  string|int $code The response code to get additional details for.
     * @return string[]         The details. Note that these strings may contain HTML.
     */
    private function get_additional_response_code_details( $code ) {
        $response_codes = require __DIR__ . '/data/response-codes.php';

        return isset( $response_codes[ $code ] ) ? $response_codes[ $code ] : [];
    }

}
