<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Id
 */
class Id extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'id';
        $this->description = __('Order internal id', 'wunderauto');
        $this->objects     = ['order'];

        $this->dataType = 'int';

        $this->usesDefault = false;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        return (int)$order->get_id();
    }
}
