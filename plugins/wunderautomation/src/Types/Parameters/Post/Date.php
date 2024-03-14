<?php

namespace WunderAuto\Types\Parameters\Post;

use WP_Post;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Date
 */
class Date extends BaseParameter
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
        $this->title       = 'date';
        $this->description = __('WordPress post publish date', 'wunderauto');
        $this->objects     = ['post'];

        $this->usesDefault    = true;
        $this->usesDateFormat = true;
    }

    /**
     * @param WP_Post   $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        $date = strtotime($post->post_date_gmt);
        $date = $this->formatDate($date, $modifiers);
        return $this->formatField($date, $modifiers);
    }
}
