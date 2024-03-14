<?php

namespace WunderAuto\Types\Parameters\Shipping;

use WC_Countries;
use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Country
 */
class Country extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'shipping';
        $this->title       = 'shipping_country';
        $this->description = __('Shipping country from the WooCommerce order', 'wunderauto');
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
        $value = $order->get_shipping_country();
        if (isset($modifiers->return) && trim($modifiers->return) === 'label') {
            $objCountries = new WC_Countries();
            $countries    = $objCountries->__get('countries');
            $value        = isset($countries[$value]) ? $countries[$value] : $value;
        }
        return $this->formatField($value, $modifiers);
    }
}
