<?php

namespace WunderAuto\Types\Parameters\Post;

use WP_Post;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Content
 */
class Content extends BaseParameter
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
        $this->title       = 'content';
        $this->description = __('WordPress post content', 'wunderauto');
        $this->objects     = ['post'];

        $this->usesDefault = true;
    }

    /**
     * @param WP_Post   $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        return $this->formatField($post->post_content, $modifiers);
    }
}
