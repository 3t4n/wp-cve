<?php

namespace MailOptin\Ctctv3Connect;

use Authifly\Provider\ConstantContactV3;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;

class AbstractCtctv3Connect extends AbstractConnect
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function callback_url()
    {
        return add_query_arg(['moauth' => 'ctctv3'], MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
    }

    /**
     * Is Constant Contact v3 successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['ctctv3_api_key']) &&
               ! empty($db_options['ctctv3_api_secret']) &&
               ! empty($db_options['ctctv3_access_token']);
    }

    /**
     * @return bool
     */
    public static function is_api_saved()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['ctctv3_api_key']) &&
               ! empty($db_options['ctctv3_api_secret']);
    }

    /**
     * Return instance of ConstantContactv3 class.
     *
     * @return ConstantContactV3
     * @throws \Exception
     *
     */
    public function ctctv3Instance()
    {
        $connections_settings = Connections::instance(true);
        $api_key              = $connections_settings->ctctv3_api_key();
        $api_secret           = $connections_settings->ctctv3_api_secret();
        $access_token         = $connections_settings->ctctv3_access_token();
        $refresh_token        = $connections_settings->ctctv3_refresh_token();
        $expires_at           = $connections_settings->ctctv3_expires_at();

        if (empty($access_token)) {
            throw new \Exception(__('Constant Contact (v3) access token not found.', 'mailoptin'));
        }

        if (empty($refresh_token)) {
            throw new \Exception(__('Constant Contact (v3) refresh token not found.', 'mailoptin'));
        }

        $config = [
            'callback' => self::callback_url(),
            'keys'     => ['id' => $api_key, 'secret' => $api_secret],
        ];

        $instance = new ConstantContactV3($config, null,
            new OAuthCredentialStorage([
                'constantcontactv3.access_token'  => $access_token,
                'constantcontactv3.refresh_token' => $refresh_token,
                'constantcontactv3.expires_at'    => $expires_at,
            ])
        );

        if ($instance->hasAccessTokenExpired()) {

            try {

                $instance->refreshAccessToken();

                $option_name = MAILOPTIN_CONNECTIONS_DB_OPTION_NAME;
                $old_data    = get_option($option_name, []);
                $expires_at  = $this->oauth_expires_at_transform($instance->getStorage()->get('constantcontactv3.expires_at'));
                $new_data    = [
                    'ctctv3_access_token'  => $instance->getStorage()->get('constantcontactv3.access_token'),
                    'ctctv3_refresh_token' => $instance->getStorage()->get('constantcontactv3.refresh_token'),
                    'ctctv3_expires_at'    => $expires_at
                ];

                update_option($option_name, array_merge($old_data, $new_data));

                $instance = new ConstantContactV3($config, null,
                    new OAuthCredentialStorage([
                        'constantcontactv3.access_token'  => $instance->getStorage()->get('constantcontactv3.access_token'),
                        'constantcontactv3.refresh_token' => $instance->getStorage()->get('constantcontactv3.refresh_token'),
                        'constantcontactv3.expires_at'    => $expires_at,
                    ]));

            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        return $instance;
    }
}