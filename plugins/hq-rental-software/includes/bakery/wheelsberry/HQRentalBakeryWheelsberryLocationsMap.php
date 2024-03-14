<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesLocations;
use HQRentalsPlugin\HQRentalsShortcodes\HQWheelsberryLocationMapShortcode;

class HQRentalBakeryWheelsberryLocationsMap extends WPBakeryShortCode
{
    private $title;
    private $button_text;
    private $reservation_url;

    public function __construct()
    {
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_wheelsberry_location_map', array($this, 'content'));
    }

    public function content($atts, $content = null)
    {
        $shortcode = new HQWheelsberryLocationMapShortcode();
        return $shortcode->renderShortcode();
    }

    public function setParams()
    {
        vc_map(
            array(
                'name' => __('HQRS Wheelsberry Location Map', 'hq-wordpress'),
                'base' => 'hq_bakery_wheelsberry_location_map',
                'content_element' => true,
                'category' => __('HQ Rental Software - Wheelsberry Theme'),
                'show_settings_on_create' => true,
                'description' => __('HQ Wheelsberry - Location Map', 'hq-wordpress'),
                'icon' => HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                )
            )
        );
    }
}
new HQRentalBakeryWheelsberryLocationsMap();
