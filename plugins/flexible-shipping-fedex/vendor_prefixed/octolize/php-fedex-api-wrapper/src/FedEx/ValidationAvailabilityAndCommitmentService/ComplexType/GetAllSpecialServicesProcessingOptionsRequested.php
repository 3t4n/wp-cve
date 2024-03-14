<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * GetAllSpecialServicesProcessingOptionsRequested
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\GetAllSpecialServicesProcessingOptionType|string[] $Options
 */
class GetAllSpecialServicesProcessingOptionsRequested extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'GetAllSpecialServicesProcessingOptionsRequested';
    /**
     * FOR FEDEX INTERNAL USE ONLY: Processing options controlling availability and validation rules.
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\GetAllSpecialServicesProcessingOptionType[]|string[] $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->values['Options'] = $options;
        return $this;
    }
}
