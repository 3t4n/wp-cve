<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Poynt;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractRequest;

defined('ABSPATH') or exit;

/**
 * Register webhooks request.
 *
 * @since 1.3.0
 */
class RegisterWebhooksRequest extends AbstractRequest
{
    /**
     * RegisterWebhooks request constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->method = 'POST';
        $this->path = '/hooks';
    }

    public function setRequestData(array $requestData)
    {
        $this->data = $requestData;
    }
}
