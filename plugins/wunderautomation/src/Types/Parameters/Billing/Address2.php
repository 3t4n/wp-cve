<?php

namespace WunderAuto\Types\Parameters\Billing;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Address2
 */
class Address2 extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'billing';
        $this->title       = 'billing_address_2';
        $this->description = __('Billing address line 2 from the WooCommerce order', 'wunderauto');
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
        return $this->formatField($order->get_billing_address_2(), $modifiers);
    }
}
