<?php
/**
 * Display Order extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Display_Order {

	public static function init() {

		add_action( 'elementor/element/common/_section_style/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/column/section_advanced/before_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/section/section_advanced/before_section_end', array( __CLASS__, 'register' ), 10 );

	}

	public static function register( Element_Base $element ) {

		$element->add_responsive_control(
			'xpro_display_order',
			array(
				'label'       => _x( 'Display Order', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => -10,
				'max'         => 10,
				'step'        => 1,
				'render_type' => 'ui',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}}' => 'order:{{VALUE}}',
				),
			)
		);
	}
}

Xpro_Elementor_Display_Order::init();
