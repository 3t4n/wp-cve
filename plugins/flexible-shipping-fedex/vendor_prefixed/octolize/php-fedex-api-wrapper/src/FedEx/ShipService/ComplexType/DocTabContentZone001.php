<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * DocTabContentZone001
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property DocTabZoneSpecification[] $DocTabZoneSpecifications
 */
class DocTabContentZone001 extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'DocTabContentZone001';
    /**
     * Set DocTabZoneSpecifications
     *
     * @param DocTabZoneSpecification[] $docTabZoneSpecifications
     * @return $this
     */
    public function setDocTabZoneSpecifications(array $docTabZoneSpecifications)
    {
        $this->values['DocTabZoneSpecifications'] = $docTabZoneSpecifications;
        return $this;
    }
}
