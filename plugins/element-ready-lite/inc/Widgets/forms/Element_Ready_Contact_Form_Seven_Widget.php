<?php

namespace Element_Ready\Widgets\forms;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Contact_Form_Seven_Widget extends Widget_Base {

    public function get_name() {
        return 'Element_Ready_Contact_Form_Seven_Widget';
    }
    
    public function get_title() {
        return esc_html__( 'ER Contact form 7', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-mail';
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }

    public function get_keywords() {
        return [ 'contact', 'contact form', 'contact form 7' ];
    }

    public function get_script_depends() {
        return [
            'nice-select',
            'element-ready-core',
        ];
    }
    
    public function get_style_depends() {
        return[
            'nice-select',
        ];
    }

    /**
     *  FORMS STYLE CLASS
     */
    public function contact_form7_layout(){
        return[
            '1' => esc_html__( 'Style One', 'element-ready-lite' ),
            '2' => esc_html__( 'Style Two', 'element-ready-lite' ),
            '3' => esc_html__( 'Style Three', 'element-ready-lite' ),
        ];
    }

    protected function register_controls() {
        /*--------------------------
            FORMS CONTENT
        ----------------------------*/
        $this->start_controls_section(
            'element_ready_contact_form_section',
            [
                'label' => esc_html__( 'Contact Form', 'element-ready-lite' ),
            ]
        );
            $this->add_control(
                'element_ready_form_layout_style',
                [
                    'label'   => esc_html__( 'Style', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => $this->contact_form7_layout(),
                ]
            );

            $this->add_control(
                'element_ready_contact_form_id',
                [
                    'label'   => esc_html__( 'Contact Form', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => element_ready_get_contact_forms_seven_list(),
                ]
            );
        $this->end_controls_section();
        /*--------------------------
            FORMS CONTENT
        ---------------------------*/

        /*---------------------------
            WRAPPER STYLE
        ----------------------------*/
        $this->start_controls_section(
            'element_ready_form_style_section',
            [
                'label' => esc_html__( 'Wrapper Style', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'element_ready_form_section_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .element__ready__form__wrapper',
                ]
            );
            $this->add_responsive_control(
                'element_ready_form_section_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element__ready__form__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'element_ready_form_section_margin',
                [
                    'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element__ready__form__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'element_ready_form_section_align',
                [
                    'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => esc_html__( 'Justified', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__form__wrapper' => 'text-align: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );
        $this->end_controls_section();
        /*--------------------------
            WRAPPER STYLE END
        ----------------------------*/

        /*----------------------------
            LABEL STYLE
        ------------------------------*/
        $this->start_controls_section(
            'element_ready_form_label_style_section',
            [
                'label' => esc_html__( 'Label', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'label_typography',
                    'selector' => '{{WRAPPER}} .element__ready__form__wrapper label',
                ]
            );
            $this->add_control(
                'label_text_color',
                [
                    'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__form__wrapper label' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'label_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .element__ready__form__wrapper label',
                ]
            );
            $this->add_responsive_control(
                'label_width',
                [
                    'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 100
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__form__wrapper label' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );            
            $this->add_group_control(
                Group_Control_Border:: get_type(),
                [
                    'name'     => 'label_border',
                    'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} .element__ready__form__wrapper label',
                ]
            );
            $this->add_responsive_control(
                'label_border_radius',
                [
                    'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                    'type'      => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__form__wrapper label' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'label_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element__ready__form__wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'label_margin',
                [
                    'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element__ready__form__wrapper label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
        $this->end_controls_section();
        /*---------------------------
            LABEL STYLE END
        -----------------------------*/

        /*---------------------------
            INPUT STYLE START
        ----------------------------*/
        $this->start_controls_section(
            'element_ready_form_input_style_section',
            [
                'label' => esc_html__( 'Input', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'input_box_tabs' );
                $this->start_controls_tab(
                    'input_box_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'input_box_typography',
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="text"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="email"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="url"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="number"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="tel"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="date"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="file"]
                            ',
                        ]
                    );
                    $this->add_control(
                        'input_box_text_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]'   => 'color:{{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]'  => 'color:{{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]'    => 'color:{{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]' => 'color:{{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]'    => 'color:{{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]'   => 'color:{{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'   => 'color:{{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'input_box_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="text"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="email"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="url"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="number"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="tel"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="date"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="file"]
                            ',
                        ]
                    );
                    $this->add_control(
                        'input_box_placeholder_color',
                        [
                            'label'     => esc_html__( 'Placeholder Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]::-webkit-input-placeholder'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]::-moz-placeholder'            => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]:-ms-input-placeholder'        => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]::-moz-placeholder'           => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]:-ms-input-placeholder'       => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]::-webkit-input-placeholder'    => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]::-moz-placeholder'             => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]:-ms-input-placeholder'         => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]::-webkit-input-placeholder' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]::-moz-placeholder'          => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]:-ms-input-placeholder'      => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]::-webkit-input-placeholder'    => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]::-moz-placeholder'             => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]:-ms-input-placeholder'         => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]::-webkit-input-placeholder'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]::-moz-placeholder'            => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]:-ms-input-placeholder'        => 'color: {{VALUE}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'                              => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_height',
                        [
                            'label'      => esc_html__( 'Height', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 55,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]'   => 'height:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]'  => 'height:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]'    => 'height:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]' => 'height:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]'    => 'height:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]'   => 'height:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'   => 'height:{{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_width',
                        [
                            'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => '%',
                                'size' => 100,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]'   => 'width:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]'  => 'width:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]'    => 'width:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]' => 'width:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]'    => 'width:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]'   => 'width:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'   => 'width:{{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'input_box_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="text"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="email"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="url"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="number"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="tel"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="date"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="file"]
                            ',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]'   => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]'  => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]'    => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]'    => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]'   => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'   => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'input_box_shadow',
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="text"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="email"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="url"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="number"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="tel"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="date"],
                                {{WRAPPER}} .element__ready__form__wrapper input[type*="file"]
                            ',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]'  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_control(
                        'input_box_transition',
                        [
                            'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0.1,
                                    'max'  => 3,
                                    'step' => 0.1,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 0.3,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]'   => 'transition: {{SIZE}}s;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]'  => 'transition: {{SIZE}}s;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]'    => 'transition: {{SIZE}}s;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]' => 'transition: {{SIZE}}s;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]'    => 'transition: {{SIZE}}s;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]'   => 'transition: {{SIZE}}s;',
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]'   => 'transition: {{SIZE}}s;',
                            ],
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'input_box_hover_tabs',
                    [
                        'label' => esc_html__( 'Focus', 'element-ready-lite' ),
                    ]
                );
                $this->add_control(
                    'input_box_hover_color',
                    [
                        'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]:focus'   => 'color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]:focus'  => 'color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]:focus'    => 'color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]:focus' => 'color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]:focus'    => 'color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]:focus'   => 'color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]:focus'   => 'color:{{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'     => 'input_box_hover_backkground',
                        'label'    => esc_html__( 'Focus Background', 'element-ready-lite' ),
                        'types'    => [ 'classic', 'gradient' ],
                        'selector' => '
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="text"]  : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="email"] : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="url"]   : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="number"]: focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]   : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="date"]  : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="file"]  : focus
                        ',
                    ]
                );
                $this->add_control(
                    'input_box_hover_border_color',
                    [
                        'label'     => esc_html__( 'Border Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="text"]:focus'   => 'border-color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="email"]:focus'  => 'border-color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="url"]:focus'    => 'border-color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="number"]:focus' => 'border-color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]:focus'    => 'border-color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="date"]:focus'   => 'border-color:{{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper input[type*="file"]:focus'   => 'border-color:{{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow:: get_type(),
                    [
                        'name'     => 'input_box_hover_shadow',
                        'selector' => '
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="text"]  : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="email"] : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="url"]   : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="number"]: focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="tel"]   : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="date"]  : focus,
                            {{WRAPPER}} .element__ready__form__wrapper input[type*="file"]  : focus
                        ',
                    ]
                );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*-----------------------------
            INPUT STYLE END
        -------------------------------*/

        /*---------------------------
            INPUT CHECKBOX / RADIO STYLE 
        ----------------------------*/
        $this->start_controls_section(
            'element_ready_form_input_readio_style_section',
            [
                'label' => esc_html__( 'Input Radio / Checkbox', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'input_radio_checkbox_tabs' );
                $this->start_controls_tab(
                    'input_radio_checkbox_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );

                    $this->add_responsive_control(
                        'input_radio_checkbox__display',
                        [
                            'label'   => esc_html__( 'Display', 'element-ready-lite' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'inline-block',
                            
                            'options' => [
                                'initial'      => esc_html__( 'Initial', 'element-ready-lite' ),
                                'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                                'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                                'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                                'inline-flex'  => esc_html__( 'Inline Flex', 'element-ready-lite' ),
                                'none'         => esc_html__( 'none', 'element-ready-lite' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'display: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'input_radio_checkbox_typography',
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item,
                                {{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item label
                            ',
                        ]
                    );
                    $this->add_control(
                        'input_radio_checkbox_text_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'color:{{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'input_radio_checkbox_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item
                            ',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_radio_checkbox_height',
                        [
                            'label'      => esc_html__( 'Height', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'height:{{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_radio_checkbox_width',
                        [
                            'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => '%',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'width:{{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'input_radio_checkbox_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item
                            ',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_radio_checkbox_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'input_radio_checkbox_shadow',
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item
                            ',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_radio_checkbox_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_radio_checkbox_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_control(
                        'input_radio_checkbox_transition',
                        [
                            'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0.1,
                                    'max'  => 3,
                                    'step' => 0.1,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 0.3,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item' => 'transition: {{SIZE}}s;',
                            ],
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'input_radio_checkbox_hover_tabs',
                    [
                        'label' => esc_html__( 'Focus', 'element-ready-lite' ),
                    ]
                );
                $this->add_control(
                    'input_radio_checkbox_hover_color',
                    [
                        'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item:focus' => 'color:{{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'     => 'input_radio_checkbox_hover_backkground',
                        'label'    => esc_html__( 'Focus Background', 'element-ready-lite' ),
                        'types'    => [ 'classic', 'gradient' ],
                        'selector' => '
                            {{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item: focus
                        ',
                    ]
                );
                $this->add_control(
                    'input_radio_checkbox_hover_border_color',
                    [
                        'label'     => esc_html__( 'Border Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item:focus' => 'border-color:{{VALUE}};'
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow:: get_type(),
                    [
                        'name'     => 'input_radio_checkbox_hover_shadow',
                        'selector' => '
                            {{WRAPPER}} .element__ready__form__wrapper span.wpcf7-list-item: focus
                        ',
                    ]
                );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*-----------------------------
            INPUT CHECKBOX / RADIO STYLE  END
        -------------------------------*/

        /*---------------------------
            SELECT BOX STYLE START
        ----------------------------*/
        $this->start_controls_section(
            'element_ready_form_select_style_section',
            [
                'label' => esc_html__( 'Select', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'select_box_tabs' );
                $this->start_controls_tab(
                    'select_box_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'select_box_typography',
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper select
                            ',
                        ]
                    );
                    $this->add_control(
                        'select_box_text_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'color:{{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'select_box_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper select
                            ',
                        ]
                    );
                    $this->add_control(
                        'select_box_placeholder_color',
                        [
                            'label'     => esc_html__( 'Placeholder Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'select_box_height',
                        [
                            'label'      => esc_html__( 'Height', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 55,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'height:{{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'select_box_width',
                        [
                            'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => '%',
                                'size' => 100,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'width:{{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'select_box_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper select
                            ',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'select_box_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'select_box_shadow',
                            'selector' => '
                                {{WRAPPER}} .element__ready__form__wrapper select
                            ',
                        ]
                    );
                    $this->add_responsive_control(
                        'select_box_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'select_box_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_control(
                        'select_box_transition',
                        [
                            'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0.1,
                                    'max'  => 3,
                                    'step' => 0.1,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 0.3,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper select' => 'transition: {{SIZE}}s;',
                            ],
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'select_box_hover_tabs',
                    [
                        'label' => esc_html__( 'Focus', 'element-ready-lite' ),
                    ]
                );
                $this->add_control(
                    'select_box_hover_color',
                    [
                        'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper select:focus' => 'color:{{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'     => 'select_box_hover_backkground',
                        'label'    => esc_html__( 'Focus Background', 'element-ready-lite' ),
                        'types'    => [ 'classic', 'gradient' ],
                        'selector' => '
                            {{WRAPPER}} .element__ready__form__wrapper select: focus
                        ',
                    ]
                );
                $this->add_control(
                    'select_box_hover_border_color',
                    [
                        'label'     => esc_html__( 'Border Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper select:focus' => 'border-color:{{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow:: get_type(),
                    [
                        'name'     => 'select_box_hover_shadow',
                        'selector' => '
                            {{WRAPPER}} .element__ready__form__wrapper select: focus
                        ',
                    ]
                );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*-----------------------------
            SELECT BOX STYLE END
        -------------------------------*/

        /*-----------------------------
            TEXTAREA STYLE
        -----------------------------*/
        $this->start_controls_section(
            'element_ready_form_textarea_style_section',
            [
                'label' => esc_html__( 'Textarea', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'textarea_box_tabs' );
            $this->start_controls_tab(
                'textarea_box_normal_tab',
                [
                    'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                ]
            );
                $this->add_responsive_control(
                    'textarea_box_height',
                    [
                        'label'      => esc_html__( 'Height', 'element-ready-lite' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range'      => [
                            'px' => [
                                'max' => 500,
                            ],
                        ],
                        'default' => [
                            'size' => 150,
                        ],

                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_responsive_control(
                    'textarea_box_width',
                    [
                        'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range'      => [
                            'px' => [
                                'max' => 500,
                            ],
                            '%' => [
                                'max' => 100,
                            ],
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Typography:: get_type(),
                    [
                        'name'     => 'textarea_box_typography',
                        'selector' => '{{WRAPPER}} .element__ready__form__wrapper textarea',
                    ]
                );
                $this->add_control(
                    'textarea_box_text_color',
                    [
                        'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_control(
                    'textarea_box_placeholder_color',
                    [
                        'label'     => esc_html__( 'Placeholder Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea::-webkit-input-placeholder' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper textarea::-moz-placeholder'          => 'color: {{VALUE}};',
                            '{{WRAPPER}} .element__ready__form__wrapper textarea:-ms-input-placeholder'      => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'     => 'textarea_box_background',
                        'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'    => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .element__ready__form__wrapper textarea',
                    ]
                );
                $this->add_group_control(
                    Group_Control_Border:: get_type(),
                    [
                        'name'     => 'textarea_box_border',
                        'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                        'selector' => '{{WRAPPER}} .element__ready__form__wrapper textarea',
                    ]
                );
                $this->add_responsive_control(
                    'textarea_box_border_radius',
                    [
                        'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                        'type'      => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                        'separator' => 'before',
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow:: get_type(),
                    [
                        'name'     => 'textarea_box_shadow',
                        'selector' => '{{WRAPPER}} .element__ready__form__wrapper textarea',
                    ]
                );
                $this->add_responsive_control(
                    'textarea_box_padding',
                    [
                        'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors'  => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' => 'before',
                    ]
                );
                $this->add_responsive_control(
                    'textarea_box_margin',
                    [
                        'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors'  => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator' => 'before',
                    ]
                );
                $this->add_control(
                    'textarea_box_transition',
                    [
                        'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => [ 'px' ],
                        'range'      => [
                            'px' => [
                                'min'  => 0.1,
                                'max'  => 3,
                                'step' => 0.1,
                            ],
                        ],
                        'default' => [
                            'unit' => 'px',
                            'size' => 0.3,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea' => 'transition: {{SIZE}}s;',
                        ],
                    ]
                );
            $this->end_controls_tab();
            $this->start_controls_tab(
                'textarea_box_hover_tabs',
                [
                    'label' => esc_html__( 'Focus', 'element-ready-lite' ),
                ]
            );
                $this->add_control(
                    'textarea_box_hover_color',
                    [
                        'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea:focus' => 'color:{{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'     => 'textarea_box_hover_backkground',
                        'label'    => esc_html__( 'Focus Background', 'element-ready-lite' ),
                        'types'    => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .element__ready__form__wrapper textarea:focus',
                    ]
                );
                $this->add_control(
                    'textarea_box_hover_border_color',
                    [
                        'label'     => esc_html__( 'Border Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .element__ready__form__wrapper textarea:focus' => 'border-color:{{VALUE}};',
                        ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow:: get_type(),
                    [
                        'name'     => 'textarea_box_hover_shadow',
                        'selector' => '{{WRAPPER}} .element__ready__form__wrapper textarea:focus',
                    ]
                );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*----------------------------
            TEXTAREA STYLE END
        -----------------------------*/

        /*----------------------------
            BUTTONS TYLE
        ------------------------------*/
        $this->start_controls_section(
            'element_ready_input_submit_style_section',
            [
                'label' => esc_html__( 'Button', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs('submit_style_tabs');
                $this->start_controls_tab(
                    'submit_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'input_submit_typography',
                            'selector' => '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]',
                        ]
                    );

                    //$css_selector = "{{WRAPPER}} element__ready__form__wrapper__" . $this->get_id()." .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} button[type='submit']";

                    $this->add_control(
                        'input_submit_text_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'input_submit_background_color',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]',
                        ]
                    );

                    $this->add_responsive_control(
                        'input_submit_width',
                        [
                            'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 200,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'width:{{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_submit_height',
                        [
                            'label'      => esc_html__( 'Height', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range'      => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 55,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'      => 'input_submit_border',
                            'label'     => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector'  => '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_submit_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'input_submit_box_shadow',
                            'label'    => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_submit_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_submit_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"],{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_control(
                        'input_submit_transition',
                        [
                            'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0.1,
                                    'max'  => 3,
                                    'step' => 0.1,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 0.3,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"], {{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'transition: {{SIZE}}s;',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'input_submit_floting',
                        [
                            'label'   => esc_html__( 'Button Floating', 'element-ready-lite' ),
                            'type'    => Controls_Manager::CHOOSE,
                            'options' => [
                                'float:left' => [
                                    'title' => esc_html__( 'Left', 'element-ready-lite' ),
                                    'icon'  => 'eicon-h-align-left',
                                ],
                                'display:block;margin-left:auto;margin-right:auto' => [
                                    'title' => esc_html__( 'None', 'element-ready-lite' ),
                                    'icon'  => 'eicon-v-align-top',
                                ],
                                'float:right' => [
                                    'title' => esc_html__( 'Right', 'element-ready-lite' ),
                                    'icon'  => 'eicon-h-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"], {{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => '{{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_submit_text_align',
                        [
                            'label'   => esc_html__( 'Text Align', 'element-ready-lite' ),
                            'type'    => Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'element-ready-lite' ),
                                    'icon'  => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'None', 'element-ready-lite' ),
                                    'icon'  => 'fa fa-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'element-ready-lite' ),
                                    'icon'  => 'fa fa-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"], {{WRAPPER}} .element__ready__form__wrapper button[type="submit"]' => 'text-align: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'submit_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );
                    $this->add_control(
                        'input_submithover_text_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"]:hover,{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'input_submithover_background_color',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"]:hover,{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]:hover',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'input_submithover_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"]:hover,{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]:hover',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'input_submithover_shadow',
                            'label'    => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element__ready__form__wrapper input[type*="submit"]:hover,{{WRAPPER}} .element__ready__form__wrapper button[type="submit"]:hover',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*----------------------------
            BUTTONS TYLE END
        ------------------------------*/
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'element__ready__form__area__attr', 'class', 'element__ready__form__wrapper' );
        $this->add_render_attribute( 'element__ready__form__area__attr', 'class', 'contact__form__style__'.esc_attr($settings['element_ready_form_layout_style'] ) );
        
        ?>
            <div <?php echo $this->get_render_attribute_string( 'element__ready__form__area__attr' ); ?> >
                <?php
                    if( !empty($settings['element_ready_contact_form_id']) ){
                        echo do_shortcode( '[contact-form-7  id="'.$settings['element_ready_contact_form_id'].'"]' ); 
                    }else{
                        echo sprintf('<div class="form_no_select">%s</div>', __('Please Select contact form.','element-ready-lite'));
                    }
                ?>
            </div>
        <?php
    }
}
