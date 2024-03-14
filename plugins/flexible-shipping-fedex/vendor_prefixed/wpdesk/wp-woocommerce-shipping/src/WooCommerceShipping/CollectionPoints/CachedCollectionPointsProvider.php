<?php

/**
 * Class CachedCollectionPointsProvider
 * @package WPDesk\WooCommerceShipping\CollectionPoints
 */
namespace FedExVendor\WPDesk\WooCommerceShipping\CollectionPoints;

use FedExVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider;
use FedExVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Address;
use FedExVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Cached Collections Point.
 * Decorates CollectionPointProvider.
 */
class CachedCollectionPointsProvider implements \FedExVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider
{
    /**
     * @var CollectionPointsProvider
     */
    private $collections_point_provider;
    /**
     * @var PersistentContainer
     */
    private $persistent_container;
    /**
     * @var string
     */
    private $salt;
    /**
     * CachedCollectionPointsProvider constructor.
     *
     * @param CollectionPointsProvider $collection_points_provider .
     * @param PersistentContainer      $persistent_container .
     * @param string                   $salt .
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider $collection_points_provider, \FedExVendor\WPDesk\Persistence\PersistentContainer $persistent_container, $salt)
    {
        $this->collections_point_provider = $collection_points_provider;
        $this->persistent_container = $persistent_container;
        $this->salt = $salt;
    }
    /**
     * @param Address $address .
     *
     * @return CollectionPoint[]
     */
    public function get_nearest_collection_points(\FedExVendor\WPDesk\AbstractShipping\Shipment\Address $address)
    {
        $item_id = $this->prepare_item_id('nearest', $this->get_address_as_string($address));
        try {
            return $this->persistent_container->get($item_id);
        } catch (\Exception $e) {
            $nearest_collection_points = $this->collections_point_provider->get_nearest_collection_points($address);
            $this->persistent_container->set($item_id, $nearest_collection_points);
            return $nearest_collection_points;
        }
    }
    /**
     * @param Address $address .
     *
     * @return CollectionPoint
     */
    public function get_single_nearest_collection_point(\FedExVendor\WPDesk\AbstractShipping\Shipment\Address $address)
    {
        $item_id = $this->prepare_item_id('single_nearest', $this->get_address_as_string($address));
        try {
            return $this->persistent_container->get($item_id);
        } catch (\Exception $e) {
            $single_nearest_collection_point = $this->collections_point_provider->get_single_nearest_collection_point($address);
            $this->persistent_container->set($item_id, $single_nearest_collection_point);
            return $single_nearest_collection_point;
        }
    }
    /**
     * @param string $collection_point_id .
     * @param string $country_code .
     *
     * @return CollectionPoint
     */
    public function get_point_by_id($collection_point_id, $country_code)
    {
        $item_id = $this->prepare_item_id('point', $collection_point_id . $country_code);
        try {
            return $this->persistent_container->get($item_id);
        } catch (\Exception $e) {
            $point = $this->collections_point_provider->get_point_by_id($collection_point_id, $country_code);
            $this->persistent_container->set($item_id, $point);
            return $point;
        }
    }
    /**
     * @param Address $address .
     *
     * @return string
     */
    private function get_address_as_string(\FedExVendor\WPDesk\AbstractShipping\Shipment\Address $address)
    {
        $address_as_string = $address->country_code . $address->address_line1 . $address->address_line2 . $address->city . $address->postal_code . $address->state_code;
        foreach ($address->street_lines as $street_line) {
            $address_as_string .= $street_line;
        }
        return $address_as_string;
    }
    /**
     * @param string $set .
     * @param string $element_id .
     *
     * @return string
     */
    private function prepare_item_id($set, $element_id)
    {
        return \md5($set . $element_id . $this->salt);
    }
}
