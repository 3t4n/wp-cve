<?php

namespace WunderAuto\Types\Parameters\Shipping;

use WC_Countries;
use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class State
 */
class State extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'shipping';
        $this->title       = 'shipping_state';
        $this->description = __('Shipping state from the WooCommerce order', 'wunderauto');
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
        $country = $order->get_shipping_country();
        $value   = $order->get_shipping_state();
        if (isset($modifiers->return) && trim($modifiers->return) === 'label') {
            $objCountries = new WC_Countries();
            $groups       = $objCountries->__get('states');
            if (count($groups[$country]) > 0) {
                if (isset($groups[$country][$value])) {
                    $value = $groups[$country][$value];
                }
            }
        }
        return $this->formatField($value, $modifiers);
    }
}
