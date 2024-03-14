<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class OrderItemsTax
 */
class OrderItemsTax extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'items_tax';
        $this->description = __('Order items total tax', 'wunderauto');
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
        $tax = 0;
        foreach ($order->get_items() as $item) {
            $tax += (float)$order->get_item_tax($item);
        }
        return $this->formatField($tax, $modifiers);
    }
}
