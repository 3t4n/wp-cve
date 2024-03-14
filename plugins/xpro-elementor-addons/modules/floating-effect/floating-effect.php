<?php
/**
 * Floating Effects extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Floating {


	public static $should_script_enqueue = false;

	public static function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', array( __CLASS__, 'register' ), 1 );
		add_action( 'elementor/frontend/widget/before_render', array( __CLASS__, 'should_script_enqueue' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * Set should_script_enqueue based extension settings
	 *
	 * @param Element_Base $section
	 *
	 * @return void
	 */
	public static function should_script_enqueue( Element_Base $section ) {
		if ( self::$should_script_enqueue ) {
			return;
		}

		if ( 'yes' === $section->get_settings_for_display( 'xpro_elementor_floating_fx' ) ) {
			self::enqueue_scripts();

			self::$should_script_enqueue = true;

			remove_action( 'elementor/frontend/widget/before_render', array( __CLASS__, 'should_script_enqueue' ) );
		}
	}

	public static function enqueue_scripts() {
		// Floating effects
		wp_enqueue_script( 'anime' );
		wp_enqueue_script(
			'xpro-floating',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/floating-effect/js/floating-effect.min.js',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
	}

	public static function register( Element_Base $element ) {

		$element->start_controls_section(
			'section_xpro_elementor_floating',
			array(
				'label' => __( 'Floating Effect', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx',
			array(
				'label'              => __( 'Enable', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_translate_toggle',
			array(
				'label'              => __( 'Translate', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::POPOVER_TOGGLE,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'xpro_elementor_floating_fx' => 'yes',
				),
			)
		);

		$element->start_popover();

		$element->add_control(
			'xpro_elementor_floating_fx_translate_x',
			array(
				'label'              => __( 'Translate X', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 20,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_translate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_translate_y',
			array(
				'label'              => __( 'Translate Y', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 20,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_translate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_translate_duration',
			array(
				'label'              => __( 'Duration', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'            => array(
					'size' => 1000,
				),
				'condition'          => array(
					'xpro_elementor_floating_fx_translate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_translate_delay',
			array(
				'label'              => __( 'Delay', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 100,
					),
				),
				'condition'          => array(
					'xpro_elementor_floating_fx_translate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->end_popover();

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_toggle',
			array(
				'label'              => __( 'Rotate', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::POPOVER_TOGGLE,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'xpro_elementor_floating_fx' => 'yes',
				),
			)
		);

		$element->start_popover();

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_mode',
			array(
				'label'     => __( 'Mode', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'compact' => array(
						'title' => __( 'Compact', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-plus-circle',
					),
					'loose'   => array(
						'title' => __( 'Loose', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-minus-circle',
					),
				),
				'default'   => 'loose',
				'toggle'    => false,
				'condition' => array(
					'xpro_elementor_floating_fx_rotate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_hr_hover',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_x',
			array(
				'label'              => __( 'Rotate X', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'unit'               => 'px',
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 30,
					),
				),
				'range'              => array(
					'px' => array(
						'min' => - 180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_rotate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
					'xpro_elementor_floating_fx_rotate_mode' => 'loose',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_y',
			array(
				'label'              => __( 'Rotate Y', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 0,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min' => - 180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_rotate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
					'xpro_elementor_floating_fx_rotate_mode' => 'loose',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_z',
			array(
				'label'              => __( 'Rotate Z', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 0,
						'to'   => 30,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min' => - 180,
						'max' => 180,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_rotate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
					'xpro_elementor_floating_fx_rotate_mode' => 'compact',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_duration',
			array(
				'label'              => __( 'Duration', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'            => array(
					'size' => 1000,
				),
				'condition'          => array(
					'xpro_elementor_floating_fx_rotate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_rotate_delay',
			array(
				'label'              => __( 'Delay', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 100,
					),
				),
				'condition'          => array(
					'xpro_elementor_floating_fx_rotate_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->end_popover();

		$element->add_control(
			'xpro_elementor_floating_fx_scale_toggle',
			array(
				'label'              => __( 'Scale', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::POPOVER_TOGGLE,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'xpro_elementor_floating_fx' => 'yes',
				),
			)
		);

		$element->start_popover();

		$element->add_control(
			'xpro_elementor_floating_fx_scale_mode',
			array(
				'label'     => __( 'Mode', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'compact' => array(
						'title' => __( 'Compact', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-plus-circle',
					),
					'loose'   => array(
						'title' => __( 'Loose', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-minus-circle',
					),
				),
				'default'   => 'loose',
				'toggle'    => false,
				'condition' => array(
					'xpro_elementor_floating_fx_scale_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_scale_hr_hover',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_scale_x',
			array(
				'label'              => __( 'Scale X', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 1,
						'to'   => 1.2,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_scale_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
					'xpro_elementor_floating_fx_scale_mode' => 'loose',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_scale_y',
			array(
				'label'              => __( 'Scale Y', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 1,
						'to'   => 1,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_scale_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
					'xpro_elementor_floating_fx_scale_mode' => 'loose',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_scale_z',
			array(
				'label'              => __( 'Scale Z', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'sizes' => array(
						'from' => 1,
						'to'   => 1.2,
					),
					'unit'  => 'px',
				),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					),
				),
				'labels'             => array(
					__( 'From', 'xpro-elementor-addons' ),
					__( 'To', 'xpro-elementor-addons' ),
				),
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => array(
					'xpro_elementor_floating_fx_scale_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
					'xpro_elementor_floating_fx_scale_mode' => 'compact',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_scale_duration',
			array(
				'label'              => __( 'Duration', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'            => array(
					'size' => 1000,
				),
				'condition'          => array(
					'xpro_elementor_floating_fx_scale_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_elementor_floating_fx_scale_delay',
			array(
				'label'              => __( 'Delay', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 100,
					),
				),
				'condition'          => array(
					'xpro_elementor_floating_fx_scale_toggle' => 'yes',
					'xpro_elementor_floating_fx' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->end_popover();

		$element->end_controls_section();
	}
}

Xpro_Elementor_Floating::init();
