<?php

namespace WunderAuto\Types\Filters\Comment;

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

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Advanced Custom field', 'wunderauto');
        $this->description = __('Filter object based on value of a comment ACF custom field.', 'wunderauto');
        $this->objects     = ['order'];
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $comment = $this->getObject();
        if (!($comment instanceof \WP_Comment)) {
            return false;
        }

        $id    = $comment->comment_ID;
        $field = $this->filterConfig->field;

        if ((int)$id < 1 || empty($field)) {
            return false;
        }

        $actualValue = get_field($this->filterConfig->field, $id);

        return $this->evaluateCompare($actualValue);
    }
}
