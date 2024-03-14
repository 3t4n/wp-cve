<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Type
 */
class Type extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Comment type', 'wunderauto');
        $this->description = __('Filter comments based on comment type.', 'wunderauto');
        $this->objects     = ['comment'];

        $this->operators = $this->stringOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';
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

        $actualValue = strlen($comment->comment_type) > 0 ? $comment->comment_type : 'comment';

        return $this->evaluateCompare($actualValue);
    }
}
