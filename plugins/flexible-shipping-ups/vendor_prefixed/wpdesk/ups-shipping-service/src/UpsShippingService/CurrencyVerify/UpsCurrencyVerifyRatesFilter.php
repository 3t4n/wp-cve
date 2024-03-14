<?php

/**
 * Rates filter for currency.
 *
 * @package WPDesk\UpsShippingService\CurrencyVerify
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService\CurrencyVerify;

use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * Can filter rates to maximum transit time settings.
 */
class UpsCurrencyVerifyRatesFilter implements \UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating
{
    /**
     * Rates to filter.
     *
     * @var ShipmentRating
     */
    private $shipment_rating;
    /** Shipping method helper.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /** Logger.
     *
     * @var LoggerInterface
     */
    private $logger;
    /**
     * UpsCurrencyVerifyRatesFilter constructor.
     *
     * @param ShipmentRating  $shipment_rating Rates .
     * @param ShopSettings    $shop_settings .
     * @param LoggerInterface $logger Logger.
     */
    public function __construct(\UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $shipment_rating, \UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings $shop_settings, \UpsFreeVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->shipment_rating = $shipment_rating;
        $this->shop_settings = $shop_settings;
        $this->logger = $logger;
    }
    /**
     * Is valid rate currency?
     *
     * @param SingleRate $rate .
     *
     * @return bool
     */
    private function is_valid_rate_currency(\UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate $rate)
    {
        if (!isset($rate->total_charge, $rate->total_charge->currency) || $this->shop_settings->get_default_currency() !== $rate->total_charge->currency) {
            return \false;
        }
        return \true;
    }
    /**
     * Returns filtered rates.
     *
     * @return SingleRate[]
     */
    public function get_ratings()
    {
        $rates = $this->shipment_rating->get_ratings();
        foreach ($rates as $key => $rate) {
            if (!$this->is_valid_rate_currency($rate)) {
                unset($rates[$key]);
                $this->logger->error(\sprintf(
                    // Translators: link.
                    \__('Invalid UPS currency %1$s for service %2$s. %3$sCheck out more →%4$s', 'flexible-shipping-ups'),
                    $rate->total_charge->currency,
                    $rate->service_type,
                    '<a href="' . ('pl_PL' === $this->shop_settings->get_locale() ? 'https://wpde.sk/ups-pro-currency-pl' : 'https://wpde.sk/ups-pro-currency') . '" target="_blank">',
                    '</a>'
                ));
            }
        }
        return $rates;
    }
}
