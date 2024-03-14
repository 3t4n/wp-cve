<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class FeesTax
 */
class FeesTax extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'fees_tax';
        $this->description = __('Tax for all fees on order (paid by customer)', 'wunderauto');
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
        $fees    = $order->get_fees();
        $sumFees = 0;
        foreach ($fees as $fee) {
            $sumFees += $fee->get_total_tax();
        }
        return $this->formatField((float)$sumFees, $modifiers);
    }
}
