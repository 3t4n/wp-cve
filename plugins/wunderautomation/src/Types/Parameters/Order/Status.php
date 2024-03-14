<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Status
 */
class Status extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'status';
        $this->description = __('Order status without wc- internal prefix', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault  = true;
        $this->usesReturnAs = true;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        global $wp_post_statuses;

        $value = $order->get_status();
        if (isset($modifiers->return) && trim($modifiers->return) === 'label') {
            if (isset($wp_post_statuses['wc-' . $value])) {
                $value = $wp_post_statuses['wc-' . $value]->label;
            }
        }

        return $this->formatField($value, $modifiers);
    }
}
