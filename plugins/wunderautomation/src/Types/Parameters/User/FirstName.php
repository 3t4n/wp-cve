<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class FirstName
 */
class FirstName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'firstname';
        $this->description = __('WordPress user first name', 'wunderauto');
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
        return $this->formatField($user->first_name, $modifiers);
    }
}
