<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class IsWooCommerceSystemNote
 */
class IsWooCommerceSystemNote extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Is WooCommerce system note', 'wunderauto');
        $this->description = __(
            'Filters on the comment being a WooCommerce system added order note',
            'wunderauto'
        );
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

        $actualValue                 = $comment->comment_author === 'WooCommerce' ? 'yes' : 'no';
        $this->filterConfig->compare = 'eq';

        return $this->evaluateCompare($actualValue);
    }
}
