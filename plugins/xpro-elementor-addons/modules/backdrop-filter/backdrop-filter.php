<?php
/**
 * Backdrop Filter extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Backdrop_Filter {

	public static function init() {

		add_action( 'elementor/element/column/section_style/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/common/_section_background/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/section/section_background/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/container/section_background/before_section_end', array( __CLASS__, 'register' ), 10, 1 );

	}

	public static function register( Element_Base $element ) {

		$element->add_control(
			'xpro_backdrop_filter',
			array(
				'label'              => __( 'Backdrop Filter', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::POPOVER_TOGGLE,
				'return_value'       => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$element->start_popover();

		$element->add_control(
			'xpro_backdrop_filter_blur',
			array(
				'label'     => __( 'Blur', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'required'  => 'true',
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'   => array(
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.elementor-widget),{{WRAPPER}} > .elementor-widget-container' => 'backdrop-filter: brightness( {{xpro_backdrop_filter_brightness.SIZE}} ) contrast( {{xpro_backdrop_filter_contrast.SIZE}} ) saturate( {{xpro_backdrop_filter_saturation.SIZE}} ) blur( {{xpro_backdrop_filter_blur.SIZE}}px ) hue-rotate( {{xpro_backdrop_filter_hue.SIZE}}deg ); -webkit-backdrop-filter: brightness( {{xpro_backdrop_filter_brightness.SIZE}} ) contrast( {{xpro_backdrop_filter_contrast.SIZE}} ) saturate( {{xpro_backdrop_filter_saturation.SIZE}} ) blur( {{xpro_backdrop_filter_blur.SIZE}}px ) hue-rotate( {{xpro_backdrop_filter_hue.SIZE}}deg )',
				),
				'condition' => array(
					'xpro_backdrop_filter' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_backdrop_filter_brightness',
			array(
				'label'       => __( 'Brightness', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'ui',
				'required'    => 'true',
				'default'     => array(
					'size' => 1,
				),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'condition'   => array(
					'xpro_backdrop_filter' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_backdrop_filter_contrast',
			array(
				'label'       => __( 'Contrast', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'ui',
				'required'    => 'true',
				'default'     => array(
					'size' => 1,
				),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'condition'   => array(
					'xpro_backdrop_filter' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_backdrop_filter_saturation',
			array(
				'label'       => __( 'Saturation', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'ui',
				'required'    => 'true',
				'default'     => array(
					'size' => 1,
				),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'condition'   => array(
					'xpro_backdrop_filter' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_backdrop_filter_hue',
			array(
				'label'       => __( 'Hue', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'ui',
				'required'    => 'true',
				'default'     => array(
					'size' => 0,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 360,
					),
				),
				'condition'   => array(
					'xpro_backdrop_filter' => 'yes',
				),
			)
		);

		$element->end_popover();

	}
}

Xpro_Elementor_Backdrop_Filter::init();
