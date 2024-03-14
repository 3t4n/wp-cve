<?php

namespace WunderAuto\Types\Filters\Customer;

/**
 * Class CompletedOrderTotal
 */
class CompletedOrderTotal extends BaseOrderCount
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Customer', 'wunderauto');
        $this->title       = __('Customer orders total (completed)', 'wunderauto');
        $this->description = __('Sum of order total of completed orders', 'wunderauto');
        $this->objects     = ['order'];
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

        $billingEmail = $order->get_billing_email('api');
        $actualValue  = $this->getOrderTotalSum($billingEmail, ['wc-completed']);

        return $this->evaluateCompare($actualValue);
    }
}
