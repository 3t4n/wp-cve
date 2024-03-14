<?php

namespace WunderAuto\Types\Parameters\Post;

use WP_Post;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Type
 */
class Type extends BaseParameter
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
        $this->title       = 'type';
        $this->description = __('WordPress post type', 'wunderauto');
        $this->objects     = ['post'];

        $this->usesDefault  = true;
        $this->usesReturnAs = true;
    }

    /**
     * @param WP_Post   $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        $value = $post->post_type;

        if (isset($modifiers->return) && trim($modifiers->return) === 'label') {
            /** @var array<string, \WP_Post_Type> $types */
            $types = get_post_types([], 'objects');
            if (isset($types[$value])) {
                $value = $types[$value]->label;
            }
        }

        return $this->formatField($value, $modifiers);
    }
}
