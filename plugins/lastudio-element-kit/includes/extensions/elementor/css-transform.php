<?php

namespace LaStudioKitExtensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CSS_Transform {

    public function __construct() {
        add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'init_module']);
    }

    public function init_module( $element ){
        $element->start_controls_section('_section_lakit_css_transform', [
            'label' => __('LA-Kit CSS Transform', 'lastudio-kit'),
            'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]);
        $element->add_control('lakit_transform_fx', [
            'label' => __('Enable', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'prefix_class' => 'lakit-css-transform-',
        ]);
        $element->start_controls_tabs('_tabs_lakit_transform', [
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_controls_tab('_tabs_lakit_transform_normal', [
            'label' => __('Normal', 'lastudio-kit'),
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->add_control('lakit_transform_fx_translate_toggle', [
            'label' => __('Translate', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('lakit_transform_fx_translate_x', [
            'label' => __('Translate X', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_translate_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-translate-x: {{SIZE}}{{UNIT}};'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_translate_y', [
            'label' => __('Translate Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_translate_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-translate-y: {{SIZE}}{{UNIT}};'
            ],
        ]);
        $element->end_popover();
        $element->add_control('lakit_transform_fx_rotate_toggle', [
            'label' => __('Rotate', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('lakit_transform_fx_rotate_mode', [
            'label' => __('Mode', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'lastudio-kit'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'lastudio-kit'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('lakit_transform_fx_rotate_hr', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('lakit_transform_fx_rotate_x', [
            'label' => __('Rotate X', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_rotate_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
                'lakit_transform_fx_rotate_mode' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-rotate-x: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_rotate_y', [
            'label' => __('Rotate Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_rotate_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
                'lakit_transform_fx_rotate_mode' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-rotate-y: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_rotate_z', [
            'label' => __('Rotate (Z)', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_rotate_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-rotate-z: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('lakit_transform_fx_scale_toggle', [
            'label' => __('Scale', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('lakit_transform_fx_scale_mode', [
            'label' => __('Mode', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'lastudio-kit'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'lastudio-kit'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('lakit_transform_fx_scale_hr', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('lakit_transform_fx_scale_x', [
            'label' => __('Scale (X)', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'lakit_transform_fx_scale_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-scale-x: {{SIZE}}; --lakit-tfx-scale-y: {{SIZE}};'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_scale_y', [
            'label' => __('Scale Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'lakit_transform_fx_scale_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
                'lakit_transform_fx_scale_mode' => 'loose',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-scale-y: {{SIZE}};'
            ],
        ]);
        $element->end_popover();
        $element->add_control('lakit_transform_fx_skew_toggle', [
            'label' => __('Skew', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('lakit_transform_fx_skew_x', [
            'label' => __('Skew X', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_skew_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-skew-x: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_skew_y', [
            'label' => __('Skew Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_skew_toggle' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-skew-y: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->end_controls_tab();
        $element->start_controls_tab('_tabs_lakit_transform_hover', [
            'label' => __('Hover', 'lastudio-kit'),
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->add_control('lakit_transform_fx_translate_toggle_hover', [
            'label' => __('Translate', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('lakit_transform_fx_translate_x_hover', [
            'label' => __('Translate X', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_translate_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-translate-x-hover: {{SIZE}}px;'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_translate_y_hover', [
            'label' => __('Translate Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_translate_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-translate-y-hover: {{SIZE}}px;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('lakit_transform_fx_rotate_toggle_hover', [
            'label' => __('Rotate', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('lakit_transform_fx_rotate_mode_hover', [
            'label' => __('Mode', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'lastudio-kit'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'lastudio-kit'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('lakit_transform_fx_rotate_hr_hover', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('lakit_transform_fx_rotate_x_hover', [
            'label' => __('Rotate X', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_rotate_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
                'lakit_transform_fx_rotate_mode_hover' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-rotate-x-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_rotate_y_hover', [
            'label' => __('Rotate Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_rotate_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
                'lakit_transform_fx_rotate_mode_hover' => 'loose'
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-rotate-y-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_rotate_z_hover', [
            'label' => __('Rotate (Z)', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_rotate_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-rotate-z-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('lakit_transform_fx_scale_toggle_hover', [
            'label' => __('Scale', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_control('lakit_transform_fx_scale_mode_hover', [
            'label' => __('Mode', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'compact' => [
                    'title' => __('Compact', 'lastudio-kit'),
                    'icon' => 'eicon-plus-circle',
                ],
                'loose' => [
                    'title' => __('Loose', 'lastudio-kit'),
                    'icon' => 'eicon-minus-circle',
                ],
            ],
            'default' => 'loose',
            'toggle' => false
        ]);
        $element->add_control('lakit_transform_fx_scale_hr_hover', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);
        $element->add_responsive_control('lakit_transform_fx_scale_x_hover', [
            'label' => __('Scale (X)', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'lakit_transform_fx_scale_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-scale-x-hover: {{SIZE}}; --lakit-tfx-scale-y-hover: {{SIZE}};'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_scale_y_hover', [
            'label' => __('Scale Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default' => [
                'size' => 1
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => .1
                ]
            ],
            'condition' => [
                'lakit_transform_fx_scale_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
                'lakit_transform_fx_scale_mode_hover' => 'loose',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-scale-y-hover: {{SIZE}};'
            ],
        ]);
        $element->end_popover();
        $element->add_control('lakit_transform_fx_skew_toggle_hover', [
            'label' => __('Skew', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
        ]);
        $element->start_popover();
        $element->add_responsive_control('lakit_transform_fx_skew_x_hover', [
            'label' => __('Skew X', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_skew_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-skew-x-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->add_responsive_control('lakit_transform_fx_skew_y_hover', [
            'label' => __('Skew Y', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['deg'],
            'range' => [
                'px' => [
                    'min' => -180,
                    'max' => 180,
                ]
            ],
            'condition' => [
                'lakit_transform_fx_skew_toggle_hover' => 'yes',
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-skew-y-hover: {{SIZE}}deg;'
            ],
        ]);
        $element->end_popover();
        $element->add_control('lakit_transform_fx_transition_duration', [
            'label' => __('Transition Duration', 'lastudio-kit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'separator' => 'before',
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 3,
                    'step' => .1,
                ]
            ],
            'condition' => [
                'lakit_transform_fx' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '--lakit-tfx-transition-duration: {{SIZE}}s;'
            ],
        ]);
        $element->end_controls_tab();
        $element->end_controls_tabs();
        $element->end_controls_section();
    }
}