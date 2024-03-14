<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;
use WunderAuto\Types\Filters\User\Email as BaseEmail;

/**
 * Class CustomerNote
 */
class CustomerNote extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Customer note', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based customer note.', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->stringOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $order = $this->getObject();
        if (!($order instanceof \WC_Order)) {
            return false;
        }

        $actualValue = $order->get_billing_email('api');

        return $this->evaluateCompare($actualValue);
    }
}
