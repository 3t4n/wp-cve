<?php

namespace WunderAuto\Types\Filters\User;

use WP_User;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Email
 */
class Email extends BaseFilter
{
    /**
     * @var string
     */
    protected $resolverName;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->resolverName = 'user';
        $this->group        = __('User', 'wunderauto');
        $this->title        = __('User email', 'wunderauto');
        $this->description  = __('Filters based on user email address', 'wunderauto');
        $this->objects      = ['user'];

        $this->operators = $this->stringOperators();
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

        $actualValue = $user->user_email;

        return $this->evaluateCompare($actualValue);
    }
}
