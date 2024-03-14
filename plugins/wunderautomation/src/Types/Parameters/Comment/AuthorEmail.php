<?php

namespace WunderAuto\Types\Parameters\Comment;

use WP_Comment;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class AuthorEmail
 */
class AuthorEmail extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'comment';
        $this->title       = 'authoremail';
        $this->description = __('Comment author email', 'wunderauto');
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
        return $this->formatField($comment->comment_author_email, $modifiers);
    }
}
