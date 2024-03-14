<?php

namespace MailOptin\MailgunConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractMailgunConnect extends AbstractConnect
{
    /** @var Settings */
    protected $plugin_settings;

    /** @var Connections */
    protected $connections_settings;

    protected $api_key;

    protected $domain_region;

    public function __construct()
    {
        $this->plugin_settings      = Settings::instance();
        $this->connections_settings = Connections::instance();
        $this->api_key              = $this->connections_settings->mailgun_api_key();
        $this->domain_region        = $this->connections_settings->mailgun_domain_region();

        parent::__construct();
    }

    /**
     * Is Constant Contact successfully connected to?
     *
     * @return bool
     */
    public static function is_connected($return_error = false)
    {
        $db_options    = isset($_POST['mailoptin_connections']) ? $_POST['mailoptin_connections'] : get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);
        $api_key       = isset($db_options['mailgun_api_key']) ? $db_options['mailgun_api_key'] : '';
        $domain_region = isset($db_options['mailgun_domain_region']) ? $db_options['mailgun_domain_region'] : '';

        if (empty($api_key)) {
            delete_transient('_mo_mailgun_is_connected');

            return false;
        }

        if (isset($_POST['wp_csa_nonce'])) {
            delete_transient('_mo_mailgun_is_connected');
        }

        //Check for connection status from cache
        if ('true' == get_transient('_mo_mailgun_is_connected')) {
            return true;
        }

        try {

            $result = (new APIClass($api_key, $domain_region))->make_request('lists');

            if (self::is_http_code_success($result['status_code'])) {
                set_transient('_mo_mailgun_is_connected', 'true', WEEK_IN_SECONDS);

                return true;
            }

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
    public function mailgun_instance()
    {
        if (empty($this->api_key)) {
            throw new \Exception(__('Mailgun API Key not found.', 'mailoptin'));
        }

        return new APIClass($this->api_key, $this->domain_region);
    }
}