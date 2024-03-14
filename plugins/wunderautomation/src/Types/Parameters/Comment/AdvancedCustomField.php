<?php

namespace WunderAuto\Types\Parameters\Comment;

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
        $this->group       = 'comment';
        $this->title       = 'acf';
        $this->description = __('Comment Advanced Custom Field', 'wunderauto');
        $this->objects     = ['comment'];
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
