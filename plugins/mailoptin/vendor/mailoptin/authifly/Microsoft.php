<?php

namespace Authifly\Provider;

use Authifly\Adapter\OAuth2;
use Authifly\Data;
use Authifly\Exception\HttpClientFailureException;
use Authifly\Exception\HttpRequestFailedException;
use Authifly\Exception\InvalidAccessTokenException;

/**
 * Microsoft Graph OAuth2 provider adapter.
 *
 * Create an "Azure Active Directory" resource at https://portal.azure.com/
 * (not from the Visual Studio site).
 *
 * The "Supported account types" choice maps to the 'tenant' setting, see "Authority" @
 * https://docs.microsoft.com/en-us/azure/active-directory/develop/msal-client-application-configuration
 *
 * Example:
 *
 *   $config = [
 *       'callback' => Authifly\HttpClient\Util::getCurrentUrl(),
 *       'keys' => ['id' => '', 'secret' => ''],
 *       'tenant' => 'user',
 *         // ^ May be 'common', 'organizations' or 'consumers' or a specific tenant ID or a domain
 *   ];
 *
 *   $adapter = new Authifly\Provider\MicrosoftGraph($config);
 *
 *   try {
 *       $adapter->authenticate();
 *
 *       $userProfile = $adapter->getUserProfile();
 *       $tokens = $adapter->getAccessToken();
 *   } catch (\Exception $e) {
 *       echo $e->getMessage() ;
 *   }
 */
class Microsoft extends OAuth2
{
    /**
     * {@inheritdoc}
     *
     * @see https://learn.microsoft.com/en-us/azure/active-directory/develop/scopes-oidc
     */
    protected $scope = 'offline_access';

    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = '<organizationUri>/api/data/v<version>/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';

    /**
     * {@inheritdoc}
     *
     * @see https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-oauth2-auth-code-flow
     */
    protected $apiDocumentation = 'https://learn.microsoft.com/en-us/power-apps/developer/data-platform/authenticate-oauth';

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $organizationUri = $this->config->get('organizationUri');

        $version = $this->config->get('version');

        if ( ! empty($organizationUri)) {
            $this->apiBaseUrl = str_replace('<organizationUri>', rtrim($organizationUri, '/'), $this->apiBaseUrl);
        }

        if ( ! empty($version)) {
            $this->apiBaseUrl = str_replace('<version>', $version, $this->apiBaseUrl);
        }

        $tenant = $this->config->get('tenant');

        if ( ! empty($tenant)) {

            $adjustedEndpoints = [
                'authorize_url'    => str_replace('/common/', '/' . $tenant . '/', $this->authorizeUrl),
                'access_token_url' => str_replace('/common/', '/' . $tenant . '/', $this->accessTokenUrl),
            ];

            $this->setApiEndpoints($adjustedEndpoints);
        }

        $access_token = $this->getStoredData('access_token');

        if (empty($access_token)) $access_token = $this->config->get('access_token');

        if ( ! empty($access_token)) {
            $this->apiRequestHeaders = [
                'Authorization' => 'Bearer ' . $access_token
            ];
        }

        $refresh_token = $this->getStoredData('refresh_token');

        if (empty($refresh_token)) $refresh_token = $this->config->get('refresh_token');

        // https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-protocols
        $this->tokenRefreshParameters = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh_token
        ];

        $this->tokenRefreshHeaders = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
    }

    /**
     * API call doesn't work when access_token is included in payload. So we removed it by redeclaring the method.
     *
     * {@inheritdoc}
     */
    public function apiRequest($url, $method = 'GET', $parameters = [], $headers = [])
    {
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = $this->apiBaseUrl . $url;
        }

        $parameters = array_replace($this->apiRequestParameters, (array)$parameters);
        $headers    = array_replace($this->apiRequestHeaders, (array)$headers);

        $response = $this->httpClient->request(
            $url,
            $method,
            $parameters,
            $headers
        );

        $this->validateApiResponse('Signed API request has returned an error');

        $response = (new Data\Parser())->parse($response);

        return $response;
    }

    /**
     * @param $error
     *
     * @return void
     * @throws HttpClientFailureException
     * @throws HttpRequestFailedException
     * @throws InvalidAccessTokenException
     */
    protected function validateApiResponse($error = '')
    {
        $error .= ! empty($error) ? '. ' : '';

        if ($this->httpClient->getResponseClientError()) {
            throw new HttpClientFailureException(
                $error . 'HTTP client error: ' . $this->httpClient->getResponseClientError() . '.'
            );
        }

        // if validateApiResponseHttpCode is set to false, we by pass verification of http status code
        if ( ! $this->validateApiResponseHttpCode) {
            return;
        }

        $status = $this->httpClient->getResponseHttpCode();

        // 401 error happens if access token is expired
        // https://learn.microsoft.com/en-us/azure/active-directory/verifiable-credentials/error-codes
        if (401 === $status) {

            throw new InvalidAccessTokenException(
                $this->httpClient->getResponseBody(),
                $status
            );
        }

        if ($status < 200 || $status > 299) {
            throw new HttpRequestFailedException(
                $this->httpClient->getResponseBody(),
                $status
            );
        }
    }
}