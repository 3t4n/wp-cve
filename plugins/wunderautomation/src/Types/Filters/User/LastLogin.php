<?php

namespace WunderAuto\Types\Filters\User;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class LastLogin
 */
class LastLogin extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('User', 'wunderauto');
        $this->title       = __('Last login date', 'wunderauto');
        $this->description = __('Filter users based on registration date.', 'wunderauto');
        $this->objects     = ['user'];

        $this->operators = $this->dateOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';
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

        $rawDate     = get_metadata('user', $user->ID, 'wa_last_login', true);
        $actualValue = strtotime($rawDate);

        return $this->evaluateCompare($actualValue);
    }
}
