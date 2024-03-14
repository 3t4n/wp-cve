<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class CreatedVia
 */
class CreatedVia extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order created via', 'wunderauto');
        $this->description = __('Filter orders based on order status.', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $checkoutMethods = ['admin', 'checkout', 'rest-api'];
        $checkoutMethods = apply_filters('wunderautomation_checkout_methods', $checkoutMethods);

        foreach ($checkoutMethods as $method) {
            $this->compareValues[] = [
                'value' => $method,
                'label' => $method,
            ];
        }
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

        $actualValue = $order->get_created_via();

        return $this->evaluateCompare($actualValue);
    }
}
