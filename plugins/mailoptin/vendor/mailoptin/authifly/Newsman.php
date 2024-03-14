<?php

namespace Authifly\Provider;

use Authifly\Adapter\OAuth2;
use Authifly\Data\Collection;
use Authifly\Data\Parser;
use Authifly\Exception\InvalidAccessTokenException;

class Newsman extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://ssl.newsman.app/api/1.2/rest/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://newsman.app/admin/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://newsman.app/admin/oauth/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://kb.newsman.com/api/1.2/';

    protected $scope = 'api';

    protected $supportRequestState = false;

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $this->AuthorizeUrlParameters = [
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'nzmplugin'     => 'MailOptin',
            'scope'         => $this->scope,
            'redirect_uri'  => $this->callback
        ];

        $this->tokenExchangeParameters = [
            'client_id'    => $this->clientId,
            'grant_type'   => 'authorization_code',
            'redirect_uri' => $this->callback
        ];

        $user_id      = $this->config->get('user_id');
        $access_token = $this->config->get('access_token');

        if ( ! empty($access_token)) {
            $this->apiBaseUrl .= "$user_id/$access_token/";
        }
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
            'user_id',
            'username',
            'firstname',
            'lastname',
        ];

        $tokens = [];

        foreach ($tokenNames as $name) {
            if ($this->getStoredData($name)) {
                $tokens[$name] = $this->getStoredData($name);
            }
        }

        return $tokens;
    }


    /**
     * @param string $response
     *
     * @return \Authifly\Data\Collection
     * @throws InvalidAccessTokenException
     */
    protected function validateAccessTokenExchange($response)
    {
        $data = (new Parser())->parse($response);

        $collection = new Collection($data);

        if ( ! $collection->exists('access_token')) {
            throw new InvalidAccessTokenException(
                'Provider returned an invalid access_token: ' . htmlentities($response)
            );
        }

        $this->storeData('access_token', $collection->get('access_token'));
        $this->storeData('token_type', $collection->get('token_type'));
        $this->storeData('user_id', $collection->get('user_id'));
        $this->storeData('username', $collection->get('username'));
        $this->storeData('firstname', $collection->get('firstname'));
        $this->storeData('lastname', $collection->get('lastname'));

        if ($collection->get('refresh_token')) {
            $this->storeData('refresh_token', $collection->get('refresh_token'));
        }

        // calculate when the access token expire
        if ($collection->exists('expires_in')) {
            $expires_at = time() + (int)$collection->get('expires_in');

            $this->storeData('expires_in', $collection->get('expires_in'));
            $this->storeData('expires_at', $expires_at);
        }

        $this->deleteStoredData('authorization_state');

        $this->initialize();

        return $collection;
    }
}
