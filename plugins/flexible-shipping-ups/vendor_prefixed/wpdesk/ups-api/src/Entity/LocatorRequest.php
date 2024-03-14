<?php

namespace UpsFreeVendor\Ups\Entity;

class LocatorRequest
{
    /**
     * @var
     */
    private $originAddress;
    /**
     * @var
     */
    private $translate;
    /**
     * @var LocationSearchCriteria
     */
    private $locationSearchCriteria;
    /**
     * @var
     */
    private $unitOfMeasurement;
    public function __construct()
    {
        $this->setOriginAddress(new \UpsFreeVendor\Ups\Entity\OriginAddress());
        $this->setTranslate(new \UpsFreeVendor\Ups\Entity\Translate());
    }
    /**
     * @return LocationSearchCriteria
     */
    public function getLocationSearchCriteria()
    {
        return $this->locationSearchCriteria;
    }
    /**
     * @param LocationSearchCriteria $locationSearchCriteria
     */
    public function setLocationSearchCriteria($locationSearchCriteria)
    {
        $this->locationSearchCriteria = $locationSearchCriteria;
    }
    /**
     * @return mixed
     */
    public function getOriginAddress()
    {
        return $this->originAddress;
    }
    /**
     * @param mixed $originAddress
     */
    public function setOriginAddress(\UpsFreeVendor\Ups\Entity\OriginAddress $originAddress)
    {
        $this->originAddress = $originAddress;
    }
    /**
     * @return mixed
     */
    public function getTranslate()
    {
        return $this->translate;
    }
    /**
     * @param mixed $translate
     */
    public function setTranslate(\UpsFreeVendor\Ups\Entity\Translate $translate)
    {
        $this->translate = $translate;
    }
    /**
     * @return mixed
     */
    public function getUnitOfMeasurement()
    {
        return $this->unitOfMeasurement;
    }
    /**
     * @param mixed $unitOfMeasurement
     */
    public function setUnitOfMeasurement(\UpsFreeVendor\Ups\Entity\UnitOfMeasurement $unitOfMeasurement)
    {
        $this->unitOfMeasurement = $unitOfMeasurement;
    }
}
