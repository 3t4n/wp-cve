<?php

namespace WunderAuto\Types\Filters\Comment;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class IsOrderStatusNote
 */
class IsOrderStatusNote extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Comment', 'wunderauto');
        $this->title       = __('Is order status note', 'wunderauto');
        $this->description = __(
            'Filters on the comment being a WooCommerce order status notice note or not',
            'wunderauto'
        );
        $this->objects     = ['comment'];

        $this->inputType     = 'select';
        $this->operators     = [];
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

        $content                     = $comment->comment_content;
        $this->filterConfig->compare = 'eq';

        $statuses = wc_get_order_statuses();
        foreach ($statuses as $key => $status) {
            $content = str_replace($status, '*', $content);
        }
        $pattern     = sprintf(__('Order status changed from %1$s to %2$s.', 'woocommerce'), '*', '*');
        $actualValue = $content === $pattern ? 'yes' : 'no';

        return $this->evaluateCompare($actualValue);
    }
}
