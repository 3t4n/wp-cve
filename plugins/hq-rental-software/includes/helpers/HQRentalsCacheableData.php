<?php

namespace HQRentalsPlugin\HQRentalsHelpers;

use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;

class HQRentalsCacheableData
{
    public function __construct()
    {
        $this->query = new HQRentalsQueriesVehicleClasses();
    }

    public function vehicleCacheData()
    {
        return $this->query->allVehiclesByRate();
    }
}
