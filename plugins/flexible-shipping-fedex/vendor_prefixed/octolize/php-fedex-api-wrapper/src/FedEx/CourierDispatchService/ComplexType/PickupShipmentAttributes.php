<?php

namespace FedExVendor\FedEx\CourierDispatchService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Descriptive information about the shipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Courier Dispatch Service
 *
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
     * Descriptive information about the dimensions of the package.
     *
     * @param Dimensions $dimensions
     * @return $this
     */
    public function setDimensions(\FedExVendor\FedEx\CourierDispatchService\ComplexType\Dimensions $dimensions)
    {
        $this->values['Dimensions'] = $dimensions;
        return $this;
    }
    /**
     * Descriptive information about the weight of the package.
     *
     * @param Weight $weight
     * @return $this
     */
    public function setWeight(\FedExVendor\FedEx\CourierDispatchService\ComplexType\Weight $weight)
    {
        $this->values['Weight'] = $weight;
        return $this;
    }
}
