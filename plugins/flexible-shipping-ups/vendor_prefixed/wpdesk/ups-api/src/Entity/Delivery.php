<?php

namespace UpsFreeVendor\Ups\Entity;

class Delivery
{
    public $PackageReferenceNumber;
    public $ShipmentReferenceNumber;
    public $TrackingNumber;
    public $ShipperNumber;
    public $Date;
    public $Time;
    public $DriverRelease;
    public $ActivityLocation;
    public $DeliveryLocation;
    public $COD;
    public $BillToAccount;
    public function __construct($response = null)
    {
        $this->ShipmentReferenceNumber = new \UpsFreeVendor\Ups\Entity\ShipmentReferenceNumber();
        $this->PackageReferenceNumber = new \UpsFreeVendor\Ups\Entity\PackageReferenceNumber();
        $this->ActivityLocation = new \UpsFreeVendor\Ups\Entity\ActivityLocation();
        $this->DeliveryLocation = new \UpsFreeVendor\Ups\Entity\DeliveryLocation();
        $this->COD = new \UpsFreeVendor\Ups\Entity\COD();
        $this->BillToAccount = new \UpsFreeVendor\Ups\Entity\BillToAccount();
        if (null !== $response) {
            if (isset($response->PackageReferenceNumber)) {
                if (\is_array($response->PackageReferenceNumber)) {
                    foreach ($response->PackageReferenceNumber as $PackageReferenceNumber) {
                        $this->PackageReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\PackageReferenceNumber($PackageReferenceNumber);
                    }
                } else {
                    $this->PackageReferenceNumber[] = new \UpsFreeVendor\Ups\Entity\PackageReferenceNumber($response->PackageReferenceNumber);
                }
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
            if (isset($response->TrackingNumber)) {
                $this->TrackingNumber = $response->TrackingNumber;
            }
            if (isset($response->ShipperNumber)) {
                $this->ShipperNumber = $response->ShipperNumber;
            }
            if (isset($response->Date)) {
                $this->Date = $response->Date;
            }
            if (isset($response->Time)) {
                $this->Time = $response->Time;
            }
            if (isset($response->DriverRelease)) {
                $this->DriverRelease = $response->DriverRelease;
            }
            if (isset($response->ActivityLocation)) {
                $this->ActivityLocation = new \UpsFreeVendor\Ups\Entity\ActivityLocation($response->ActivityLocation);
            }
            if (isset($response->DeliveryLocation)) {
                $this->DeliveryLocation = new \UpsFreeVendor\Ups\Entity\DeliveryLocation($response->DeliveryLocation);
            }
            if (isset($response->COD)) {
                $this->COD = new \UpsFreeVendor\Ups\Entity\COD($response->COD);
            }
            if (isset($response->BillToAccount)) {
                $this->BillToAccount = new \UpsFreeVendor\Ups\Entity\BillToAccount($response->BillToAccount);
            }
        }
    }
}
