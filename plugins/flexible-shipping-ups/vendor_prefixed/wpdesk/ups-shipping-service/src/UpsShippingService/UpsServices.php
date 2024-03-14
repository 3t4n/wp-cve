<?php

/**
 * UPS implementation: UpsServices class.
 *
 * @package WPDesk\UpsShippingService
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * A class that defines UPS services.
 *
 * @package WPDesk\UpsShippingService
 */
class UpsServices
{
    const SUREPOST_LESS_THAN_1_LB = '92';
    const SUREPOST_1_LB_OR_GREATER = '93';
    /**
     * EU countries.
     *
     * @var array
     */
    private static $eu_countries = [];
    /**
     * Services.
     *
     * @var array
     */
    private static $services = null;
    /**
     * Get services.
     *
     * @return array
     */
    public static function get_services()
    {
        if (empty(self::$services)) {
            self::$services = ['all' => ['96' => \__('UPS Worldwide Express Freight', 'flexible-shipping-ups'), '71' => \__('UPS Worldwide Express Freight Midday', 'flexible-shipping-ups')], 'other' => ['07' => \__('UPS Express', 'flexible-shipping-ups'), '11' => \__('UPS Standard', 'flexible-shipping-ups'), '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'), '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'), '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups')], 'PR' => [
                // Puerto Rico.
                '02' => \__('UPS 2nd Day Air', 'flexible-shipping-ups'),
                '03' => \__('UPS Ground', 'flexible-shipping-ups'),
                '01' => \__('UPS Next Day Air', 'flexible-shipping-ups'),
                '14' => \__('UPS Next Day Air Early', 'flexible-shipping-ups'),
                '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Worldwide Express', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
            ], 'PL' => [
                // Poland.
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '83' => \__('UPS Today Dedicated Courrier', 'flexible-shipping-ups'),
                '85' => \__('UPS Today Express', 'flexible-shipping-ups'),
                '86' => \__('UPS Today Express Saver', 'flexible-shipping-ups'),
                '82' => \__('UPS Today Standard', 'flexible-shipping-ups'),
                '08' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Express', 'flexible-shipping-ups'),
                '54' => \__('UPS Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Express Saver', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
            ], 'MX' => [
                // Mexico.
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '08' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Express', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
            ], 'EU' => [
                // European Union.
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '08' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Express', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
            ], 'CA' => [
                // Canada.
                '02' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '13' => \__('UPS Express Saver', 'flexible-shipping-ups'),
                '12' => \__('UPS 3 Day Select', 'flexible-shipping-ups'),
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '01' => \__('UPS Express', 'flexible-shipping-ups'),
                '14' => \__('UPS Express Early', 'flexible-shipping-ups'),
                '65' => \__('UPS Express Saver', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Worldwide Express', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
            ], 'US' => [
                // USA.
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '07' => \__('UPS Worldwide Express', 'flexible-shipping-ups'),
                '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
                '02' => \__('UPS 2nd Day Air', 'flexible-shipping-ups'),
                '59' => \__('UPS 2nd Day Air A.M.', 'flexible-shipping-ups'),
                '12' => \__('UPS 3 Day Select', 'flexible-shipping-ups'),
                '03' => \__('UPS Ground', 'flexible-shipping-ups'),
                '01' => \__('UPS Next Day Air', 'flexible-shipping-ups'),
                '14' => \__('UPS Next Day Air Early', 'flexible-shipping-ups'),
                '13' => \__('UPS Next Day Air Saver', 'flexible-shipping-ups'),
            ]];
        }
        return self::$services;
    }
    /**
     * @return array
     */
    public function get_surepost_services()
    {
        return [self::SUREPOST_LESS_THAN_1_LB => \__('SurePost Less than 1 lb', 'flexible-shipping-ups'), self::SUREPOST_1_LB_OR_GREATER => \__('SurePost 1 lb or Greater', 'flexible-shipping-ups'), '94' => \__('SurePost BPM', 'flexible-shipping-ups'), '95' => \__('SurePost Media Mail', 'flexible-shipping-ups')];
    }
    /**
     * @return array
     */
    public function get_surepost_same_services()
    {
        return [[self::SUREPOST_LESS_THAN_1_LB, self::SUREPOST_1_LB_OR_GREATER]];
    }
    /**
     * Set EU countries.
     *
     * @param array $eu_countries .
     */
    public static function set_eu_countries(array $eu_countries)
    {
        self::$eu_countries = $eu_countries;
    }
    /**
     * Get services for country.
     *
     * @param string $country_code .
     *
     * @return array
     */
    public static function get_services_for_country($country_code)
    {
        $services = self::get_services();
        $services_for_country = [];
        if (isset($services[$country_code])) {
            $services_for_country = $services[$country_code];
        }
        if ('PL' !== $country_code && \in_array($country_code, self::$eu_countries, \true)) {
            $services_for_country = $services['EU'];
        }
        if (0 === \count($services_for_country)) {
            $services_for_country = $services['other'];
        }
        foreach ($services['all'] as $key => $value) {
            $services_for_country[$key] = $value;
        }
        return $services_for_country;
    }
}
