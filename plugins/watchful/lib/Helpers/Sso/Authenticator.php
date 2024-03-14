<?php

namespace Watchful\Helpers\Sso;

use WP_Error;
use WP_User;

class Authenticator
{
    /** @var Client */
    private $client;

    /** @var bool */
    private $sso_enabled;

    /** @var bool */
    private $sso_adminonly;

    /** @var UserManager */
    private $user_manager;

    public function __construct()
    {
        $this->client = new Client();
        $this->user_manager = new UserManager();

        $settings = get_option('watchfulSettings', '000');
        $this->sso_enabled = isset($settings['watchful_sso_authentication']) && $settings['watchful_sso_authentication'];
        $this->sso_adminonly = isset($settings['watchful_sso_authentication_adminonly']) && $settings['watchful_sso_authentication_adminonly'];
    }

    /**
     * @param WP_User|WP_Error|null $user
     * @param string $username
     * @param string $password
     * @return WP_Error|WP_User
     */
    public function authenticate($user, $username, $password)
    {
        if ($user instanceof WP_User) {
            return $user;
        }

        if ($this->sso_adminonly && !is_admin()) {
            return $user;
        }

        if (empty($username) || empty($password) || !$this->sso_enabled) {
            return $user;
        }

        $user_data = $this->client->perform_api_authentication($username, $password);

        if (is_wp_error($user_data)) {
            return $user_data;
        }

        return $this->user_manager->get_wp_user_by_data($user_data);
    }
}
