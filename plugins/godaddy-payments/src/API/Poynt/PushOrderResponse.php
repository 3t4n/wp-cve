<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Poynt;

use GoDaddy\WooCommerce\Poynt\API\Responses\AbstractResponse;

defined('ABSPATH') or exit;

/**
 * Push Order response.
 *
 * @since 1.3.1
 */
class PushOrderResponse extends AbstractResponse
{
    /**
     * Gets the order id.
     *
     * @since 1.3.1
     *
     * @return string|null orderId
     */
    public function getOrderId()
    {
        return $this->__get('id');
    }
}
