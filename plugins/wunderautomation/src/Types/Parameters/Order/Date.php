<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Date
 */
class Date extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'date';
        $this->description = __('Order date of the WooCommerce order', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault    = false;
        $this->usesDateFormat = true;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        $dateTime = $order->get_date_created();
        if (is_null($dateTime)) {
            return null;
        }

        $date = $this->formatDate($dateTime, $modifiers);
        return $this->formatField($date, $modifiers);
    }
}
