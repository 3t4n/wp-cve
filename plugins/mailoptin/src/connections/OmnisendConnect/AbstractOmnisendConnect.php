<?php

namespace MailOptin\OmnisendConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;

class AbstractOmnisendConnect extends AbstractConnect
{
    /** @var Connections */
    protected $connections_settings;

    public function __construct()
    {
        $this->connections_settings = Connections::instance();

        parent::__construct();
    }

    /**
     * @return bool
     */
    public static function is_connected($return_error = false)
    {
        $db_options = isset($_POST['mailoptin_connections']) ? $_POST['mailoptin_connections'] : get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);
        $api_key    = isset($db_options['omnisend_api_key']) ? $db_options['omnisend_api_key'] : '';

        return ! empty($api_key);
    }

    /**
     * Returns instance of API class.
     *
     * @return APIClass
     * @throws \Exception
     *
     */
    public function omnisend_instance()
    {
        $api_key = $this->connections_settings->omnisend_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('Omnisend API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}