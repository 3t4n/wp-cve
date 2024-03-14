<?php

namespace MailOptin\Mailerlitev2Connect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractMailerlitev2Connect extends AbstractConnect
{
    /** @var Settings */
    protected $plugin_settings;

    /** @var Connections */
    protected $connections_settings;

    public function __construct()
    {
        $this->plugin_settings      = Settings::instance();
        $this->connections_settings = Connections::instance();

        parent::__construct();
    }

    /**
     * Is MailerLite successfully connected to?
     *
     * @return bool
     */
    public static function is_connected($return_error = false)
    {
        $db_options = isset($_POST['mailoptin_connections']) ? $_POST['mailoptin_connections'] : get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        $api_key = isset($db_options['mailerlitev2_api_key']) ? $db_options['mailerlitev2_api_key'] : '';

        if (empty($api_key)) {
            delete_transient('_mo_mailerlitev2_is_connected');

            return false;
        }

        if (isset($_POST['wp_csa_nonce'])) {
            delete_transient('_mo_mailerlitev2_is_connected');
        }

        //Check for connection status from cache
        if ('true' == get_transient('_mo_mailerlitev2_is_connected')) {
            return true;
        }

        try {

            $api    = new APIClass($api_key);
            $result = $api->make_request('timezones');

            if (self::is_http_code_success($result['status_code'])) {
                set_transient('_mo_mailerlitev2_is_connected', 'true', WEEK_IN_SECONDS);

                return true;
            }

            return $return_error === true ? wp_json_encode($result['body']) : false;

        } catch (\Exception $e) {

            return $return_error === true ? $e->getMessage() : false;
        }

    }

    /**
     * Returns instance of API class.
     *
     * @return APIClass
     * @throws \Exception
     *
     */
    public function mailerlitev2_instance()
    {
        $api_key = $this->connections_settings->mailerlitev2_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('MailerLiteV2 API key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}