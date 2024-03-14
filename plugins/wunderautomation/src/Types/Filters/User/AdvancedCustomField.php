<?php

namespace WunderAuto\Types\Filters\User;

use WunderAuto\Types\Filters\BaseAdvancedCustomField;

/**
 * Class AdvancedCustomField
 */
class AdvancedCustomField extends BaseAdvancedCustomField
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('User', 'wunderauto');
        $this->title       = __('Advanced Custom field', 'wunderauto');
        $this->description = __('Filter object based on value of an user ACF custom field.', 'wunderauto');
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

        $id    = $user->ID;
        $field = $this->filterConfig->field;

        if ((int)$id < 1 || empty($field)) {
            return false;
        }

        $actualValue = get_field($this->filterConfig->field, 'user_' . $id);

        return $this->evaluateCompare($actualValue);
    }
}
