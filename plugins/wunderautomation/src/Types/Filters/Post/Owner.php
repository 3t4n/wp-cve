<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Owner
 */
class Owner extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Post owner / author', 'wunderauto');
        $this->description = __('Filter posts based on owner / author.', 'wunderauto');
        $this->objects     = ['post'];

        $this->operators   = $this->setOperators();
        $this->inputType   = 'ajaxmultiselect';
        $this->ajaxAction  = 'wa_search_users';
        $this->nonceName   = 'search_users_nonce';
        $this->placeholder = 'Search WordPress user';
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

        $actualValue = $post->post_author;

        return $this->evaluateCompare($actualValue);
    }
}
