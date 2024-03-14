<?php

namespace UpsFreeVendor\Ups\Entity;

class Exception
{
    public $PackageReferenceNumber;
    public $ShipmentReferenceNumber;
    public $TrackingNumber;
    public $Date;
    public $Time;
    public $UpdatedAddress;
    public $StatusCode;
    public $StatusDescription;
    public $ReasonCode;
    public $ReasonDescription;
    public $Resolution;
    public $RescheduledDeliveryDate;
    public $RescheduledDeliveryTime;
    public $ActivityLocation;
    public $BillToAccount;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        $this->PackageReferenceNumber = [];
        $this->ShipmentReferenceNumber = [];
        $this->Resolution = new \UpsFreeVendor\Ups\Entity\Resolution();
        $this->ActivityLocation = new \UpsFreeVendor\Ups\Entity\ActivityLocation();
        $this->BillToAccount = new \UpsFreeVendor\Ups\Entity\BillToAccount();
        if (null !== $response) {
            if (isset($response->PackageReferenceNumber)) {
                foreach ($response->PackageReferenceNumber as $PackageReferenceNumber) {
                    $this->PackageReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\PackageReferenceNumber($PackageReferenceNumber);
                }
            }
            if (isset($response->ShipmentReferenceNumber)) {
                foreach ($response->ShipmentReferenceNumber as $ShipmentReferenceNumber) {
                    $this->ShipmentReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\ShipmentReferenceNumber($ShipmentReferenceNumber);
                }
            }
            if (isset($response->TrackingNumber)) {
                $this->TrackingNumber = $response->TrackingNumber;
            }
            if (isset($response->Date)) {
                $this->Date = $response->Date;
            }
            if (isset($response->Time)) {
                $this->Time = $response->Time;
            }
            if (isset($response->UpdatedAddress)) {
                $this->UpdatedAddress = new \UpsFreeVendor\Ups\Entity\UpdatedAddress($response->UpdatedAddress);
            }
            if (isset($response->StatusCode)) {
                $this->StatusCode = $response->StatusCode;
            }
            if (isset($response->StatusDescription)) {
                $this->StatusDescription = $response->StatusDescription;
            }
            if (isset($response->ReasonCode)) {
                $this->ReasonCode = $response->ReasonCode;
            }
            if (isset($response->ReasonDescription)) {
                $this->ReasonDescription = $response->ReasonDescription;
            }
            if (isset($response->Resolution)) {
                $this->Resolution = new \UpsFreeVendor\Ups\Entity\Resolution($response->Resolution);
            }
            if (isset($response->RescheduledDeliveryDate)) {
                $this->RescheduledDeliveryDate = $response->RescheduledDeliveryDate;
            }
            if (isset($response->RescheduledDeliveryTime)) {
                $this->RescheduledDeliveryTime = $response->RescheduledDeliveryTime;
            }
            if (isset($response->ActivityLocation)) {
                $this->ActivityLocation = new \UpsFreeVendor\Ups\Entity\ActivityLocation($response->ActivityLocation);
            }
            if (isset($response->BillToAccount)) {
                $this->BillToAccount = new \UpsFreeVendor\Ups\Entity\BillToAccount($response->BillToAccount);
            }
        }
    }
}
