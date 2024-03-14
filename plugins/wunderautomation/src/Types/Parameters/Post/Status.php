<?php

namespace WunderAuto\Types\Parameters\Post;

use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Status
 */
class Status extends BaseParameter
{
    /**
     * @var string
     */
    protected $objectId = 'post';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'post';
        $this->title       = 'status';
        $this->description = __('WordPress post status', 'wunderauto');
        $this->objects     = ['post'];

        $this->usesDefault  = true;
        $this->usesReturnAs = true;
    }

    /**
     * @param \WP_Post  $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        global $wp_post_statuses;

        $value = $post->post_status;

        if (isset($modifiers->return) && trim($modifiers->return) === 'label') {
            if (isset($wp_post_statuses[$value])) {
                $value = $wp_post_statuses[$value]->label;
            }
        }

        return $this->formatField($value, $modifiers);
    }
}
