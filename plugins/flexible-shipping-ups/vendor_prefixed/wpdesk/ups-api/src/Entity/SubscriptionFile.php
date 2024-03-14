<?php

namespace UpsFreeVendor\Ups\Entity;

class SubscriptionFile
{
    public $FileName;
    public $StatusType;
    public $Manifest;
    public $Origin;
    public $Exception;
    public $Delivery;
    public $Generic;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        $this->StatusType = new \UpsFreeVendor\Ups\Entity\StatusType();
        $this->Manifest = new \UpsFreeVendor\Ups\Entity\Manifest();
        $this->Origin = new \UpsFreeVendor\Ups\Entity\Origin();
        $this->Exception = new \UpsFreeVendor\Ups\Entity\Exception();
        $this->Delivery = new \UpsFreeVendor\Ups\Entity\Delivery();
        $this->Generic = new \UpsFreeVendor\Ups\Entity\Generic();
        if (null !== $response) {
            if (isset($response->FileName)) {
                $this->FileName = $response->FileName;
            }
            if (isset($response->StatusType)) {
                $this->StatusType = new \UpsFreeVendor\Ups\Entity\StatusType($response->StatusType);
            }
            if (isset($response->Manifest)) {
                $this->Manifest = new \UpsFreeVendor\Ups\Entity\Manifest($response->Manifest);
            }
            if (isset($response->Origin)) {
                $this->Origin = new \UpsFreeVendor\Ups\Entity\Origin($response->Origin);
            }
            if (isset($response->Exception)) {
                $this->Exception = new \UpsFreeVendor\Ups\Entity\Exception($response->Exception);
            }
            if (isset($response->Delivery)) {
                $this->Delivery = new \UpsFreeVendor\Ups\Entity\Delivery($response->Delivery);
            }
            if (isset($response->Generic)) {
                $this->Generic = new \UpsFreeVendor\Ups\Entity\Generic($response->Generic);
            }
        }
    }
}
