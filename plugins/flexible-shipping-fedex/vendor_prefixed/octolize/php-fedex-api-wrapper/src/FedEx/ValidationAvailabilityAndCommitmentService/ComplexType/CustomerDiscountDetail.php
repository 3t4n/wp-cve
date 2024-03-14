<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CustomerDiscountDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property boolean $DiscountsApplied
 * @property DateOrTimeRange $EffectiveRange
 */
class CustomerDiscountDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CustomerDiscountDetail';
    /**
     * Set DiscountsApplied
     *
     * @param boolean $discountsApplied
     * @return $this
     */
    public function setDiscountsApplied($discountsApplied)
    {
        $this->values['DiscountsApplied'] = $discountsApplied;
        return $this;
    }
    /**
     * Set EffectiveRange
     *
     * @param DateOrTimeRange $effectiveRange
     * @return $this
     */
    public function setEffectiveRange(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\DateOrTimeRange $effectiveRange)
    {
        $this->values['EffectiveRange'] = $effectiveRange;
        return $this;
    }
}
