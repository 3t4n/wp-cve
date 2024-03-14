<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class LoginCount
 */
class LoginCount extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'logincount';
        $this->description = __('WordPress login count', 'wunderauto');
        $this->objects     = ['user'];
    }

    /**
     * @param WP_User   $user
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($user, $modifiers)
    {
        $count = (int)get_metadata('user', $user->ID, 'wa_login_count', true);
        return $this->formatField($count, $modifiers);
    }
}
