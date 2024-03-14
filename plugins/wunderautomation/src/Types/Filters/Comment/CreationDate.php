<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class CreationDate
 */
class CreationDate extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Comment content', 'wunderauto');
        $this->description = __('Filter comments based on creation date.', 'wunderauto');
        $this->objects     = ['comment'];

        $this->operators = $this->dateOperators();
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

        $actualValue = $comment->comment_date;

        return $this->evaluateCompare($actualValue);
    }
}
