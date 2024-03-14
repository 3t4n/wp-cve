<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi;

use DhlVendor\DHL\Entity\AM\GetQuoteResponse;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Rate;
use DhlVendor\WPDesk\AbstractShipping\Rate\Money;
use DhlVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use DhlVendor\WPDesk\AbstractShipping\Rate\SingleRate;
/**
 * Get response from API
 *
 * @package WPDesk\DhlExpressShippingService\DhlApi
 */
class RestApiDhlRateReplyInterpretation implements \DhlVendor\WPDesk\AbstractShipping\Rate\ShipmentRating
{
    /**
     * Is tax enabled.
     *
     * @var bool
     */
    private $is_tax_enabled;
    /**
     * Reply.
     *
     * @var GetQuoteResponse
     */
    private $reply;
    /**
     * @var string
     */
    private $shop_default_currency;
    /**
     * DhlRateReplyInterpretation constructor.
     *
     * @param array $reply Rate reply.
     * @param bool $is_tax_enabled Is tax enabled.
     * @param string $shop_default_currency Shop default currency.
     */
    public function __construct($reply, $is_tax_enabled, $shop_default_currency)
    {
        $this->reply = $reply;
        $this->is_tax_enabled = $is_tax_enabled;
        $this->shop_default_currency = $shop_default_currency;
    }
    /**
     * Get single rate.
     *
     * @param Rate $single_quote .
     *
     * @return SingleRate
     */
    protected function get_single_rate($single_quote)
    {
        $rate = new \DhlVendor\WPDesk\AbstractShipping\Rate\SingleRate();
        $rate->service_type = $single_quote->getProductCode();
        $rate->service_name = $single_quote->getProductName();
        $money = new \DhlVendor\WPDesk\AbstractShipping\Rate\Money();
        if ($this->is_tax_enabled) {
            $money->amount = $single_quote->getTotalPrice() - $single_quote->getTotalTax();
        } else {
            $money->amount = (float) $single_quote->getTotalPrice();
        }
        $money->currency = (string) $single_quote->getCurrency();
        $rate->total_charge = $money;
        return $rate;
    }
    /**
     * Get response from Dhl.
     *
     * @return SingleRate[]
     */
    public function get_ratings()
    {
        $rates = [];
        /** @var Rate $single_quote */
        foreach ($this->reply as $single_quote) {
            if (0.0 !== \round((float) $single_quote->getTotalPrice(), 2) && !empty((string) $single_quote->getCurrency()) && $this->shop_default_currency === (string) $single_quote->getCurrency()) {
                $rates[] = $this->get_single_rate($single_quote);
            }
        }
        return $rates;
    }
}
