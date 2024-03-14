<?php

namespace MailOptin\FlodeskConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractFlodeskConnect extends AbstractConnect
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
     * Is Flodesk successfully connected to?
     *
     * @return bool
     */
    public static function is_connected($return_error = false)
    {
        $db_options = isset($_POST['mailoptin_connections']) ? $_POST['mailoptin_connections'] : get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);
        $api_key    = isset($db_options['flodesk_api_key']) ? $db_options['flodesk_api_key'] : '';

        //If the user has not setup flodesk, abort early
        if (empty($api_key)) {
            delete_transient('_mo_flodesk_is_connected');

            return false;
        }

        if (isset($_POST['wp_csa_nonce'])) {
            delete_transient('_mo_flodesk_is_connected');
        }

        //Check for connection status from cache
        if ('true' == get_transient('_mo_flodesk_is_connected')) {
            return true;
        }

        try {

            $api    = new APIClass($api_key);
            $result = $api->make_request('segments');

            if (self::is_http_code_success($result['status_code'])) {
                set_transient('_mo_flodesk_is_connected', 'true', WEEK_IN_SECONDS);

                return true;
            }

            return $return_error === true ? $result['body']->message : false;

        } catch (\Exception $e) {

            return $return_error === true ? $e->getMessage() : false;
        }
    }

    /**
     * Returns instance of API class.
     *
     * @throws \Exception
     *
     * @return APIClass
     */
    public function flodesk_instance()
    {
        $api_key = $this->connections_settings->flodesk_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('Flodesk API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}