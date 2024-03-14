<?php

namespace LaStudioKitExtensions\Elementor\Controls;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

class Group_Control_Box_Shadow extends Group_Control_Base {

    protected static $fields;

    public static function get_type() {
        return 'box-shadow';
    }

    protected function init_fields() {

        $fields = [];

        $fields['_boxshadow_c'] = array(
            'label'     => _x( 'Color', 'Box Shadow Control', 'lastudio-kit' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.5)',
            'title'     => _x( 'Color', 'Box Shadow Control', 'lastudio-kit' ),
            'selectors' => [
                '{{SELECTOR}}' => 'box-shadow: var(--shadow_horizontal, 0) var(--shadow_vertical, 0) var(--shadow_blur, 0) var(--shadow_spread, 0) {{VALUE}} {{_boxshadow_p.VALUE}};',
            ],
            'global'    => [],
            'render_type' => 'ui',
        );

        $fields['_boxshadow_h'] = [
            'label'      => esc_html_x( 'Horizontal', 'Box Shadow Control', 'elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'responsive' => true,
            'range'      => array(
                'px' => array(
                    'min' => -100,
                    'max' => 100,
                ),
            ),
            'selectors' => [
                '{{SELECTOR}}' => '--shadow_horizontal: {{SIZE}}px',
            ],
            'render_type' => 'ui',
        ];

        $fields['_boxshadow_v'] = [
            'label'      => esc_html_x( 'Vertical', 'Box Shadow Control', 'elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'responsive' => true,
            'range'      => array(
                'px' => array(
                    'min' => -100,
                    'max' => 100,
                ),
            ),
            'render_type' => 'ui',
            'selectors' => [
                '{{SELECTOR}}' => '--shadow_vertical: {{SIZE}}px',
            ],
        ];

        $fields['_boxshadow_b'] = [
            'label'      => esc_html_x( 'Blur', 'Box Shadow Control', 'elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'responsive' => true,
            'default' => [
                'size' => 10,
            ],
            'range'      => array(
                'px' => array(
                    'min' => -100,
                    'max' => 100,
                ),
            ),
            'selectors' => [
                '{{SELECTOR}}' => '--shadow_blur: {{SIZE}}px',
            ],
            'render_type' => 'ui',
        ];

        $fields['_boxshadow_s'] = [
            'label'      => esc_html_x( 'Spread', 'Box Shadow Control', 'elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'responsive' => true,
            'range'      => array(
                'px' => array(
                    'min' => -100,
                    'max' => 100,
                ),
            ),
            'selectors' => [
                '{{SELECTOR}}' => '--shadow_spread: {{SIZE}}px',
            ],
            'render_type' => 'ui',
        ];

        $fields['_boxshadow_p'] = [
            'label' => _x( 'Position', 'Box Shadow Control', 'lastudio-kit' ),
            'type' => Controls_Manager::SELECT,
            'options' => array(
                ' '     => _x( 'Outline', 'Box Shadow Control', 'lastudio-kit' ),
                'inset' => _x( 'Inset', 'Box Shadow Control', 'lastudio-kit' ),
            ),
            'default' => ' ',
            'render_type' => 'ui',
        ];

        return $fields;
    }

    protected function get_default_options() {
        return [
            'popover' => [
                'starter_title' => esc_html_x( 'Box Shadow', 'Box Shadow Control', 'lastudio-kit' ),
                'starter_name' => 'box_shadow_type',
                'starter_value' => 'yes',
                'settings' => [
                    'render_type' => 'ui',
                ],
            ],
        ];
    }
}
