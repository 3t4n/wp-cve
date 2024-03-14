<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Fees
 */
class Fees extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'fees';
        $this->description = __('All fees on order (paid by customer)', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault = true;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return string|float|int
     */
    public function getValue($order, $modifiers)
    {
        $fees    = $order->get_fees();
        $sumFees = 0;
        foreach ($fees as $fee) {
            $sumFees += (float)$fee->get_total();
        }

        return $this->formatField((float)$sumFees, $modifiers);
    }
}
