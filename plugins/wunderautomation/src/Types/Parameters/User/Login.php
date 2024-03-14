<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Login
 */
class Login extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'login';
        $this->description = __('WordPress user login name', 'wunderauto');
        $this->objects     = ['user'];

        $this->usesDefault = true;
    }

    /**
     * @param WP_User|null $user
     * @param \stdClass    $modifiers
     *
     * @return mixed
     */
    public function getValue($user, $modifiers)
    {
        if (!is_null($user)) {
            return $this->formatField($user->user_login, $modifiers);
        }

        return null;
    }
}
