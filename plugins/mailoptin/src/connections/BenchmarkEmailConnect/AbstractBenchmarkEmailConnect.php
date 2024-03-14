<?php

namespace MailOptin\BenchmarkEmailConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;

class AbstractBenchmarkEmailConnect extends AbstractConnect
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
        $api_key    = isset($db_options['benchmarkemail_api_key']) ? $db_options['benchmarkemail_api_key'] : '';

        //If the user has not setup Benchmark Email, abort early
        if (empty($api_key)) {
            delete_transient('_mo_benchmarkemail_is_connected');

            return false;
        }

        if (isset($_POST['wp_csa_nonce'])) {
            delete_transient('_mo_benchmarkemail_is_connected');
        }

        //Check for connection status from cache
        if ('true' == get_transient('_mo_benchmarkemail_is_connected')) {
            return true;
        }

        try {

            $api = new APIClass($api_key);

            $result = $api->make_request('Client/');

            if (self::is_http_code_success($result['status'])) {
                set_transient('_mo_benchmarkemail_is_connected', 'true', WEEK_IN_SECONDS);

                return true;
            }

            return $return_error === true ? $result['body'] : false;

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
    public function benchmarkemail_instance()
    {
        $api_key = $this->connections_settings->benchmarkemail_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('Benchmark Email API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}