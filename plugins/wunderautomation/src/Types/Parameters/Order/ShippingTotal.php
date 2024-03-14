<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class ShippingTotal
 */
class ShippingTotal extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'shipping_total';
        $this->description = __('Order shipping total', 'wunderauto');
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
        return $this->formatField((float)$order->get_shipping_total('api'), $modifiers);
    }
}
