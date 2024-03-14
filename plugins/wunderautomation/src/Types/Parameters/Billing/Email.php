<?php

namespace WunderAuto\Types\Parameters\Billing;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Email
 */
class Email extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'billing';
        $this->title       = 'billing_email';
        $this->description = __('Billing Email from the WooCommerce order', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault = true;
    }

    /**
     * @param WC_Order|null $order
     * @param \stdClass     $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        if (is_null($order)) {
            return $this->formatField('', $modifiers);
        }
        return $this->formatField($order->get_billing_email(), $modifiers);
    }
}
