<?php

namespace FedExVendor\FedEx\AddressValidationService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * AddressToValidate
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Address Validation Service
 *
 * @property string $ClientReferenceId
 * @property Contact $Contact
 * @property Address $Address
 */
class AddressToValidate extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'AddressToValidate';
    /**
     * A reference id provided by the client.
     *
     * @param string $clientReferenceId
     * @return $this
     */
    public function setClientReferenceId($clientReferenceId)
    {
        $this->values['ClientReferenceId'] = $clientReferenceId;
        return $this;
    }
    /**
     * Set Contact
     *
     * @param Contact $contact
     * @return $this
     */
    public function setContact(\FedExVendor\FedEx\AddressValidationService\ComplexType\Contact $contact)
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
    public function setAddress(\FedExVendor\FedEx\AddressValidationService\ComplexType\Address $address)
    {
        $this->values['Address'] = $address;
        return $this;
    }
}
