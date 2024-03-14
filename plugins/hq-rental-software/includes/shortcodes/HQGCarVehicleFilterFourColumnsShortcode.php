<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQGCarVehicleFilterFourColumnsShortcode
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        $this->settings = new HQRentalsSettings();
        add_shortcode('hq_vehicle_grid_advanced', array ($this, 'renderShortcode'));
    }
    public function renderShortcode($atts = [])
    {
        $this->assets->gCarVehicleFilterFourColumnsAssets();
        $dataToJS = array(
            'baseURL' => get_site_url() . '/',
            'vehiclesURL' => 'vehicle-class'
        );
        wp_localize_script(
            'hq-gcar-vehicle-filter-four-columns-js',
            'HQGCarVehicleFilter',
            $dataToJS
        );
        $html = HQRentalsAssetsHandler::getHQFontAwesomeForHTML() . "<div id='hq-gcar-vehicle-filter'></div>";
        return $html;
    }
}
