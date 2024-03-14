<?php

namespace FedExVendor\FedEx\CourierDispatchService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * The descriptive data for a person or company entitiy doing business with FedEx.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Courier Dispatch Service
 *
 * @property Contact $Contact
 * @property Address $Address
 */
class ContactAndAddress extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ContactAndAddress';
    /**
     * Descriptive data identifying the point-of-contact person.
     *
     * @param Contact $contact
     * @return $this
     */
    public function setContact(\FedExVendor\FedEx\CourierDispatchService\ComplexType\Contact $contact)
    {
        $this->values['Contact'] = $contact;
        return $this;
    }
    /**
     * The descriptive data for a physical location.
     *
     * @param Address $address
     * @return $this
     */
    public function setAddress(\FedExVendor\FedEx\CourierDispatchService\ComplexType\Address $address)
    {
        $this->values['Address'] = $address;
        return $this;
    }
}
