<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * ContactAndAddress
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
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
     * Set Contact
     *
     * @param Contact $contact
     * @return $this
     */
    public function setContact(\FedExVendor\FedEx\ShipService\ComplexType\Contact $contact)
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
    public function setAddress(\FedExVendor\FedEx\ShipService\ComplexType\Address $address)
    {
        $this->values['Address'] = $address;
        return $this;
    }
}
