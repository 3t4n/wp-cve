<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * TrackingDocumentSpecification
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property \FedEx\TrackService\SimpleType\TrackingDocumentType|string[] $DocumentTypes
 * @property TrackingBillOfLadingDocumentDetail $BillOfLadingDocumentDetail
 * @property TrackingFreightBillingDocumentDetail $FreightBillingDocumentDetail
 * @property TrackingSignatureProofOfDeliveryDetail $SignatureProofOfDeliveryDetail
 */
class TrackingDocumentSpecification extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'TrackingDocumentSpecification';
    /**
     * Set DocumentTypes
     *
     * @param \FedEx\TrackService\SimpleType\TrackingDocumentType[]|string[] $documentTypes
     * @return $this
     */
    public function setDocumentTypes(array $documentTypes)
    {
        $this->values['DocumentTypes'] = $documentTypes;
        return $this;
    }
    /**
     * Set BillOfLadingDocumentDetail
     *
     * @param TrackingBillOfLadingDocumentDetail $billOfLadingDocumentDetail
     * @return $this
     */
    public function setBillOfLadingDocumentDetail(\FedExVendor\FedEx\TrackService\ComplexType\TrackingBillOfLadingDocumentDetail $billOfLadingDocumentDetail)
    {
        $this->values['BillOfLadingDocumentDetail'] = $billOfLadingDocumentDetail;
        return $this;
    }
    /**
     * Set FreightBillingDocumentDetail
     *
     * @param TrackingFreightBillingDocumentDetail $freightBillingDocumentDetail
     * @return $this
     */
    public function setFreightBillingDocumentDetail(\FedExVendor\FedEx\TrackService\ComplexType\TrackingFreightBillingDocumentDetail $freightBillingDocumentDetail)
    {
        $this->values['FreightBillingDocumentDetail'] = $freightBillingDocumentDetail;
        return $this;
    }
    /**
     * Set SignatureProofOfDeliveryDetail
     *
     * @param TrackingSignatureProofOfDeliveryDetail $signatureProofOfDeliveryDetail
     * @return $this
     */
    public function setSignatureProofOfDeliveryDetail(\FedExVendor\FedEx\TrackService\ComplexType\TrackingSignatureProofOfDeliveryDetail $signatureProofOfDeliveryDetail)
    {
        $this->values['SignatureProofOfDeliveryDetail'] = $signatureProofOfDeliveryDetail;
        return $this;
    }
}
