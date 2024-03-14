<?php

namespace WunderAuto\Types\Filters\Comment;

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

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Custom field', 'wunderauto');
        $this->description = __('Filter object based on value of comment custom field.', 'wunderauto');
        $this->objects     = ['comment'];
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

        $actualValue = get_metadata('comment', (int)$comment->comment_ID, $this->filterConfig->field, true);

        return $this->evaluateCompare($actualValue);
    }
}
