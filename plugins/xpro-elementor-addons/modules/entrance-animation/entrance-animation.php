<?php
/**
 * Entrance extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Entrance_Animation {


	public static function init() {
		add_filter( 'elementor/controls/animations/additional_animations', array( __CLASS__, 'additional_animations' ) );

		add_action( 'elementor/element/column/section_effects/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/common/section_effects/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/section/section_effects/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/container/section_effects/before_section_end', array( __CLASS__, 'register' ), 10, 1 );

	}

	/**
	 * Undocumented function
	 *
	 * @param array $list
	 *
	 * @return array
	 */
	public static function additional_animations( $list ) {

		$animations = array(
			'Xpro Mask'   => array(
				'xpro-anim-mask-from-up'    => 'Mask From Up',
				'xpro-anim-mask-from-down'  => 'Mask From Down',
				'xpro-anim-mask-from-left'  => 'Mask From Left',
				'xpro-anim-mask-from-right' => 'Mask From Right',
			),
			'Xpro Reveal' => array(
				'xpro-anim-reveal-from-up'    => 'Reveal From Up',
				'xpro-anim-reveal-from-down'  => 'Reveal From Down',
				'xpro-anim-reveal-from-left'  => 'Reveal From Left',
				'xpro-anim-reveal-from-right' => 'Reveal From Right',
			),
			'Xpro Flip'   => array(
				'flipInX' => 'Flip Horizontal',
				'flipInY' => 'Flip Vertical',
			),
		);

		return array_merge( $animations, $list );
	}

	public static function register( Element_Base $element ) {

		$element->add_control(
			'xpro_elementor_reveal_color',
			array(
				'label'       => __( 'Reveal Color', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .elementor-container::after'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .elementor-element-populated::after'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .elementor-widget-container::after'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .e-con-inner::after'   => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'_animation' => array( 'xpro-anim-reveal-from-up', 'xpro-anim-reveal-from-down', 'xpro-anim-reveal-from-left', 'xpro-anim-reveal-from-right' ),
				),
				'render_type' => 'template',
			)
		);

		$element->add_control(
			'xpro_elementor_reveal_bg_color',
			array(
				'label'       => __( 'Reveal Color', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .elementor-container::after'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .elementor-element-populated::after'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .elementor-widget-container::after'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}}[class*=xpro-anim-reveal-from] > .e-con-inner::after'   => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'animation' => array( 'xpro-anim-reveal-from-up', 'xpro-anim-reveal-from-down', 'xpro-anim-reveal-from-left', 'xpro-anim-reveal-from-right' ),
				),
				'render_type' => 'template',
			)
		);

	}

}


Xpro_Elementor_Entrance_Animation::init();
