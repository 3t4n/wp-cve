<?php

namespace WunderAuto\Types\Parameters\Billing;

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
        $this->group       = 'billing';
        $this->title       = 'billing_phone';
        $this->description = __('Billing Phone from the WooCommerce order', 'wunderauto');
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
        $number = $order->get_billing_phone();

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
