<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\CollectionPoint;

use FedExVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use FedExVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException;
use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint;
use FedExVendor\WPDesk\Persistence\Adapter\ArrayContainer;
use FedExVendor\WPDesk\Persistence\Adapter\WooCommerce\WooCommerceSessionContainer;
use FedExVendor\WPDesk\WooCommerceShipping\Cache\CachedRating;
use FedExVendor\WPDesk\WooCommerceShipping\CollectionPoints\CheckoutHandler;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingBuilder;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasRateCache;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\ErrorLogCatcher;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\RateMethod;
use FedExVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * Rate method that uses Shipping service to create collection points rates in method.
 * Requires static injection of CheckoutHandler to work.
 *
 * @package WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Standard\
 */
class CollectionPointRateMethod implements \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\RateMethod
{
    const COLLECTION_POINT = 'collection-point';
    /**
     * Collection Points Checkout Handler.
     *
     * @var CheckoutHandler
     */
    private static $collection_points_checkout_handler;
    /** @var CanRateToCollectionPoint */
    private $rate_provider;
    /**
     * StandardServiceRateMethod constructor.
     *
     * @param CanRateToCollectionPoint $service Service that provides the rates.
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint $service)
    {
        $this->rate_provider = $service;
    }
    /**
     * Set collection points checkout handler.
     *
     * @param CheckoutHandler $collection_points_checkout_handler .
     */
    public static function set_collection_points_checkout_handler(\FedExVendor\WPDesk\WooCommerceShipping\CollectionPoints\CheckoutHandler $collection_points_checkout_handler)
    {
        self::$collection_points_checkout_handler = $collection_points_checkout_handler;
    }
    /**
     * Adds shipment rates to method.
     *
     * @param \WC_Shipping_Method $method Method to add rates.
     * @param ErrorLogCatcher $logger Special logger that can return last error.
     * @param WooCommerceShippingMetaDataBuilder $metadata_builder
     * @param WooCommerceShippingBuilder $shipment_builder Class that can build shipment from package
     *
     * @return void
     */
    public function handle_rates(\WC_Shipping_Method $method, \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\ErrorLogCatcher $logger, \FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder $metadata_builder, \FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingBuilder $shipment_builder)
    {
        try {
            $rates_count = \count($method->rates);
            $this->add_rates_to_collection_point($method, $this->rate_provider, $metadata_builder, $shipment_builder);
            if ($rates_count === \count($method->rates)) {
                $logger->info(\__('No rates added from collection point rates!', 'flexible-shipping-fedex'));
            }
        } catch (\FedExVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException $cpe) {
            $logger->error(\__('Collection point not found.', 'flexible-shipping-fedex'));
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
        }
    }
    /**
     * Rate shipment.
     *
     * @param \WC_Shipping_Method $method Method.
     * @param CanRateToCollectionPoint $service Service.
     * @param WooCommerceShippingMetaDataBuilder $meta_data_builder Meta data builder.
     * @param WooCommerceShippingBuilder $shipment_builder Class that can build shipment from package
     *
     * @throws CollectionPointNotFoundException .
     */
    private function add_rates_to_collection_point(\FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod $method, \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint $service, \FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder $meta_data_builder, \FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingBuilder $shipment_builder)
    {
        $service_id = $method->id;
        $service_settings = \apply_filters("{$service_id}_settings_before_rate", $method->create_settings_values_as_array());
        if ($service->is_rate_to_collection_point_enabled($service_settings)) {
            $shipment = \apply_filters("{$service_id}_shipment_before_rate", $shipment_builder->build_shipment(), $method);
            $collection_point = $this->get_collection_point_for_rates($shipment_builder->get_woocommerce_package(), $method);
            if (null !== $collection_point) {
                if ($method instanceof \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasRateCache) {
                    $cached_rating = new \FedExVendor\WPDesk\WooCommerceShipping\Cache\CachedRating((new \FedExVendor\WPDesk\WooCommerceShipping\Cache\ShopSettingsMd5HashGenerator())->generate_md5_hash(new \FedExVendor\WPDesk\WooCommerceShipping\ShopSettings($service_id)), \WC()->session ? new \FedExVendor\WPDesk\Persistence\Adapter\WooCommerce\WooCommerceSessionContainer(\WC()->session) : new \FedExVendor\WPDesk\Persistence\Adapter\ArrayContainer());
                    $rates_from_response = $cached_rating->rate_shipment_to_collection_point($service_settings, $shipment, $collection_point, $service);
                } else {
                    $rates_from_response = $service->rate_shipment_to_collection_point($service_settings, $shipment, $collection_point);
                }
                $rates_from_response = \apply_filters("{$service_id}_rates", $rates_from_response, $method);
                $meta_data_builder = \apply_filters("{$service_id}_meta_data_builder", $meta_data_builder, $method);
                $this->add_rates_to_collection_point_from_response($method, $shipment, $rates_from_response, $collection_point, $meta_data_builder);
            } else {
                throw new \FedExVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException();
            }
        }
    }
    /**
     * Get collection point for rates.
     *
     * @param array $package .
     *
     * @param \WC_Shipping_Method $method .
     *
     * @return CollectionPoint|null
     */
    protected function get_collection_point_for_rates(array $package, $method)
    {
        $service_id = $method->id;
        try {
            $collection_point = self::$collection_points_checkout_handler->get_collection_point_for_rates($package['destination']);
        } catch (\FedExVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException $e) {
            $collection_point = null;
        }
        return \apply_filters("{$service_id}_collection_point_before_rate", $collection_point, $method);
    }
    /**
     * Add Woocommerce shipping rates.
     *
     * @param \WC_Shipping_Method $method Method.
     * @param Shipment $shipment Shipment.
     * @param ShipmentRating $shipment_ratings Shipment rates.
     * @param CollectionPoint|null $collection_point Collection point.
     * @param WooCommerceShippingMetaDataBuilder $meta_data_builder Meta data builder.
     */
    private function add_rates_to_collection_point_from_response(\WC_Shipping_Method $method, \FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $shipment_ratings, $collection_point, \FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder $meta_data_builder)
    {
        foreach ($shipment_ratings->get_ratings() as $rate) {
            if (isset($meta_data_builder)) {
                $meta_data = $meta_data_builder->build_meta_data_for_rate_to_collection_point($rate, $collection_point, $shipment);
            } else {
                $meta_data = [];
            }
            $meta_data = (array) \apply_filters($method->id . '/rate/meta_data', $meta_data, $method);
            $method->add_rate(['id' => $method->id . ':' . $method->instance_id . ':' . $rate->service_type . ':' . self::COLLECTION_POINT, 'label' => $rate->service_name, 'cost' => $rate->total_charge->amount, 'sort' => 0, 'meta_data' => $meta_data]);
        }
    }
    /**
     * Add rate method settings to shipment service settings.
     *
     * @param array $settings Settings from \WC_Shipping_Method
     *
     * @return array Settings with rate settings
     */
    public function add_to_settings(array $settings)
    {
        return $settings;
    }
}
