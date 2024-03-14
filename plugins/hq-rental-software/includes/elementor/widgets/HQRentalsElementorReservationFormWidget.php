<?php

use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsReservationFormSnippetShortcode;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesBrands;

class HQRentalsElementorReservationFormWidget extends \Elementor\Widget_Base
{
    private $query;
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->query = new HQRentalsDBQueriesBrands();
    }

    public function get_name()
    {
        return 'Reservation Form';
    }

    public function get_title()
    {
        return __('Reservation Form', 'hq-wordpress');
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
            'brand_id_form_widget',
            [
                'label' => __('Branches', 'hq-wordpress'),
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
        $shortcode = new HQRentalsReservationFormSnippetShortcode($settings);
        echo $shortcode->renderShortcode();
    }
}
