<?php

namespace App\Pages;

use App\Base\Plugin;
use App\Utils\Helper;

class Admin extends Plugin
{
    /**
     * Register services
     * @return void
     */
    public function register()
    {
        add_action('admin_menu', [$this, 'addMenu']);
    }

    /**
     * Add admin menu
     * @return void
     */
    public function addMenu()
    {
        add_menu_page(
            'Nextsale admin dashboard',
            'Nextsale',
            'manage_options',
            'nextsale',
            [$this, 'callback'],
            self::$plugin_url . 'assets/menu-icon.svg',
            110
        );
    }

    /**
     * Callback function
     * @return void
     */
    public function callback()
    {
        $action = null;
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }

        if ($action == 'revoke' && Helper::isAuthGranted()) {
            $this->revoke();
        } else {
            $this->invoke();
        }
    }

    /**
     * Revoke permissions
     *
     * @return void
     */
    private function revoke()
    {
        if (isset($_POST['nsio-revoke'])) {
            delete_option('nextsale_auth_granted');

            return require(self::$plugin_path . '/templates/revoked.php');
        }

        return require(self::$plugin_path . '/templates/revoke-confirmation.php');
    }

    /**
     * Invoke
     *
     * @return void
     */
    private function invoke()
    {
        if (isset($_POST['nsio-authorize'])) {
            update_option('nextsale_auth_granted', true);
        }

        $access_token = get_option('nextsale_access_token');

        if (!Helper::isAuthGranted()) {
            return require(self::$plugin_path . '/templates/auth-confirmation.php');
        }

        // Generate new exchange code
        $exchange_code = Helper::generateExchangeCode();
        update_option('nextsale_exchange_code', $exchange_code);

        $data = [
            'shop' => Helper::getDomain(),
            'platform' => Helper::getPlatform(),
            'timestamp' => time(),
            'code' => $exchange_code
        ];

        if ($access_token) {
            $query_string = [];
            foreach ($data as $key => $value) {
                $query_string[] = $key . '=' . urlencode($value);
            }

            sort($query_string);

            $hmac = hash_hmac('sha256', implode('&', $query_string), $access_token);
            $data['hmac'] = $hmac;
        }

        $query_string = [];
        foreach ($data as $key => $value) {
            $query_string[] = $key . '=' . urlencode($value);
        }

        sort($query_string);

        $redirect_url = getenv("OAUTH_ENDPOINT") . '?' . implode('&', $query_string);
        $image_url = self::$plugin_url . '/assets/loading.svg';

        require(self::$plugin_path . '/templates/redirect.php');
    }
}
