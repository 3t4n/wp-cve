<?php

namespace Woo_MP\Payment_Gateways\Stripe\API;

defined( 'ABSPATH' ) || die;

/**
 * Request configuration.
 */
class Config {

    /**
     * Default base URL.
     *
     * @var string
     */
    const DEFAULT_BASE_URL = 'https://api.stripe.com/v1';

    /**
     * Default request timeout in seconds.
     *
     * @var int
     */
    const DEFAULT_REQUEST_TIMEOUT = 80;

    /**
     * Base URL.
     *
     * @var string
     */
    public $base_url = self::DEFAULT_BASE_URL;

    /**
     * Request timeout in seconds.
     *
     * @var int
     */
    public $request_timeout = self::DEFAULT_REQUEST_TIMEOUT;

    /**
     * Request headers.
     *
     * These headers will override default headers.
     *
     * @var array
     */
    public $headers = [];

    /**
     * Stripe API version.
     *
     * @var string|null
     */
    public $api_version = null;

    /**
     * Stripe API secret key.
     *
     * @var string
     */
    public $secret_key = '';

    /**
     * Idempotency key.
     *
     * If an idempotency key is not provided, a random one will be generated for each request.
     *
     * @var string
     */
    public $idempotency_key = '';

    /**
     * Create a new configuration object.
     *
     * @param array $config Associative array of configuration options.
     */
    public function __construct( $config = [] ) {
        foreach ( $config as $name => $value ) {
            $this->$name = $value;
        }
    }

}
