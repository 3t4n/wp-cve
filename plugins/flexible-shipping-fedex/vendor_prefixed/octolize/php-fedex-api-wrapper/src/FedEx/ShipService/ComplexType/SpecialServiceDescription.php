<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * SpecialServiceDescription
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property OfferingIdentifierDetail $Identifier
 * @property ProductName[] $Names
 */
class SpecialServiceDescription extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'SpecialServiceDescription';
    /**
     * Set Identifier
     *
     * @param OfferingIdentifierDetail $identifier
     * @return $this
     */
    public function setIdentifier(\FedExVendor\FedEx\ShipService\ComplexType\OfferingIdentifierDetail $identifier)
    {
        $this->values['Identifier'] = $identifier;
        return $this;
    }
    /**
     * Set Names
     *
     * @param ProductName[] $names
     * @return $this
     */
    public function setNames(array $names)
    {
        $this->values['Names'] = $names;
        return $this;
    }
}
