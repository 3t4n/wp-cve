<?php

namespace WunderAuto\Types\Filters\User;

use WP_Post;
use WP_User;
use WunderAuto\Resolver;
use WunderAuto\Types\Filters\BaseCustomField;

/**
 * Class CustomField
 */
class CustomField extends BaseCustomField
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('User', 'wunderauto');
        $this->title       = __('Custom field', 'wunderauto');
        $this->description = __('Filter object based on value of user custom field.', 'wunderauto');
        $this->objects     = ['user'];
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

        $actualValue = get_metadata('user', $user->ID, $this->filterConfig->field, true);

        return $this->evaluateCompare($actualValue);
    }
}
