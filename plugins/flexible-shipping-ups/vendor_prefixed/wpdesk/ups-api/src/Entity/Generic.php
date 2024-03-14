<?php

namespace UpsFreeVendor\Ups\Entity;

class Generic
{
    const AT_VOIDFORMANIFEST = 'VM';
    const AT_UNDELIVERABLERETURNS = 'UR';
    const AT_INVOICEREMOVALSUCCESSFUL = 'IR';
    const AT_TRANSPORTCOMPANYUSPSSCAN = 'TC';
    const AT_POSTALSERVICEPOSSESSIONSCAN = 'PS';
    const AT_UPSEMAILNOTIFICATIONFAILURE = 'FN';
    const AT_DESTINATIONSCAN = 'DS';
    public $ActivityType;
    public $TrackingNumber;
    public $ShipperNumber;
    public $ShipmentReferenceNumber;
    public $PackageReferenceNumber;
    public $Service;
    public $Activity;
    public $BillToAccount;
    public $ShipTo;
    public $RescheduledDeliveryDate;
    public $FailureNotification;
    public $Bookmark;
    public function __construct($response = null)
    {
        $this->ShipmentReferenceNumber = new \UpsFreeVendor\Ups\Entity\ShipmentReferenceNumber();
        $this->PackageReferenceNumber = new \UpsFreeVendor\Ups\Entity\PackageReferenceNumber();
        $this->Service = new \UpsFreeVendor\Ups\Entity\Service();
        $this->Activity = new \UpsFreeVendor\Ups\Entity\Activity();
        $this->BillToAccount = new \UpsFreeVendor\Ups\Entity\BillToAccount();
        $this->ShipTo = new \UpsFreeVendor\Ups\Entity\ShipTo();
        $this->FailureNotification = new \UpsFreeVendor\Ups\Entity\FailureNotification();
        if (null !== $response) {
            if (isset($response->ActivityType)) {
                $this->ActivityType = $response->ActivityType;
            }
            if (isset($response->TrackingNumber)) {
                $this->TrackingNumber = $response->TrackingNumber;
            }
            if (isset($response->ShipperNumber)) {
                $this->ShipperNumber = $response->ShipperNumber;
            }
            if (isset($response->ShipmentReferenceNumber)) {
                if (\is_array($response->ShipmentReferenceNumber)) {
                    foreach ($response->ShipmentReferenceNumber as $ShipmentReferenceNumber) {
                        $this->ShipmentReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\ShipmentReferenceNumber($ShipmentReferenceNumber);
                    }
                } else {
                    $this->ShipmentReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\ShipmentReferenceNumber($response->ShipmentReferenceNumber);
                }
            }
            if (isset($response->PackageReferenceNumber)) {
                if (\is_array($response->PackageReferenceNumber)) {
                    foreach ($response->PackageReferenceNumber as $PackageReferenceNumber) {
                        $this->PackageReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\PackageReferenceNumber($PackageReferenceNumber);
                    }
                } else {
                    $this->PackageReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\PackageReferenceNumber($response->PackageReferenceNumber);
                }
            }
            if (isset($response->Service)) {
                $this->Service->setCode($response->Service->Code);
            }
            if (isset($response->Activity)) {
                $this->Activity = new \UpsFreeVendor\Ups\Entity\Activity($response->Activity);
            }
            if (isset($response->BillToAccount)) {
                $this->BillToAccount = new \UpsFreeVendor\Ups\Entity\BillToAccount($response->BillToAccount);
            }
            if (isset($response->ShipTo)) {
                $this->ShipTo = new \UpsFreeVendor\Ups\Entity\ShipTo($response->ShipTo);
            }
            if (isset($response->RescheduledDeliveryDate)) {
                $this->RescheduledDeliveryDate = $response->RescheduledDeliveryDate;
            }
            if (isset($response->FailureNotification)) {
                $this->FailureNotification = new \UpsFreeVendor\Ups\Entity\FailureNotification($response->FailureNotification);
            }
            if (isset($response->Bookmark)) {
                $this->Bookmark = $response->Bookmark;
            }
        }
    }
}
