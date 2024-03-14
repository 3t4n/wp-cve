<?php

namespace WunderAuto\Types\Filters\User;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class LoginCount
 */
class LoginCount extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('User', 'wunderauto');
        $this->title       = __('Login count', 'wunderauto');
        $this->description = __('Filter users based on number of logins.', 'wunderauto');
        $this->objects     = ['user'];

        $this->operators = $this->numberOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'number';
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $user = $this->getObject();
        if (!($user instanceof \WP_User)) {
            return false;
        }

        $actualValue = (int)get_metadata('user', $user->ID, 'wa_login_count', true);

        return $this->evaluateCompare($actualValue);
    }
}
