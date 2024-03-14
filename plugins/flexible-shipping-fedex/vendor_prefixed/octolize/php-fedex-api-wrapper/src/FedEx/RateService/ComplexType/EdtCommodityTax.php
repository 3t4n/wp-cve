<?php

namespace FedExVendor\FedEx\RateService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * EdtCommodityTax
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Rate Service
 *
 * @property string $HarmonizedCode
 * @property EdtTaxDetail[] $Taxes
 * @property Money $Total
 */
class EdtCommodityTax extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'EdtCommodityTax';
    /**
     * Set HarmonizedCode
     *
     * @param string $harmonizedCode
     * @return $this
     */
    public function setHarmonizedCode($harmonizedCode)
    {
        $this->values['HarmonizedCode'] = $harmonizedCode;
        return $this;
    }
    /**
     * Set Taxes
     *
     * @param EdtTaxDetail[] $taxes
     * @return $this
     */
    public function setTaxes(array $taxes)
    {
        $this->values['Taxes'] = $taxes;
        return $this;
    }
    /**
     * Set Total
     *
     * @param Money $total
     * @return $this
     */
    public function setTotal(\FedExVendor\FedEx\RateService\ComplexType\Money $total)
    {
        $this->values['Total'] = $total;
        return $this;
    }
}
