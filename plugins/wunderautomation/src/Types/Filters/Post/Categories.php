<?php

namespace WunderAuto\Types\Filters\Post;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Categories
 */
class Categories extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Post', 'wunderauto');
        $this->title       = __('Post categories', 'wunderauto');
        $this->description = __('Filters based on post categories', 'wunderauto');
        $this->objects     = ['post'];

        $this->operators   = $this->multiSetOperators();
        $this->inputType   = 'ajaxmultiselect';
        $this->ajaxAction  = 'wa_search_categories';
        $this->nonceName   = 'search_tax_nonce';
        $this->placeholder = 'Search post categories';
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

        $terms = get_the_terms($post->ID, 'category');
        $terms = ($terms instanceof \WP_Error) || $terms === false ? [] : $terms;

        $actualValue = [];
        foreach ($terms as $term) {
            $actualValue[] = $term->term_id;
        }

        return $this->evaluateCompare($actualValue);
    }
}
