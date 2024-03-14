<?php

namespace MailOptin\EgoiConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractEgoiConnect extends AbstractConnect
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
     * Is Constant Contact successfully connected to?
     *
     * @return bool
     */
    public static function is_connected($return_error = false)
    {
        $db_options = isset($_POST['mailoptin_connections']) ? $_POST['mailoptin_connections'] : get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);
        $api_key    = isset($db_options['egoi_api_key']) ? $db_options['egoi_api_key'] : '';

        if (empty($api_key)) {
            delete_transient('_mo_egoi_is_connected');

            return false;
        }

        if (isset($_POST['wp_csa_nonce'])) {
            delete_transient('_mo_egoi_is_connected');
        }

        //Check for connection status from cache
        if ('true' == get_transient('_mo_egoi_is_connected')) {
            return true;
        }

        try {

            $result = (new APIClass($api_key))->make_request('my-account');

            if (self::is_http_code_success($result['status_code'])) {
                set_transient('_mo_egoi_is_connected', 'true', WEEK_IN_SECONDS);

                return true;
            }

            return false;

        } catch (\Exception $e) {
            return $return_error === true ? $e->getMessage() : false;
        }
    }

    /**
     * Returns instance of API class.
     *
     * @return APIClass
     * @throws \Exception
     */
    public function egoi_instance()
    {
        $api_key = $this->connections_settings->egoi_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('E-Goi API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}