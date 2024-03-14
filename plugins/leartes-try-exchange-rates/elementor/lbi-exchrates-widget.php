<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI__Elementor_Exchange_Rates_Widget extends Widget_Base {

    public function get_name() {
        return 'lbi_elementor_exchange_rates';
    }

    public function get_title() {
        return esc_html__('Turkish Lira Exchange Rates','lbi-exchrates');
    }

    public function get_icon() {
        return 'eicon-exchange';  
    }

    public function get_categories() {
        return [ 'leartes-elements' ];
    }

    protected function _register_controls() {
        //https://developers.elementor.com/docs/controls/control-settings/
        global $exch_currencies;
        $this->start_controls_section(
            'content_section',
            [
                'label'     => esc_html__( 'Content', 'lbi-exchrates' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__( 'Title', 'lbi-exchrates' ),
                'placeholder' => esc_html__( 'Optional Title', 'lbi-exchrates' ),
                //'description' => __( 'Optional Title', 'lbi-exchrates' ),
            ]
        );

        $this->add_control(
            'currencies_all',
            [
                'label'     => esc_html__( 'Display Currencies', 'lbi-exchrates' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'true'   => __('Show all currencies', 'lbi-exchrates'),
                    'false'  => __('Choose currencies to display ', 'lbi-exchrates') ,
                ),
                'default' => 'true',
            ]
        );

        $this->add_control(
            'currencies',
            [
                'label' => esc_html__( 'Choose currencies to display', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $exch_currencies,
                'default' => [ 'USD', 'EUR' ],
                'condition' => [
                    'currencies_all' => 'false',
                ],
            ]
        );

        $this->add_control(
            'caption',
            [
                'label'     => esc_html__( 'Currency Title', 'lbi-exchrates' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'code'   => __('Currency Code', 'lbi-exchrates'),
                    'name'   => __('Currency Name', 'lbi-exchrates'),
                    'both'   => __('Both', 'lbi-exchrates')
                ),
                'default' => 'name',
            ]
        );

        $this->add_control(
            'captions',
            [
                'label' => esc_html__( 'Show Captions', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => __( 'Show Rate\'s Captions ( e.g "Buy", "Sell")', 'lbi-exchrates' ),
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'unit',
            [
                'label' => esc_html__( 'Show Currency Unit', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'flag',
            [
                'label' => esc_html__( 'Show Country Flags', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $this->add_control(
            'flag_path',
            [
                'label' => esc_html__( 'Flag Path', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__( 'Leave empty to use default', 'lbi-exchrates' ),
                'condition' => [
                    'flag' => 'true',
                ],
            ]
        );

        $this->add_control(
            'fb',
            [
                'label' => esc_html__( 'Show Forex Buying', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'fs',
            [
                'label' => esc_html__( 'Show Forex Selling', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $this->add_control(
            'bb',
            [
                'label' => esc_html__( 'Show Banknote Buying', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'bs',
            [
                'label' => esc_html__( 'Show Banknote Selling', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'cr',
            [
                'label' => esc_html__( 'Show Cross Rate', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'showdate',
            [
                'label' => esc_html__( 'Show Date Announced', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'showsource',
            [
                'label' => esc_html__( 'Show Data Source', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lbi-exchrates' ),
                'label_off' => esc_html__( 'No', 'lbi-exchrates'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'class',
            [
                'label' => esc_html__( 'Extra CSS class', 'lbi-exchrates' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__( 'Extra CSS class', 'lbi-exchrates' ),
            ]
        );

        $this->end_controls_section();
    }

    private function false2empty( $v ){

        return  $v == 'false'  ? '': $v;
    }

    protected function render() {
        $instance = $this->get_settings_for_display();
        $title = apply_filters('widget_title', $instance['title'] );
        echo  "f:{".$instance['flag']."}";

        $settings = array(
            'title'          => $title,
            'currencies_all' => empty($instance['currencies_all']) ? 'true' : $instance['currencies_all'],
            'currencies'     => empty($instance['currencies']) ? 'USD,EUR' : implode(',', $instance['currencies']),
            'caption'        => empty($instance['caption']) ? 'name' : $instance['caption'],
            'captions'       => $this->false2empty($instance['captions']),
            'unit'           => $this->false2empty($instance['unit']),
            'flag'           => $this->false2empty($instance['flag']),
            'flag_path'      => $instance['flag_path'],
            'fb'             => $this->false2empty($instance['fb']),
            'fs'             => $this->false2empty($instance['fs']),
            'bb'             => $this->false2empty($instance['bb']),
            'bs'             => $this->false2empty($instance['bs']),
            'cr'             => $this->false2empty($instance['cr']),
            'showdate'       => $this->false2empty($instance['showdate']),
            'showsource'     => $this->false2empty($instance['showsource']),
            'class'          => $instance['class'],
            'widget'         => 'false'
        );

        $args = implode(' ', array_map(
            function ($v, $k) { return sprintf('%s="%s"', $k, $v); },
            $settings,
            array_keys($settings)
        ));

        echo do_shortcode('[lbi_exchange_rates '.$args.']');

        /*
        echo class_exists( 'LBI_Exchange_Rates' ) ? "YES":"NO";
        //echo $this->rates->shortcode_exchange_rates($settings);
        echo shortcode_exists("lbi_exchange_rates") ? "YES":"NO";
        */

    }
}

Plugin::instance()->widgets_manager->register_widget_type( new LBI__Elementor_Exchange_Rates_Widget );
?>