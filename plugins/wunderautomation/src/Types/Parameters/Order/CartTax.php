<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class CartTax
 */
class CartTax extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'cart';
        $this->title       = 'cart_tax';
        $this->description = __('Order cart tax', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault = false;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        return $this->formatField($order->get_cart_tax('api'), $modifiers);
    }
}
