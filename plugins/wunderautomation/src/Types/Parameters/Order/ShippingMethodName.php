<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class ShippingMethodName
 */
class ShippingMethodName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'shippingmethod';
        $this->description = __('Shipping method name', 'wunderauto');
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
        return $this->formatField($order->get_shipping_method(), $modifiers);
    }
}
