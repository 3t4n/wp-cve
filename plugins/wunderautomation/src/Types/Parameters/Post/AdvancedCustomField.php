<?php

namespace WunderAuto\Types\Parameters\Post;

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
        $this->group       = 'post';
        $this->title       = 'acf';
        $this->description = __('Post Advanced Custom Field', 'wunderauto');
        $this->objects     = ['post'];
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
