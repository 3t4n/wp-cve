<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class AuthorEmail
 */
class AuthorEmail extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Comment author email', 'wunderauto');
        $this->description = __('Filter comments based on comment author email.', 'wunderauto');
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

        $actualValue = $comment->comment_author_email;

        return $this->evaluateCompare($actualValue);
    }
}
