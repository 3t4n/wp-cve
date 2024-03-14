<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Date
 */
class Date extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'date';
        $this->description = __('WordPress user registration date', 'wunderauto');
        $this->objects     = ['user'];

        $this->usesDefault    = true;
        $this->usesDateFormat = true;
    }

    /**
     * @param WP_User   $user
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($user, $modifiers)
    {
        $date = strtotime($user->user_registered);
        $date = $this->formatDate($date, $modifiers);
        return $this->formatField($date, $modifiers);
    }
}
