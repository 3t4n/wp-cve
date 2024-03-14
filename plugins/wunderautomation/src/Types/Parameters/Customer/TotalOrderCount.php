<?php

namespace WunderAuto\Types\Parameters\Customer;

use WC_Order;
use WunderAuto\Types\Filters\Customer\BaseOrderCount;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class TotalOrderCount
 */
class TotalOrderCount extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'customer';
        $this->title       = 'customer_order_count';
        $this->description = __('Total order count of the customer ', 'wunderauto');
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

        return $baseOrderCount->getOrderCount($billingEmail);
    }
}
