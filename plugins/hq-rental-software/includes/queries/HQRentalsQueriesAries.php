<?php

namespace HQRentalsPlugin\HQRentalsQueries;

class HQRentalsQueriesAries
{
    protected $typeGlobalFrontEndName = 'hqRentalsAriesTypes';

    public function __construct()
    {
        $this->vehicleQueries = new HQRentalsQueriesVehicleClasses();
        add_action('wp_enqueue_scripts', array($this, 'registerAndEnqueueFrontEndAriesGlobalVariable'), 30);
    }

    public function registerAndEnqueueFrontEndAriesGlobalVariable()
    {
        $data = array();
        foreach ($this->vehicleQueries->getAllDifferentsValuesFromCustomField('f233') as $field) {
            $data[] = $field['meta_value'];
        }
        wp_localize_script('hq-dummy-script', $this->typeGlobalFrontEndName, $data);
    }
}
