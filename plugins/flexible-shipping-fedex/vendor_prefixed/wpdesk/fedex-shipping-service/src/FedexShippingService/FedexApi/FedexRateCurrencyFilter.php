<?php

namespace FedExVendor\WPDesk\FedexShippingService\FedexApi;

use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use FedExVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
use FedExVendor\WPDesk\FedexShippingService\Exception\NoRatesInCurrencyInRatingsException;
/**
 * Can filter rates by currency.
 *
 * @package WPDesk\FedexShippingService\FedexApi
 */
class FedexRateCurrencyFilter implements \FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating
{
    /** @var ShipmentRating */
    private $rating;
    /** Shipping method helper.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * .
     *
     * @param ShipmentRating $rating .
     * @param ShopSettings $shop_settings .
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $rating, \FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings $shop_settings)
    {
        $this->rating = $rating;
        $this->shop_settings = $shop_settings;
    }
    /**
     * Get filtered ratings.
     *
     * @return SingleRate[]
     */
    public function get_ratings()
    {
        $rates = [];
        $ratings = $this->rating->get_ratings();
        foreach ($ratings as $key => $rate) {
            if ($rate->total_charge->currency === $this->shop_settings->get_default_currency()) {
                $rates[$key] = $rate;
            }
        }
        if (0 !== \count($ratings) && 0 === \count($rates)) {
            throw new \FedExVendor\WPDesk\FedexShippingService\Exception\NoRatesInCurrencyInRatingsException($this->shop_settings);
        }
        return $rates;
    }
}
