<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * BrokerDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\BrokerType|string $Type
 * @property Party $Broker
 * @property Payment $BrokerageProcessingChargesPayment
 */
class BrokerDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'BrokerDetail';
    /**
     * Set Type
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\BrokerType|string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->values['Type'] = $type;
        return $this;
    }
    /**
     * Set Broker
     *
     * @param Party $broker
     * @return $this
     */
    public function setBroker(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\Party $broker)
    {
        $this->values['Broker'] = $broker;
        return $this;
    }
    /**
     * Specifies details about how the charges for the brokerage processing are paid for.
     *
     * @param Payment $brokerageProcessingChargesPayment
     * @return $this
     */
    public function setBrokerageProcessingChargesPayment(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\Payment $brokerageProcessingChargesPayment)
    {
        $this->values['BrokerageProcessingChargesPayment'] = $brokerageProcessingChargesPayment;
        return $this;
    }
}
