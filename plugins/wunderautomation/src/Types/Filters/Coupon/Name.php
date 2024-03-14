<?php

namespace WunderAuto\Types\Filters\Coupon;

use WC_Order;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Name
 */
class Name extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Coupon', 'wunderauto');
        $this->title       = __('Coupon name', 'wunderauto');
        $this->description = __('Filter orders based on coupon name (code) used.', 'wunderauto');
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
        if (!$order instanceof WC_Order) {
            return false;
        }

        foreach ($order->get_coupon_codes() as $code) {
            if ($this->evaluateCompare($code)) {
                return true;
            }
        }

        return false;
    }
}
