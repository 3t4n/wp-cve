<?php

namespace WunderAuto\Types\Filters\Post;

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
        $this->description = __('Filter object based on value of an post ACF custom field.', 'wunderauto');
        $this->objects     = ['post'];
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $post = $this->getObject();
        if (!($post instanceof \WP_Post)) {
            return false;
        }

        $id    = $post->ID;
        $field = $this->filterConfig->field;

        if ((int)$id < 1 || empty($field)) {
            return false;
        }

        $actualValue = get_field($this->filterConfig->field, $id);

        return $this->evaluateCompare($actualValue);
    }
}
