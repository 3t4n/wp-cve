<?php

namespace UpsFreeVendor\Ups\Entity;

class DimensionalWeight
{
    /**
     * @var UnitOfMeasurement
     */
    public $UnitOfMeasurement;
    public $Weight;
    public function __construct()
    {
        $this->UnitOfMeasurement = new \UpsFreeVendor\Ups\Entity\UnitOfMeasurement();
    }
}
