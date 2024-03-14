<?php

namespace HQRentalsPlugin\HQRentalsApihelpers;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsVendor\Carbon;
use HQRentalsPlugin\HQRentalsWebhooks\HQRentalsWebsiteEndpoints;

class HQRentalsApihelpersAvailability
{
    protected static $systemFormat = 'Y-m-d H:i';

    public function __construct()
    {
        $this->connector = new HQRentalsApiConnector();
        $this->vehicleClassQuery = new HQRentalsQueriesVehicleClasses();
        $this->websiteEndpoints = new HQRentalsWebsiteEndpoints();
    }

    public static function getAvailability($startDate = '', $endDate = '', $brandId = '1')
    {
        $data = array(
            'start_date' => $startDate,
            'end_date' => $endDate,
            'brand_id' => $brandId
        );
        $connector = new HQRentalsApiConnector();
        return $connector->getHQRentalsAvailability($data);
    }

    public function getAvailabilityFromDates($data)
    {
        try {
            $connector = new HQRentalsApiConnector();
            $response = $connector->getVehiclesAvailabilityDates($data);
            if ($response->success) {
                if ($response->data->applicable_classes) {
                    return $this->websiteEndpoints->resolveResponse((object)[
                        'vehicles' => $this->vehicleClassQuery->vehiclesPublicInterfaceFromHQDatesApi($response),
                    ], true);
                } else {
                    return [];
                }
            }
        } catch (\Throwable $e) {
            return $this->resolveResponse($e, false);
        }
    }

    public function getMonthlyAvailability($vehicleClassId)
    {
        $now = Carbon::now()->format("Y-m-d");
        $month = Carbon::now()->addDay(30)->format("Y-m-d");
        $time = '12:00';
        $location = '1';
        $data = [
            'pick_up_location' => $location,
            'return_location' => $location,
            'pick_up_time' => $time,
            'return_time' => $time,
            'pick_up_date' => $now,
            'return_date' => $month
        ];
        return $this->getAvailabilityFromDates($data);
    }
}
