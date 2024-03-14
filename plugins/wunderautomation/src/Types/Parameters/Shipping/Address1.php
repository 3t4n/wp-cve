<?php

namespace WunderAuto\Types\Parameters\Shipping;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Address1
 */
class Address1 extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'shipping';
        $this->title       = 'shipping_address_1';
        $this->description = __('Shipping address line 1 from the WooCommerce order', 'wunderauto');
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
        return $this->formatField($order->get_shipping_address_1(), $modifiers);
    }
}
