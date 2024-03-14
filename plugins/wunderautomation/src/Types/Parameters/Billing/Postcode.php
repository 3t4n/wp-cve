<?php

namespace WunderAuto\Types\Parameters\Billing;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Postcode
 */
class Postcode extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'billing';
        $this->title       = 'billing_postcode';
        $this->description = __('Billing postcode from the WooCommerce order', 'wunderauto');
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
        return $this->formatField($order->get_billing_postcode(), $modifiers);
    }
}
