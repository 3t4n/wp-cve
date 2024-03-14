<?php

namespace WunderAuto\Types\Filters\Order;

use WC_Countries;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ShippingState
 */
class ShippingState extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order shipping state', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on order shipping state', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize dropdown etc.
     *
     * @return void
     */
    public function initialize()
    {
        $objCountries = new WC_Countries();
        $groups       = $objCountries->__get('states');
        foreach ($groups as $states) {
            foreach ($states as $key => $state) {
                $this->compareValues[] = ['value' => $key, 'label' => $state];
            }
        }
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $order = $this->getObject();
        if (!($order instanceof \WC_Order)) {
            return false;
        }

        $actualValue = $order->get_shipping_state('api');

        return $this->evaluateCompare($actualValue);
    }
}
