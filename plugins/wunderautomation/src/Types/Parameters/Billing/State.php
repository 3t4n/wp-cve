<?php

namespace WunderAuto\Types\Parameters\Billing;

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
        $this->group       = 'billing';
        $this->title       = 'billing_state';
        $this->description = __('Billing state from the WooCommerce order', 'wunderauto');
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
        $country = $order->get_billing_country();
        $value   = $order->get_billing_state();
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
