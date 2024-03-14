<?php

namespace Woo_MP\Payment_Gateways\Stripe\API\Exceptions;

use stdClass;
use Exception;
use Throwable;

defined( 'ABSPATH' ) || die;

/**
 * Stripe error exception.
 */
class Stripe_Exception extends API_Exception {

    /**
     * The Stripe error object.
     *
     * @var stdClass
     */
    private $error;

    /**
     * Create a new Stripe error exception.
     *
     * @param stdClass                 $error    The Stripe error object.
     * @param array                    $request  The request arguments as used by the WordPress HTTP API.
     * @param array                    $response The response as returned by the WordPress HTTP API.
     * @param Throwable|Exception|null $previous The previous `Throwable` (or `Exception` on PHP versions below 7.0.0).
     */
    public function __construct( $error, $request, $response, $previous = null ) {
        $message = isset( $error->message ) ? $error->message : '';
        $code    = isset( $error->code ) ? $error->code : 0;

        parent::__construct( $message, $code, $request, $response, $previous );

        $this->error = $error;
    }

    /**
     * Get the Stripe error object.
     *
     * @return stdClass The error.
     */
    public function get_error() {
        return $this->error;
    }

}
