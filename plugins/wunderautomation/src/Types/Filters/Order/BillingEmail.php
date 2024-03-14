<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\User\Email as BaseEmail;

/**
 * Class BillingCity
 */
class BillingEmail extends BaseEmail
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order billing email', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on billing email.', 'wunderauto');
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

        $actualValue = $order->get_billing_email('api');

        return $this->evaluateCompare($actualValue);
    }
}
