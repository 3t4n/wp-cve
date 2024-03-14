<?php

namespace WunderAuto\Types\Parameters\Shipping;

use WC_Countries;
use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Phone
 */
class Phone extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'shipping';
        $this->title       = 'shipping_phone';
        $this->description = __('Shipping Phone from the WooCommerce order', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault     = true;
        $this->usesPhoneFormat = true;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        $number = $order->get_shipping_phone();

        if (isset($modifiers->format) && trim($modifiers->format) === 'e.164') {
            // 1. Determine country
            $isoCountry = $order->get_billing_country();
            if (empty($isoCountry)) {
                $objCountries = new WC_Countries();
                $isoCountry   = $objCountries->get_base_country();
            }
            if (empty($isoCountry)) {
                $isoCountry = 'US';
            }

            // 2. Format as E.164
            $phoneFormat = new \WunderAuto\Format\Phone();
            $number      = $phoneFormat->formatE164($number, $isoCountry);
        }

        return $this->formatField($number, $modifiers);
    }
}
