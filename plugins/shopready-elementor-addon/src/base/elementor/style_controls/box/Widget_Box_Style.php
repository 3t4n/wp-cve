<?php

/**
 * @package Shop Ready
 */

namespace Shop_Ready\base\elementor\style_controls\box;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

trait Widget_Box_Style
{

    public function box_minimum_css($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title'        => esc_html__('Box Style', 'shopready-elementor-addon'),
                'slug'         => 'mini_box_style',
                'element_name' => '__mangocube__',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'tab'          => Controls_Manager::TAB_STYLE,
                'disable_controls' => []

            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        $tab_start_section_args = [
            'label' => $title,
            'tab'   => $tab,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->start_controls_tabs($widget . '_tabs_style');
        $this->start_controls_tab(
            $widget . '_normal_tab',
            [
                'label' => esc_html__('Style', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            $widget . '_sr_extension_hook_id',
            [
                'label'   => __('Extension Hook id', 'shopready-elementor-addon'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'custom_' . $widget
            ]
        );

        do_action('custom_' . $widget, $this);

        if (!in_array('alignment', $disable_controls)) {
            $this->add_responsive_control(
                $widget . 'grid_service_yuit_alignment',
                [
                    'label'     => esc_html__('Alignment', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::CHOOSE,
                    'options'   => [

                        'left'    => [

                            'title' => esc_html__('Left', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-left',

                        ],
                        'center'  => [

                            'title' => esc_html__('Center', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-center',

                        ],
                        'right'   => [

                            'title' => esc_html__('Right', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-right',

                        ],

                        'justify' => [

                            'title' => esc_html__('Justified', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-justify',

                        ],
                    ],

                    'selectors' => [
                        $selector => 'text-align: {{VALUE}} !important;',
                    ],

                ]
            ); //Responsive control end
        }

        if (!in_array('bg', $disable_controls)) {
            // Background
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => $widget . '_background',
                    'label'    => esc_html__('Background', 'shopready-elementor-addon'),
                    'types'    => ['classic', 'gradient'],
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('border', $disable_controls)) {

            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => $widget . '_border',
                    'label'    => esc_html__('Border', 'shopready-elementor-addon'),
                    'selector' => $selector,
                ]
            );

            // Radius
            $this->add_responsive_control(
                $widget . '_radius',
                [
                    'label'      => esc_html__('Border Radius', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    ],
                ]
            );
        }

        if (!in_array('dimensions', $disable_controls)) {

            // Margin
            $this->add_responsive_control(
                $widget . '_margin',
                [
                    'label'      => esc_html__('Margin', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [

                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                $widget . '_padding',
                [
                    'label'      => esc_html__('Padding', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    ],
                ]
            );
        }

        $this->end_controls_tab();

        $this->end_controls_tabs();
        if (!in_array('display', $disable_controls)) {

            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label'     => esc_html__('Display', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'flex'         => esc_html__('Flex Layout', 'shopready-elementor-addon'),
                        'block'        => esc_html__('Block Layout', 'shopready-elementor-addon'),
                        'inline-block' => esc_html__('Inline Layout', 'shopready-elementor-addon'),
                        'none'         => esc_html__('None', 'shopready-elementor-addon'),
                        ''             => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_basis',
                [
                    'label'      => esc_html__('Item Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inherit']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],
                        '%'  => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-basis: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_order',
                [
                    'label'      => esc_html__('Item Order', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit', 'initial', 'grid']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'order: {{SIZE}}',

                    ],
                ]
            );
        }

        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }
    public function box_css($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title'        => esc_html__('Box Style', 'shopready-elementor-addon'),
                'slug'         => '_box_style',
                'element_name' => 'woo_ready__',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'tab'          => Controls_Manager::TAB_STYLE,
                'disable_controls' => []

            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        $tab_start_section_args = [
            'label' => $title,
            'tab'   => $tab,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->start_controls_tabs($widget . '_tabs_style');

        $this->start_controls_tab(
            $widget . '_normal_tab',
            [
                'label' => esc_html__('Normal', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            $widget . '_sr_extension_hook_id',
            [
                'label'   => __('Extension Hook id', 'shopready-elementor-addon'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'custom_' . $widget
            ]
        );

        do_action('custom_' . $widget, $this);

        if (!in_array('alignment', $disable_controls)) {

            $this->add_responsive_control(
                $widget . 'grids_service_yuit_alignment',
                [
                    'label'     => esc_html__('Alignment', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::CHOOSE,
                    'options'   => [

                        'left'    => [

                            'title' => esc_html__('Left', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-left',

                        ],
                        'center'  => [

                            'title' => esc_html__('Center', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-center',

                        ],
                        'right'   => [

                            'title' => esc_html__('Right', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-right',

                        ],

                        'justify' => [

                            'title' => esc_html__('Justified', 'shopready-elementor-addon'),
                            'icon'  => 'eicon-text-align-justify',

                        ],
                    ],

                    'selectors' => [
                        $selector => 'text-align: {{VALUE}};',
                    ],

                ]
            ); //Responsive control end
        }

        if (!in_array('bg', $disable_controls)) {
            // Background
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => $widget . '_background',
                    'label'    => esc_html__('Background', 'shopready-elementor-addon'),
                    'types'    => ['classic', 'gradient'],
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('border', $disable_controls)) {
            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => $widget . '_border',
                    'label'    => esc_html__('Border', 'shopready-elementor-addon'),
                    'selector' => $selector,
                ]
            );

            // Radius
            $this->add_responsive_control(
                $widget . '_radius',
                [
                    'label'      => esc_html__('Border Radius', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }


        if (!in_array('box-shadow', $disable_controls)) {

            // Shadow
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => $widget . '_shadow',
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('dimensions', $disable_controls)) {
            // Margin
            $this->add_responsive_control(
                $widget . '_margin',
                [
                    'label'      => esc_html__('Margin', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [

                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                $widget . '_padding',
                [
                    'label'      => esc_html__('Padding', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    ],
                ]
            );
        }

        $this->end_controls_tab();

        if (!in_array('position', $disable_controls)) {

            $this->start_controls_tab(
                $widget . '_positionl_tab',
                [
                    'label' => esc_html__('Position', 'shopready-elementor-addon'),
                ]
            );

            $this->add_responsive_control(
                $widget . '_section__' . $element_name . '_position_type',
                [
                    'label'     => esc_html__('Position', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'fixed'    => esc_html__('Fixed', 'shopready-elementor-addon'),
                        'absolute' => esc_html__('Absolute', 'shopready-elementor-addon'),
                        'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
                        'sticky'   => esc_html__('Sticky', 'shopready-elementor-addon'),
                        'static'   => esc_html__('Static', 'shopready-elementor-addon'),
                        'inherit'  => esc_html__('inherit', 'shopready-elementor-addon'),
                        ''         => esc_html__('none', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'position: {{VALUE}};',

                    ],

                ]
            );

            $this->add_responsive_control(
                $widget . 'wrain_section_' . $element_name . '_position_left',
                [
                    'label'      => esc_html__('Position Left', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -3000,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'left: {{SIZE}}{{UNIT}};'

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_top',
                [
                    'label'      => esc_html__('Position Top', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -3000,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'top: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_bottom',
                [
                    'label'      => esc_html__('Position Bottom', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -2100,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'bottom: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );
            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_right',
                [
                    'label'      => esc_html__('Position Right', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -1600,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'right: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->end_controls_tab();
        }

        if (!in_array('size', $disable_controls)) {

            $this->start_controls_tab(
                $widget . '_size_tab',
                [
                    'label' => esc_html__('Size', 'shopready-elementor-addon'),
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_section__width',
                [
                    'label'      => esc_html__('Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_container_height',
                [
                    'label'      => esc_html__('Height', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vh'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'height: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_section_min__width',
                [
                    'label'      => esc_html__('Min Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'min-width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_section_max__width',
                [
                    'label'      => esc_html__('Max Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vh'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_tab();
        }

        do_action('custom_tab_' . $widget, $this);

        $this->end_controls_tabs();

        if (!in_array('display', $disable_controls)) {

            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label'     => esc_html__('Display', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'flex'          => esc_html__('Flex Layout', 'shopready-elementor-addon'),
                        'inherit'       => esc_html__('Flex Child Layout', 'shopready-elementor-addon'),
                        'inline-flex'   => esc_html__('Inline Flex Layout', 'shopready-elementor-addon'),
                        'block'         => esc_html__('Block Layout', 'shopready-elementor-addon'),
                        'inline-block'  => esc_html__('Inline Layout', 'shopready-elementor-addon'),
                        'grid'          => esc_html__('Grid Layout', 'shopready-elementor-addon'),
                        'inline-grid'   => esc_html__('Grid Inline Layout', 'shopready-elementor-addon'),
                        'initial'       => esc_html__('Grid Child Layout', 'shopready-elementor-addon'),
                        'table-caption' => esc_html__('Table Layout', 'shopready-elementor-addon'),
                        'flow-root'     => esc_html__('Flow Layout', 'shopready-elementor-addon'),
                        'none'          => esc_html__('None', 'shopready-elementor-addon'),
                        ''              => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section___section_flex_direction_' . $element_name . '_display',
                [
                    'label'     => esc_html__('Flex Direction', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'column'         => esc_html__('Column', 'shopready-elementor-addon'),
                        'row'            => esc_html__('Row', 'shopready-elementor-addon'),
                        'column-reverse' => esc_html__('Column Reverse', 'shopready-elementor-addon'),
                        'row-reverse'    => esc_html__('Row Reverse', 'shopready-elementor-addon'),
                        'revert'         => esc_html__('Revert', 'shopready-elementor-addon'),
                        'none'           => esc_html__('None', 'shopready-elementor-addon'),
                        ''               => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-direction: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                ]

            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_basis',
                [
                    'label'      => esc_html__('Item Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inherit']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],
                        '%'  => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-basis: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_grow',
                [
                    'label'      => esc_html__('Item Grow', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-grow: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_shrink',
                [
                    'label'      => esc_html__('Item Shrink', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-shrink: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_order',
                [
                    'label'      => esc_html__('Item Order', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit', 'initial', 'grid']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'order: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_gap',
                [
                    'label'      => esc_html__('Gap', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . '_section___section_flex_wrap_' . $element_name . '_display',
                [
                    'label'     => esc_html__('Flex Wrap', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'wrap'         => esc_html__('Wrap', 'shopready-elementor-addon'),
                        'wrap-reverse' => esc_html__('Wrap Reverse', 'shopready-elementor-addon'),
                        'nowrap'       => esc_html__('No Wrap', 'shopready-elementor-addon'),
                        'unset'        => esc_html__('Unset', 'shopready-elementor-addon'),
                        'normal'       => esc_html__('None', 'shopready-elementor-addon'),
                        'inherit'      => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-wrap: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section_align_section_e_' . $element_name . '_flex_align',
                [
                    'label'     => esc_html__('Horizontal Alignment', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'flex-start'    => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end'      => esc_html__('Right', 'shopready-elementor-addon'),
                        'center'        => esc_html__('Center', 'shopready-elementor-addon'),
                        'space-around'  => esc_html__('Space Around', 'shopready-elementor-addon'),
                        'space-between' => esc_html__('Space Between', 'shopready-elementor-addon'),
                        'space-evenly'  => esc_html__('Space Evenly', 'shopready-elementor-addon'),
                        ''              => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],

                    'selectors' => [
                        $selector => 'justify-content: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section_align_items_section_e_' . $element_name . '_flex_align',
                [
                    'label'     => esc_html__('Vartical Alignment', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end'   => esc_html__('Right', 'shopready-elementor-addon'),
                        'center'     => esc_html__('Center', 'shopready-elementor-addon'),
                        'baseline'   => esc_html__('Baseline', 'shopready-elementor-addon'),
                        ''           => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],

                    'selectors' => [
                        $selector => 'align-items: {{VALUE}};',
                    ],
                ]

            );

            // grid

            $this->add_responsive_control(
                $widget . '_section_align_items_section_e_' . $element_name . '_grid_align_items',
                [
                    'label'     => esc_html__('V/H Alignment', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'left',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'options'   => [
                        'start'                => esc_html__('Left/ Start', 'shopready-elementor-addon'),
                        'end'                  => esc_html__('Right / End', 'shopready-elementor-addon'),
                        'center'               => esc_html__('Center', 'shopready-elementor-addon'),
                        'center start'         => esc_html__('center Left', 'shopready-elementor-addon'),
                        'center end'           => esc_html__('center end', 'shopready-elementor-addon'),
                        'center stretch'       => esc_html__('center stretch', 'shopready-elementor-addon'),
                        'end space-between'    => esc_html__('end space-between', 'shopready-elementor-addon'),
                        'start space-between'  => esc_html__('left space-between', 'shopready-elementor-addon'),
                        'center space-between' => esc_html__('center space-between', 'shopready-elementor-addon'),
                        'center space-evenly'  => esc_html__('center space-evenly', 'shopready-elementor-addon'),
                        'start space-evenly'   => esc_html__('start space-evenly', 'shopready-elementor-addon'),
                        'end space-evenly'     => esc_html__('end space-evenly', 'shopready-elementor-addon'),

                        ''                     => esc_html__('default', 'shopready-elementor-addon'),
                    ],

                    'selectors' => [
                        $selector => 'place-items: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section_align_items_section_e_' . $element_name . '_grid_align_place_content',
                [
                    'label'     => esc_html__('H/V Content', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'center',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'options'   => [
                        'start'                => esc_html__('Start / Left', 'shopready-elementor-addon'),
                        'end'                  => esc_html__('Right / End', 'shopready-elementor-addon'),
                        'center'               => esc_html__('Center', 'shopready-elementor-addon'),
                        'center start'         => esc_html__('center Left', 'shopready-elementor-addon'),
                        'center end'           => esc_html__('center end', 'shopready-elementor-addon'),
                        'center stretch'       => esc_html__('center stretch', 'shopready-elementor-addon'),
                        'end space-between'    => esc_html__('end space-between', 'shopready-elementor-addon'),
                        'start space-between'  => esc_html__('left space-between', 'shopready-elementor-addon'),
                        'center space-between' => esc_html__('center space-between', 'shopready-elementor-addon'),
                        'center space-evenly'  => esc_html__('center space-evenly', 'shopready-elementor-addon'),
                        'start space-evenly'   => esc_html__('start space-evenly', 'shopready-elementor-addon'),
                        'end space-evenly'     => esc_html__('end space-evenly', 'shopready-elementor-addon'),

                        ''                     => esc_html__('default', 'shopready-elementor-addon'),
                    ],

                    'selectors' => [
                        $selector => 'place-content: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section_align_items_section_e_' . $element_name . '_grid_justify_items_align',
                [
                    'label'     => esc_html__('Place Self Column', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'left',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inline-grid', 'initial']],
                    'options'   => [
                        'start auto'           => esc_html__('Start / Left', 'shopready-elementor-addon'),
                        'end normal'           => esc_html__('End / Right', 'shopready-elementor-addon'),
                        'center normal'        => esc_html__('Center', 'shopready-elementor-addon'),
                        'baseline normal'      => esc_html__('Baseline', 'shopready-elementor-addon'),
                        'stretch auto'         => esc_html__('Stretch', 'shopready-elementor-addon'),
                        'first baseline auto'  => esc_html__('First Base', 'shopready-elementor-addon'),
                        'last baseline normal' => esc_html__('last baseline normal', 'shopready-elementor-addon'),
                        'space-between'        => esc_html__('space-between', 'shopready-elementor-addon'),
                        ''                     => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],

                    'selectors' => [
                        $selector => 'place-self: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_grid_cols_gap',
                [
                    'label'      => esc_html__('Columns Gap', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'size_units' => ['px'],
                    'range'      => [

                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'column-gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_grid_row_gap',
                [
                    'label'      => esc_html__('Row Gap', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'size_units' => ['px'],
                    'range'      => [

                        'px' => [
                            'min'  => 0,
                            'max'  => 500,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'row-gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_grid_col',
                [
                    'label'      => esc_html__('Column', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'size_units' => ['px'],
                    'range'      => [

                        'px' => [
                            'min'  => 0,
                            'max'  => 10,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'grid-template-columns: repeat( {{SIZE}}, 1fr);',

                    ],
                ]
            );
        }

        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }

    public function box_layout($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title'        => esc_html__('Box Layout', 'shopready-elementor-addon'),
                'slug'         => '_layout_style',
                'element_name' => '__mangocube__',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'disable_controls' => []

            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        $tab_start_section_args = [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_LAYOUT,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        if (!in_array('display', $disable_controls)) {
            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label'     => esc_html__('Display', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'flex'          => esc_html__('Flex Layout', 'shopready-elementor-addon'),
                        'inherit'       => esc_html__('Flex Child Layout', 'shopready-elementor-addon'),
                        'inline-flex'   => esc_html__('Inline Flex Layout', 'shopready-elementor-addon'),
                        'block'         => esc_html__('Block Layout', 'shopready-elementor-addon'),
                        'inline-block'  => esc_html__('Inline Layout', 'shopready-elementor-addon'),
                        'grid'          => esc_html__('Grid Layout', 'shopready-elementor-addon'),
                        'inline-grid'   => esc_html__('Grid Inline Layout', 'shopready-elementor-addon'),
                        'initial'       => esc_html__('Grid Child Layout', 'shopready-elementor-addon'),
                        'table-caption' => esc_html__('Table Layout', 'shopready-elementor-addon'),
                        'flow-root'     => esc_html__('Flow Layout', 'shopready-elementor-addon'),
                        'none'          => esc_html__('None', 'shopready-elementor-addon'),
                        ''              => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section___section_flex_direction_' . $element_name . '_display',
                [
                    'label'     => esc_html__('Flex Direction', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'default'   => '',
                    'options'   => [
                        'column'         => esc_html__('Column', 'shopready-elementor-addon'),
                        'row'            => esc_html__('Row', 'shopready-elementor-addon'),
                        'column-reverse' => esc_html__('Column Reverse', 'shopready-elementor-addon'),
                        'row-reverse'    => esc_html__('Row Reverse', 'shopready-elementor-addon'),
                        'revert'         => esc_html__('Revert', 'shopready-elementor-addon'),
                        'none'           => esc_html__('None', 'shopready-elementor-addon'),
                        ''               => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-direction: {{VALUE}};',
                    ],

                ]

            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_basis',
                [
                    'label'      => esc_html__('Item Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inherit']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1900,
                            'step' => 1,
                        ],
                        '%'  => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-basis: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_grow',
                [
                    'label'      => esc_html__('Item Grow', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1900,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-grow: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_shrink',
                [
                    'label'      => esc_html__('Item Shrink', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit']],
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1900,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-shrink: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_order',
                [
                    'label'      => esc_html__('Item Order', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inherit']],
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => -130,
                            'max'  => 999,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'order: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_gap',
                [
                    'label'      => esc_html__('Gap', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . '_section___section_flex_wrap_' . $element_name . '_display',
                [
                    'label'     => esc_html__('Flex Wrap', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'wrap',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'options'   => [
                        'wrap'         => esc_html__('Wrap', 'shopready-elementor-addon'),
                        'wrap-reverse' => esc_html__('Wrap Reverse', 'shopready-elementor-addon'),
                        'nowrap'       => esc_html__('No Wrap', 'shopready-elementor-addon'),
                        'unset'        => esc_html__('Unset', 'shopready-elementor-addon'),
                        'normal'       => esc_html__('None', 'shopready-elementor-addon'),
                        'inherit'      => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-wrap: {{VALUE}};',
                    ],

                ]

            );

            $this->add_responsive_control(
                $widget . '_section_align_section_e_' . $element_name . '_flex_align',
                [
                    'label'     => esc_html__('Alignment', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'flex-start',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'options'   => [
                        'flex-start'    => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end'      => esc_html__('Right', 'shopready-elementor-addon'),
                        'center'        => esc_html__('Center', 'shopready-elementor-addon'),
                        'space-around'  => esc_html__('Space Around', 'shopready-elementor-addon'),
                        'space-between' => esc_html__('Space Between', 'shopready-elementor-addon'),
                        'space-evenly'  => esc_html__('Space Evenly', 'shopready-elementor-addon'),
                        ''              => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],

                    'selectors' => [
                        $selector => 'justify-content: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section_align_items_section_e_' . $element_name . '_flex_align',
                [
                    'label'     => esc_html__('Align Items', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'left',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'options'   => [
                        'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end'   => esc_html__('Right', 'shopready-elementor-addon'),
                        'center'     => esc_html__('Center', 'shopready-elementor-addon'),
                        'baseline'   => esc_html__('Baseline', 'shopready-elementor-addon'),
                        ''           => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],

                    'selectors' => [
                        $selector => 'align-items: {{VALUE}};',
                    ],
                ]

            );
            // grid

            $this->add_responsive_control(
                $widget . '_section_al_items_section_e_' . $element_name . '_grid_align_items',
                [
                    'label'     => esc_html__('Place Items', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'left',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'options'   => [
                        'start'                => esc_html__('Left/ Start', 'shopready-elementor-addon'),
                        'end'                  => esc_html__('Right / End', 'shopready-elementor-addon'),
                        'center'               => esc_html__('Center', 'shopready-elementor-addon'),
                        'center start'         => esc_html__('center Left', 'shopready-elementor-addon'),
                        'center end'           => esc_html__('center end', 'shopready-elementor-addon'),
                        'center stretch'       => esc_html__('center stretch', 'shopready-elementor-addon'),
                        'end space-between'    => esc_html__('end space-between', 'shopready-elementor-addon'),
                        'start space-between'  => esc_html__('left space-between', 'shopready-elementor-addon'),
                        'center space-between' => esc_html__('center space-between', 'shopready-elementor-addon'),
                        'center space-evenly'  => esc_html__('center space-evenly', 'shopready-elementor-addon'),
                        'start space-evenly'   => esc_html__('start space-evenly', 'shopready-elementor-addon'),
                        'end space-evenly'     => esc_html__('end space-evenly', 'shopready-elementor-addon'),

                        ''                     => esc_html__('default', 'shopready-elementor-addon'),
                    ],

                    'selectors' => [
                        $selector => 'place-items: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section_al_items_section_e_' . $element_name . '_grid_align_place_content',
                [
                    'label'     => esc_html__('Place Content', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'center',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'options'   => [
                        'start'                => esc_html__('Start / Left', 'shopready-elementor-addon'),
                        'end'                  => esc_html__('Right / End', 'shopready-elementor-addon'),
                        'center'               => esc_html__('Center', 'shopready-elementor-addon'),
                        'center start'         => esc_html__('center Left', 'shopready-elementor-addon'),
                        'center end'           => esc_html__('center end', 'shopready-elementor-addon'),
                        'center stretch'       => esc_html__('center stretch', 'shopready-elementor-addon'),
                        'end space-between'    => esc_html__('end space-between', 'shopready-elementor-addon'),
                        'start space-between'  => esc_html__('left space-between', 'shopready-elementor-addon'),
                        'center space-between' => esc_html__('center space-between', 'shopready-elementor-addon'),
                        'center space-evenly'  => esc_html__('center space-evenly', 'shopready-elementor-addon'),
                        'start space-evenly'   => esc_html__('start space-evenly', 'shopready-elementor-addon'),
                        'end space-evenly'     => esc_html__('end space-evenly', 'shopready-elementor-addon'),

                        ''                     => esc_html__('default', 'shopready-elementor-addon'),
                    ],

                    'selectors' => [
                        $selector => 'place-content: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_grid_cols_gap',
                [
                    'label'      => esc_html__('Column Gap', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['initial', 'grid', 'inline-grid']],
                    'size_units' => ['px'],
                    'range'      => [

                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'column-gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_grid_row_gap',
                [
                    'label'      => esc_html__('Row Gap', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'size_units' => ['px'],
                    'range'      => [

                        'px' => [
                            'min'  => 0,
                            'max'  => 500,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'row-gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_grid_col',
                [
                    'label'      => esc_html__('Column', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid', 'initial']],
                    'size_units' => ['px'],
                    'range'      => [

                        'px' => [
                            'min'  => 0,
                            'max'  => 10,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'grid-template-columns: repeat( {{SIZE}}, 1fr);',

                    ],
                ]
            );
        }

        if (!in_array('position', $disable_controls)) {
            $this->add_control(
                $widget . '_section___section_popover_' . $element_name . '_position',
                [
                    'label'        => esc_html__('Position', 'shopready-elementor-addon'),
                    'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off'    => esc_html__('Default', 'shopready-elementor-addon'),
                    'label_on'     => esc_html__('Custom', 'shopready-elementor-addon'),
                    'return_value' => 'yes',
                ]
            );

            $this->start_popover();
            $this->add_responsive_control(
                $widget . '_section__' . $element_name . '_position_type',
                [
                    'label'     => esc_html__('Position', 'shopready-elementor-addon'),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'fixed'    => esc_html__('Fixed', 'shopready-elementor-addon'),
                        'absolute' => esc_html__('Absolute', 'shopready-elementor-addon'),
                        'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
                        'sticky'   => esc_html__('Sticky', 'shopready-elementor-addon'),
                        'static'   => esc_html__('Static', 'shopready-elementor-addon'),
                        'inherit'  => esc_html__('inherit', 'shopready-elementor-addon'),
                        ''         => esc_html__('none', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'position: {{VALUE}};',

                    ],

                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_position_left',
                [
                    'label'      => esc_html__('Position Left', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -5000,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'left: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_top',
                [
                    'label'      => esc_html__('Position Top', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -3000,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'top: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_bottom',
                [
                    'label'      => esc_html__('Position Bottom', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -2500,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'bottom: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );
            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_right',
                [
                    'label'      => esc_html__('Position Right', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -2600,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'right: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );
            $this->end_popover();
        }

        if (!in_array('box-size', $disable_controls)) {

            $this->add_control(
                $widget . 'main_section_' . $element_name . '_rbox_popover_section_sizen',
                [
                    'label'        => esc_html__('Box Size', 'shopready-elementor-addon'),
                    'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off'    => esc_html__('Default', 'shopready-elementor-addon'),
                    'label_on'     => esc_html__('Custom', 'shopready-elementor-addon'),
                    'return_value' => 'yes',

                ]
            );

            $this->start_popover();

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_section__width',
                [
                    'label'      => esc_html__('Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_container_height',
                [
                    'label'      => esc_html__('Height', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vh'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 3000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors'  => [
                        $selector => 'height: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->end_popover();
        }

        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }
    public function box_layout_child($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title'        => esc_html__('Box Layout', 'shopready-elementor-addon'),
                'slug'         => '_layout_style',
                'element_name' => '__mangocube__',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'disable_controls' => []

            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        $tab_start_section_args = [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_LAYOUT,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->add_responsive_control(
            $widget . '_section___section_show_hide_' . $element_name . '_display',
            [
                'label'     => esc_html__('Display', 'shopready-elementor-addon'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'inherit',
                'options'   => [
                    'inherit'      => esc_html__('Flex Child Layout', 'shopready-elementor-addon'),
                    'flex'         => esc_html__('Flex Layout', 'shopready-elementor-addon'),
                    'inline-flex'  => esc_html__('Inline Flex Layout', 'shopready-elementor-addon'),
                    'block'        => esc_html__('Block Layout', 'shopready-elementor-addon'),
                    'inline-block' => esc_html__('Inline Layout', 'shopready-elementor-addon'),
                    'none'         => esc_html__('None', 'shopready-elementor-addon'),
                    ''             => esc_html__('inherit', 'shopready-elementor-addon'),
                ],
                'selectors' => [
                    $selector => 'display: {{VALUE}};',
                ],
            ]

        );

        $this->add_responsive_control(
            $widget . '_section___section_flex_direction_' . $element_name . '_display',
            [
                'label'     => esc_html__('Flex Direction', 'shopready-elementor-addon'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                'default'   => '',
                'options'   => [
                    'column'         => esc_html__('Column', 'shopready-elementor-addon'),
                    'row'            => esc_html__('Row', 'shopready-elementor-addon'),
                    'column-reverse' => esc_html__('Column Reverse', 'shopready-elementor-addon'),
                    'row-reverse'    => esc_html__('Row Reverse', 'shopready-elementor-addon'),
                    'revert'         => esc_html__('Revert', 'shopready-elementor-addon'),
                    'none'           => esc_html__('None', 'shopready-elementor-addon'),
                    ''               => esc_html__('inherit', 'shopready-elementor-addon'),
                ],
                'selectors' => [
                    $selector => 'flex-direction: {{VALUE}};',
                ],

            ]

        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_flex_basis',
            [
                'label'      => esc_html__('Item Flex Width', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::SLIDER,
                'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inherit']],
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 800,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],

                ],

                'selectors'  => [
                    $selector => 'flex-basis: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_flex_widthss',
            [
                'label'      => esc_html__('Item Width', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::SLIDER,

                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 800,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],

                ],

                'selectors'  => [
                    $selector => 'width: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_flex_grow',
            [
                'label'      => esc_html__('Item Grow', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::SLIDER,
                'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit']],
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 800,
                        'step' => 1,
                    ],

                ],

                'selectors'  => [
                    $selector => 'flex-grow: {{SIZE}}',

                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_flex_shrink',
            [
                'label'      => esc_html__('Item Shrink', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::SLIDER,
                'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit']],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 800,
                        'step' => 1,
                    ],

                ],

                'selectors'  => [
                    $selector => 'flex-shrink: {{SIZE}}',

                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_flex_order',
            [
                'label'      => esc_html__('Item Order', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::SLIDER,
                'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inherit']],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => -30,
                        'max'  => 100,
                        'step' => 1,
                    ],

                ],

                'selectors'  => [
                    $selector => 'order: {{SIZE}}',

                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_flex_gap',
            [
                'label'      => esc_html__('Gap', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::SLIDER,
                'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 800,
                        'step' => 1,
                    ],

                ],

                'selectors'  => [
                    $selector => 'gap: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_responsive_control(
            $widget . '_section___section_flex_wrap_' . $element_name . '_display',
            [
                'label'     => esc_html__('Flex Wrap', 'shopready-elementor-addon'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'wrap',
                'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                'options'   => [
                    'wrap'         => esc_html__('Wrap', 'shopready-elementor-addon'),
                    'wrap-reverse' => esc_html__('Wrap Reverse', 'shopready-elementor-addon'),
                    'nowrap'       => esc_html__('No Wrap', 'shopready-elementor-addon'),
                    'unset'        => esc_html__('Unset', 'shopready-elementor-addon'),
                    'normal'       => esc_html__('None', 'shopready-elementor-addon'),
                    'inherit'      => esc_html__('inherit', 'shopready-elementor-addon'),
                ],
                'selectors' => [
                    $selector => 'flex-wrap: {{VALUE}};',
                ],

            ]

        );

        $this->add_responsive_control(
            $widget . '_section_align_section_e_' . $element_name . '_flex_align',
            [
                'label'     => esc_html__('Alignment', 'shopready-elementor-addon'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'flex-start',
                'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                'options'   => [
                    'flex-start'    => esc_html__('Left', 'shopready-elementor-addon'),
                    'flex-end'      => esc_html__('Right', 'shopready-elementor-addon'),
                    'center'        => esc_html__('Center', 'shopready-elementor-addon'),
                    'space-around'  => esc_html__('Space Around', 'shopready-elementor-addon'),
                    'space-between' => esc_html__('Space Between', 'shopready-elementor-addon'),
                    'space-evenly'  => esc_html__('Space Evenly', 'shopready-elementor-addon'),
                    ''              => esc_html__('inherit', 'shopready-elementor-addon'),
                ],

                'selectors' => [
                    $selector => 'justify-content: {{VALUE}};',
                ],
            ]

        );

        $this->add_responsive_control(
            $widget . '_section_align_items_section_e_' . $element_name . '_flex_align',
            [
                'label'     => esc_html__('Align Items', 'shopready-elementor-addon'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'left',
                'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                'options'   => [
                    'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                    'flex-end'   => esc_html__('Right', 'shopready-elementor-addon'),
                    'center'     => esc_html__('Center', 'shopready-elementor-addon'),
                    'baseline'   => esc_html__('Baseline', 'shopready-elementor-addon'),
                    ''           => esc_html__('inherit', 'shopready-elementor-addon'),
                ],

                'selectors' => [
                    $selector => 'align-items: {{VALUE}};',
                ],
            ]

        );

        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }
}
