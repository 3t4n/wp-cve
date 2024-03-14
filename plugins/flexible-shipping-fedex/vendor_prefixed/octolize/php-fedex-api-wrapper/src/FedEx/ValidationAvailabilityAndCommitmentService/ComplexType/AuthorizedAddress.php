<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies the address and a sequence number to identify the address.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property string $SequenceNumber
 * @property Address $Address
 */
class AuthorizedAddress extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'AuthorizedAddress';
    /**
     * FOR FEDEX INTERNAL USE ONLY. This the sequence number used by Customer Identity (CI) to identify the address.
     *
     * @param string $sequenceNumber
     * @return $this
     */
    public function setSequenceNumber($sequenceNumber)
    {
        $this->values['SequenceNumber'] = $sequenceNumber;
        return $this;
    }
    /**
     * Set Address
     *
     * @param Address $address
     * @return $this
     */
    public function setAddress(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\Address $address)
    {
        $this->values['Address'] = $address;
        return $this;
    }
}
