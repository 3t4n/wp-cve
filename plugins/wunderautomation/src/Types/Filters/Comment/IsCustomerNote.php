<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class IsCustomerNote
 */
class IsCustomerNote extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Is customer note', 'wunderauto');
        $this->description = __('Filters on the comment being a WooCommerce customer note or not', 'wunderauto');
        $this->objects     = ['comment'];

        $this->inputType = 'select';
        $this->operators = [];

        $this->compareValues = [
            ['value' => 'yes', 'label' => __('Yes', 'wunderauto')],
            ['value' => 'no', 'label' => __('No', 'wunderauto')],
        ];
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $comment = $this->getObject();
        if (!($comment instanceof \WP_Comment)) {
            return false;
        }

        // Since this filter is defined without operators. We
        // need to set one here to be able to reuse evaluateCompare
        $this->filterConfig->compare = 'eq';

        // If this workflow is direct (!delayed), the comment meta
        // is_customer_note isn't written to the DB yet. Our comment
        // Submitted trigger has special Woo handling for this and will
        // set the property isCustomerNote to true if needed.
        if (isset($comment->isCustomerNote)) {
            $actualValue = $comment->isCustomerNote ? 'yes' : 'no';
        } else {
            $metaData    = get_metadata('comment', (int)$comment->comment_ID, 'is_customer_note');
            $actualValue = $metaData === 1 ? 'yes' : 'no';
        }

        return $this->evaluateCompare($actualValue);
    }
}
