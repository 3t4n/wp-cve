<?php

namespace WunderAuto\Types\Parameters\Comment;

use WP_Comment;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class AuthorName
 */
class AuthorName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'comment';
        $this->title       = 'authorname';
        $this->description = __('Comment author name', 'wunderauto');
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
        return $this->formatField($comment->comment_author, $modifiers);
    }
}
