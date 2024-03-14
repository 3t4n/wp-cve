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
 * Request to complete a remote Poynt Order.
 */
class ForceCompleteOrderRequest extends AbstractResourceRequest
{
    /** @var string */
    const RESOURCE_PLURAL = 'orders';

    /** @var string the API action */
    const RESOURCE_ACTION = 'forceComplete';

    /**
     * @param string $remoteOrderId identifies the remote order to complete
     * @param string $businessId the configured business ID
     * @throws Exception
     */
    public function __construct(string $remoteOrderId, string $businessId)
    {
        $this->method = 'POST';
        parent::__construct($businessId, static::RESOURCE_PLURAL, $remoteOrderId, static::RESOURCE_ACTION);
    }
}
