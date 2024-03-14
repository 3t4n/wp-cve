<?php

namespace WunderAuto\Types\Filters\Order;

use WC_Shipping;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ShippingMethod
 */
class ShippingMethod extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order shipping method', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on shipping method', 'wunderauto');
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
        $shipping = WC_Shipping::instance();
        $methods  = $shipping->get_shipping_methods();
        foreach ($methods as $key => $method) {
            $title                 = strlen($method->get_title()) > 0 ?
                $method->get_title() :
                $method->get_method_title();
            $this->compareValues[] = ['value' => $key, 'label' => $title];
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

        $methods = $order->get_shipping_methods();
        $method  = reset($methods);
        if (!($method instanceof \WC_Order_Item_Shipping )) {
            return false;
        }
        $actualValue = $method->get_method_id();

        return $this->evaluateCompare($actualValue);
    }
}
