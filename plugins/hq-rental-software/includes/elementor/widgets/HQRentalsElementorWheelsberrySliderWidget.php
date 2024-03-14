<?php

use HQRentalsPlugin\HQRentalsShortcodes\HQWheelsberrySliderShortcode;

class HQRentalsElementorWheelsberrySliderWidget extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    public function get_name()
    {
        return 'HQ Wheelsberry - Slider';
    }

    public function get_title()
    {
        return __('HQ Wheelsberry - Slider', 'hq-wordpress');
    }

    public function get_icon()
    {
        return 'eicon-slider-full-screen';
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
            'title',
            [
                'label' => __('Slider Title', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'sub_title',
            [
                'label' => __('Slider Sub Title', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'form_title',
            [
                'label' => __('Form Title', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'form_sub_title',
            [
                'label' => __('Form Sub Title', 'hq-wordpress'),
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
        $this->add_control(
            'reservation_url',
            [
                'label' => __('Reservation Engine Page URL', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'render_form',
            [
                'label' => __('Render Form', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'input_type' => 'string',
                'label_on' => __('Yes', 'hq-wordpress'),
                'label_off' => __('No', 'hq-wordpress'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );
        $this->add_control(
            'target_step',
            [
                'label' => __('Reservation Engine Page URL', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'default'   => '3'
            ]
        );
        $this->add_control(
            'render_vehicle_field',
            [
                'label' => __('Display Vehicle Class Field', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'input_type' => 'string',
                'label_on' => __('Yes', 'hq-wordpress'),
                'label_off' => __('No', 'hq-wordpress'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $shortcode = new HQWheelsberrySliderShortcode($settings);
        echo $shortcode->renderShortcode($settings);
    }
}
