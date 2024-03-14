<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class CreationDate
 */
class CreationDate extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Post creation date', 'wunderauto');
        $this->description = __('Filter posts based on creation date.', 'wunderauto');
        $this->objects     = ['post'];

        $this->operators = $this->dateOperators();
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

        $actualValue = $post->post_date;

        return $this->evaluateCompare($actualValue);
    }
}
