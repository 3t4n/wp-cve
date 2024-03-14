<?php

namespace WunderAuto\Types\Parameters\Billing;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class LastName
 */
class LastName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'billing';
        $this->title       = 'billing_last_name';
        $this->description = __('Billing last name from the WooCommerce order', 'wunderauto');
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
        return $this->formatField($order->get_billing_last_name(), $modifiers);
    }
}
