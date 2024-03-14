<?php

/**
 * Meta data builder.
 *
 * @package WPDesk\WooCommerceShipping\Ups
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Ups;

use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
/**
 * Can build UPS meta data.
 */
class UpsMetaDataBuilder extends \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder
{
    const META_UPS_SERVICE_CODE = 'ups_service_code';
    const META_UPS_ACCESS_POINT = 'ups_access_point';
    const META_UPS_ACCESS_POINT_ADDRESS = 'ups_access_point_address';
    const COLLECTION_POINT_ID = 'collection_point_id';
    const COLLECTION_POINT_ADDRESS = 'collection_point_address';
    /**
     * Build meta data for rate.
     *
     * @param SingleRate $rate .
     * @param Shipment $shipment .
     *
     * @return array
     */
    public function build_meta_data_for_rate(\UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate $rate, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        $meta_data = parent::build_meta_data_for_rate($rate, $shipment);
        $meta_data = $this->add_ups_service_code_to_metadata($meta_data, $rate);
        return $meta_data;
    }
    /**
     * Build meta data for rate to collection point.
     *
     * @param SingleRate           $rate .
     * @param CollectionPoint|null $collection_point .
     * @param Shipment             $shipment .
     *
     * @return array
     */
    public function build_meta_data_for_rate_to_collection_point(\UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate $rate, $collection_point, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        $meta_data = parent::build_meta_data_for_rate_to_collection_point($rate, $collection_point, $shipment);
        $meta_data = $this->add_ups_service_code_to_metadata($meta_data, $rate);
        return $this->append_ups_access_point_data($meta_data, $collection_point);
    }
    /**
     * Build metadata to collection point.
     *
     * @param CollectionPoint $collection_point .
     *
     * @return array
     */
    public function build_meta_data_to_collection_point(\UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point)
    {
        $meta_data = parent::build_meta_data_to_collection_point($collection_point);
        return $this->append_ups_access_point_data($meta_data, $collection_point);
    }
    /**
     * Append UPS access point data.
     *
     * @param array $meta_data
     * @param CollectionPoint $collection_point .
     *
     * @return array
     */
    private function append_ups_access_point_data(array $meta_data, \UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point)
    {
        $meta_data[self::META_UPS_ACCESS_POINT] = $meta_data[self::COLLECTION_POINT_ID];
        $meta_data[self::META_UPS_ACCESS_POINT_ADDRESS] = $meta_data[self::COLLECTION_POINT_ADDRESS];
        return $meta_data;
    }
    /**
     * Add UPS service to metadata.
     *
     * @param array $meta_data
     * @param SingleRate $rate
     *
     * @return array
     */
    private function add_ups_service_code_to_metadata(array $meta_data, \UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate $rate)
    {
        $meta_data[self::META_UPS_SERVICE_CODE] = $rate->service_type;
        return $meta_data;
    }
}
