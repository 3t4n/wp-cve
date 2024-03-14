<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Total
 */
class Total extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order total amount', 'wunderauto');
        $this->description = __('Filters based on order total amount (excluding any VAT)', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->numberOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'number';
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

        $actualValue = $order->get_total();

        return $this->evaluateCompare($actualValue);
    }
}
