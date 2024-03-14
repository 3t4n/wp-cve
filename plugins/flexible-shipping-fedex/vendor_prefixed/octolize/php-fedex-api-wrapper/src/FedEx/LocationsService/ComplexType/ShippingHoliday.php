<?php

namespace FedExVendor\FedEx\LocationsService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * ShippingHoliday
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 *
 * @property Holiday $Holiday
 * @property \FedEx\LocationsService\SimpleType\ShippingActionType|string[] $UnavailableActions
 */
class ShippingHoliday extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ShippingHoliday';
    /**
     * Set Holiday
     *
     * @param Holiday $holiday
     * @return $this
     */
    public function setHoliday(\FedExVendor\FedEx\LocationsService\ComplexType\Holiday $holiday)
    {
        $this->values['Holiday'] = $holiday;
        return $this;
    }
    /**
     * Set UnavailableActions
     *
     * @param \FedEx\LocationsService\SimpleType\ShippingActionType[]|string[] $unavailableActions
     * @return $this
     */
    public function setUnavailableActions(array $unavailableActions)
    {
        $this->values['UnavailableActions'] = $unavailableActions;
        return $this;
    }
}
