<?php

use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsReservationFormSnippetShortcode;
use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsReservationFormByVehicleType;

class HQRentalsElementorReservationFormByVehicleType extends \Elementor\Widget_Base
{
    private $query;
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    public function get_name()
    {
        return 'Reservation Form - Vehicle Type';
    }

    public function get_title()
    {
        return __('Reservation Form - Vehicle Type', 'hq-wordpress');
    }

    public function get_icon()
    {
        return 'eicon-product-categories';
    }

    public function get_categories()
    {
        return ['hq-rental-software'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'hq-wordpress'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'reservation_url',
            [
                'label' => __('Reservations URL', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'render_warning',
            [
                'label' => __('Display Warning Message', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'input_type' => 'string',
                'default' => 'false',
                'options' => [
                    'true'  => __('Yes', 'hq-wordpress'),
                    'false' => __('No', 'hq-wordpress'),
                ],
            ]
        );
        $this->add_control(
            'warning_message',
            [
                'label' => __('Form Warning Message', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'submit_bottom_label',
            [
                'label' => __('Submit Button Label', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'default' => 'Find a Car'
            ]
        );
        $this->add_control(
            'orientation',
            [
                'label' => __('Form Orientation', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => [
                    'horizontal'  => __('Horizontal', 'hq-wordpress'),
                    'vertical' => __('Vertical', 'hq-wordpress'),
                ],
            ]
        );
        //'render_warning' => 'false',
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $shortcode = new HQRentalsReservationFormByVehicleType();
        echo $shortcode->renderShortcode($settings);
    }
}
