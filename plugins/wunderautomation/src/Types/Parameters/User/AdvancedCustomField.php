<?php

namespace WunderAuto\Types\Parameters\User;

use WunderAuto\Types\Parameters\BaseAdvancedCustomField;

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
        $this->group       = 'user';
        $this->title       = 'acf';
        $this->description = __('User Advanced Custom Field', 'wunderauto');
        $this->objects     = ['user'];
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        return parent::getValue($object, $modifiers);
    }
}
