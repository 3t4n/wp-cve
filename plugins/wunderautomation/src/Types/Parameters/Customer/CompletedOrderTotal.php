<?php

namespace WunderAuto\Types\Parameters\Customer;

use WC_Order;
use WunderAuto\Types\Filters\Customer\BaseOrderCount;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class CompletedOrderTotal
 */
class CompletedOrderTotal extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'customer';
        $this->title       = 'customer_completed_order_total';
        $this->description = __('Completed order total of the customer ', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault = true;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        $baseOrderCount = new BaseOrderCount();
        $billingEmail   = $order->get_billing_email('api');

        return $baseOrderCount->getOrderTotalSum($billingEmail, ['wc-completed']);
    }
}
