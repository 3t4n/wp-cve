<?php

namespace Woo_MP\Payment_Gateways\Stripe\API;

use stdClass;
use WP_Error;
use Woo_MP\Payment_Gateways\Stripe\API\Exceptions\API_Exception;
use Woo_MP\Payment_Gateways\Stripe\API\Exceptions\Stripe_Exception;

defined( 'ABSPATH' ) || die;

/**
 * Stripe API client using the WordPress HTTP API.
 *
 * The following features are not supported:
 *
 * * Stripe Connect
 * * File uploads
 * * Default `User-Agent` and `X-Stripe-Client-User-Agent` headers
 */
class Client {

    /**
     * Global request configuration.
     *
     * @var array
     */
    private $config = [];

    /**
     * Create a new client.
     *
     * @param array $config Associative array of {@see Config configuration options} to be used for all requests.
     */
    public function __construct( $config = [] ) {
        $this->config = $config;
    }

    /**
     * Send a request to Stripe.
     *
     * @param  string   $method   The HTTP method.
     * @param  string   $endpoint The endpoint.
     * @param  array    $args     Request arguments.
     * @param  array    $config   Associative array of {@see Config configuration options} to be used for this request.
     * @return stdClass           The response.
     */
    public function request( $method, $endpoint, $args = [], $config = [] ) {
        $config  = new Config( array_replace_recursive( $this->config, $config ) );
        $method  = strtoupper( $method );
        $args    = $this->process_args( $args );
        $url     = $this->generate_request_url( $method, $endpoint, $args, $config );
        $headers = $this->generate_request_headers( $method, $config );

        $request = [
            'timeout' => $config->request_timeout,
            'method'  => $method,
            'headers' => $headers,
            'body'    => $args,
        ];

        $response = wp_remote_request( $url, $request );

        return $this->process_response( $request, $response );
    }

    /**
     * Process request arguments.
     *
     * Boolean values will be converted to their string representations.
     *
     * @param  array $args The arguments.
     * @return array       The processed arguments.
     */
    private function process_args( $args ) {
        array_walk_recursive( $args, function ( &$value ) {
            if ( is_bool( $value ) ) {
                $value = $value ? 'true' : 'false';
            }
        } );

        return $args;
    }

    /**
     * Generate the complete URL for a request.
     *
     * @param  string $processed_method The uppercase HTTP method.
     * @param  string $endpoint         The endpoint.
     * @param  array  $processed_args   The processed request arguments.
     * @param  Config $config           Request configuration.
     * @return string                   The URL.
     */
    private function generate_request_url( $processed_method, $endpoint, $processed_args, $config ) {
        $url = "$config->base_url/$endpoint";

        if ( $processed_method === 'GET' || $processed_method === 'DELETE' ) {
            $url .= '?' . http_build_query( $processed_args );
        }

        return $url;
    }

    /**
     * Generate request headers.
     *
     * @param  string $processed_method The uppercase HTTP method.
     * @param  Config $config           Request configuration.
     * @return array                    Associative array of headers.
     */
    private function generate_request_headers( $processed_method, $config ) {
        $headers = [];

        if ( $config->api_version ) {
            $headers['Stripe-Version'] = $config->api_version;
        }

        if ( $config->secret_key ) {
            $headers['Authorization'] = "Bearer $config->secret_key";
        }

        if ( $processed_method === 'POST' ) {
            $headers['Idempotency-Key'] = $config->idempotency_key ?: bin2hex( random_bytes( 16 ) );
        }

        $headers = $config->headers + $headers;

        return $headers;
    }

    /**
     * Process a response.
     *
     * @param  array          $request  The request arguments as used by the WordPress HTTP API.
     * @param  array|WP_Error $response The response as returned by the WordPress HTTP API.
     * @return stdClass                 The decoded response body.
     */
    private function process_response( $request, $response ) {
        $this->handle_wp_error( $request, $response );

        $decoded_body = json_decode( wp_remote_retrieve_body( $response ) );

        $this->handle_decode_error( $decoded_body, $request, $response );

        $this->handle_stripe_error( $decoded_body, $request, $response );

        return $decoded_body;
    }

    /**
     * Handle WordPress HTTP API errors.
     *
     * @param  array          $request  The request arguments as used by the WordPress HTTP API.
     * @param  array|WP_Error $response The response as returned by the WordPress HTTP API.
     * @return void
     * @throws API_Exception            If the response is an instance of `WP_Error`.
     */
    private function handle_wp_error( $request, $response ) {
        if ( is_wp_error( $response ) ) {
            throw new API_Exception( $response->get_error_message(), $response->get_error_code(), $request );
        }
    }

    /**
     * Handle issues with decoding the response.
     *
     * @param  mixed          $decoded_body The decoded response body.
     * @param  array          $request      The request arguments as used by the WordPress HTTP API.
     * @param  array|WP_Error $response     The response as returned by the WordPress HTTP API.
     * @return void
     * @throws API_Exception                If there was an issue with decoding the response.
     */
    private function handle_decode_error( $decoded_body, $request, $response ) {
        if ( ! ( $decoded_body instanceof stdClass ) ) {
            $message = 'Unable to decode response.';
            $code    = 0;

            if ( $decoded_body === null && wp_remote_retrieve_body( $response ) !== 'null' ) {
                $message .= sprintf( ' JSON error: "%s"', json_last_error_msg() );
                $code     = json_last_error();
            }

            throw new API_Exception( $message, $code, $request, $response );
        }
    }

    /**
     * Handle Stripe errors.
     *
     * @param  stdClass         $decoded_body The decoded response body.
     * @param  array            $request      The request arguments as used by the WordPress HTTP API.
     * @param  array|WP_Error   $response     The response as returned by the WordPress HTTP API.
     * @return void
     * @throws Stripe_Exception               If the response is a Stripe error.
     */
    private function handle_stripe_error( $decoded_body, $request, $response ) {
        if ( isset( $decoded_body->error ) ) {
            throw new Stripe_Exception( $decoded_body->error, $request, $response );
        }
    }

}
