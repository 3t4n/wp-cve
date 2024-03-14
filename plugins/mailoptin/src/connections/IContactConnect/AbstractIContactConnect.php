<?php

namespace MailOptin\IContactConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractIContactConnect extends AbstractConnect
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
        $db_options       = isset($_POST['mailoptin_connections']) ? $_POST['mailoptin_connections'] : get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);
        $app_id           = isset($db_options['icontact_app_id']) ? $db_options['icontact_app_id'] : '';
        $username         = isset($db_options['icontact_username']) ? $db_options['icontact_username'] : '';
        $password         = isset($db_options['icontact_password']) ? $db_options['icontact_password'] : '';
        $account_id       = isset($db_options['icontact_account_id']) ? $db_options['icontact_account_id'] : '';
        $client_folder_id = isset($db_options['icontact_client_folder_id']) ? $db_options['icontact_client_folder_id'] : '';

        if (empty($app_id)) {
            delete_transient('_mo_icontact_is_connected');

            return false;
        }

        if (isset($_POST['wp_csa_nonce'])) {
            delete_transient('_mo_icontact_is_connected');
        }

        //Check for connection status from cache
        if ('true' == get_transient('_mo_icontact_is_connected')) {
            return true;
        }

        try {

            $result = (new APIClass($app_id, $username, $password, $account_id, $client_folder_id))->make_request('lists');

            if (self::is_http_code_success($result['status_code'])) {
                set_transient('_mo_icontact_is_connected', 'true', WEEK_IN_SECONDS);

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
    public function icontact_instance()
    {
        $app_id           = $this->connections_settings->icontact_app_id();
        $username         = $this->connections_settings->icontact_username();
        $password         = $this->connections_settings->icontact_password();
        $account_id       = $this->connections_settings->icontact_account_id();
        $client_folder_id = $this->connections_settings->icontact_client_folder_id();

        if (empty($app_id)) {
            throw new \Exception(__('iContact API ID not found.', 'mailoptin'));
        }

        if (empty($username)) {
            throw new \Exception(__('iContact API username not found.', 'mailoptin'));
        }

        if (empty($password)) {
            throw new \Exception(__('iContact API password not found.', 'mailoptin'));
        }

        if (empty($account_id)) {
            throw new \Exception(__('iContact account ID not found.', 'mailoptin'));
        }

        if (empty($client_folder_id)) {
            throw new \Exception(__('iContact client folder ID not found.', 'mailoptin'));
        }

        return new APIClass($app_id, $username, $password, $account_id, $client_folder_id);
    }
}