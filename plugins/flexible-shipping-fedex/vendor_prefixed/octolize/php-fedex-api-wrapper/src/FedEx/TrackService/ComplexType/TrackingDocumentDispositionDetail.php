<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * TrackingDocumentDispositionDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property \FedEx\TrackService\SimpleType\TrackingDocumentDispositionType|string $DispositionType
 * @property TrackingDocumentEmailDetail $EMailDetail
 * @property FaxDetail[] $FaxDetails
 */
class TrackingDocumentDispositionDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'TrackingDocumentDispositionDetail';
    /**
     * Set DispositionType
     *
     * @param \FedEx\TrackService\SimpleType\TrackingDocumentDispositionType|string $dispositionType
     * @return $this
     */
    public function setDispositionType($dispositionType)
    {
        $this->values['DispositionType'] = $dispositionType;
        return $this;
    }
    /**
     * Set EMailDetail
     *
     * @param TrackingDocumentEmailDetail $eMailDetail
     * @return $this
     */
    public function setEMailDetail(\FedExVendor\FedEx\TrackService\ComplexType\TrackingDocumentEmailDetail $eMailDetail)
    {
        $this->values['EMailDetail'] = $eMailDetail;
        return $this;
    }
    /**
     * Specifies the information used to fax the document.
     *
     * @param FaxDetail[] $faxDetails
     * @return $this
     */
    public function setFaxDetails(array $faxDetails)
    {
        $this->values['FaxDetails'] = $faxDetails;
        return $this;
    }
}
