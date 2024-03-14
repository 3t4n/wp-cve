<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Content
 */
class Content extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Post content', 'wunderauto');
        $this->description = __('Filter post based on content ', 'wunderauto');
        $this->objects     = ['post'];

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
        $post = $this->getObject();
        if (!($post instanceof \WP_Post)) {
            return false;
        }

        $actualValue = $post->post_content;

        return $this->evaluateCompare($actualValue);
    }
}
