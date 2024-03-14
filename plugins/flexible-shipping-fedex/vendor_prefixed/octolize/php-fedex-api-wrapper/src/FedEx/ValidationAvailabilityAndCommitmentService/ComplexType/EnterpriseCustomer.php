<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * EnterpriseCustomer
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\CustomerDetailType|string $DetailLevel
 * @property CustomerShippingEnablementDetail $ShippingEnablementDetail
 * @property EnterpriseProfile $EnterpriseProfile
 * @property ExpressProfile $ExpressProfile
 * @property FreightProfile $FreightProfile
 * @property RecipientProfile $RecipientProfile
 */
class EnterpriseCustomer extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'EnterpriseCustomer';
    /**
     * Set DetailLevel
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\CustomerDetailType|string $detailLevel
     * @return $this
     */
    public function setDetailLevel($detailLevel)
    {
        $this->values['DetailLevel'] = $detailLevel;
        return $this;
    }
    /**
     * Set ShippingEnablementDetail
     *
     * @param CustomerShippingEnablementDetail $shippingEnablementDetail
     * @return $this
     */
    public function setShippingEnablementDetail(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\CustomerShippingEnablementDetail $shippingEnablementDetail)
    {
        $this->values['ShippingEnablementDetail'] = $shippingEnablementDetail;
        return $this;
    }
    /**
     * Set EnterpriseProfile
     *
     * @param EnterpriseProfile $enterpriseProfile
     * @return $this
     */
    public function setEnterpriseProfile(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\EnterpriseProfile $enterpriseProfile)
    {
        $this->values['EnterpriseProfile'] = $enterpriseProfile;
        return $this;
    }
    /**
     * Set ExpressProfile
     *
     * @param ExpressProfile $expressProfile
     * @return $this
     */
    public function setExpressProfile(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\ExpressProfile $expressProfile)
    {
        $this->values['ExpressProfile'] = $expressProfile;
        return $this;
    }
    /**
     * Set FreightProfile
     *
     * @param FreightProfile $freightProfile
     * @return $this
     */
    public function setFreightProfile(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\FreightProfile $freightProfile)
    {
        $this->values['FreightProfile'] = $freightProfile;
        return $this;
    }
    /**
     * Set RecipientProfile
     *
     * @param RecipientProfile $recipientProfile
     * @return $this
     */
    public function setRecipientProfile(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\RecipientProfile $recipientProfile)
    {
        $this->values['RecipientProfile'] = $recipientProfile;
        return $this;
    }
}
