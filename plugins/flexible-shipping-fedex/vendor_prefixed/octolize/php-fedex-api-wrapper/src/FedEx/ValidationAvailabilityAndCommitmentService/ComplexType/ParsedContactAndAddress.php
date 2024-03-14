<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * This type contains equivalent data to ContactAndAddress, but uses a form of person name with separate first, middle and last names.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property ParsedContact $Contact
 * @property Address $Address
 */
class ParsedContactAndAddress extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ParsedContactAndAddress';
    /**
     * Set Contact
     *
     * @param ParsedContact $contact
     * @return $this
     */
    public function setContact(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\ParsedContact $contact)
    {
        $this->values['Contact'] = $contact;
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
