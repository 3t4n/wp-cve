<?php

namespace WunderAuto\Types\Parameters\Post;

use WP_Post;
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
        $this->group       = 'post';
        $this->title       = 'customfield';
        $this->description = __('Post custom field', 'wunderauto');
        $this->objects     = ['post'];

        $this->customFieldNameCaption = __('Custom field', 'wunderauto');
        $this->customFieldNameDesc    = __('Custom field name (meta key)', 'wunderauto');
    }

    /**
     * @param WP_Post   $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        $value = get_metadata('post', $post->ID, $modifiers->field, true);
        return $this->formatCustomField($value, $post, $modifiers);
    }
}
