<?php

namespace UpsFreeVendor\Ups\Entity;

class LabelRecoveryRequest
{
    public $LabelSpecification;
    public $Translate;
    public $LabelDelivery;
    public $TrackingNumber;
    public $ReferenceNumber;
    public $ShipperNumber;
    public function __construct()
    {
        $this->LabelSpecification = new \UpsFreeVendor\Ups\Entity\LabelSpecification();
        $this->Translate = new \UpsFreeVendor\Ups\Entity\Translate();
        $this->LabelDelivery = new \UpsFreeVendor\Ups\Entity\LabelDelivery();
        $this->ReferenceNumber = new \UpsFreeVendor\Ups\Entity\ReferenceNumber();
    }
}
