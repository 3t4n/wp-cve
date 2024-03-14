<?php
namespace WEDOS\Mon\WP\ApiClient;

/**
 * Base class for monitoring API client
 *
 * @author    Petr Stastny <petr@stastny.eu>
 * @copyright WEDOS Internet, a.s.
 * @license   GPLv3
 */
class MonApiClient extends \PHPF\WP\Api\ApiClient
{
    /**
     * Construct
     *
     * @param string $method API method name
     * @param string|null $user API client username (NULL = from options)
     * @param string|null $token API client token (NULL = from options)
     */
    public function __construct($method, $user = null, $token = null)
    {
        $baseUrl = get_option('won_api_url', 'https://api.wedos.online');

        $url = $baseUrl.'/wp/'.$method;

        if (is_null($user)) {
            $user = get_option('won_pair_checkId');
        }

        if (is_null($token)) {
            $token = get_option('won_pair_apiToken');
        }

        if (!$user || !$token) {
            throw new \Exception('API options missing');
        }

        parent::__construct($url, $user, $token);
    }
}
