<?php

use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsReservationsSnippetShortcode;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesBrands;

class HQRentalsElementorReservationsWidget extends \Elementor\Widget_Base
{
    private $query;
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->query = new HQRentalsDBQueriesBrands();
    }

    public function get_name()
    {
        return 'Reservation Engine';
    }

    public function get_title()
    {
        return __('Reservation Engine', 'hq-wordpress');
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
            //brand_id -> to avoid change underline shortcode
            'brand_id_reservation_engine',
            [
                'label' => __('Branch', 'hq-wordpress'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->query->getBrandsSelectorForElementor(),
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $shortcode = new HQRentalsReservationsSnippetShortcode($settings);
        echo $shortcode->renderShortcode();
    }
}
