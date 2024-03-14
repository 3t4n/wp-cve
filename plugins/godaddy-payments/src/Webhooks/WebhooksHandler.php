<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Webhooks;

/**
 * Abstract webhook received class.
 */
abstract class WebhooksHandler
{
    /** @var array */
    protected $headers;

    /** @var string */
    protected $payload;

    /**
     * Gets the headers.
     *
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * Set the headers.
     *
     * @param array $headers
     * @return array
     */
    public function setHeaders(array $headers) : array
    {
        return $this->headers = $headers;
    }

    /**
     * Gets the payload.
     *
     * @return string
     */
    public function getPayload() : string
    {
        return $this->payload;
    }

    /**
     * Set the payload.
     *
     * @param string $payload
     * @return string
     */
    public function setPayload(string $payload) : string
    {
        return $this->payload = $payload;
    }

    /**
     * Gets the JSON payload as a decoded array.
     *
     * @return array
     */
    public function getPayloadDecoded() : array
    {
        return json_decode($this->getPayload(), true);
    }
}
