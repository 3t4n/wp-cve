<?php

namespace WunderAuto\Types\Parameters\Post;

use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Slug
 */
class Slug extends BaseParameter
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
        $this->title       = 'slug';
        $this->description = __('WordPress post slug', 'wunderauto');
        $this->objects     = ['post'];

        $this->usesDefault = true;
    }

    /**
     * @param \WP_Post  $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        return $this->formatField($post->post_name, $modifiers);
    }
}
