<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * PickupShipmentAttributes
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property string $ServiceType
 * @property string $PackagingType
 * @property Dimensions $Dimensions
 * @property Weight $Weight
 */
class PickupShipmentAttributes extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'PickupShipmentAttributes';
    /**
     * Set ServiceType
     *
     * @param string $serviceType
     * @return $this
     */
    public function setServiceType($serviceType)
    {
        $this->values['ServiceType'] = $serviceType;
        return $this;
    }
    /**
     * Set PackagingType
     *
     * @param string $packagingType
     * @return $this
     */
    public function setPackagingType($packagingType)
    {
        $this->values['PackagingType'] = $packagingType;
        return $this;
    }
    /**
     * Set Dimensions
     *
     * @param Dimensions $dimensions
     * @return $this
     */
    public function setDimensions(\FedExVendor\FedEx\PickupService\ComplexType\Dimensions $dimensions)
    {
        $this->values['Dimensions'] = $dimensions;
        return $this;
    }
    /**
     * Set Weight
     *
     * @param Weight $weight
     * @return $this
     */
    public function setWeight(\FedExVendor\FedEx\PickupService\ComplexType\Weight $weight)
    {
        $this->values['Weight'] = $weight;
        return $this;
    }
}
