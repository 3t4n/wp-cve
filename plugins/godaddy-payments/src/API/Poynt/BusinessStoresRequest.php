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
 * Store devices request.
 *
 * @since 1.3.1
 */
class BusinessStoresRequest extends AbstractBusinessRequest
{
    /**
     * store devices request constructor.
     *
     * @since 1.3.1
     *
     * @param string $businessId the business ID
     */
    public function __construct(string $businessId)
    {
        parent::__construct($businessId);
        $this->method = 'GET';
        $this->path = "{$this->path}/stores";
    }
}
