<?php

namespace WunderAuto\Types\Filters\Order;

use WC_Countries;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ShippingCountry
 */
class ShippingCountry extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order shipping country', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on order shipping country', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize drop downs etc.
     *
     * @return void
     */
    public function initialize()
    {
        $objCountries = new WC_Countries();
        $countries    = $objCountries->__get('countries');
        foreach ($countries as $key => $country) {
            $this->compareValues[] = ['value' => $key, 'label' => $country];
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

        $actualValue = $order->get_shipping_country('api');

        return $this->evaluateCompare($actualValue);
    }
}
