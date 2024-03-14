<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesBrands;
use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsSimpleFormShortcode;

class HQRentalsBakerySimpleReservationForm extends WPBakeryShortCode
{
    public function __construct()
    {
        $this->query = new HQRentalsDBQueriesBrands();
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_simple_reservation_form', array($this, 'content'));
    }

    public function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'reservation_url' => '',
            'my_reservation_url' => '',
            'button_text' => 'Check Availability',
            'title' => 'RESERVE A VEHICLE',
        ), $atts));
        $shortcode = new HQRentalsSimpleFormShortcode();
        return $shortcode->render($atts);
    }
    public function setParams()
    {
        vc_map(
            array(
                'name'                    => __('Simple Reservation Form', 'hq-wordpress'),
                'base'                    => 'hq_bakery_simple_reservation_form',
                'content_element'         => true,
                "category" => __('HQ Rental Software'),
                'show_settings_on_create' => true,
                'description'             => __('Reservation Form with Custom Location', 'hq-wordpress'),
                'icon'                    =>    HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Reservation URL', 'hq-wordpress'),
                        'param_name'  => 'reservation_url',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Reservation URL - My Reservations', 'hq-wordpress'),
                        'param_name'  => 'my_reservation_url',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Submit Button Label', 'hq-wordpress'),
                        'param_name'  => 'button_text',
                        'value'       => ''
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => __('Form Title', 'hq-wordpress'),
                        'param_name'  => 'title',
                        'value'       => ''
                    ),
                )
            )
        );
    }
}
new HQRentalsBakerySimpleReservationForm();
