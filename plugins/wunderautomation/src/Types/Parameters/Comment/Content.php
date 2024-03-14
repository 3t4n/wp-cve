<?php

namespace WunderAuto\Types\Parameters\Comment;

use WP_Comment;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Content
 */
class Content extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'comment';
        $this->title       = 'content';
        $this->description = __('Comment content', 'wunderauto');
        $this->objects     = ['comment'];

        $this->usesDefault = true;
    }

    /**
     * @param WP_Comment $comment
     * @param \stdClass  $modifiers
     *
     * @return mixed
     */
    public function getValue($comment, $modifiers)
    {
        return $this->formatField($comment->comment_content, $modifiers);
    }
}
