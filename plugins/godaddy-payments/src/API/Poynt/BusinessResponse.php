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
 * Business response.
 *
 * @since 1.3.1
 */
class BusinessResponse extends AbstractResponse
{
    /**
     * Gets the appId from the response.
     *
     * @since 1.3.1
     *
     * @return string|null
     */
    public function getAppId()
    {
        $appId = $this->response_data->attributes->pcAppKey ?? null;

        if ($appId) {
            $appIdPieces = explode('=', $appId);
            $appId = end($appIdPieces);
        }

        return $appId;
    }

    /**
     * Gets the serviceId from the response.
     *
     * @since 1.3.1
     *
     * @return string|null
     */
    public function getServiceId()
    {
        return $this->response_data->attributes->godaddyServiceId ?? null;
    }
}
