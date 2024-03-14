<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Tags
 */
class Tags extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Post tags', 'wunderauto');
        $this->description = __('Filters based on post tags', 'wunderauto');
        $this->objects     = ['post'];

        $this->operators   = $this->multiSetOperators();
        $this->inputType   = 'ajaxmultiselect';
        $this->ajaxAction  = 'wa_search_tags';
        $this->nonceName   = 'search_tax_nonce';
        $this->placeholder = 'Search post tags';
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

        $terms = get_the_terms($post->ID, 'post_tag');
        $terms = ($terms instanceof \WP_Error) || $terms === false ? [] : $terms;

        $actualValue = [];
        foreach ($terms as $term) {
            $actualValue[] = $term->term_id;
        }

        return $this->evaluateCompare($actualValue);
    }
}
