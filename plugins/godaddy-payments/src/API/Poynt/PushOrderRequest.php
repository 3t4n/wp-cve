<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Poynt;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractBusinessRequest;

defined('ABSPATH') or exit;

/**
 * Push order request.
 *
 * @since 1.3.1
 */
class PushOrderRequest extends AbstractBusinessRequest
{
    /**
     * Push order request constructor.
     *
     * @since 1.3.0
     *
     * @param string $businessId the business ID
     * @param array $body of request
     */
    public function __construct(string $businessId, array $body)
    {
        parent::__construct($businessId);

        $this->method = 'POST';
        $this->path = "{$this->path}/orders";

        $this->data = $body;
    }
}
