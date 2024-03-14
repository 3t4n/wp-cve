<?php

namespace WunderAuto\Types\Parameters\Shipping;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Company
 */
class Company extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'shipping';
        $this->title       = 'shipping_company';
        $this->description = __('Shipping company name the WooCommerce order', 'wunderauto');
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
        return $this->formatField($order->get_shipping_company(), $modifiers);
    }
}
