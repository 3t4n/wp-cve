<?php

namespace Packlink\BusinessLogic\Http\DTO;

use Logeecom\Infrastructure\Data\DataTransferObject;
use Logeecom\Infrastructure\ServiceRegister;
use Packlink\BusinessLogic\Brand\BrandConfigurationService;

/**
 * This class holds search parameters that are used when searching for services
 * for specific source and destination location information and specific parcel.
 *
 * @package Packlink\BusinessLogic\Http\DTO
 */
class ShippingServiceSearch extends DataTransferObject
{
    /**
     * Service ID. Optional.
     *
     * @var int
     */
    public $serviceId;
    /**
     * Departure country 2 letter code.
     *
     * @var string
     */
    public $fromCountry;
    /**
     * Departure country postal/zip code.
     *
     * @var string
     */
    public $fromZip;
    /**
     * Destination country 2 letter code.
     *
     * @var string
     */
    public $toCountry;
    /**
     * Destination country postal/zip code.
     *
     * @var string
     */
    public $toZip;
    /**
     * Array of packages.
     *
     * @var \Packlink\BusinessLogic\Http\DTO\Package[]
     */
    public $packages;

    /**
     * ShippingServiceSearch constructor.
     *
     * @param int $serviceId Service Id.
     * @param string $fromCountry Departure country 2-letter code.
     * @param string $fromZip Departure country postal/zip code.
     * @param string $toCountry Destination country 2-letter code.
     * @param string $toZip Destination country postal/zip code.
     * @param Package[] $packages Array of packages.
     */
    public function __construct(
        $serviceId = null,
        $fromCountry = '',
        $fromZip = '',
        $toCountry = '',
        $toZip = '',
        $packages = array()
    ) {
        $this->serviceId = $serviceId;
        $this->fromCountry = $fromCountry;
        $this->fromZip = $fromZip;
        $this->toCountry = $toCountry;
        $this->toZip = $toZip;
        $this->packages = $packages;
    }

    /**
     * Transforms DTO to its array format suitable for http client.
     *
     * @return array DTO in array format.
     */
    public function toArray()
    {
        /** @var BrandConfigurationService $brandConfigurationService */
        $brandConfigurationService = ServiceRegister::getService(BrandConfigurationService::CLASS_NAME);
        $brand = $brandConfigurationService->get();

        $data = array(
            'from[country]' => $this->fromCountry,
            'from[zip]' => $this->fromZip,
            'to[country]' => $this->toCountry,
            'to[zip]' => $this->toZip,
            'source' => $brand->shippingServiceSource,
        );

        if ($this->serviceId) {
            $data['serviceId'] = $this->serviceId;
        }

        foreach ($this->packages as $index => $package) {
            $data["packages[$index][height]"] = (int)ceil($package->height);
            $data["packages[$index][width]"] = (int)ceil($package->width);
            $data["packages[$index][length]"] = (int)ceil($package->length);
            $data["packages[$index][weight]"] = $package->weight;
        }

        return $data;
    }

    /**
     * Transforms raw array data to object.
     *
     * @param array $raw Raw array data.
     *
     * @return static Transformed DTO object.
     */
    public static function fromArray(array $raw)
    {
        $instance = new static();

        $instance->serviceId = self::getDataValue($raw, 'service_id', null);
        $instance->fromCountry = self::getDataValue($raw, 'from[country]');
        $instance->fromZip = self::getDataValue($raw, 'from[zip]');
        $instance->toCountry = self::getDataValue($raw, 'to[country]');
        $instance->toZip = self::getDataValue($raw, 'to[zip]');
        $instance->packages = array();

        $index = 0;
        while (array_key_exists("packages[$index][height]", $raw)) {
            $package = new Package();

            $package->height = self::getDataValue($raw, "packages[$index][height]");
            $package->width = self::getDataValue($raw, "packages[$index][width]");
            $package->length = self::getDataValue($raw, "packages[$index][length]");
            $package->weight = self::getDataValue($raw, "packages[$index][weight]");

            $instance->packages[] = $package;
            $index++;
        }

        return $instance;
    }

    /**
     * Validates if all parameters are valid.
     *
     * @return bool Is valid flag.
     */
    public function isValid()
    {
        return !empty($this->fromCountry) && !empty($this->fromZip) && !empty($this->toCountry)
            && !empty($this->toZip) && !empty($this->packages);
    }
}
