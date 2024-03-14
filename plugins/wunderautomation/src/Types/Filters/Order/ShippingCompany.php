<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ShippingCompany
 */
class ShippingCompany extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order shipping company', 'wunderauto');
        $this->description = __(
            'Filter WooCommerce orders based on order shipping company. ' .
            'Separate multiple company names with comma. I.e Apple, Microsoft',
            'wunderauto'
        );
        $this->objects     = ['order'];

        $this->operators = $this->stringOperators();
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

        $actualValue = $order->get_shipping_company('api');

        return $this->evaluateCompare($actualValue);
    }
}
