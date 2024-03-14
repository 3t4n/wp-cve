<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies details for origin-country LTL services performed by FedEx.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property Payment $Payment
 * @property string $LtlScacCode
 */
class TransborderDistributionLtlDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'TransborderDistributionLtlDetail';
    /**
     * Payment for LTL transportation.
     *
     * @param Payment $payment
     * @return $this
     */
    public function setPayment(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\Payment $payment)
    {
        $this->values['Payment'] = $payment;
        return $this;
    }
    /**
     * Standard Carrier Alpha Code for origin-country LTL services.
     *
     * @param string $ltlScacCode
     * @return $this
     */
    public function setLtlScacCode($ltlScacCode)
    {
        $this->values['LtlScacCode'] = $ltlScacCode;
        return $this;
    }
}
