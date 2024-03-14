<?php

namespace WunderAuto\Types\Parameters\Comment;

use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Status
 */
class Status extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'comment';
        $this->title       = 'status';
        $this->description = __('Comment status', 'wunderauto');
        $this->objects     = ['comment'];

        $this->usesDefault = true;
    }

    /**
     * @param \WP_Comment $comment
     * @param \stdClass   $modifiers
     *
     * @return mixed
     */
    public function getValue($comment, $modifiers)
    {
        $value = '';
        switch ($comment->comment_approved) {
            case '0':
                $value = __('Unapproved');
                break;
            case '1':
                $value = _x('Approved', 'comment status');
                break;
            case 'spam':
                $value = _x('Spam', 'comment status');
                break;
            case 'trash':
                $value = _x('Trash', 'comment status');
                break;
        }

        return $this->formatField($value, $modifiers);
    }
}
