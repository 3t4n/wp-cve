<?php

namespace Authifly\Provider;

use Authifly\Adapter\OAuth2;
use Authifly\Data\Collection;
use Authifly\Data;
use Authifly\Exception\InvalidAccessTokenException;
use Authifly\Exception\InvalidArgumentException;
use Authifly\Exception\InvalidAuthorizationCodeException;
use Authifly\HttpClient\Util;

/**
 * Stripe OAuth2 provider adapter.
 */
class Stripe extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://api.stripe.com/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://connect.stripe.com/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://connect.stripe.com/oauth/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://stripe.com/docs/';

    /**
     * {@inheritdoc}
     */
    protected $scope = 'read_write';

    protected $supportRequestState = false;

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $this->tokenExchangeParameters = [
            'grant_type'    => 'authorization_code',
            'client_secret' => $this->clientSecret
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
     * @param string $response
     *
     * @return Collection
     * @throws InvalidAccessTokenException
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
        $this->storeData('livemode', $collection->get('livemode'));
        $this->storeData('stripe_user_id', $collection->get('stripe_user_id'));
        $this->storeData('stripe_publishable_key', $collection->get('stripe_publishable_key'));

        if ($collection->get('refresh_token')) {
            $this->storeData('refresh_token', $collection->get('refresh_token'));
        }

        $this->deleteStoredData('authorization_state');

        $this->initialize();

        return $collection;
    }

    public function getAccessToken()
    {
        $tokenNames = [
            'access_token',
            'token_type',
            'refresh_token',
            'livemode',
            'stripe_user_id',
            'stripe_publishable_key',
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
     * @throws InvalidAuthorizationCodeException
     */
    protected function authenticateCheckError()
    {
        $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS);

        if (! empty($error)) {
            $error_description = filter_input(INPUT_GET, 'error_description', FILTER_SANITIZE_SPECIAL_CHARS);
            $error_uri = filter_input(INPUT_GET, 'error_uri', FILTER_SANITIZE_SPECIAL_CHARS);

            throw new InvalidAuthorizationCodeException(
                sprintf('%s – %s %s', $error, $error_description, $error_uri)
            );
        }
    }
}
