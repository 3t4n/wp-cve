<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class CartTotal
 */
class CartTotal extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'cart';
        $this->title       = 'cart_total';
        $this->description = __('Cart total', 'wunderauto');
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
        $feesParameter = new Fees();

        $fees = $feesParameter->getValue($order, (object)[]);
        $fees = !is_numeric(($fees)) ? 0 : $fees;
        return $this->formatField($order->get_subtotal() + $fees, $modifiers);
    }
}
