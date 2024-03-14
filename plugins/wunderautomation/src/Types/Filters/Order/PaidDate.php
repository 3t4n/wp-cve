<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class PaidDate
 */
class PaidDate extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order paid date', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on paid date.', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->dateOperators();
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

        $dateTime = $order->get_date_paid();
        if (is_null($dateTime)) {
            return false;
        }

        return $this->evaluateCompare($dateTime->getTimestamp());
    }
}
