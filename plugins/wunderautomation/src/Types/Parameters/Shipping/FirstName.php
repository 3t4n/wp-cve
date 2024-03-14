<?php

namespace WunderAuto\Types\Parameters\Shipping;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class FirstName
 */
class FirstName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'shipping';
        $this->title       = 'shipping_first_name';
        $this->description = __('Shipping first name from the WooCommerce order', 'wunderauto');
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
        return $this->formatField($order->get_shipping_first_name(), $modifiers);
    }
}
