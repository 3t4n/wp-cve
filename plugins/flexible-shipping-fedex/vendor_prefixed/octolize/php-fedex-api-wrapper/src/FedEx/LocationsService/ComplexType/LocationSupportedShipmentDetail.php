<?php

namespace FedExVendor\FedEx\LocationsService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * LocationSupportedShipmentDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 *
 * @property LocationSupportedPackageDetail[] $PackageDetails
 */
class LocationSupportedShipmentDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'LocationSupportedShipmentDetail';
    /**
     * Set PackageDetails
     *
     * @param LocationSupportedPackageDetail[] $packageDetails
     * @return $this
     */
    public function setPackageDetails(array $packageDetails)
    {
        $this->values['PackageDetails'] = $packageDetails;
        return $this;
    }
}
