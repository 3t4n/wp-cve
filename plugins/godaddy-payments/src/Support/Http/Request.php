<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Support\Http;

use SkyVerge\WooCommerce\PluginFramework\v5_12_1\SV_WC_API_Exception;
use WP_Error;

defined('ABSPATH') or exit;

/**
 * The support HTTP request handler.
 *
 * @since 1.2.0
 */
class Request
{
    /** @var array request body */
    private $body = '';

    /** @var string request method */
    private $method = 'POST';

    /** @var string the URL to send the request to */
    private $url = 'https://api.mwc.secureserver.net/v1/support/request';

    /**
     * Sets the body value.
     *
     * @since 1.2.0
     * @param string $value JSON-encoded request body string
     * @return self
     */
    public function setBody(string $value) : Request
    {
        $this->body = $value;

        return $this;
    }

    /**
     * Gets the body value.
     *
     * @since 1.2.0
     *
     * @return string JSON
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * Sends the request.
     *
     * @since 1.2.0
     *
     * @return array
     * @throws SV_WC_API_Exception
     */
    public function send() : array
    {
        /** @var array|WP_Error $responseData */
        $responseData = wp_remote_request($this->url, [
            'body'    => $this->body,
            'method'  => $this->method,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
        ]);

        if (is_wp_error($responseData)) {
            throw new SV_WC_API_Exception($responseData->get_error_message(), 500);
        }

        if (! is_array($responseData)) {
            throw new SV_WC_API_Exception(__('Invalid response.', 'godaddy-payments'), 500);
        }

        return $responseData;
    }
}
