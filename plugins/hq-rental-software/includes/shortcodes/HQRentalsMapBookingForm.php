<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsMapBookingForm
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
        $this->settings = new HQRentalsSettings();
        add_shortcode('hq_rentals_map_booking_form', array($this, 'shortcode'));
    }

    public function shortcode($atts = [])
    {
        $this->assets->loadMapFormAssets();
        $atts = shortcode_atts(
            array(
                'background_url' => '',
            ),
            $atts
        );
        $dataToJS = array(
            'backgroundImageURL' => $atts['background_url'],
            'baseURL' => get_site_url() . '/',
            'defaultLatitude' => $this->settings->getDefaultLatitudeSetting(),
            'defaultLongitude' => $this->settings->getDefaultLongitudeSetting()
        );
        wp_localize_script('hq-map-form-script', 'HQMapFormShortcode', $dataToJS);
        ?>
        <div id="hq-map-booking-form"></div>
        <?php
    }
}
