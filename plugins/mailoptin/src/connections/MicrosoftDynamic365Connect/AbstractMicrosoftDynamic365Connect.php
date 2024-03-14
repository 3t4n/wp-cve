<?php

namespace MailOptin\MicrosoftDynamic365Connect;

use Authifly\Exception\InvalidAccessTokenException;
use Authifly\Provider\Microsoft;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;

class AbstractMicrosoftDynamic365Connect extends AbstractConnect
{
    public function __construct()
    {
        parent::__construct();
    }

    public function callback_url()
    {
        $connections_settings = Connections::instance(true);

        $args = [
            'org_url'       => $connections_settings->microsoftdynamic365_org_url(),
            'client_id'     => $connections_settings->microsoftdynamic365_client_id(),
            'client_secret' => $connections_settings->microsoftdynamic365_client_secret()
        ];

        return add_query_arg($args, $this->get_oauth_url('microsoftdynamic'));
    }

    /**
     * Is Microsoft Dynamics 365 successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['microsoftdynamic365_client_id']) &&
               ! empty($db_options['microsoftdynamic365_client_secret']) &&
               ! empty($db_options['microsoftdynamic365_org_url']) &&
               ! empty($db_options['microsoftdynamic365_access_token']);
    }

    /**
     * @return bool
     */
    public static function is_api_saved()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['microsoftdynamic365_client_id']) &&
               ! empty($db_options['microsoftdynamic365_client_secret']) &&
               ! empty($db_options['microsoftdynamic365_org_url']);
    }

    /**
     * @return Microsoft
     * @throws \Exception
     */
    public function microsoftdynamic365Instance()
    {
        $connections_settings = Connections::instance(true);
        $api_key              = $connections_settings->microsoftdynamic365_client_id();
        $api_secret           = $connections_settings->microsoftdynamic365_client_secret();
        $access_token         = $connections_settings->microsoftdynamic365_access_token();
        $refresh_token        = $connections_settings->microsoftdynamic365_refresh_token();
        $expires_at           = $connections_settings->microsoftdynamic365_expires_at();
        $org_url              = $connections_settings->microsoftdynamic365_org_url();

        if (empty($access_token)) throw new \Exception(__('Microsoft Dynamics 365 access token not found.', 'mailoptin'));

        if (empty($refresh_token)) throw new \Exception(__('Microsoft Dynamics 365 refresh token not found.', 'mailoptin'));

        $api_version = apply_filters('mailoptin_microsoftdynamic365_rest_api_version', '9.2');

        $config = [
            'callback'        => self::callback_url(),
            'keys'            => ['id' => $api_key, 'secret' => $api_secret],
            'organizationUri' => $org_url,
            'version'         => $api_version
        ];

        return new Microsoft($config, null,
            new OAuthCredentialStorage([
                'microsoft.access_token'  => $access_token,
                'microsoft.refresh_token' => $refresh_token,
                'microsoft.expires_at'    => $expires_at
            ])
        );
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
        $instance = $this->microsoftdynamic365Instance();

        try {

            // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/webapi/query-data-web-api
            $core_headers = [
                'Content-Type'     => 'application/json',
                'OData-MaxVersion' => '4.0',
                'OData-Version'    => '4.0',
                'If-None-Match'    => 'null'
            ];

            $headers = array_replace($core_headers, $headers);

            $response = $instance->apiRequest($url, $method, $parameters, $headers);

            return ['status' => $instance->getHttpClient()->getResponseHttpCode(), 'body' => $response];

        } catch (InvalidAccessTokenException $e) {

            if (401 == $e->getCode()) {

                $instance->refreshAccessToken();

                $option_name = MAILOPTIN_CONNECTIONS_DB_OPTION_NAME;
                $old_data    = get_option($option_name, []);
                $new_data    = [
                    'microsoftdynamic365_access_token'  => $instance->getStorage()->get('microsoft.access_token'),
                    'microsoftdynamic365_refresh_token' => $instance->getStorage()->get('microsoft.refresh_token'),
                    'microsoftdynamic365_expires_at'    => $instance->getStorage()->get('microsoft.expires_at')
                ];

                update_option($option_name, array_merge($old_data, $new_data));

                // important to call microsoftdynamic365Instance() to use new oauth credentials
                $instance = $this->microsoftdynamic365Instance();

                $response = $instance->apiRequest($url, $method, $parameters, $headers);

                return ['status' => $instance->getHttpClient()->getResponseHttpCode(), 'body' => $response];
            }

            throw new InvalidAccessTokenException($e->getMessage(), $e->getCode());
        }
    }
}