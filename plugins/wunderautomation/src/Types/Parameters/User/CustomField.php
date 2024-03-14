<?php

namespace WunderAuto\Types\Parameters\User;

use WP_User;
use WunderAuto\Types\Parameters\BaseCustomField;

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
        $this->group       = 'user';
        $this->title       = 'customfield';
        $this->description = __('User custom field', 'wunderauto');
        $this->objects     = ['user'];

        $this->customFieldNameCaption = __('Custom field', 'wunderauto');
        $this->customFieldNameDesc    = __('Custom field name (meta key)', 'wunderauto');
    }

    /**
     * @param WP_User   $user
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($user, $modifiers)
    {
        $value = get_metadata('user', $user->ID, $modifiers->field, true);
        return $this->formatCustomField($value, $user, $modifiers);
    }
}
