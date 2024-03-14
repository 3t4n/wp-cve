<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ShippingCity
 */
class ShippingCity extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order shipping city', 'wunderauto');
        $this->description = __(
            'Filter WooCommerce orders based on order shipping city. ' .
            'Separate multiple city names with comma. I.e New York, Sacramento',
            'wunderauto'
        );
        $this->objects     = ['order'];

        $this->operators = $this->setOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';
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

        $actualValue = $order->get_shipping_city('api');
        return $this->evaluateCompare($actualValue);
    }
}
