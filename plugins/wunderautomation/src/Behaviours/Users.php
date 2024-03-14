<?php

namespace WunderAuto\Behaviours;

use WunderAuto\Loader;

/**
 * Class Users
 */
class Users
{
    /**
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('wp_login', $this, 'userLogin', 10, 2);
    }

    /**
     * @param string   $login
     * @param \WP_User $user
     *
     * @return void
     */
    public function userLogin($login, $user)
    {
        $loginCount = (int)get_metadata('user', $user->ID, 'wa_login_count', true);
        $loginCount++;
        update_metadata('user', $user->ID, 'wa_login_count', $loginCount);
        update_metadata('user', $user->ID, 'wa_last_login', wp_date('Y-m-d H:i:s'));
    }
}
