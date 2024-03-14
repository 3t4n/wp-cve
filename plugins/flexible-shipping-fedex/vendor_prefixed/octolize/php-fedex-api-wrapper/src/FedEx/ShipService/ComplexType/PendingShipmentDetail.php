<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * This information describes the kind of pending shipment being requested.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property \FedEx\ShipService\SimpleType\PendingShipmentType|string $Type
 * @property string $ExpirationDate
 * @property EMailLabelDetail $EmailLabelDetail
 * @property PendingShipmentProcessingOptionsRequested $ProcessingOptions
 * @property RecommendedDocumentSpecification $RecommendedDocumentSpecification
 * @property UploadDocumentReferenceDetail[] $DocumentReferences
 */
class PendingShipmentDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'PendingShipmentDetail';
    /**
     * Set Type
     *
     * @param \FedEx\ShipService\SimpleType\PendingShipmentType|string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->values['Type'] = $type;
        return $this;
    }
    /**
     * Date after which the pending shipment will no longer be available for completion.
     *
     * @param string $expirationDate
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        $this->values['ExpirationDate'] = $expirationDate;
        return $this;
    }
    /**
     * Only used with type of EMAIL.
     *
     * @param EMailLabelDetail $emailLabelDetail
     * @return $this
     */
    public function setEmailLabelDetail(\FedExVendor\FedEx\ShipService\ComplexType\EMailLabelDetail $emailLabelDetail)
    {
        $this->values['EmailLabelDetail'] = $emailLabelDetail;
        return $this;
    }
    /**
     * Set ProcessingOptions
     *
     * @param PendingShipmentProcessingOptionsRequested $processingOptions
     * @return $this
     */
    public function setProcessingOptions(\FedExVendor\FedEx\ShipService\ComplexType\PendingShipmentProcessingOptionsRequested $processingOptions)
    {
        $this->values['ProcessingOptions'] = $processingOptions;
        return $this;
    }
    /**
     * These are documents that are recommended to be included with the shipment.
     *
     * @param RecommendedDocumentSpecification $recommendedDocumentSpecification
     * @return $this
     */
    public function setRecommendedDocumentSpecification(\FedExVendor\FedEx\ShipService\ComplexType\RecommendedDocumentSpecification $recommendedDocumentSpecification)
    {
        $this->values['RecommendedDocumentSpecification'] = $recommendedDocumentSpecification;
        return $this;
    }
    /**
     * Upload document details provided by the initator of the shipment.
     *
     * @param UploadDocumentReferenceDetail[] $documentReferences
     * @return $this
     */
    public function setDocumentReferences(array $documentReferences)
    {
        $this->values['DocumentReferences'] = $documentReferences;
        return $this;
    }
}
