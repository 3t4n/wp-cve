<?php

namespace Authifly\Provider;

use Authifly\Adapter\OAuth2;
use Authifly\Data;
use Authifly\Exception\InvalidAccessTokenException;

/**
 * Class Zoho
 * @see https://www.zoho.com/accounts/protocol/oauth/web-apps/authorization.html
 * @package Authifly\Provider
 */
class Zoho extends OAuth2
{
    /**
     * {@inheritdoc
     *
     * This is public we are overriding this for ZohoCRM
     */
    public $apiBaseUrl = 'https://campaigns.zoho.com/api/v1.1/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://accounts.zoho.com/oauth/v2/auth';

    /**
     * {@inheritdoc}
     *
     *
     * This is public we are overriding this for refreshing token
     */
    public $accessTokenUrl = 'https://accounts.zoho.com/oauth/v2/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://www.zoho.com/campaigns/help/developers/index.html';

    /**
     * {@inheritdoc}
     */
    protected $supportRequestState = false;

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $refresh_token = $this->getStoredData('refresh_token');

        if (empty($refresh_token)) {
            $refresh_token = $this->config->get('refresh_token');
        }

        $client_secret = $this->clientSecret;

        //if (isset($_GET['location']) && $_GET['location'] != 'us') {
        //    $client_secret = $this->config->filter('keys')->get($_GET['location'] . '_secret');
        //}

        $this->tokenRefreshParameters = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id'     => $this->clientId,
            'client_secret' => $client_secret,
        ];

        $this->AuthorizeUrlParameters = [
            'response_type' => 'code',
            'access_type'   => 'offline',
            'prompt'        => 'consent',
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->callback,
            'scope'         => $this->scope,
        ];

        $access_token = $this->getStoredData('access_token');

        if (empty($access_token)) $access_token = $this->config->get('access_token');

        if ( ! empty($access_token)) {
            $this->apiRequestHeaders = [
                'Authorization' => 'Bearer ' . $access_token
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see https://prezoho.zohocorp.com/accounts/protocol/oauth/multi-dc.html
     * @see https://www.zoho.com/accounts/protocol/oauth/web-apps/authorization.html
     */
    protected function exchangeCodeForAccessToken($code)
    {
        /**
         * @see https://www.zoho.com/accounts/protocol/oauth/multi-dc/client-authorization.html
         * @see https://www.zoho.com/accounts/protocol/oauth/web-apps/access-token.html
         */
        $this->accessTokenUrl                  = $_GET['accounts-server'] . '/oauth/v2/token';
        $this->tokenExchangeParameters['code'] = $code;

        //if (isset($_GET['location']) && $_GET['location'] != 'us') {
        //    $this->tokenExchangeParameters['client_secret'] = $this->config->filter('keys')->get($_GET['location'] . '_secret');
        //}

        $response = $this->httpClient->request(
            $this->accessTokenUrl,
            $this->tokenExchangeMethod,
            $this->tokenExchangeParameters,
            $this->tokenExchangeHeaders
        );

        $this->validateApiResponse('Unable to exchange code for API access token');

        return $response;
    }

    /**
     * {@inheritdoc}
     *
     * @see https://prezoho.zohocorp.com/accounts/protocol/oauth/multi-dc.html
     * @see https://www.zoho.com/accounts/protocol/oauth/web-apps/authorization.html
     */
    protected function validateAccessTokenExchange($response)
    {
        $data = (new Data\Parser())->parse($response);

        $collection = new Data\Collection($data);

        if ( ! $collection->exists('access_token')) {
            throw new InvalidAccessTokenException(
                'Provider returned an invalid access_token: ' . htmlentities($response)
            );
        }

        $this->storeData('access_token', $collection->get('access_token'));
        $this->storeData('token_type', $collection->get('token_type'));

        if ($collection->get('refresh_token')) {
            $this->storeData('refresh_token', $collection->get('refresh_token'));
        }

        // calculate when the access token expire
        // for zoho, expires_in is in milliseconds. Instead we use expires_in_sec
        if ($collection->exists('expires_in_sec')) {
            $expires_at = time() + (int)$collection->get('expires_in_sec');

            $this->storeData('expires_in', $collection->get('expires_in_sec'));
            $this->storeData('expires_at', $expires_at);
        }

        // custom feature starts
        if (isset($_GET['accounts-server'])) {
            $this->storeData('accounts_server', $_GET['accounts-server']);
        }

        if (isset($_GET['location'])) {
            $this->storeData('location', $_GET['location']);
        }

        if ($collection->exists('api_domain')) {
            $this->storeData('api_domain', $collection->get('api_domain'));
        }

        // custom feature ends.

        $this->deleteStoredData('authorization_state');

        $this->initialize();

        return $collection;
    }

    public function refreshAccessToken($parameters = [])
    {
        $accessTokenUrl = $this->accessTokenUrl;

        if (isset($_GET['location'])) {

            /** @see https://www.zoho.com/crm/developer/docs/api/v2/refresh.html */
            switch ($_GET['location']) {
                case 'eu':
                    $accessTokenUrl = 'https://accounts.zoho.eu/oauth/v2/token';
                    break;
                case 'au':
                    $accessTokenUrl = 'https://accounts.zoho.com.au/oauth/v2/token';
                    break;
                case 'in':
                    $accessTokenUrl = 'https://accounts.zoho.in/oauth/v2/token';
                    break;
                case 'cn':
                    $accessTokenUrl = 'https://accounts.zoho.com.cn/oauth/v2/token';
                    break;
                case 'jp':
                    $accessTokenUrl = 'https://accounts.zoho.jp/oauth/v2/token';
                    break;
                default:
                    $accessTokenUrl = sprintf('https://accounts.zoho.com/oauth/v2/token', sanitize_text_field($_GET['location']));
                    break;
            }
        }

        $this->tokenRefreshParameters = ! empty($parameters)
            ? $parameters
            : $this->tokenRefreshParameters;

        $response = $this->httpClient->request(
            $accessTokenUrl,
            $this->tokenRefreshMethod,
            $this->tokenRefreshParameters,
            $this->tokenRefreshHeaders
        );

        $this->validateApiResponse('Unable to refresh the access token');

        $this->validateRefreshAccessToken($response);

        return $response;
    }

    public function getAccessToken()
    {
        $tokenNames = [
            'access_token',
            'access_token_secret',
            'token_type',
            'refresh_token',
            'expires_in',
            'expires_at',
            'api_domain',
            'location',
            'accounts_server'
        ];

        $tokens = [];

        foreach ($tokenNames as $name) {
            if ($this->getStoredData($name)) {
                $tokens[$name] = $this->getStoredData($name);
            }
        }

        return $tokens;
    }

    public function apiRequest($url, $method = 'GET', $parameters = [], $headers = [])
    {
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = $this->apiBaseUrl . $url;
        }

        if ($this->getStoredData('access_token')) {
            $this->apiRequestParameters[$this->accessTokenName] = $this->getStoredData('access_token');
        }

        $parameters = array_replace($this->apiRequestParameters, (array)$parameters);
        $headers    = array_replace($this->apiRequestHeaders, (array)$headers);

        $response = $this->httpClient->request(
            $url,
            $method,     // HTTP Request Method. Defaults to GET.
            $parameters, // Request Parameters
            $headers     // Request Headers
        );

        $this->validateApiResponse('Signed API request has returned an error');

        $response = (new Data\Parser())->parse($response);

        return $response;
    }
}