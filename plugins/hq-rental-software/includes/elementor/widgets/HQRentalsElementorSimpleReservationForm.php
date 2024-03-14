<?php

use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsSimpleFormShortcode;

class HQRentalsElementorSimpleReservationForm extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    public function get_name()
    {
        return 'Simple Reservation Form';
    }

    public function get_title()
    {
        return __('Simple Reservation Form', 'hq-wordpress');
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
            'my_reservation_url',
            [
                'label' => __('My Reservations URL', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => __('Form Title', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'button_text',
            [
                'label' => __('Submit Button Label', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $shortcode = new HQRentalsSimpleFormShortcode();
        echo $shortcode->render($settings);
    }
}
