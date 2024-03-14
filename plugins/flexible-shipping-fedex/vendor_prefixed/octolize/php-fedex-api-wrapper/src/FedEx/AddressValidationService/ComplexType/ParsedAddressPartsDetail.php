<?php

namespace FedExVendor\FedEx\AddressValidationService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * ParsedAddressPartsDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Address Validation Service
 *
 * @property ParsedStreetLineDetail $ParsedStreetLine
 * @property ParsedPostalCodeDetail $ParsedPostalCode
 */
class ParsedAddressPartsDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ParsedAddressPartsDetail';
    /**
     * Set ParsedStreetLine
     *
     * @param ParsedStreetLineDetail $parsedStreetLine
     * @return $this
     */
    public function setParsedStreetLine(\FedExVendor\FedEx\AddressValidationService\ComplexType\ParsedStreetLineDetail $parsedStreetLine)
    {
        $this->values['ParsedStreetLine'] = $parsedStreetLine;
        return $this;
    }
    /**
     * The postal code specified in a form that is supported by USPS as base, secondary and tertiary.
     *
     * @param ParsedPostalCodeDetail $parsedPostalCode
     * @return $this
     */
    public function setParsedPostalCode(\FedExVendor\FedEx\AddressValidationService\ComplexType\ParsedPostalCodeDetail $parsedPostalCode)
    {
        $this->values['ParsedPostalCode'] = $parsedPostalCode;
        return $this;
    }
}
