<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

use function WC;

/**
 * Class PaymentMethod
 */
class PaymentMethod extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'paymentmethod';
        $this->description = __('Order internal id', 'wunderauto');
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
        $value = $order->get_payment_method('api');
        if (isset($modifiers->return) && trim($modifiers->return) === 'label') {
            $gateways = WC()->payment_gateways();
            foreach ($gateways->payment_gateways as $gateway) {
                if ($gateway->id == $value) {
                    $value = $gateway->title;
                }
            }
        }
        return $this->formatField($value, $modifiers);
    }
}
