<?php

namespace HQRentalsPlugin\HQRentalsWebhooks;

class HQRentalsApiClientAdapter
{
    public static function adaptDataForAvailability($data)
    {
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $brandID = $data['brand_id'];
        return array(
            'start_date' => $startDate,
            'end_date' => $endDate,
            'brand_id' => $brandID
        );
    }
}
