<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Element_Ready_Effects {

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self:: $instance;
    }

    public function init() {
        add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_section' ], 1 );
        add_action( 'elementor/frontend/after_enqueue_scripts', array ( $this, 'floating_effect_script' ), 10 );
    }

    public function floating_effect_script(){

        wp_enqueue_script( 'anime' );
        wp_enqueue_script( 'base_effect' );
        wp_enqueue_script( 'tilt' );
        wp_enqueue_script( 'element-ready-core' );

    }

    public static function add_controls_section( Element_Base $element ) {
        $element->start_controls_section(
            '_section_element_ready_effects',
            [
                'label' => esc_html__( 'Element Effects', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

            self::add_floating_effects( $element );
            self::add_css_effects( $element );
            self::add_tilt_effects( $element );

        $element->end_controls_section();
    }

    public static function add_floating_effects( Element_Base $element ) {
        $element->add_control(
            'element_ready_floating_fx',
            [
                'label'              => esc_html__( 'Floating Effects', 'element-ready-lite' ),
                'type'               => Controls_Manager::SWITCHER,
                'return_value'       => 'yes',
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'element_ready_floating_fx_translate_toggle',
            [
                'label'              => esc_html__( 'Translate', 'element-ready-lite' ),
                'type'               => Controls_Manager::POPOVER_TOGGLE,
                'return_value'       => 'yes',
                'frontend_available' => true,
                'condition'          => [
                    'element_ready_floating_fx' => 'yes',
                ]
            ]
        );
        $element->start_popover();
            $element->add_control(
                'element_ready_floating_fx_translate_x',
                [
                    'label'   => esc_html__( 'Translate X', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to'   => 5,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ]
                    ],
                    'labels' => [
                        esc_html__( 'From', 'element-ready-lite' ),
                        esc_html__( 'To', 'element-ready-lite' ),
                    ],
                    'scales'    => 1,
                    'handles'   => 'range',
                    'condition' => [
                        'element_ready_floating_fx_translate_toggle' => 'yes',
                        'element_ready_floating_fx'                  => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_translate_y',
                [
                    'label'   => esc_html__( 'Translate Y', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to'   => 5,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -100,
                            'max' => 100,
                        ]
                    ],
                    'labels' => [
                        esc_html__( 'From', 'element-ready-lite' ),
                        esc_html__( 'To', 'element-ready-lite' ),
                    ],
                    'scales'    => 1,
                    'handles'   => 'range',
                    'condition' => [
                        'element_ready_floating_fx_translate_toggle' => 'yes',
                        'element_ready_floating_fx'                  => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_translate_duration',
                [
                    'label'      => esc_html__( 'Duration', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 10000,
                            'step' => 100
                        ]
                    ],
                    'default' => [
                        'size' => 1000,
                    ],
                    'condition' => [
                        'element_ready_floating_fx_translate_toggle' => 'yes',
                        'element_ready_floating_fx'                  => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_translate_delay',
                [
                    'label'      => esc_html__( 'Delay', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 5000,
                            'step' => 100
                        ]
                    ],
                    'condition' => [
                        'element_ready_floating_fx_translate_toggle' => 'yes',
                        'element_ready_floating_fx'                  => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );
        $element->end_popover();

        $element->add_control(
            'element_ready_floating_fx_rotate_toggle',
            [
                'label'              => esc_html__( 'Rotate', 'element-ready-lite' ),
                'type'               => Controls_Manager::POPOVER_TOGGLE,
                'return_value'       => 'yes',
                'frontend_available' => true,
                'condition'          => [
                    'element_ready_floating_fx' => 'yes',
                ]
            ]
        );
        $element->start_popover();
            $element->add_control(
                'element_ready_floating_fx_rotate_x',
                [
                    'label'   => esc_html__( 'Rotate X', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to'   => 45,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'labels' => [
                        esc_html__( 'From', 'element-ready-lite' ),
                        esc_html__( 'To', 'element-ready-lite' ),
                    ],
                    'scales'    => 1,
                    'handles'   => 'range',
                    'condition' => [
                        'element_ready_floating_fx_rotate_toggle' => 'yes',
                        'element_ready_floating_fx'               => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_rotate_y',
                [
                    'label'   => esc_html__( 'Rotate Y', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to'   => 45,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'labels' => [
                        esc_html__( 'From', 'element-ready-lite' ),
                        esc_html__( 'To', 'element-ready-lite' ),
                    ],
                    'scales'    => 1,
                    'handles'   => 'range',
                    'condition' => [
                        'element_ready_floating_fx_rotate_toggle' => 'yes',
                        'element_ready_floating_fx'               => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_rotate_z',
                [
                    'label'   => esc_html__( 'Rotate Z', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 0,
                            'to'   => 45,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'labels' => [
                        esc_html__( 'From', 'element-ready-lite' ),
                        esc_html__( 'To', 'element-ready-lite' ),
                    ],
                    'scales'    => 1,
                    'handles'   => 'range',
                    'condition' => [
                        'element_ready_floating_fx_rotate_toggle' => 'yes',
                        'element_ready_floating_fx'               => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_rotate_duration',
                [
                    'label'      => esc_html__( 'Duration', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 10000,
                            'step' => 100
                        ]
                    ],
                    'default' => [
                        'size' => 1000,
                    ],
                    'condition' => [
                        'element_ready_floating_fx_rotate_toggle' => 'yes',
                        'element_ready_floating_fx'               => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_rotate_delay',
                [
                    'label'      => esc_html__( 'Delay', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 5000,
                            'step' => 100
                        ]
                    ],
                    'condition' => [
                        'element_ready_floating_fx_rotate_toggle' => 'yes',
                        'element_ready_floating_fx'               => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );
        $element->end_popover();

        $element->add_control(
            'element_ready_floating_fx_scale_toggle',
            [
                'label'              => esc_html__( 'Scale', 'element-ready-lite' ),
                'type'               => Controls_Manager::POPOVER_TOGGLE,
                'return_value'       => 'yes',
                'frontend_available' => true,
                'condition'          => [
                    'element_ready_floating_fx' => 'yes',
                ]
            ]
        );
        $element->start_popover();
            $element->add_control(
                'element_ready_floating_fx_scale_x',
                [
                    'label'   => esc_html__( 'Scale X', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 1,
                            'to'   => 1.2,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 5,
                            'step' => .1
                        ]
                    ],
                    'labels' => [
                        esc_html__( 'From', 'element-ready-lite' ),
                        esc_html__( 'To', 'element-ready-lite' ),
                    ],
                    'scales'    => 1,
                    'handles'   => 'range',
                    'condition' => [
                        'element_ready_floating_fx_scale_toggle' => 'yes',
                        'element_ready_floating_fx'              => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_scale_y',
                [
                    'label'   => esc_html__( 'Scale Y', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'sizes' => [
                            'from' => 1,
                            'to'   => 1.2,
                        ],
                        'unit' => 'px',
                    ],
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 5,
                            'step' => .1
                        ]
                    ],
                    'labels' => [
                        esc_html__( 'From', 'element-ready-lite' ),
                        esc_html__( 'To', 'element-ready-lite' ),
                    ],
                    'scales'    => 1,
                    'handles'   => 'range',
                    'condition' => [
                        'element_ready_floating_fx_scale_toggle' => 'yes',
                        'element_ready_floating_fx'              => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_scale_duration',
                [
                    'label'      => esc_html__( 'Duration', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 10000,
                            'step' => 100
                        ]
                    ],
                    'default' => [
                        'size' => 1000,
                    ],
                    'condition' => [
                        'element_ready_floating_fx_scale_toggle' => 'yes',
                        'element_ready_floating_fx'              => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );

            $element->add_control(
                'element_ready_floating_fx_scale_delay',
                [
                    'label'      => esc_html__( 'Delay', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 5000,
                            'step' => 100
                        ]
                    ],
                    'condition' => [
                        'element_ready_floating_fx_scale_toggle' => 'yes',
                        'element_ready_floating_fx'              => 'yes',
                    ],
                    'render_type'        => 'none',
                    'frontend_available' => true,
                ]
            );
        $element->end_popover();

        $element->add_control(
            'element_ready_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
    }

    public static function add_css_effects( Element_Base $element ) {
        $element->add_control(
            'element_ready_transform_fx',
            [
                'label'        => esc_html__( 'CSS Transform', 'element-ready-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'element_ready_transform_fx_translate_toggle',
            [
                'label'        => esc_html__( 'Translate', 'element-ready-lite' ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'element_ready_transform_fx' => 'yes',
                ],
            ]
        );

        $element->start_popover();
            $element->add_responsive_control(
                'element_ready_transform_fx_translate_x',
                [
                    'label'      => esc_html__( 'Translate X', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_translate_toggle' => 'yes',
                        'element_ready_transform_fx'                  => 'yes',
                    ],
                ]
            );

            $element->add_responsive_control(
                'element_ready_transform_fx_translate_y',
                [
                    'label'      => esc_html__( 'Translate Y', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min' => -1000,
                            'max' => 1000,
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_translate_toggle' => 'yes',
                        'element_ready_transform_fx'                  => 'yes',
                    ],
                    'selectors' => [
                        '(desktop){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px);',
                        '(tablet){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px);',
                        '(mobile){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px);',
                    ]
                ]
            );
        $element->end_popover();

        $element->add_control(
            'element_ready_transform_fx_rotate_toggle',
            [
                'label'     => esc_html__( 'Rotate', 'element-ready-lite' ),
                'type'      => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'element_ready_transform_fx' => 'yes',
                ],
            ]
        );
        $element->start_popover();
            $element->add_responsive_control(
                'element_ready_transform_fx_rotate_x',
                [
                    'label'      => esc_html__( 'Rotate X', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_rotate_toggle' => 'yes',
                        'element_ready_transform_fx'               => 'yes',
                    ],
                ]
            );

            $element->add_responsive_control(
                'element_ready_transform_fx_rotate_y',
                [
                    'label'      => esc_html__( 'Rotate Y', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_rotate_toggle' => 'yes',
                        'element_ready_transform_fx'               => 'yes',
                    ],
                ]
            );

            $element->add_responsive_control(
                'element_ready_transform_fx_rotate_z',
                [
                    'label'      => esc_html__( 'Rotate Z', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_rotate_toggle' => 'yes',
                        'element_ready_transform_fx'               => 'yes',
                    ],
                    'selectors' => [
                        '(desktop){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg);',
                        '(tablet){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg);',
                        '(mobile){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                    ]
                ]
            );
        $element->end_popover();

        $element->add_control(
            'element_ready_transform_fx_scale_toggle',
            [
                'label'        => esc_html__( 'Scale', 'element-ready-lite' ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'element_ready_transform_fx' => 'yes',
                ],
            ]
        );
        $element->start_popover();
            $element->add_responsive_control(
                'element_ready_transform_fx_scale_x',
                [
                    'label'      => esc_html__( 'Scale X', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'default'    => [
                        'size' => 1
                    ],
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 5,
                            'step' => .1
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_scale_toggle' => 'yes',
                        'element_ready_transform_fx'              => 'yes',
                    ],
                ]
            );

            $element->add_responsive_control(
                'element_ready_transform_fx_scale_y',
                [
                    'label'      => esc_html__( 'Scale Y', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'default'    => [
                        'size' => 1
                    ],
                    'range' => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 5,
                            'step' => .1
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_scale_toggle' => 'yes',
                        'element_ready_transform_fx'              => 'yes',
                    ],
                    'selectors' => [
                        '(desktop){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y.SIZE || 1}});'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y.SIZE || 1}});'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y.SIZE || 1}});',
                        '(tablet){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_tablet.SIZE || 1}});'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_tablet.SIZE || 1}});'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_tablet.SIZE || 1}});',
                        '(mobile){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_mobile.SIZE || 1}});'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_mobile.SIZE || 1}});'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_mobile.SIZE || 1}});'
                    ]
                ]
            );
        $element->end_popover();

        $element->add_control(
            'element_ready_transform_fx_skew_toggle',
            [
                'label'        => esc_html__( 'Skew', 'element-ready-lite' ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'element_ready_transform_fx' => 'yes',
                ],
            ]
        );
        $element->start_popover();
            $element->add_responsive_control(
                'element_ready_transform_fx_skew_x',
                [
                    'label'      => esc_html__( 'Skew X', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['deg'],
                    'range'      => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_skew_toggle' => 'yes',
                        'element_ready_transform_fx'             => 'yes',
                    ],
                ]
            );

            $element->add_responsive_control(
                'element_ready_transform_fx_skew_y',
                [
                    'label'      => esc_html__( 'Skew Y', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['deg'],
                    'range'      => [
                        'px' => [
                            'min' => -180,
                            'max' => 180,
                        ]
                    ],
                    'condition' => [
                        'element_ready_transform_fx_skew_toggle' => 'yes',
                        'element_ready_transform_fx'             => 'yes',
                    ],
                    'selectors' => [
                        '(desktop){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y.SIZE || 0}}deg);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y.SIZE || 0}}deg);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x.SIZE || 0}}px, {{element_ready_transform_fx_translate_y.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y.SIZE || 0}}deg);',
                        '(tablet){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_tablet.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_tablet.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_tablet.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_tablet.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y_tablet.SIZE || 0}}deg);',
                        '(mobile){{WRAPPER}}' => 
                            '-ms-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_mobile.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                            . '-webkit-transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_mobile.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                            . 'transform:'
                                . 'translate({{element_ready_transform_fx_translate_x_mobile.SIZE || 0}}px, {{element_ready_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotateX({{element_ready_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{element_ready_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{element_ready_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                                . 'scaleX({{element_ready_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{element_ready_transform_fx_scale_y_mobile.SIZE || 1}}) '
                                . 'skew({{element_ready_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{element_ready_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                    ]
                ]
            );
        $element->end_popover();
        $element->add_control(
            'element_ready_transform_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
    }

    public static function add_tilt_effects( Element_Base $element ) {
        $element->add_control(
            'element_ready_tilt_effect',
            [
                'label'        => esc_html__( 'Tilt Effect', 'element-ready-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'tilt_maxTilt',
            [
                'label'      => esc_html__( 'Max Tilt', 'element-ready-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                        'step' => 1,
                    ]
                ],
                'condition' => [
                    'element_ready_tilt_effect' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'tilt_perspective',
            [
                'label'      => esc_html__( 'Perspective', 'element-ready-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'condition' => [
                    'element_ready_tilt_effect' => 'yes',
                ],
            ]
        );
        
        $element->add_control(
            'tilt_scale',
            [
                'label'      => esc_html__( 'Scale', 'element-ready-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => -1,
                        'max' => 5,
                        'step' => .1,
                    ]
                ],
                'default' => [
                    'size' => 1,
                ],
                'condition' => [
                    'element_ready_tilt_effect' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'tilt_speed',
            [
                'label'      => esc_html__( 'Speed', 'element-ready-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 300,
                        'max' => 5000,
                        'step' => 100,
                    ]
                ],
                'default' => [
                    'size' => 300,
                ],
                'condition' => [
                    'element_ready_tilt_effect' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'tilt_transition',
            [
                'label'        => esc_html__( 'Transition', 'element-ready-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition'    => [
                    'element_ready_tilt_effect' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'element_ready_tilt_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );
    }

}
if( element_ready_get_modules_option('floating_effect')){
    Element_Ready_Effects::instance()->init();
}
