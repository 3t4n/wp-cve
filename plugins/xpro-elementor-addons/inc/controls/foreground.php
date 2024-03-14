<?php
/**
 * Foreground control class
 *
 * @package Xpro_Elementor_Addons
 */

namespace XproElementorAddons\Control;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Group_Control_Foreground extends Group_Control_Base {


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
	 * Retrieve the control type, in this case.
	 *
	 * @return string Control type.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_type() {
		return 'foreground';
	}

	/**
	 * Init fields.
	 *
	 * Initialize background control fields.
	 *
	 * @return array Control fields.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function init_fields() {
		$fields = array();

		$fields['color_type'] = array(
			'label'       => _x( 'Color Type', 'Background Control', 'xpro-elementor-addons' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'render_type' => 'ui',
			'options'     => array(
				'classic'  => array(
					'title' => _x( 'Classic', 'Text Color Control', 'xpro-elementor-addons' ),
					'icon'  => 'eicon-paint-brush',
				),
				'gradient' => array(
					'title' => _x( 'Gradient', 'Text Color Control', 'xpro-elementor-addons' ),
					'icon'  => 'eicon-barcode',
				),
			)
		);

		$fields['color'] = array(
			'label'     => _x( 'Color', 'Background Control', 'xpro-elementor-addons' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'title'     => _x( 'Color', 'Background Control', 'xpro-elementor-addons' ),
			'selectors' => array(
				'{{SELECTOR}}' => 'color: {{VALUE}};',
			),
			'condition' => array(
				'color_type' => array( 'classic', 'gradient' ),
			)
		);

		$fields['color_stop'] = array(
			'label'       => _x( 'Location', 'Background Control', 'xpro-elementor-addons' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => array( '%' ),
			'default'     => array(
				'unit' => '%',
				'size' => 0,
			),
			'render_type' => 'ui',
			'condition'   => array(
				'color_type' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['color_b'] = array(
			'label'       => _x( 'Second Color', 'Background Control', 'xpro-elementor-addons' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f2295b',
			'render_type' => 'ui',
			'condition'   => array(
				'color_type' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['color_b_stop'] = array(
			'label'       => _x( 'Location', 'Background Control', 'xpro-elementor-addons' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => array( '%' ),
			'default'     => array(
				'unit' => '%',
				'size' => 100,
			),
			'render_type' => 'ui',
			'condition'   => array(
				'color_type' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['gradient_type'] = array(
			'label'       => _x( 'Type', 'Background Control', 'xpro-elementor-addons' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'linear' => _x( 'Linear', 'Background Control', 'xpro-elementor-addons' ),
				'radial' => _x( 'Radial', 'Background Control', 'xpro-elementor-addons' ),
			),
			'default'     => 'linear',
			'render_type' => 'ui',
			'condition'   => array(
				'color_type' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['gradient_angle'] = array(
			'label'      => _x( 'Angle', 'Background Control', 'xpro-elementor-addons' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'deg' ),
			'default'    => array(
				'unit' => 'deg',
				'size' => 180,
			),
			'range'      => array(
				'deg' => array(
					'step' => 10,
				),
			),
			'selectors'  => array(
				'{{SELECTOR}}' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			),
			'condition'  => array(
				'color_type'    => array( 'gradient' ),
				'gradient_type' => 'linear',
			),
			'of_type'    => 'gradient',
		);

		$fields['gradient_position'] = array(
			'label'     => _x( 'Position', 'Background Control', 'xpro-elementor-addons' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'center center' => _x( 'Center Center', 'Background Control', 'xpro-elementor-addons' ),
				'center left'   => _x( 'Center Left', 'Background Control', 'xpro-elementor-addons' ),
				'center right'  => _x( 'Center Right', 'Background Control', 'xpro-elementor-addons' ),
				'top center'    => _x( 'Top Center', 'Background Control', 'xpro-elementor-addons' ),
				'top left'      => _x( 'Top Left', 'Background Control', 'xpro-elementor-addons' ),
				'top right'     => _x( 'Top Right', 'Background Control', 'xpro-elementor-addons' ),
				'bottom center' => _x( 'Bottom Center', 'Background Control', 'xpro-elementor-addons' ),
				'bottom left'   => _x( 'Bottom Left', 'Background Control', 'xpro-elementor-addons' ),
				'bottom right'  => _x( 'Bottom Right', 'Background Control', 'xpro-elementor-addons' ),
			),
			'default'   => 'center center',
			'selectors' => array(
				'{{SELECTOR}}' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			),
			'condition' => array(
				'color_type'    => array( 'gradient' ),
				'gradient_type' => 'radial',
			),
			'of_type'   => 'gradient',
		);

		return $fields;
	}

	/**
	 * Get child default args.
	 *
	 * Retrieve the default arguments for all the child controls for a specific group
	 * control.
	 *
	 * @return array Default arguments for all the child controls.
	 * @since 1.0.0
	 * @access protected
	 *
	 */
	protected function get_child_default_args() {
		return array(
			'types' => array( 'classic', 'gradient' )
		);
	}

	/**
	 * Filter fields.
	 *
	 * Filter which controls to display, using `include`, `exclude`, `condition`
	 * and `of_type` arguments.
	 *
	 * @return array Control fields.
	 * @since 1.0.0
	 * @access protected
	 *
	 */
	protected function filter_fields() {
		$fields = parent::filter_fields();

		$args = $this->get_args();

		foreach ( $fields as &$field ) {
			if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'], true ) ) {
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
	 * @return array Default background control options.
	 * @since 1.0.0
	 * @access protected
	 *
	 */
	protected function get_default_options() {
		return array(
			'popover' => false,
		);
	}
}
