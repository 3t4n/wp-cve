<?php

namespace WunderAuto\Types\Filters\Customer;

use WC_Order;

/**
 * Class IsLastOrder
 */
class IsLastOrder extends BaseOrderCount
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Customer', 'wunderauto');
        $this->title       = __('Is customers last order', 'wunderauto');
        $this->description = __('Filters on the order being the latest order for this customer', 'wunderauto');
        $this->objects     = ['order'];

        $this->inputType = 'select';
        $this->operators = [];

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
        $order = $this->getObject();
        if (!$order instanceof WC_Order) {
            return false;
        }

        $orderCompleted = $order->get_date_completed();
        if (is_null($orderCompleted)) {
            return false;
        }

        $actualValue  = 'yes';
        $billingEmail = $order->get_billing_email();
        $allOrders    = wc_get_orders(['billing_email' => $billingEmail]);
        if (is_array($allOrders)) {
            foreach ($allOrders as $otherOrder) {
                $otherOrderCompleted = $otherOrder->get_date_completed();
                if (is_null($otherOrderCompleted)) {
                    continue;
                }

                if ($otherOrderCompleted->getTimestamp() > $orderCompleted->getTimestamp()) {
                    $actualValue = 'no';
                    break;
                }
            }
        }

        $this->filterConfig->compare = 'eq';

        return $this->evaluateCompare($actualValue);
    }
}
