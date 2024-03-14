<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Describes the Franchisee relationship(s) of a customer account.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property string[] $CountryCodes
 */
class CustomerAccountFranchiseeDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CustomerAccountFranchiseeDetail';
    /**
     * Specifies the country codes in which the customer account has a relationship with a Franchisee.
     *
     * @param string $countryCodes
     * @return $this
     */
    public function setCountryCodes($countryCodes)
    {
        $this->values['CountryCodes'] = $countryCodes;
        return $this;
    }
}
