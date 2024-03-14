<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * AncillaryFeeAndTax
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property \FedEx\ShipService\SimpleType\AncillaryFeeAndTaxType|string $Type
 * @property string $Description
 * @property Money $Amount
 */
class AncillaryFeeAndTax extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'AncillaryFeeAndTax';
    /**
     * Set Type
     *
     * @param \FedEx\ShipService\SimpleType\AncillaryFeeAndTaxType|string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->values['Type'] = $type;
        return $this;
    }
    /**
     * Set Description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->values['Description'] = $description;
        return $this;
    }
    /**
     * Set Amount
     *
     * @param Money $amount
     * @return $this
     */
    public function setAmount(\FedExVendor\FedEx\ShipService\ComplexType\Money $amount)
    {
        $this->values['Amount'] = $amount;
        return $this;
    }
}
