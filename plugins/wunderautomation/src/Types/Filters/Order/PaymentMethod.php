<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

use function WC;

/**
 * Class PaymentMethod
 */
class PaymentMethod extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order payment method', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on payment method', 'wunderauto');
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
        $gateways = WC()->payment_gateways()->get_available_payment_gateways();
        foreach ($gateways as $key => $gateway) {
            $this->compareValues[] = [
                'value' => $key,
                'label' => $gateway->title,
            ];
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

        $actualValue = $order->get_payment_method('api');

        return $this->evaluateCompare($actualValue);
    }
}
