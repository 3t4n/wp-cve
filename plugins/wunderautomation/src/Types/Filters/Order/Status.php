<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Status
 */
class Status extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order status', 'wunderauto');
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
        $statuses = wc_get_order_statuses();
        foreach ($statuses as $key => $status) {
            $this->compareValues[] = [
                'value' => $key,
                'label' => $status . " ($key)",
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

        $orderId = $order->get_id();
        // Need to pick up the order status by ID because
        // the $order->get_status() function strips the wc_prefix
        $actualValue = get_post_status($orderId);
        $actualValue = $actualValue === false ? '' : $actualValue;

        return $this->evaluateCompare($actualValue);
    }
}
