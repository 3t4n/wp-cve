<?php

namespace Woo_MP\Payment_Gateways\Eway;

use Woo_MP\Detailed_Exception;
use Eway\Rapid;
use Eway\Rapid\Contract\Client;

defined( 'ABSPATH' ) || die;

if ( ! class_exists( Rapid::class ) ) {
    require WOO_MP_PATH . '/includes/payment-gateways/eway/libraries/eway-rapid-php-1.4.1/include_eway.php';
}

/**
 * Parent class for Eway transaction processors.
 */
class Transaction_Processor extends \Woo_MP\Payment_Gateway\Transaction_Processor {

    /**
     * Eway API client.
     *
     * @var Client
     */
    private $client;

    /**
     * Initialize Eway SDK.
     */
    public function __construct() {
        $this->client = Rapid::createClient(
            get_option( 'woo_mp_eway_api_key' ),
            get_option( 'woo_mp_eway_api_password' ),
            get_option( 'woo_mp_eway_sandbox_mode' ) === 'yes'
            ? Client::MODE_SANDBOX
            : Client::MODE_PRODUCTION
        );
    }

    /**
     * Make a request to Eway.
     *
     * Errors are automatically handled.
     *
     * @param  string             $method  The method to call on the client.
     * @param  mixed              ...$args The arguments to pass to the method.
     * @return mixed                       The response.
     * @throws Detailed_Exception          For detailed errors.
     */
    protected function request( $method, ...$args ) {
        $response   = $this->client->$method( ...$args );
        $error_code = null;

        if ( $response->getErrors() ) {
            $error_code = $response->getErrors()[0];

            if ( $error_code === Client::ERROR_HTTP_AUTHENTICATION_ERROR ) {
                throw new Detailed_Exception(
                    'Sorry, the API Key, API Password, or both, are incorrect. Please check your settings and try again.',
                    $error_code
                );
            }
        }

        if ( isset( $response->Transactions ) && ! $response->Transactions[0]->TransactionStatus ) {
            $error_code = explode( ',', $response->Transactions[0]->ResponseMessage )[0];
        }

        if ( $error_code ) {
            throw new Detailed_Exception( Rapid::getMessage( $error_code ), $error_code );
        }

        return $response;
    }

    /**
     * Get all Eway response code messages.
     *
     * @return array Associative array with codes as keys and messages as values.
     */
    public static function get_response_code_messages() {
        return parse_ini_file( WOO_MP_PATH . '/includes/payment-gateways/eway/libraries/eway-rapid-php-1.4.1/resource/lang/en.ini' );
    }

}
