<?php

namespace Woo_MP\Payment_Gateways\Stripe\API\Exceptions;

use Exception;
use Throwable;

defined( 'ABSPATH' ) || die;

/**
 * API exception.
 */
class API_Exception extends Exception {

    /**
     * The request arguments as used by the WordPress HTTP API.
     *
     * @var array|null
     */
    private $request;

    /**
     * The response as returned by the WordPress HTTP API.
     *
     * @var array|null
     */
    private $response;

    /**
     * Create a new API exception.
     *
     * @param string                   $message  The message.
     * @param int|string               $code     The code.
     * @param array|null               $request  The request arguments as used by the WordPress HTTP API.
     * @param array|null               $response The response as returned by the WordPress HTTP API.
     * @param Throwable|Exception|null $previous The previous `Throwable` (or `Exception` on PHP versions below 7.0.0).
     */
    public function __construct( $message = '', $code = 0, $request = null, $response = null, $previous = null ) {
        parent::__construct( $message, 0, $previous );

        // Bypass the integer restriction.
        $this->code = $code;

        unset( $request['headers']['Authorization'] );

        $this->request = $request;

        $this->response = $response;
    }

    /**
     * Get the request arguments as used by the WordPress HTTP API.
     *
     * @return array|null
     */
    public function get_request() {
        return $this->request;
    }

    /**
     * Get the response as returned by the WordPress HTTP API.
     *
     * @return array|null
     */
    public function get_response() {
        return $this->response;
    }

}
