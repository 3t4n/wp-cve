<?php

use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsVehicleTypesGrid;

class HQRentalsElementorVehiclesTypesGridWidget extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->linkURL = '';
    }

    public function get_name()
    {
        return 'Vehicles Types Grid';
    }

    public function get_title()
    {
        return __('Vehicles Types Grid', 'hq-wordpress');
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
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
            'reservation_url_vehicle_grid',
            [
                'label' => __('Reservations URL', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'title_vehicle_grid',
            [
                'label' => __('Title', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
            ]
        );
        $this->add_control(
            'disable_features_vehicle_grid',
            [
                'label' => __('Hide Features', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'input_type' => 'string',
                'label_on' => __('No', 'hq-wordpress'),
                'label_off' => __('Yes', 'hq-wordpress'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'button_position_vehicle_grid',
            [
                'label' => __('Rent Button Position', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'input_type' => 'string',
                'label_on' => __('Hide', 'hq-wordpress'),
                'label_off' => __('Show', 'hq-wordpress'),
                'return_value' => 'yes',
                'default' => 'left',
                'options' => [
                    'left'  => __('Left', 'hq-wordpress'),
                    'right' => __('Right', 'hq-wordpress'),
                ],
            ]
        );
        $this->add_control(
            'force_vehicles_by_rate',
            [
                'label' => __('Force Order Vehicle By Rates', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'input_type' => 'string',
                'label_on' => __('Yes', 'hq-wordpress'),
                'label_off' => __('No', 'hq-wordpress'),
                'return_value' => 'true',
                'default_value' => 'false'
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $shortcode = new HQRentalsVehicleTypesGrid($settings);
        echo $shortcode->renderShortcode();
    }
}
