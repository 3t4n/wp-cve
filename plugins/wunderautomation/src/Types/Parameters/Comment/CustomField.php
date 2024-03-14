<?php

namespace WunderAuto\Types\Parameters\Comment;

use WP_Comment;
use WunderAuto\Types\Parameters\BaseCustomField;

/**
 * Class CustomField
 */
class CustomField extends BaseCustomField
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'comment';
        $this->title       = 'customfield';
        $this->description = __('Comment custom field', 'wunderauto');
        $this->objects     = ['comment'];

        $this->customFieldNameCaption = __('Custom field', 'wunderauto');
        $this->customFieldNameDesc    = __('Custom field name (meta key)', 'wunderauto');
    }

    /**
     * @param WP_Comment $comment
     * @param \stdClass  $modifiers
     *
     * @return mixed
     */
    public function getValue($comment, $modifiers)
    {
        $value = get_metadata('comment', (int)$comment->comment_ID, $modifiers->field, true);
        return $this->formatCustomField($value, $comment, $modifiers);
    }
}
