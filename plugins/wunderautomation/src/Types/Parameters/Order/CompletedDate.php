<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class CompletedDate
 */
class CompletedDate extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'completeddate';
        $this->description = __('Completion date of the WooCommerce order', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault    = true;
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
        $dateTime = $order->get_date_completed();
        if (is_null($dateTime)) {
            return null;
        }

        $date = $this->formatDate($dateTime, $modifiers);
        return $this->formatField($date, $modifiers);
    }
}
