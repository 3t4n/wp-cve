<?php
/**
 * Foreground control class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Group_Control_Foreground extends Group_Control_Base {

    /**
     * Fields.
     *
     * Holds all the background control fields.
     *
     * @access protected
     * @static
     *
     * @var array Background control fields.
     */
    protected static $fields;

    /**
     * Get background control type.
     *
     * Retrieve the control type, in this case `skt_addons_elementor_text_color`.
     *
     * @since 1.0
     * @access public
     * @static
     *
     * @return string Control type.
     */
    public static function get_type() {
        return 'foreground';
    }

    /**
     * Init fields.
     *
     * Initialize background control fields.
     *
     * @since 1.0
     * @access public
     *
     * @return array Control fields.
     */
    public function init_fields() {
        $fields = [];

        $fields['color_type'] = [
            'label' => _x( 'Text Color', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => false,
            'render_type' => 'ui',
            'options' => [
                'classic' => [
                    'title' => _x( 'Classic', 'Text Color Control', 'skt-addons-elementor' ),
                    'icon' => 'eicon-paint-brush',
                ],
                'gradient' => [
                    'title' => _x( 'Gradient', 'Text Color Control', 'skt-addons-elementor' ),
                    'icon' => 'eicon-barcode',
                ],
            ],
            'default' => 'classic'
        ];

        $fields['color'] = [
            'label' => _x( 'Color', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'title' => _x( 'Text Color', 'Background Control', 'skt-addons-elementor' ),
            'selectors' => [
                '{{SELECTOR}}' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'color_type' => [ 'classic', 'gradient' ],
            ],
        ];

        $fields['color_stop'] = [
            'label' => _x( 'Location', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'default' => [
                'unit' => '%',
                'size' => 0,
            ],
            'render_type' => 'ui',
            'condition' => [
                'color_type' => [ 'gradient' ],
            ],
            'of_type' => 'gradient',
        ];

        $fields['color_b'] = [
            'label' => _x( 'Second Color', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#f2295b',
            'render_type' => 'ui',
            'condition' => [
                'color_type' => [ 'gradient' ],
            ],
            'of_type' => 'gradient',
        ];

        $fields['color_b_stop'] = [
            'label' => _x( 'Location', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'default' => [
                'unit' => '%',
                'size' => 100,
            ],
            'render_type' => 'ui',
            'condition' => [
                'color_type' => [ 'gradient' ],
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_type'] = [
            'label' => _x( 'Type', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'linear' => _x( 'Linear', 'Background Control', 'skt-addons-elementor' ),
                'radial' => _x( 'Radial', 'Background Control', 'skt-addons-elementor' ),
            ],
            'default' => 'linear',
            'render_type' => 'ui',
            'condition' => [
                'color_type' => [ 'gradient' ],
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_angle'] = [
            'label' => _x( 'Angle', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'deg' ],
            'default' => [
                'unit' => 'deg',
                'size' => 180,
            ],
            'range' => [
                'deg' => [
                    'step' => 10,
                ],
            ],
            'selectors' => [
                '{{SELECTOR}}' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ],
            'condition' => [
                'color_type' => [ 'gradient' ],
                'gradient_type' => 'linear',
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_position'] = [
            'label' => _x( 'Position', 'Background Control', 'skt-addons-elementor' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'center center' => _x( 'Center Center', 'Background Control', 'skt-addons-elementor' ),
                'center left' => _x( 'Center Left', 'Background Control', 'skt-addons-elementor' ),
                'center right' => _x( 'Center Right', 'Background Control', 'skt-addons-elementor' ),
                'top center' => _x( 'Top Center', 'Background Control', 'skt-addons-elementor' ),
                'top left' => _x( 'Top Left', 'Background Control', 'skt-addons-elementor' ),
                'top right' => _x( 'Top Right', 'Background Control', 'skt-addons-elementor' ),
                'bottom center' => _x( 'Bottom Center', 'Background Control', 'skt-addons-elementor' ),
                'bottom left' => _x( 'Bottom Left', 'Background Control', 'skt-addons-elementor' ),
                'bottom right' => _x( 'Bottom Right', 'Background Control', 'skt-addons-elementor' ),
            ],
            'default' => 'center center',
            'selectors' => [
                '{{SELECTOR}}' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ],
            'condition' => [
                'color_type' => [ 'gradient' ],
                'gradient_type' => 'radial',
            ],
            'of_type' => 'gradient',
        ];

        return $fields;
    }

    /**
     * Get child default args.
     *
     * Retrieve the default arguments for all the child controls for a specific group
     * control.
     *
     * @since 1.0
     * @access protected
     *
     * @return array Default arguments for all the child controls.
     */
    protected function get_child_default_args() {
        return [
            'types' => [ 'classic', 'gradient' ],
        ];
    }

    /**
     * Filter fields.
     *
     * Filter which controls to display, using `include`, `exclude`, `condition`
     * and `of_type` arguments.
     *
     * @since 1.0
     * @access protected
     *
     * @return array Control fields.
     */
    protected function filter_fields() {
        $fields = parent::filter_fields();

        $args = $this->get_args();

        foreach ( $fields as &$field ) {
            if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'] ) ) {
                unset( $field );
            }
        }

        return $fields;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the background control. Used to return the
     * default options while initializing the background control.
     *
     * @since 1.0
     * @access protected
     *
     * @return array Default background control options.
     */
    protected function get_default_options() {
        return [
            'popover' => false,
        ];
    }
}