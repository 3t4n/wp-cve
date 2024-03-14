<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesBrands;
use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsPlacesReservationForm;

class HQRentalsBakeryPlacesReservationForm extends WPBakeryShortCode
{
    public function __construct()
    {
        $this->query = new HQRentalsDBQueriesBrands();
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_places_reservation_form', array($this, 'content'));
    }

    public function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'reservation_url_places_form' =>    '',
            'orientation_places_form'   =>  'horizontal',
            'support_for_custom_location' => 'true',
            'custom_location_label' => '',
            'minimum_rental_period' => '1',
            'google_country' => 'us',
            'google_map_center' => '',
            'google_map_center_radius' => '',
            'submit_button_label' => '',
            'time_step' => '15',
            'forced_locale' => '',
            'brand_id' => ''
        ), $atts));
        $shortcode = new HQRentalsPlacesReservationForm();
        return $shortcode->renderShortcode($atts);
    }
    public function setParams()
    {
        vc_map(
            array(
                'name'                    => __('Custom Reservation Form', 'hq-wordpress'),
                'base'                    => 'hq_bakery_places_reservation_form',
                'content_element'         => true,
                "category" => __('HQ Rental Software'),
                'show_settings_on_create' => true,
                'description'             => __('Reservation Form with Google Maps Support', 'hq-wordpress'),
                'icon'                    =>    HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Reservation URL', 'hq-wordpress'),
                        'param_name'  => 'reservation_url_places_form',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'checkbox',
                        'heading'     => __('Support for Custom Location', 'hq-wordpress'),
                        'param_name'  => 'support_for_custom_location',
                        'value'       => 'yes'
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Custom Location Label', 'hq-wordpress'),
                        'param_name'  => 'custom_location_label',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Minimum Rental Period', 'hq-wordpress'),
                        'param_name'  => 'minimum_rental_period',
                        'value'       => '1'
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Google Map Country Code', 'hq-wordpress'),
                        'param_name'  => 'google_country',
                        'value'       => 'us'
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Address Center', 'hq-wordpress'),
                        'param_name'  => 'google_map_center',
                        'description' => 'lat,lon',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Address Radius', 'hq-wordpress'),
                        'param_name'  => 'google_map_center_radius',
                        'description' => 'Degrees',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'dropdown',
                        'heading'     => __('Orientation', 'hq-wordpress'),
                        'param_name'  => 'orientation_places_form',
                        'value' => ['', 'horizontal', 'vertical']
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Submit Button Label', 'hq-wordpress'),
                        'param_name'  => 'submit_button_label',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Time Interval for Timepicker', 'hq-wordpress'),
                        'param_name'  => 'time_step',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Force Locale', 'hq-wordpress'),
                        'param_name'  => 'forced_locale',
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Brand', 'hq-wordpress'),
                        'param_name'  => 'brand_id',
                    ),
                )
            )
        );
    }
}
new HQRentalsBakeryPlacesReservationForm();
