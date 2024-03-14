<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * GetTrackingDocumentsRequest
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property WebAuthenticationDetail $WebAuthenticationDetail
 * @property ClientDetail $ClientDetail
 * @property UserDetail $UserDetail
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property TrackSelectionDetail[] $SelectionDetails
 * @property TrackingDocumentSpecification $TrackingDocumentSpecification
 */
class GetTrackingDocumentsRequest extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'GetTrackingDocumentsRequest';
    /**
     * Descriptive data to be used in authentication of the sender's identity (and right to use FedEx web services).
     *
     * @param WebAuthenticationDetail $webAuthenticationDetail
     * @return $this
     */
    public function setWebAuthenticationDetail(\FedExVendor\FedEx\TrackService\ComplexType\WebAuthenticationDetail $webAuthenticationDetail)
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
    public function setClientDetail(\FedExVendor\FedEx\TrackService\ComplexType\ClientDetail $clientDetail)
    {
        $this->values['ClientDetail'] = $clientDetail;
        return $this;
    }
    /**
     * Set UserDetail
     *
     * @param UserDetail $userDetail
     * @return $this
     */
    public function setUserDetail(\FedExVendor\FedEx\TrackService\ComplexType\UserDetail $userDetail)
    {
        $this->values['UserDetail'] = $userDetail;
        return $this;
    }
    /**
     * Set TransactionDetail
     *
     * @param TransactionDetail $transactionDetail
     * @return $this
     */
    public function setTransactionDetail(\FedExVendor\FedEx\TrackService\ComplexType\TransactionDetail $transactionDetail)
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
    public function setVersion(\FedExVendor\FedEx\TrackService\ComplexType\VersionId $version)
    {
        $this->values['Version'] = $version;
        return $this;
    }
    /**
     * Set SelectionDetails
     *
     * @param TrackSelectionDetail[] $selectionDetails
     * @return $this
     */
    public function setSelectionDetails(array $selectionDetails)
    {
        $this->values['SelectionDetails'] = $selectionDetails;
        return $this;
    }
    /**
     * Set TrackingDocumentSpecification
     *
     * @param TrackingDocumentSpecification $trackingDocumentSpecification
     * @return $this
     */
    public function setTrackingDocumentSpecification(\FedExVendor\FedEx\TrackService\ComplexType\TrackingDocumentSpecification $trackingDocumentSpecification)
    {
        $this->values['TrackingDocumentSpecification'] = $trackingDocumentSpecification;
        return $this;
    }
}
