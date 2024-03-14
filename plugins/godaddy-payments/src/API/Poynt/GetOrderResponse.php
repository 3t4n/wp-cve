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
 * Get order response.
 *
 * @since 1.3.0
 */
class GetOrderResponse extends AbstractResponse
{
    /**
     * Gets Response data.
     *
     * @since 1.3.0
     *
     * @return array
     */
    public function getBody()
    {
        if (empty($data = json_decode($this->raw_response_json, true))) {
            return [];
        }

        return $data;
    }
}
