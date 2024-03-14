<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Type
 */
class Type extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Post type', 'wunderauto');
        $this->description = __('Filter posts based on post type.', 'wunderauto');
        $this->objects     = ['post'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $types = get_post_types([], 'objects');
        foreach ($types as $key => $type) {
            if (!($type instanceof \WP_Post_Type)) {
                continue;
            }
            $this->compareValues[] = [
                'value' => $key,
                'label' => $type->label . "({$type->name})",
            ];
        }
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

        $actualValue = $post->post_type;

        return $this->evaluateCompare($actualValue);
    }
}
