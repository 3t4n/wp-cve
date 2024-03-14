<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class LastName
 */
class LastName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'lastname';
        $this->description = __('WordPress user last name', 'wunderauto');
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
        return $this->formatField($user->last_name, $modifiers);
    }
}
