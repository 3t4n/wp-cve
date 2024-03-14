<?php

namespace WunderAuto\Types\Parameters\Billing;

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
        $this->group       = 'billing';
        $this->title       = 'billing_company';
        $this->description = __('Billing company name the WooCommerce order', 'wunderauto');
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
        return $this->formatField($order->get_billing_company(), $modifiers);
    }
}
