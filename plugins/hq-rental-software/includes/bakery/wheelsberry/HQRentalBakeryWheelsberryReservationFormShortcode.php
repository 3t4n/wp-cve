<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsShortcodes\HQWheelsberrySliderShortcode;

class HQRentalBakeryWheelsberryReservationFormShortcode extends WPBakeryShortCode
{
    public function __construct()
    {
        global $post;
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_wheelsberry_reservation_form', array($this, 'content'));
    }

    public function content($atts = [], $content = null)
    {
        shortcode_atts(array(
            'title' => "",
            'sub_title' => "",
            'form_title' => '',
            'form_sub_title' => '',
            'button_text' => esc_html__('Continue Booking', 'hq-wordpress'),
            'reservation_url' => '/reservations/',
            'render_form' => '',
            'target_step' => '3',
            'render_vehicle_field' => 'true'
        ), $atts);
        $atts['render_form'] = isset($atts['render_form']) ? 'true' : 'false';
        $shortcode = new HQWheelsberrySliderShortcode();
        return $shortcode->renderShortcode($atts);
    }

    public function setParams()
    {
        vc_map(
            array(
                'name' => __('HQRS Wheelsberry Reservation Form', 'hq-wordpress'),
                'base' => 'hq_bakery_wheelsberry_reservation_form',
                'content_element' => true,
                "category" => __('HQ Rental Software - Wheelsberry Theme'),
                'show_settings_on_create' => true,
                'description' => __('HQ Wheelsberry Reservation Form', 'hq-wordpress'),
                'icon' => HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Enter the Slider Title', 'hq-wordpress'),
                        'param_name' => 'title',
                        'value' => '',
                        'description' => esc_html__('Enter the Slider Title', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Enter the Slider Subtitle', 'hq-wordpress'),
                        'param_name' => 'sub_title',
                        'value' => '',
                        'description' => esc_html__('Enter the Slider Subtitle', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Form Title', 'hq-wordpress'),
                        'param_name' => 'form_title',
                        'value' => '',
                        'description' => esc_html__('Enter the Form Title', 'hq-wordpress')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Reservation Engine Page URL', 'hq-wordpress'),
                        'param_name' => 'reservation_url',
                        'value' => '',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Render Form', 'hq-wordpress'),
                        'param_name' => 'render_form',
                        'value' => 'true'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Reservation Engine Target Step', 'hq-wordpress'),
                        'param_name' => 'target_step',
                        'value' => '3',
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Render Vehicle Field', 'hq-wordpress'),
                        'param_name' => 'render_vehicle_field',
                        'value' => 'true'
                    ),
                )
            )
        );
    }
}
new HQRentalBakeryWheelsberryReservationFormShortcode();
