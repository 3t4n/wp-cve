<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies special or custom features to be applied during the processing of the enclosing RequestedShipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\ShipmentProcessingOptionType|string[] $Options
 * @property CustomTransitTimeDetail $CustomTransitTimeDetail
 */
class ShipmentProcessingOptionsRequested extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ShipmentProcessingOptionsRequested';
    /**
     * Identifies options to be applied.
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\ShipmentProcessingOptionType[]|string[] $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->values['Options'] = $options;
        return $this;
    }
    /**
     * Specifies custom transit time to be applied to the shipment.
     *
     * @param CustomTransitTimeDetail $customTransitTimeDetail
     * @return $this
     */
    public function setCustomTransitTimeDetail(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\CustomTransitTimeDetail $customTransitTimeDetail)
    {
        $this->values['CustomTransitTimeDetail'] = $customTransitTimeDetail;
        return $this;
    }
}
