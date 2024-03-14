<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies a single type of weighing performed on a shipment
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property \FedEx\PickupService\SimpleType\WeighingScaleType|string $Type
 */
class WeighingDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'WeighingDetail';
    /**
     * Type of scale used
     *
     * @param \FedEx\PickupService\SimpleType\WeighingScaleType|string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->values['Type'] = $type;
        return $this;
    }
}
