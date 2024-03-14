<?php

use HQRentalsPlugin\HQRentalsShortcodes\HQWheelsberryLocationMapShortcode;

class HQRentalsElementorWheelsberryLocationsWidget extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    public function get_name()
    {
        return 'HQ Wheelsberry - Locations Map';
    }

    public function get_title()
    {
        return __('HQ Wheelsberry - Locations Map', 'hq-wordpress');
    }

    public function get_icon()
    {
        return 'eicon-google-maps';
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
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $shortcode = new HQWheelsberryLocationMapShortcode($settings);
        echo $shortcode->renderShortcode($settings);
    }
}
