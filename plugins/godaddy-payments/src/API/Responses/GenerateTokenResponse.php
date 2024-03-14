<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Responses;

defined('ABSPATH') or exit;

/**
 * Generate token response.
 *
 * @since 1.0.0
 */
class GenerateTokenResponse extends AbstractResponse
{
    /**
     * Gets an access token from the response.
     *
     * @link https://docs.poynt.com/api-reference/#model-tokenresponse
     *
     * @since 1.0.0
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->response_data->accessToken ?? null;
    }

    /**
     * Gets the log-safe representation of the response.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function to_string_safe()
    {
        $string = parent::to_string_safe();

        if (! empty($this->response_data->accessToken)) {
            $string = str_replace($this->response_data->accessToken, str_repeat('*', strlen($this->response_data->accessToken)), $string);
        }

        if (! empty($this->response_data->refreshToken)) {
            $string = str_replace($this->response_data->refreshToken, str_repeat('*', strlen($this->response_data->refreshToken)), $string);
        }

        return $string;
    }
}
