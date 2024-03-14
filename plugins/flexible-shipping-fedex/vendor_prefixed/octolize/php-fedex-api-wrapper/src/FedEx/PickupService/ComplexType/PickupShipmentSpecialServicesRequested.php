<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * PickupShipmentSpecialServicesRequested
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property string[] $SpecialServiceTypes
 */
class PickupShipmentSpecialServicesRequested extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'PickupShipmentSpecialServicesRequested';
    /**
     * Set SpecialServiceTypes
     *
     * @param string $specialServiceTypes
     * @return $this
     */
    public function setSpecialServiceTypes($specialServiceTypes)
    {
        $this->values['SpecialServiceTypes'] = $specialServiceTypes;
        return $this;
    }
}
