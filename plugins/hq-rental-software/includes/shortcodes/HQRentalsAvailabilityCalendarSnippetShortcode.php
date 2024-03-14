<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;

class HQRentalsAvailabilityCalendarSnippetShortcode extends HQBaseShortcode
{
    public function __construct()
    {
        $this->brand = new HQRentalsModelsBrand();
        $this->vehicleClass = new HQRentalsQueriesVehicleClasses();
        add_shortcode('hq_rentals_vehicle_calendar_snippet', array( $this, 'calendarShortcode' ));
    }

    public function calendarShortcode($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'id'                =>  '',
                'vehicle_class_id'  => '',
                'forced_locale'     =>  '',
            ),
            $atts
        );
        ob_start();
        $brandId = $atts['id'];
        try {
            if ($brandId) {
                $brand = new HQRentalsModelsBrand();
                $brand->findBySystemId($atts['id']);
                $vehicle = $this->vehicleClass->getVehicleClassBySystemId($atts['vehicle_class_id']);
                $uuid = $vehicle->getUUID();
                return $this->filledSnippetData($brand->getCalendarClassSnippet(), array(
                    'class' => $uuid,
                    'forced_locale' => ($atts['forced_locale']) ? $atts['forced_locale'] : 'en'
                ));
            }
            return '';
        } catch (\Throwable $e) {
            return '';
        }
    }
}
