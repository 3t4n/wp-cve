<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseCustomField;

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

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Custom field', 'wunderauto');
        $this->description = __('Filter object based on value of post custom field.', 'wunderauto');
        $this->objects     = ['post'];
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

        $actualValue = get_metadata('post', $post->ID, $this->filterConfig->field, true);

        return $this->evaluateCompare($actualValue);
    }
}
