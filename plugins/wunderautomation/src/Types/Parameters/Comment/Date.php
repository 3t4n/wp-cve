<?php

namespace WunderAuto\Types\Parameters\Comment;

use WP_Comment;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Date
 */
class Date extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'comment';
        $this->title       = 'date';
        $this->description = __('Comment date', 'wunderauto');
        $this->objects     = ['comment'];

        $this->usesDefault    = true;
        $this->usesDateFormat = true;
    }

    /**
     * @param WP_Comment $comment
     * @param \stdClass  $modifiers
     *
     * @return mixed
     */
    public function getValue($comment, $modifiers)
    {
        $date = strtotime($comment->comment_date);
        $date = $this->formatDate($date, $modifiers);
        return $this->formatField($date, $modifiers);
    }
}
