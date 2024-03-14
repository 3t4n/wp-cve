<?php

namespace FedExVendor\FedEx\CloseService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * ReprintGroundCloseDocumentsRequest
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Close Service
 *
 * @property WebAuthenticationDetail $WebAuthenticationDetail
 * @property ClientDetail $ClientDetail
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property \FedEx\CloseService\SimpleType\ReprintGroundCloseDocumentsOptionType|string $ReprintOption
 * @property string $CloseDate
 * @property string $TrackingNumber
 * @property CloseDocumentSpecification $CloseDocumentSpecification
 */
class ReprintGroundCloseDocumentsRequest extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ReprintGroundCloseDocumentsRequest';
    /**
     * Descriptive data to be used in authentication of the sender's identity (and right to use FedEx web services).
     *
     * @param WebAuthenticationDetail $webAuthenticationDetail
     * @return $this
     */
    public function setWebAuthenticationDetail(\FedExVendor\FedEx\CloseService\ComplexType\WebAuthenticationDetail $webAuthenticationDetail)
    {
        $this->values['WebAuthenticationDetail'] = $webAuthenticationDetail;
        return $this;
    }
    /**
     * Set ClientDetail
     *
     * @param ClientDetail $clientDetail
     * @return $this
     */
    public function setClientDetail(\FedExVendor\FedEx\CloseService\ComplexType\ClientDetail $clientDetail)
    {
        $this->values['ClientDetail'] = $clientDetail;
        return $this;
    }
    /**
     * Set TransactionDetail
     *
     * @param TransactionDetail $transactionDetail
     * @return $this
     */
    public function setTransactionDetail(\FedExVendor\FedEx\CloseService\ComplexType\TransactionDetail $transactionDetail)
    {
        $this->values['TransactionDetail'] = $transactionDetail;
        return $this;
    }
    /**
     * Set Version
     *
     * @param VersionId $version
     * @return $this
     */
    public function setVersion(\FedExVendor\FedEx\CloseService\ComplexType\VersionId $version)
    {
        $this->values['Version'] = $version;
        return $this;
    }
    /**
     * Set ReprintOption
     *
     * @param \FedEx\CloseService\SimpleType\ReprintGroundCloseDocumentsOptionType|string $reprintOption
     * @return $this
     */
    public function setReprintOption($reprintOption)
    {
        $this->values['ReprintOption'] = $reprintOption;
        return $this;
    }
    /**
     * Date on which shipments were closed
     *
     * @param string $closeDate
     * @return $this
     */
    public function setCloseDate($closeDate)
    {
        $this->values['CloseDate'] = $closeDate;
        return $this;
    }
    /**
     * Set TrackingNumber
     *
     * @param string $trackingNumber
     * @return $this
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->values['TrackingNumber'] = $trackingNumber;
        return $this;
    }
    /**
     * Specifies characteristics of document(s) to be returned for this request.
     *
     * @param CloseDocumentSpecification $closeDocumentSpecification
     * @return $this
     */
    public function setCloseDocumentSpecification(\FedExVendor\FedEx\CloseService\ComplexType\CloseDocumentSpecification $closeDocumentSpecification)
    {
        $this->values['CloseDocumentSpecification'] = $closeDocumentSpecification;
        return $this;
    }
}
