<?php

namespace WunderAuto\Types\Filters\Order;

use WC_Countries;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class BillingState
 */
class BillingState extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order billing state', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on order billing state', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
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

        $actualValue = $order->get_billing_state('api');

        return $this->evaluateCompare($actualValue);
    }
}
