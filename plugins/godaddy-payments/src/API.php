<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt;

use GoDaddy\WooCommerce\Poynt\API\Requests\AbstractRequest;
use GoDaddy\WooCommerce\Poynt\API\Responses\AbstractResponse;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * The base API handler class.
 *
 * @since 1.0.0
 */
class API extends Framework\SV_WC_API_Base
{
    /** the production endpoint URL */
    const ENDPOINT_PRODUCTION = 'https://services.poynt.net';

    /** the staging endpoint URL */
    const ENDPOINT_STAGING = 'https://services-ote.poynt.net';

    /** @var string the value to use for the sourceApp parameter */
    const SOURCE_APP = 'Poynt a GoDaddy Brand for WooCommerce/'.Plugin::VERSION;

    /** @var string options name to save poynt API token */
    const API_TOKEN_OPTIONS_KEY = 'godaddy_payments_poynt_api_token';

    /** @var string holds the currently authenticated access token, if any */
    protected $accessToken;

    /** @var string the desired environment */
    protected $environment;

    /**
     * Builds a new API instance.
     *
     * @since 1.0.0
     *
     * @param string $environment the desired environment
     */
    public function __construct(string $environment)
    {
        $this->environment = $environment;

        $this->setRequestUri($environment);

        $this->set_request_content_type_header('application/json');
        $this->set_request_accept_header('application/json');
    }

    /**
     * Gets the current access token.
     *
     * @since 1.0.0
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken ?? get_option(self::API_TOKEN_OPTIONS_KEY, null);
    }

    /**
     * Sets the access token.
     *
     * @since 1.0.0
     *
     * @param string|null $value the new access token
     * @return API
     */
    public function setAccessToken(string $value = null)
    {
        update_option(self::API_TOKEN_OPTIONS_KEY, $this->accessToken = $value);

        return $this;
    }

    /**
     * Clears the access token.
     *
     * @since 1.3.1
     *
     * @return API
     */
    public function clearAccessToken()
    {
        return $this->setAccessToken();
    }

    /**
     * Gets the desired environment.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getEnvironment() : string
    {
        return $this->environment;
    }

    /**
     * Validates the response after it's been parsed.
     *
     * @since 1.0.0
     *
     * @return bool
     * @throws Framework\SV_WC_API_Exception
     */
    protected function do_post_parse_response_validation() : bool
    {
        if (! Framework\SV_WC_Helper::str_starts_with((string) $this->get_response_code(), '20')) {
            $message = $this->get_response_message();

            // handle errors related to invalid country code
            if (Framework\SV_WC_Helper::str_exists(strtolower($this->get_raw_response_body()), 'country code is not valid')) {
                $message = __('Your transaction can\'t be completed due to an invalid country code in the checkout form. Please contact the store owner to place your order.', 'godaddy-payments');
            }
            throw new Framework\SV_WC_API_Exception($message, $this->get_response_code());
        }

        return true;
    }

    /**
     * Get the request user agent.
     *
     * @since 1.1.0
     *
     * @return string
     */
    protected function get_request_user_agent() : string
    {
        return sprintf('%s/%s (WooCommerce/%s; WordPress/%s)', 'GoDaddy-Payments-for-WooCommerce', $this->get_plugin()->get_version(), WC_VERSION, $GLOBALS['wp_version']);
    }

    /**
     * Gets a new request object.
     *
     * @since 1.0.0
     *
     * @param array $args optional request arguments
     * @return Framework\SV_WC_API_Request|object
     */
    protected function get_new_request($args = [])
    {
        /* TODO: Return a new request object and add return type declaration to the method signature once they're available. {AC 2020-02-01} */
        return null;
    }

    /**
     * Gets the plugin class instance associated with this API.
     *
     * @since 1.0.0
     *
     * @return Plugin
     */
    protected function get_plugin() : Plugin
    {
        return poynt_for_woocommerce();
    }

    /**
     * Performs the request and returns the parsed response.
     *
     * @since 1.0.0
     *
     * @param AbstractRequest $request request object
     * @return AbstractResponse
     * @throws Framework\SV_WC_API_Exception
     */
    protected function perform_request($request) : AbstractResponse
    {
        $this->setAuthenticationHeaders();

        return parent::perform_request($request);
    }

    /**
     * Sets authentication headers for performing requests.
     *
     * @since 1.0.0
     */
    private function setAuthenticationHeaders()
    {
        $headers = [
            'Api-Version'      => '1.2',
            'Poynt-Request-Id' => wp_generate_uuid4(),
        ];

        if ($token = $this->getAccessToken()) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        $this->set_request_headers($headers);
    }

    /**
     * Sets the request URI according to the environment.
     *
     * @since 1.0.0
     *
     * @param string $environment the desired environment
     */
    private function setRequestUri(string $environment)
    {
        if (Plugin::ENVIRONMENT_PRODUCTION === $environment) {
            $this->request_uri = self::ENDPOINT_PRODUCTION;
        } elseif (Plugin::ENVIRONMENT_STAGING === $environment) {
            $this->request_uri = self::ENDPOINT_STAGING;
        }
    }
}
