<?php

namespace WunderAuto\Types\Parameters\Order;

use WunderAuto\Types\Parameters\BaseAdvancedCustomField;

/**
 * Class AdvancedCustomFieldPost
 */
class AdvancedCustomField extends BaseAdvancedCustomField
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'acf';
        $this->description = __('Order Advanced Custom Field', 'wunderauto');
        $this->objects     = ['order'];
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
