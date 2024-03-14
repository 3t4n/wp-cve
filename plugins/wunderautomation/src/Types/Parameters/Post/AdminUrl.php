<?php

namespace WunderAuto\Types\Parameters\Post;

use WP_Post;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class AdminUrl
 */
class AdminUrl extends BaseParameter
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
        $this->title       = 'adminurl';
        $this->description = __('The edit post link for post', 'wunderauto');
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
        $action         = '&action=edit';
        $postTypeObject = get_post_type_object($post->post_type);
        if (is_null($postTypeObject)) {
            return null;
        }

        $link = admin_url(sprintf($postTypeObject->_edit_link . $action, $post->ID));
        return $this->formatField($link, $modifiers);
    }
}
