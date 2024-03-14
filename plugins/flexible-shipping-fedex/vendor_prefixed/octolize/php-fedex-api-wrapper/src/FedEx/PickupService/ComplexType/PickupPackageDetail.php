<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * PickupPackageDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property string[] $PackageSpecialServices
 * @property SignatureOptionDetail $SignatureOptionDetail
 * @property Weight $Weight
 * @property Money $InsuredValue
 * @property string $Description
 * @property CustomerReference[] $CustomerReferences
 */
class PickupPackageDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'PickupPackageDetail';
    /**
     * Set PackageSpecialServices
     *
     * @param string $packageSpecialServices
     * @return $this
     */
    public function setPackageSpecialServices($packageSpecialServices)
    {
        $this->values['PackageSpecialServices'] = $packageSpecialServices;
        return $this;
    }
    /**
     * Set SignatureOptionDetail
     *
     * @param SignatureOptionDetail $signatureOptionDetail
     * @return $this
     */
    public function setSignatureOptionDetail(\FedExVendor\FedEx\PickupService\ComplexType\SignatureOptionDetail $signatureOptionDetail)
    {
        $this->values['SignatureOptionDetail'] = $signatureOptionDetail;
        return $this;
    }
    /**
     * Set Weight
     *
     * @param Weight $weight
     * @return $this
     */
    public function setWeight(\FedExVendor\FedEx\PickupService\ComplexType\Weight $weight)
    {
        $this->values['Weight'] = $weight;
        return $this;
    }
    /**
     * Set InsuredValue
     *
     * @param Money $insuredValue
     * @return $this
     */
    public function setInsuredValue(\FedExVendor\FedEx\PickupService\ComplexType\Money $insuredValue)
    {
        $this->values['InsuredValue'] = $insuredValue;
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
     * Set CustomerReferences
     *
     * @param CustomerReference[] $customerReferences
     * @return $this
     */
    public function setCustomerReferences(array $customerReferences)
    {
        $this->values['CustomerReferences'] = $customerReferences;
        return $this;
    }
}
