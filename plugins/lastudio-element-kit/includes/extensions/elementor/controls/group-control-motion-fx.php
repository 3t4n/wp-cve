<?php

namespace LaStudioKitExtensions\Elementor\Controls;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

class Group_Control_Motion_Fx extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'motion_fx';
	}

    protected function init_fields() {
        $fields = [
            'motion_fx_scrolling' => [
                'label' => __( 'Scrolling Effects', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Off', 'lastudio-kit' ),
                'label_on' => __( 'On', 'lastudio-kit' ),
                'render_type' => 'ui',
                'frontend_available' => true,
            ],
        ];

        $this->prepare_effects( 'scrolling', $fields );

        $transform_origin_conditions = [
            'terms' => [
                [
                    'name' => 'motion_fx_scrolling',
                    'value' => 'yes',
                ],
                [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'rotateZ_effect',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'scale_effect',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ],
        ];

        $fields['transform_origin_x'] = [
            'label' => __( 'X Anchor Point', 'lastudio-kit' ),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'center',
            'options' => [
                'left' => [
                    'title' => __( 'Left', 'lastudio-kit' ),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __( 'Center', 'lastudio-kit' ),
                    'icon' => 'eicon-h-align-center',
                ],
                'right' => [
                    'title' => __( 'Right', 'lastudio-kit' ),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'conditions' => $transform_origin_conditions,
            'label_block' => false,
            'toggle' => false,
            'render_type' => 'ui',
        ];

        $fields['transform_origin_y'] = [
            'label' => __( 'Y Anchor Point', 'lastudio-kit' ),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'center',
            'options' => [
                'top' => [
                    'title' => __( 'Top', 'lastudio-kit' ),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __( 'Center', 'lastudio-kit' ),
                    'icon' => 'eicon-v-align-middle',
                ],
                'bottom' => [
                    'title' => __( 'Bottom', 'lastudio-kit' ),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'conditions' => $transform_origin_conditions,
            'selectors' => [
                '{{SELECTOR}}' => 'transform-origin: {{transform_origin_x.VALUE}} {{VALUE}}',
            ],
            'label_block' => false,
            'toggle' => false,
        ];

        $activeBreakpoints = array_merge(
            lastudio_kit_helper()->get_active_breakpoints(false,true),
            [
                'desktop' => __('Desktop', 'lastudio-kit'),
            ],
        );

//	    if(!isset($activeBreakpoints['widescreen'])){
//		    $activeBreakpoints['widescreen'] = $activeBreakpoints['desktop'];
//		    unset($activeBreakpoints['desktop']);
//	    }

	    $fields['range'] = [
		    'label' => __( 'Effects Relative To', 'lastudio-kit' ),
		    'type' => Controls_Manager::SELECT,
		    'options' => [
			    '' => __( 'Default', 'lastudio-kit' ),
			    'viewport' => __( 'Viewport', 'lastudio-kit' ),
			    'page' => __( 'Entire Page', 'lastudio-kit' ),
		    ],
		    'condition' => [
			    'motion_fx_scrolling' => 'yes',
		    ],
		    'render_type' => 'none',
		    'frontend_available' => true,
	    ];

        $fields['motion_fx_mouse'] = [
            'label' => __( 'Mouse Effects', 'lastudio-kit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __( 'Off', 'lastudio-kit' ),
            'label_on' => __( 'On', 'lastudio-kit' ),
            'separator' => 'before',
            'render_type' => 'ui',
            'frontend_available' => true,
        ];

        $this->prepare_effects( 'mouse', $fields );

        $fields['devices'] = [
            'label' => __( 'Apply Effects On', 'lastudio-kit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => 'true',
            'default' => array_keys($activeBreakpoints),
            'options' => $activeBreakpoints,
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => 'motion_fx_scrolling',
                        'value' => 'yes',
                    ],
//                    [
//                        'name' => 'mouseTrack_effect',
//                        'value' => 'yes',
//                    ]
                ],
            ],
            'render_type' => 'none',
            'frontend_available' => true,
        ];

        return $fields;
    }

    protected function get_default_options() {
        return [
            'popover' => false,
        ];
    }

    private function get_scrolling_effects() {
        return [
            'translateY' => [
                'label' => __( 'Vertical Scroll', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '' => __( 'Up', 'lastudio-kit' ),
                            'negative' => __( 'Down', 'lastudio-kit' ),
                        ],
                    ],
                    'speed' => [
                        'label' => __( 'Speed', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 3,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'affectedRange' => [
                        'label' => __( 'Viewport', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __( 'Bottom', 'lastudio-kit' ),
                            __( 'Top', 'lastudio-kit' ),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'translateX' => [
                'label' => __( 'Horizontal Scroll', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '' => __( 'To Left', 'lastudio-kit' ),
                            'negative' => __( 'To Right', 'lastudio-kit' ),
                        ],
                    ],
                    'speed' => [
                        'label' => __( 'Speed', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 4,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'affectedRange' => [
                        'label' => __( 'Viewport', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __( 'Bottom', 'lastudio-kit' ),
                            __( 'Top', 'lastudio-kit' ),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'opacity' => [
                'label' => __( 'Transparency', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'out-in',
                        'options' => [
                            'out-in' => 'Fade In',
                            'in-out' => 'Fade Out',
                            'in-out-in' => 'Fade Out In',
                            'out-in-out' => 'Fade In Out',
                        ],
                    ],
                    'level' => [
                        'label' => __( 'Level', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 10,
                        ],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'range' => [
                        'label' => __( 'Viewport', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __( 'Bottom', 'lastudio-kit' ),
                            __( 'Top', 'lastudio-kit' ),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'blur' => [
                'label' => __( 'Blur', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'out-in',
                        'options' => [
                            'out-in' => 'Fade In',
                            'in-out' => 'Fade Out',
                            'in-out-in' => 'Fade Out In',
                            'out-in-out' => 'Fade In Out',
                        ],
                    ],
                    'level' => [
                        'label' => __( 'Level', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 7,
                        ],
                        'range' => [
                            'px' => [
                                'min' => 1,
                                'max' => 15,
                            ],
                        ],
                    ],
                    'range' => [
                        'label' => __( 'Viewport', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __( 'Bottom', 'lastudio-kit' ),
                            __( 'Top', 'lastudio-kit' ),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'rotateZ' => [
                'label' => __( 'Rotate', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '' => __( 'To Left', 'lastudio-kit' ),
                            'negative' => __( 'To Right', 'lastudio-kit' ),
                        ],
                    ],
                    'speed' => [
                        'label' => __( 'Speed', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 1,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                    'affectedRange' => [
                        'label' => __( 'Viewport', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 0,
                                'end' => 100,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __( 'Bottom', 'lastudio-kit' ),
                            __( 'Top', 'lastudio-kit' ),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
            'scale' => [
                'label' => __( 'Scale', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'out-in',
                        'options' => [
                            'out-in' => 'Scale Up',
                            'in-out' => 'Scale Down',
                            'in-out-in' => 'Scale Down Up',
                            'out-in-out' => 'Scale Up Down',
                        ],
                    ],
                    'speed' => [
                        'label' => __( 'Speed', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 4,
                        ],
                        'range' => [
                            'px' => [
                                'min' => -10,
                                'max' => 10,
                            ],
                        ],
                    ],
                    'range' => [
                        'label' => __( 'Viewport', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'sizes' => [
                                'start' => 20,
                                'end' => 80,
                            ],
                            'unit' => '%',
                        ],
                        'labels' => [
                            __( 'Bottom', 'lastudio-kit' ),
                            __( 'Top', 'lastudio-kit' ),
                        ],
                        'scales' => 1,
                        'handles' => 'range',
                    ],
                ],
            ],
        ];
    }

    private function get_mouse_effects() {
        return [
            'mouseTrack' => [
                'label' => __( 'Mouse Track', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            '' => __( 'Opposite', 'lastudio-kit' ),
                            'negative' => __( 'Direct', 'lastudio-kit' ),
                        ],
                    ],
                    'speed' => [
                        'label' => __( 'Speed', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 1,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                ],
            ],
            'tilt' => [
                'label' => __( '3D Tilt', 'lastudio-kit' ),
                'fields' => [
                    'direction' => [
                        'label' => __( 'Direction', 'lastudio-kit' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            '' => __( 'Direct', 'lastudio-kit' ),
                            'negative' => __( 'Opposite', 'lastudio-kit' ),
                        ],
                    ],
                    'speed' => [
                        'label' => __( 'Speed', 'lastudio-kit' ),
                        'type' => Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 4,
                        ],
                        'range' => [
                            'px' => [
                                'max' => 10,
                                'step' => 0.1,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function prepare_effects( $effects_group, array & $fields ) {
        $method_name = "get_{$effects_group}_effects";

        $effects = $this->$method_name();

        foreach ( $effects as $effect_name => $effect_args ) {
            $args = [
                'label' => $effect_args['label'],
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'motion_fx_' . $effects_group => 'yes',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ];

            if ( ! empty( $effect_args['separator'] ) ) {
                $args['separator'] = $effect_args['separator'];
            }

            $fields[ $effect_name . '_effect' ] = $args;

            $effect_fields = $effect_args['fields'];

            $first_field = & $effect_fields[ key( $effect_fields ) ];

            $first_field['popover']['start'] = true;

            end( $effect_fields );

            $last_field = & $effect_fields[ key( $effect_fields ) ];

            $last_field['popover']['end'] = true;

            reset( $effect_fields );

            foreach ( $effect_fields as $field_name => $field ) {
                $field = array_merge( $field, [
                    'condition' => [
                        'motion_fx_' . $effects_group => 'yes',
                        $effect_name . '_effect' => 'yes',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ] );

                $fields[ $effect_name . '_' . $field_name ] = $field;
            }
        }
    }
}
