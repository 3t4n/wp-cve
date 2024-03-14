<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * TrackingSignatureProofOfDeliveryDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property TrackingDocumentFormat $DocumentFormat
 */
class TrackingSignatureProofOfDeliveryDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'TrackingSignatureProofOfDeliveryDetail';
    /**
     * Set DocumentFormat
     *
     * @param TrackingDocumentFormat $documentFormat
     * @return $this
     */
    public function setDocumentFormat(\FedExVendor\FedEx\TrackService\ComplexType\TrackingDocumentFormat $documentFormat)
    {
        $this->values['DocumentFormat'] = $documentFormat;
        return $this;
    }
}
