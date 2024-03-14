<?php

namespace WunderAuto\Types\Parameters\Comment;

use WP_Comment;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Type
 */
class Type extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'comment';
        $this->title       = 'type';
        $this->description = __('Comment type', 'wunderauto');
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
        $value = strlen($comment->comment_type) > 0 ?
            $comment->comment_type :
            'comment';

        return $this->formatField($value, $modifiers);
    }
}
