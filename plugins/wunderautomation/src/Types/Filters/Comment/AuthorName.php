<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class AuthorName
 */
class AuthorName extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Comment author name', 'wunderauto');
        $this->description = __('Filter comments based on comment author name.', 'wunderauto');
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

        $actualValue = $comment->comment_author;

        return $this->evaluateCompare($actualValue);
    }
}
