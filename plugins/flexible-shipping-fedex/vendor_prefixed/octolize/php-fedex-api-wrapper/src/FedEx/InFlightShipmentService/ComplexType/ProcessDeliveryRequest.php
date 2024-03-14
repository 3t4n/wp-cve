<?php

namespace FedExVendor\FedEx\InFlightShipmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Request parameter of IFSS method to process a specific delivery option.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  In Flight Shipment Service
 *
 * @property WebAuthenticationDetail $WebAuthenticationDetail
 * @property ClientDetail $ClientDetail
 * @property UserDetail $UserDetail
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property string $ApplicationId
 * @property \FedEx\InFlightShipmentService\SimpleType\DeliveryActionType|string $ActionRequested
 * @property UniqueTrackingNumber $UniqueTrackingNumber
 * @property Contact $RecipientContact
 * @property Address $DestinationAddress
 * @property DeliveryRequestDetail $DeliveryRequestDetail
 */
class ProcessDeliveryRequest extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ProcessDeliveryRequest';
    /**
     * Descriptive data to be used in authentication of the sender's identity (and right to use FedEx web services).
     *
     * @param WebAuthenticationDetail $webAuthenticationDetail
     * @return $this
     */
    public function setWebAuthenticationDetail(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\WebAuthenticationDetail $webAuthenticationDetail)
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
    public function setClientDetail(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\ClientDetail $clientDetail)
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
    public function setUserDetail(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\UserDetail $userDetail)
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
    public function setTransactionDetail(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\TransactionDetail $transactionDetail)
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
    public function setVersion(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\VersionId $version)
    {
        $this->values['Version'] = $version;
        return $this;
    }
    /**
     * Set ApplicationId
     *
     * @param string $applicationId
     * @return $this
     */
    public function setApplicationId($applicationId)
    {
        $this->values['ApplicationId'] = $applicationId;
        return $this;
    }
    /**
     * Set ActionRequested
     *
     * @param \FedEx\InFlightShipmentService\SimpleType\DeliveryActionType|string $actionRequested
     * @return $this
     */
    public function setActionRequested($actionRequested)
    {
        $this->values['ActionRequested'] = $actionRequested;
        return $this;
    }
    /**
     * Set UniqueTrackingNumber
     *
     * @param UniqueTrackingNumber $uniqueTrackingNumber
     * @return $this
     */
    public function setUniqueTrackingNumber(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\UniqueTrackingNumber $uniqueTrackingNumber)
    {
        $this->values['UniqueTrackingNumber'] = $uniqueTrackingNumber;
        return $this;
    }
    /**
     * Set RecipientContact
     *
     * @param Contact $recipientContact
     * @return $this
     */
    public function setRecipientContact(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\Contact $recipientContact)
    {
        $this->values['RecipientContact'] = $recipientContact;
        return $this;
    }
    /**
     * Set DestinationAddress
     *
     * @param Address $destinationAddress
     * @return $this
     */
    public function setDestinationAddress(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\Address $destinationAddress)
    {
        $this->values['DestinationAddress'] = $destinationAddress;
        return $this;
    }
    /**
     * Set DeliveryRequestDetail
     *
     * @param DeliveryRequestDetail $deliveryRequestDetail
     * @return $this
     */
    public function setDeliveryRequestDetail(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\DeliveryRequestDetail $deliveryRequestDetail)
    {
        $this->values['DeliveryRequestDetail'] = $deliveryRequestDetail;
        return $this;
    }
}
