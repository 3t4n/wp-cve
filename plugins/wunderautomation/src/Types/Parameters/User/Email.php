<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Email
 */
class Email extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'email';
        $this->description = __('WordPress user email address', 'wunderauto');
        $this->objects     = ['user'];
        $this->usesDefault = true;
    }

    /**
     * @param WP_User   $user
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($user, $modifiers)
    {
        return $this->formatField($user->user_email, $modifiers);
    }
}
