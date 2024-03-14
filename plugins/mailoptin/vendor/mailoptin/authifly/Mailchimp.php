<?php

namespace Authifly\Provider;

use Authifly\Adapter\OAuth2;
use Authifly\Exception\HttpClientFailureException;
use Authifly\Exception\HttpRequestFailedException;
use Authifly\Exception\InvalidAccessTokenException;
use Authifly\Exception\InvalidArgumentException;

/**
 * VerticalResponse OAuth2 provider adapter.
 */
class Mailchimp extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://<dc>.api.mailchimp.com/3.0';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://login.mailchimp.com/oauth2/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://login.mailchimp.com/oauth2/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://mailchimp.com/developer/marketing/guides/access-user-data-oauth-2/';

    /**
     * {@inheritdoc}
     */
    protected $scope = '';

    protected $supportRequestState = false;

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $data_center = $this->config->get('dc');

        if ( ! empty($data_center)) {
            $this->apiBaseUrl = str_replace('<dc>', $data_center, $this->apiBaseUrl);
        }

        $access_token = $this->getStoredData('access_token');

        if (empty($access_token)) $access_token = $this->config->get('access_token');

        if ( ! empty($access_token)) {
            $this->apiRequestHeaders = [
                'Authorization' => 'Bearer ' . $access_token
            ];
        }
    }

    public function hasAccessTokenExpired()
    {
        return false;
    }

    /**
     * @return mixed
     * @throws HttpClientFailureException
     * @throws HttpRequestFailedException
     * @throws InvalidAccessTokenException
     */
    public function connectionPing()
    {
        return $this->apiRequest("/ping");
    }

    /**
     * @return object
     * @throws HttpClientFailureException
     * @throws HttpRequestFailedException
     * @throws InvalidAccessTokenException
     */
    public function getOauthMetaData()
    {
        return $this->apiRequest("https://login.mailchimp.com/oauth2/metadata");
    }
}