<?php

namespace MailOptin\SalesforceConnect;

use Authifly\Exception\InvalidAccessTokenException;
use Authifly\Provider\Salesforce;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;

class AbstractSalesforceConnect extends AbstractConnect
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function callback_url()
    {
        return add_query_arg(['moauth' => 'salesforce'], MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
    }

    /**
     * Is Salesforce successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['salesforce_consumer_key']) &&
               ! empty($db_options['salesforce_consumer_secret']) &&
               ! empty($db_options['salesforce_access_token']);
    }

    /**
     * @return bool
     */
    public static function is_api_saved()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['salesforce_consumer_key']) &&
               ! empty($db_options['salesforce_consumer_secret']);
    }

    /**
     * @return Salesforce
     * @throws \Exception
     */
    public function salesforceInstance()
    {
        $connections_settings = Connections::instance(true);
        $api_key              = $connections_settings->salesforce_consumer_key();
        $api_secret           = $connections_settings->salesforce_consumer_secret();
        $access_token         = $connections_settings->salesforce_access_token();
        $refresh_token        = $connections_settings->salesforce_refresh_token();
        $instance_url         = $connections_settings->salesforce_instance_url();

        if (empty($access_token)) throw new \Exception(__('Salesforce access token not found.', 'mailoptin'));

        if (empty($refresh_token)) throw new \Exception(__('Salesforce refresh token not found.', 'mailoptin'));

        $api_version = apply_filters('mailoptin_salesforce_rest_api_version', '58.0');

        $config = [
            'callback'   => self::callback_url(),
            'keys'       => ['id' => $api_key, 'secret' => $api_secret],
            'apiBaseUrl' => rtrim($instance_url, '/') . '/services/data/v' . $api_version . '/'
        ];

        $instance = new Salesforce($config, null,
            new OAuthCredentialStorage([
                'salesforce.access_token'  => $access_token,
                'salesforce.refresh_token' => $refresh_token
            ])
        );

        return $instance;
    }

    /**
     * @param $url
     * @param $method
     * @param $parameters
     * @param $headers
     *
     * @return mixed
     * @throws InvalidAccessTokenException
     * @throws \Authifly\Exception\HttpClientFailureException
     * @throws \Authifly\Exception\HttpRequestFailedException
     * @throws \Exception
     */
    public function makeRequest($url, $method = 'GET', $parameters = [], $headers = [])
    {
        $instance = $this->salesforceInstance();

        try {

            return $instance->apiRequest($url, $method, $parameters, $headers);

        } catch (InvalidAccessTokenException $e) {

            if (401 == $e->getCode()) {

                $instance->refreshAccessToken();

                $option_name = MAILOPTIN_CONNECTIONS_DB_OPTION_NAME;
                $old_data    = get_option($option_name, []);
                $new_data    = [
                    'salesforce_access_token'  => $instance->getStorage()->get('salesforce.access_token'),
                    'salesforce_refresh_token' => $instance->getStorage()->get('salesforce.refresh_token')
                ];

                update_option($option_name, array_merge($old_data, $new_data));

                // important to call salesforceInstance() to use new oauth credentials
                return $this->salesforceInstance()->apiRequest($url, $method, $parameters, $headers);
            }

            throw new InvalidAccessTokenException($e->getMessage(), $e->getCode());
        }
    }
}