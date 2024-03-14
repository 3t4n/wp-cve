<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Transactions;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractBusinessRequest;

defined('ABSPATH') or exit;

/**
 * Get single transaction request.
 *
 * @since 1.3.0
 */
class TransactionRequest extends AbstractBusinessRequest
{
    /**
     * Get Transaction request constructor.
     *
     * @since 1.3.0
     *
     * @param string $businessId the business ID
     * @param string $transactionId the transaction ID
     */
    public function __construct(string $businessId, string $transactionId, $method = 'GET')
    {
        parent::__construct($businessId);
        $this->method = $method;
        $this->path = "{$this->path}/transactions/{$transactionId}";
    }

    /**
     * Sets the transaction request data.
     *
     * @since 1.3.0
     *
     * @param $requestBody
     */
    public function setRequestData($requestBody)
    {
        $this->data = $requestBody;
    }
}
