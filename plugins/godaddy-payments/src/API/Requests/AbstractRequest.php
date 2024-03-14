<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Requests;

use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Abstract API request object.
 *
 * @since 1.0.0
 */
abstract class AbstractRequest extends Framework\SV_WC_API_JSON_Request
{
    /**
     * Gets the request data.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_data() : array
    {
        /*
         * Filters the request data.
         *
         * @since 1.0.0
         *
         * @param array $requestData the request data to be filtered
         * @param AbstractRequest $request the current request object
         */
        return (array) apply_filters('wc_poynt_api_request_data', parent::get_data(), $this);
    }
}
