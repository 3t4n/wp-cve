<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class LastLogin
 */
class LastLogin extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'lastlogin';
        $this->description = __('WordPress user last login date', 'wunderauto');
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
        $rawDate = get_metadata('user', $user->ID, 'wa_last_login', true);
        $date    = strtotime($rawDate);
        $date    = $this->formatDate($date, $modifiers);
        return $this->formatField($date, $modifiers);
    }
}
