<?php

namespace Authifly\Provider;

use Authifly\Adapter\OAuth2;
use Authifly\Exception\InvalidArgumentException;
use Authifly\Data;

/**
 * Hubspot OAuth2 provider adapter.
 */
class Hubspot extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    protected $apiBaseUrl = 'https://api.hubapi.com/';

    /**
     * {@inheritdoc}
     */
    protected $authorizeUrl = 'https://app.hubspot.com/oauth/authorize';

    /**
     * {@inheritdoc}
     */
    protected $accessTokenUrl = 'https://api.hubapi.com/oauth/v1/token';

    /**
     * {@inheritdoc}
     */
    protected $apiDocumentation = 'https://developers.hubspot.com/docs/';

    /**
     * {@inheritdoc}
     */
    protected $scope = 'oauth crm.lists.read crm.objects.contacts.read crm.objects.contacts.write crm.schemas.contacts.read crm.lists.write crm.schemas.contacts.write crm.objects.owners.read';

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

        $this->tokenRefreshParameters = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        /** Hubspot explicitly require access token to be set as Bearer.  */
        $access_token = $this->getStoredData('access_token');

        if (empty($access_token)) $access_token = $this->config->get('access_token');

        if ( ! empty($access_token)) {
            $this->apiRequestHeaders = [
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type'  => 'application/json'
            ];
        }
    }

    public function apiRequest($url, $method = 'GET', $parameters = [], $headers = [])
    {
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = $this->apiBaseUrl . $url;
        }

        //When sending campaigns, Hubspot can throw an error if the payload contains an access_token param
        /*if($this->getStoredData('access_token')) {
            $this->apiRequestParameters[$this->accessTokenName] = $this->getStoredData('access_token');
        }*/

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

    /**
     * Return the contact lists for a portal.
     *
     * @param integer $count max number of results to fetch
     *
     * @return array
     */
    public function getEmailList($count = 250)
    {
        $response = $this->apiRequest("contacts/v1/lists/static", "GET", ['count' => $count]);
        $data     = new Data\Collection($response);
        $lists    = $data->filter('lists')->toArray();

        if ( ! is_array($lists)) {
            return array();
        }

        $filtered = [];
        foreach ($lists as $list) {
            $filtered[$list->listId] = $list->name;
        }

        return $filtered;
    }

    /**
     * Get custom fields.
     *
     *
     * @return array
     * @throws \Authifly\Exception\HttpClientFailureException
     * @throws \Authifly\Exception\HttpRequestFailedException
     * @throws \Authifly\Exception\InvalidAccessTokenException
     */
    public function getListCustomFields()
    {
        $fields = $this->apiRequest("crm/v3/properties/contacts");

        if (empty($fields->results)) {
            return [];
        }

        $filtered = [];
        foreach ($fields->results as $field) {

            //Ensure the field is not automatically set by Hubspot
            if ($field->modificationMetadata->readOnlyValue === true) {
                continue;
            }

            if ($field->fieldType == 'calculation_equation') {
                continue;
            }

            // legacy properties
            if (in_array($field->name, ['owneremail', 'ownername'])) {
                continue;
            }

            $filtered[$field->name] = $field->label;
        }

        return $filtered;
    }

    /**
     * Add subscriber to an email list.
     *
     * @param string $list_id
     * @param $email
     * @param array $contact_data
     *
     * @return object
     * @throws InvalidArgumentException
     * @throws \Authifly\Exception\HttpClientFailureException
     * @throws \Authifly\Exception\HttpRequestFailedException
     * @throws \Authifly\Exception\InvalidAccessTokenException
     */
    public function addSubscriber($list_id, $email, $contact_data = [])
    {
        if (empty($list_id)) {
            throw new InvalidArgumentException('List ID is missing');
        }

        if (empty($email)) {
            throw new InvalidArgumentException('Email address is missing');
        }

        $response = $this->apiRequest("contacts/v1/contact/createOrUpdate/email/$email", 'POST', $contact_data);

        if ( ! empty($response->vid) && 'all' != $list_id) {
            $this->addSubscriberToList($list_id, $email);
        }

        return $response;
    }

    /**
     * Adds subscriber to an email list.
     *
     * @param string $list_id
     * @param string $email
     *
     * @return object
     * @throws \Authifly\Exception\HttpClientFailureException
     * @throws \Authifly\Exception\HttpRequestFailedException
     * @throws \Authifly\Exception\InvalidAccessTokenException
     */
    private function addSubscriberToList($list_id, $email)
    {
        $params = (object)[
            'emails' => [$email]
        ];

        return $this->apiRequest("contacts/v1/lists/$list_id/add", 'POST', $params);
    }
}