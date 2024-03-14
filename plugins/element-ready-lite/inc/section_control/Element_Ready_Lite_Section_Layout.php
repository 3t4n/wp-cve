<?php

namespace Element_Ready\section_control;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

class Element_Ready_Lite_Section_Layout
{

    private static $instance = null;
    public function __construct()
    {
        add_action('elementor/element/section/section_layout/before_section_end', [$this, 'section_layout'], 10, 2);
    }

    public function column_start_wrapper($element)
    {
        echo wp_kses_post(sprintf('<a href="#">'));
    }
    public function column_end_wrapper($element)
    {
        echo wp_kses_post(sprintf('</a>'));
    }

    public function section_col_layout($element, $args)
    {

        $element->add_control(
            'element_ready_lite_column_wrapper_tag_active',
            [
                'label'        => esc_html__('Column Link Enable', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',

            ]
        );

        $element->add_control(
            'element_ready_lite_inner_wrapper_link',
            [
                'label'         => esc_html__('Link', 'element-ready-lite'),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => esc_html__('https://yoururl.com', 'element-ready-lite'),
                'show_external' => true,

            ]
        );
    }
    public function section_layout($element, $args)
    {

        $element->add_responsive_control(
            'element_ready_lite_section_layout_custom_gap',
            [
                'label'      => __('ER Column Outter Gap', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 400,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'separator'    => 'before',
                'selectors' => [
                    '{{WRAPPER}} > .elementor-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

Element_Ready_Lite_Section_Layout::getInstance();
