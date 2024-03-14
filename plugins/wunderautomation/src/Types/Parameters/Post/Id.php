<?php

namespace WunderAuto\Types\Parameters\Post;

use WP_Post;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Id
 */
class Id extends BaseParameter
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
        $this->title       = 'id';
        $this->description = __('WordPress post id', 'wunderauto');
        $this->objects     = ['post'];

        $this->usesDefault = false;
    }

    /**
     * @param WP_Post   $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        return $post->ID;
    }
}
