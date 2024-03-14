<?php

/**
 * UPS implementation: UpsAccessPoints class.
 *
 * @package WPDesk\UpsShippingService\UpsApi
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService\UpsApi;

use UpsFreeVendor\Octolize\Ups\RestApi\RestApiClient;
use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\Ups\Entity\AccessPointSearch;
use UpsFreeVendor\Ups\Entity\AddressKeyFormat;
use UpsFreeVendor\Ups\Entity\LocationSearchCriteria;
use UpsFreeVendor\Ups\Entity\LocatorRequest;
use UpsFreeVendor\Ups\Entity\OriginAddress;
use UpsFreeVendor\Ups\Entity\Translate;
use UpsFreeVendor\Ups\Entity\UnitOfMeasurement;
use UpsFreeVendor\Ups\Locator;
use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address;
/**
 * Provides UPS access points as Collection Points.
 */
class UpsAccessPoints implements \UpsFreeVendor\WPDesk\AbstractShipping\CollectionPointCapability\CollectionPointsProvider
{
    /**
     * Access key.
     *
     * @var string
     */
    private $access_key;
    /**
     * User id.
     *
     * @var string
     */
    private $user_id;
    /**
     * Password.
     *
     * @var string
     */
    private $password;
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var RestApiClient
     */
    private $rest_api_client;
    /**
     * @var string
     */
    private $api_type;
    /**
     * UpsAccessPoints constructor.
     *
     * @param string          $access_key .
     * @param string          $user_id .
     * @param string          $password .
     * @param LoggerInterface $logger Logger.
     */
    public function __construct($access_key, $user_id, $password, \UpsFreeVendor\Psr\Log\LoggerInterface $logger, \UpsFreeVendor\Octolize\Ups\RestApi\RestApiClient $client, string $api_type)
    {
        $this->access_key = $access_key;
        $this->user_id = $user_id;
        $this->password = $password;
        $this->logger = $logger;
        $this->rest_api_client = $client;
        $this->api_type = $api_type;
    }
    /**
     * Search access points.
     *
     * @param AddressKeyFormat  $address_key_format .
     * @param AccessPointSearch $access_point_search .
     * @param int               $maximum_list_size .
     *
     * @return \stdClass
     * @throws \Exception .
     */
    private function search_access_points(\UpsFreeVendor\Ups\Entity\AddressKeyFormat $address_key_format, \UpsFreeVendor\Ups\Entity\AccessPointSearch $access_point_search, int $maximum_list_size = 50)
    {
        $locator_request = new \UpsFreeVendor\Ups\Entity\LocatorRequest();
        $origin_address = new \UpsFreeVendor\Ups\Entity\OriginAddress();
        $origin_address->setAddressKeyFormat($address_key_format);
        $locator_request->setOriginAddress($origin_address);
        $location_search = new \UpsFreeVendor\Ups\Entity\LocationSearchCriteria();
        $location_search->setAccessPointSearch($access_point_search);
        $location_search->setMaximumListSize($maximum_list_size);
        $unit_of_measurement = new \UpsFreeVendor\Ups\Entity\UnitOfMeasurement();
        $unit_of_measurement->setCode(\UpsFreeVendor\Ups\Entity\UnitOfMeasurement::UOM_KM);
        $locator_request->setUnitOfMeasurement($unit_of_measurement);
        $locator_request->setLocationSearchCriteria($location_search);
        $translate = new \UpsFreeVendor\Ups\Entity\Translate();
        $translate->setLocale(\get_locale());
        $translate->setLanguageCode(\substr(\get_locale(), 0, 2));
        $locator_request->setTranslate($translate);
        if ($this->api_type === 'xml') {
            $locator = new \UpsFreeVendor\Ups\Locator($this->access_key, $this->user_id, $this->password);
        } else {
            $locator = new \UpsFreeVendor\Octolize\Ups\RestApi\Locator($this->rest_api_client);
        }
        $locator->setLogger($this->logger);
        return $locator->getLocations($locator_request, \UpsFreeVendor\Ups\Locator::OPTION_UPS_ACCESS_POINT_LOCATIONS);
    }
    /**
     * Convert location to collection point.
     *
     * @param \stdClass $location structure returned by UPS API. @see docs/location.MD.
     *
     * @return CollectionPoint
     */
    private function convert_location_to_collection_point(\stdClass $location)
    {
        $collection_point = new \UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint();
        $collection_point->collection_point_id = $location->AccessPointInformation->PublicAccessPointID;
        // phpcs:ignore
        $collection_point->collection_point_name = $location->AddressKeyFormat->ConsigneeName;
        // phpcs:ignore
        $address = new \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address();
        $address->address_line1 = $location->AddressKeyFormat->AddressLine;
        // phpcs:ignore
        $address->postal_code = $location->AddressKeyFormat->PostcodePrimaryLow;
        // phpcs:ignore
        $address->city = $location->AddressKeyFormat->PoliticalDivision2;
        // phpcs:ignore
        $address->country_code = $location->AddressKeyFormat->CountryCode;
        // phpcs:ignore
        $collection_point->collection_point_address = $address;
        return $collection_point;
    }
    /**
     * Get get collection point by given id.
     *
     * @param string $collection_point_id .
     * @param string $country_code .
     *
     * @return CollectionPoint
     * @throws CollectionPointNotFoundException .
     */
    public function get_point_by_id($collection_point_id, $country_code)
    {
        $access_point_id = $collection_point_id;
        $address = new \UpsFreeVendor\Ups\Entity\AddressKeyFormat();
        $address->setCountryCode($country_code);
        $access_point_search = new \UpsFreeVendor\Ups\Entity\AccessPointSearch();
        $access_point_search->setAccessPointStatus(\UpsFreeVendor\Ups\Entity\AccessPointSearch::STATUS_ACTIVE_AVAILABLE);
        $access_point_search->setPublicAccessPointId($access_point_id);
        try {
            $locations = $this->search_access_points($address, $access_point_search, 1);
            return $this->convert_location_to_collection_point($locations->SearchResults->DropLocation);
            // phpcs:ignore
        } catch (\Exception $e) {
            throw new \UpsFreeVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Get nearest collection points to given address.
     *
     * @param Address $address .
     *
     * @return CollectionPoint[]
     * @throws CollectionPointNotFoundException .
     */
    public function get_nearest_collection_points(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address $address, int $maximum_list_size = 50)
    {
        $address_key_format = new \UpsFreeVendor\Ups\Entity\AddressKeyFormat();
        $address_key_format->setAddressLine1($address->address_line1);
        $address_key_format->setAddressLine2($address->address_line2);
        $address_key_format->setCountryCode($address->country_code);
        $address_key_format->setPoliticalDivision2($address->city);
        $address_key_format->setPostcodePrimaryLow($address->postal_code);
        $access_point_search = new \UpsFreeVendor\Ups\Entity\AccessPointSearch();
        $access_point_search->setAccessPointStatus(\UpsFreeVendor\Ups\Entity\AccessPointSearch::STATUS_ACTIVE_AVAILABLE);
        try {
            $locations = $this->search_access_points($address_key_format, $access_point_search, $maximum_list_size);
            $collection_points = array();
            if (!\is_array($locations->SearchResults->DropLocation)) {
                // phpcs:ignore
                $locations->SearchResults->DropLocation = array($locations->SearchResults->DropLocation);
                // phpcs:ignore
            }
            foreach ($locations->SearchResults->DropLocation as $location) {
                // phpcs:ignore
                $collection_point = $this->convert_location_to_collection_point($location);
                $collection_points[$collection_point->collection_point_id] = $collection_point;
            }
            return $collection_points;
        } catch (\Exception $e) {
            throw new \UpsFreeVendor\WPDesk\AbstractShipping\Exception\CollectionPointNotFoundException($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Get single nearest collection point to given address.
     *
     * @param Address $address .
     *
     * @return CollectionPoint
     * @throws CollectionPointNotFoundException .
     */
    public function get_single_nearest_collection_point(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Address $address)
    {
        $collection_points = $this->get_nearest_collection_points($address, 1);
        return \array_shift($collection_points);
    }
}
