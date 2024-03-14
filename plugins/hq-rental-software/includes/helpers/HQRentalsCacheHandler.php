<?php

namespace HQRentalsPlugin\HQRentalsHelpers;

class HQRentalsCacheHandler
{
    private static $vehiclesQueryKey = 'hq_vehicles_classes_cache';
    private static $cacheExpiration = 60;

    public function addDataToCache($key, $data)
    {
        return set_transient($key, $data, HQRentalsCacheHandler::$cacheExpiration);
    }

    public function addVehiclesClassesToCache()
    {
        $query = new HQRentalsCacheableData();
        return $this->addDataToCache(HQRentalsCacheHandler::$vehiclesQueryKey, $query->vehicleCacheData());
    }

    public function getDataFromCache($key)
    {
        $cacheData = get_transient($key);
        if ($cacheData) {
            return $cacheData;
        }
        return false;
    }

    public function getVehicleClassesFromCache()
    {
        return $this->getDataFromCache(HQRentalsCacheHandler::$vehiclesQueryKey);
    }
}
