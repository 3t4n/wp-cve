<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Status
 */
class Status extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Post status', 'wunderauto');
        $this->description = __('Filter posts based on post status.', 'wunderauto');
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
        global $wp_post_statuses;

        foreach ((array)$wp_post_statuses as $key => $status) {
            $this->compareValues[] = [
                'value' => $key,
                'label' => $status->label . " ($key)",
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

        $actualValue = $post->post_status;

        return $this->evaluateCompare($actualValue);
    }
}
