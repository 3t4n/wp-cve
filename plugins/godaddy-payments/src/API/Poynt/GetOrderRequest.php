<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Poynt;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractResourceRequest;

defined('ABSPATH') or exit;
/**
 * Request to get a remote Poynt Order.
 */
class GetOrderRequest extends AbstractResourceRequest
{
    /** @var string */
    const RESOURCE_PLURAL = 'orders';

    /**
     * @param string $remoteOrderId identifies the remote order to complete
     * @param string $businessId the configured business ID
     * @throws Exception
     */
    public function __construct(string $remoteOrderId, string $businessId)
    {
        $this->method = 'GET';
        parent::__construct($businessId, static::RESOURCE_PLURAL, $remoteOrderId);
    }
}
