<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\Cache;

use FedExVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use FedExVendor\WPDesk\AbstractShipping\Rate\Money;
use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRatingImplementation;
use FedExVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint;
use FedExVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Can cache rates.
 */
class CachedRating
{
    const EXPIRES = 'expires';
    const EXPIRATION_TIME_IN_SECONDS = 600;
    const RATES = 'rates';
    const SERVICE_NAME = 'service_name';
    const SERVICE_TYPE = 'service_type';
    const AMOUNT = 'amount';
    const CURRENCY = 'currency';
    const BUSINESS_DAYS_IN_TRANSIT = 'business_days_in_transit';
    const IS_COLLECTION_POINT_RATE = 'is_collection_point_rate';
    const DELIVERY_DATE = 'delivery_date';
    const CACHE_KEY = 'flexible_shipping_rates';
    /**
     * @var string
     */
    private $shop_settings_md5_hash;
    /**
     * @var PersistentContainer
     */
    private $container;
    /**
     * @param string $shop_settings_md5_hash
     * @param PersistentContainer $container
     */
    public function __construct($shop_settings_md5_hash, \FedExVendor\WPDesk\Persistence\PersistentContainer $container)
    {
        $this->shop_settings_md5_hash = $shop_settings_md5_hash;
        $this->container = $container;
    }
    /**
     * @param SettingsValuesAsArray $settings
     * @param Shipment $shipment
     * @param CanRate $service
     *
     * @return ShipmentRating
     */
    public function rate_shipment(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray $settings, \FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate $service)
    {
        $rates_key = $this->prepare_rates_key($settings, $shipment);
        $shipment_rating = $this->get_shipment_rating_from_cache($rates_key);
        if (!$shipment_rating) {
            $shipment_rating = $service->rate_shipment($settings, $shipment);
            $this->store_shipment_rating_in_cache($rates_key, $shipment_rating);
        }
        return $shipment_rating;
    }
    /**
     * @param SettingsValuesAsArray $settings
     * @param Shipment $shipment
     * @param CollectionPoint $collection_point
     * @param CanRateToCollectionPoint $service
     *
     * @return ShipmentRating
     */
    public function rate_shipment_to_collection_point(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray $settings, \FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \FedExVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point, \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint $service)
    {
        $rates_key = $this->prepare_rates_key($settings, $shipment, $collection_point->collection_point_id);
        $shipment_rating = $this->get_shipment_rating_from_cache($rates_key);
        if (!$shipment_rating) {
            $shipment_rating = $service->rate_shipment_to_collection_point($settings, $shipment, $collection_point);
            $this->store_shipment_rating_in_cache($rates_key, $shipment_rating);
        }
        return $shipment_rating;
    }
    /**
     * @param SettingsValuesAsArray $settings
     * @param Shipment $shipment
     * @param string $collection_point
     * @return string
     */
    private function prepare_rates_key(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray $settings, \FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, string $collection_point = '')
    {
        return \md5($settings->get_settings_md5_hash() . $this->prepare_shipment_md5_hash($shipment) . $this->shop_settings_md5_hash . $collection_point);
    }
    /**
     * @param Shipment $shipment
     * @return string
     */
    private function prepare_shipment_md5_hash(\FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        return \md5(\json_encode($shipment));
    }
    /**
     * @param string $rates_key
     * @param ShipmentRating $shipment_rating
     * @return void
     */
    private function store_shipment_rating_in_cache($rates_key, \FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $shipment_rating)
    {
        $rates = [];
        foreach ($shipment_rating->get_ratings() as $single_rate) {
            $rates[] = [self::SERVICE_NAME => $single_rate->service_name, self::SERVICE_TYPE => $single_rate->service_type, self::AMOUNT => $single_rate->total_charge->amount, self::CURRENCY => $single_rate->total_charge->currency, self::BUSINESS_DAYS_IN_TRANSIT => $single_rate->business_days_in_transit, self::IS_COLLECTION_POINT_RATE => $single_rate->is_collection_point_rate, self::DELIVERY_DATE => $single_rate->delivery_date ? $single_rate->delivery_date->format('Y-m-d H:i:s') : null];
        }
        $rates_data = [self::EXPIRES => \time() + self::EXPIRATION_TIME_IN_SECONDS, self::RATES => $rates];
        $this->add_rates_to_cache($rates_key, $rates_data);
    }
    /**
     * @param string $rates_key
     * @return false|ShipmentRating
     */
    private function get_shipment_rating_from_cache($rates_key)
    {
        $cached_rates = $this->get_cached_rates();
        if (isset($cached_rates[$rates_key])) {
            return $this->prepare_shipment_rating_from_cached_data($cached_rates[$rates_key]);
        }
        return \false;
    }
    /**
     * @param array $data
     * @return false|ShipmentRatingImplementation
     * @throws \Exception
     */
    private function prepare_shipment_rating_from_cached_data($data)
    {
        if (\is_array($data)) {
            if (isset($data[self::EXPIRES]) && (int) $data[self::EXPIRES] > \time()) {
                $rates = [];
                if (isset($data[self::RATES]) && \is_array($data[self::RATES])) {
                    foreach ($data[self::RATES] as $rate) {
                        if (\is_array($rate)) {
                            $single_rate = new \FedExVendor\WPDesk\AbstractShipping\Rate\SingleRate();
                            $single_rate->service_name = $rate[self::SERVICE_NAME] ?? null;
                            $single_rate->service_type = $rate[self::SERVICE_TYPE] ?? null;
                            $total_charge = new \FedExVendor\WPDesk\AbstractShipping\Rate\Money();
                            $total_charge->amount = $rate[self::AMOUNT] ?? null;
                            $total_charge->currency = $rate[self::CURRENCY] ?? null;
                            $single_rate->total_charge = $total_charge;
                            $single_rate->business_days_in_transit = $rate[self::BUSINESS_DAYS_IN_TRANSIT] ?? null;
                            $single_rate->is_collection_point_rate = $rate[self::IS_COLLECTION_POINT_RATE] ?? \false;
                            $single_rate->delivery_date = $rate[self::DELIVERY_DATE] ? new \DateTime($rate[self::DELIVERY_DATE]) : null;
                            $rates[] = $single_rate;
                        }
                    }
                }
                return new \FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRatingImplementation($rates);
            }
            return \false;
        }
        return \false;
    }
    private function add_rates_to_cache($rates_key, $rates_data)
    {
        $cached_rates = $this->get_cached_rates();
        $cached_rates[$rates_key] = $rates_data;
        $this->container->set(self::CACHE_KEY, $cached_rates);
    }
    /**
     * @return array
     */
    private function get_cached_rates()
    {
        if ($this->container->has(self::CACHE_KEY)) {
            $flexible_shipping_rates = $this->container->get(self::CACHE_KEY);
            return $this->clear_expired(\is_array($flexible_shipping_rates) ? $flexible_shipping_rates : []);
        }
        return [];
    }
    /**
     * @param array $cached_rates
     * @return array
     */
    private function clear_expired(array $cached_rates)
    {
        foreach ($cached_rates as $key => $cached_rate) {
            if (\is_array($cached_rate) && isset($cached_rate[self::EXPIRES]) && (int) $cached_rate[self::EXPIRES] < \time()) {
                unset($cached_rates[$key]);
            }
        }
        return $cached_rates;
    }
}
